<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Pages/SecurePage.php');

class AutoCompletePage extends Page
{
    private $listMethods = [];

    public function __construct()
    {
        parent::__construct();

        $this->listMethods[AutoCompleteType::User] = 'GetUsers';
        $this->listMethods[AutoCompleteType::XUser] = 'XGetUsers';
        $this->listMethods[AutoCompleteType::MyUsers] = 'GetMyUsers';
        $this->listMethods[AutoCompleteType::Group] = 'GetGroups';
        $this->listMethods[AutoCompleteType::Organization] = 'GetOrganizations';
    }

    public function PageLoad()
    {
        $results = $this->GetResults($this->GetType(), $this->GetSearchTerm());

        Log::Debug(sprintf('AutoComplete: %s results found for search type: %s, term: %s', count($results), $this->GetType(), $this->GetSearchTerm()));

        $this->SetJson($results);
    }

    private function GetResults($type, $term)
    {
        if (array_key_exists($type, $this->listMethods)) {
            $method = $this->listMethods[$type];
            return $this->$method($term);
        }

        Log::Debug("AutoComplete for type: $type not defined");

        return [];
    }

    public function GetType()
    {
        return $this->GetQuerystring(QueryStringKeys::AUTOCOMPLETE_TYPE);
    }

    public function GetSearchTerm()
    {
        return $this->GetQuerystring(QueryStringKeys::AUTOCOMPLETE_TERM);
    }

    /**
     * @param $term string
     * @return array|AutocompleteUser[]
     */
    private function GetUsers($term)
    {
        if ($term == 'group') {
            return $this->GetGroupUsers($this->GetQuerystring(QueryStringKeys::GROUP_ID));
        }
        if (empty($term)) {
            $term = '';
        }


        $onlyActive = false;
        $activeQS = $this->GetQuerystring(QueryStringKeys::ACCOUNT_STATUS);
        if ($activeQS == AccountStatus::ACTIVE) {
            $onlyActive = true;
        }
        $filter = new SqlFilterLike(ColumnNames::FIRST_NAME, $term);
        $filter->_Or(new SqlFilterLike(ColumnNames::LAST_NAME, $term));
        $filter->_Or(new SqlFilterLike(ColumnNames::EMAIL, $term));
        $filter->_Or(new SqlFilterLike(ColumnNames::USERNAME, $term));

        $r = new UserRepository();
        $currentUser = ServiceLocator::GetServer()->GetUserSession();
        $user = $r->LoadById($currentUser->UserId);

        $status = AccountStatus::ACTIVE;
        if (!$onlyActive && ($currentUser->IsAdmin || $currentUser->IsGroupAdmin)) {
            $status = AccountStatus::ALL;
        }
        $results = $r->GetList(1, PageInfo::All, null, null, $filter, $status)->Results();

        $hideUserDetails = Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, new BooleanConverter());
        $users = [];
        /** @var UserItemView $result */
        foreach ($results as $result) {
            if (!$hideUserDetails || $result->Id == $currentUser->UserId || $user->IsGroupAdminFor($result->GroupIds) || $currentUser->IsAdmin) {
                $users[] = new AutocompleteUser($result->Id, $result->First, $result->Last, $result->Email, $result->Username, $result->CurrentCreditCount);
            }
        }

        return $users;
    }

    /**
     * @param $term string
     * @return array|XAutocompleteUser[]
     */
    private function XGetUsers($term)
    {
        $users = $this->GetUsers($term);

        $outUsers = [new XAutocompleteUser("", "")];
        foreach ($users as $user) {
            $value = $user->Name . " <" . $user->Email . ">";
            $outUsers[] = new XAutocompleteUser($value, $value);
        }
        return $outUsers;
    }

    private function GetGroups($term)
    {
        $filter = new SqlFilterLike(new SqlFilterColumn(TableNames::GROUPS_ALIAS, ColumnNames::GROUP_NAME), $term);
        $r = new GroupRepository();
        return $r->GetList(1, PageInfo::All, null, null, $filter)->Results();
    }

    /**
     * @param $term string
     * @return array|AutocompleteUser[]
     */
    private function GetMyUsers($term)
    {
        $userSession = ServiceLocator::GetServer()->GetUserSession();
        if ($userSession->IsAdmin || $userSession->IsResourceAdmin || $userSession->IsScheduleAdmin) {
            return $this->GetUsers($term);
        }

        $userRepo = new UserRepository();
        $user = $userRepo->LoadById($userSession->UserId);

        $groupIds = [];

        foreach ($user->GetAdminGroups() as $group) {
            $groupIds[] = $group->GroupId;
        }

        $users = [];
        if (!empty($groupIds)) {
            $userFilter = new SqlFilterLike(ColumnNames::FIRST_NAME, $term);
            $userFilter->_Or(new SqlFilterLike(ColumnNames::LAST_NAME, $term));

            $groupRepo = new GroupRepository();
            $results = $groupRepo->GetUsersInGroup($groupIds, null, null, $userFilter)->Results();

            /** @var UserItemView $result */
            foreach ($results as $result) {
                // consolidates results by user id if the user is in multiple groups
                $users[$result->Id] = new AutocompleteUser($result->Id, $result->First, $result->Last, $result->Email, $result->Username);
            }
        }

        return array_values($users);
    }

    private function GetGroupUsers($groupId)
    {
        $groupRepo = new GroupRepository();
        $results = $groupRepo->GetUsersInGroup($groupId)->Results();

        $users = [];
        /** @var UserItemView $result */
        foreach ($results as $result) {
            // consolidates results by user id if the user is in multiple groups
            $users[$result->Id] = new AutocompleteUser($result->Id, $result->First, $result->Last, $result->Email, $result->Username);
        }

        return array_values($users);
    }

    private function GetOrganizations($term)
    {
        $filter = new SqlFilterLike(ColumnNames::ORGANIZATION, $term);

        $r = new UserRepository();
        $results = $r->GetList(1, PageInfo::All, null, null, $filter)->Results();

        $organizations = [];
        /** @var UserItemView $result */
        foreach ($results as $result) {
            $organizations[] = $result->Organization;
        }

        return $organizations;
    }
}

class XAutocompleteUser
{
    public $value;
    public $text;
    public function __construct($value, $text)
    {
        $this->value = $value;
        $this->text = $text;
    }
}

class AutocompleteUser
{
    public $Id;
    public $First;
    public $Last;
    public $Name;
    public $Email;
    public $UserName;
    public $CurrentCreditCount;
    public $DisplayName;

    public function __construct($userId, $firstName, $lastName, $email, $userName, $currentCreditCount = null)
    {
        $full = new FullName(htmlspecialchars_decode($firstName ?? '', ENT_QUOTES), htmlspecialchars_decode($lastName ?? '', ENT_QUOTES));
        $this->Id = $userId;
        $this->First = $firstName;
        $this->Last = $lastName;
        $this->Name = $full->__toString();
        $this->Email = $email;
        $this->UserName = $userName;
        $this->DisplayName = "{$full} ($email)";
        $this->CurrentCreditCount = floatval($currentCreditCount);
    }
}

class AutoCompleteType
{
    public const User = 'user';
    public const XUser = 'xuser';
    public const Group = 'group';
    public const MyUsers = 'myUsers';
    public const Organization = 'organization';
}
