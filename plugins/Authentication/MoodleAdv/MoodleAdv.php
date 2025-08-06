<?php

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'lib/Database/MySQL/namespace.php');
require_once(ROOT_DIR . 'plugins/Authentication/MoodleAdv/MoodleAdvOptions.php');

class MoodleAdv extends Authentication implements IAuthentication
{
    /**
     * @var IAuthentication
     */
    private $authToDecorate;

    /**
     * @var IRegistration
     */
    private $_registration;

    /**
     * @var MoodleAdvOptions
     */
    private $options;

    /**
     * Needed to register user if they are logging in to Moodle but do not have a LibreBooking account yet
     */
    private function GetRegistration()
    {
        if ($this->_registration == null) {
            $this->_registration = new Registration();
        }

        return $this->_registration;
    }

    private $db;
    private $retryDB;
    private $authmethod;
    private $moodleRoles;
    private $moodleField;

    /**
     * @param IAuthentication $authentication Authentication class to decorate
     */
    public function __construct(IAuthentication $authentication)
    {
        $this->options = new MoodleAdvOptions();

        $this->authmethod = $this->options->GetAuthMethod();

        if ($this->authmethod === 'roles') {
            $this->moodleRoles = $this->options->GetRoles();
        } elseif ($this->authmethod === 'field') {
            $this->moodleField = $this->options->GetField();
        }

        $this->authToDecorate = $authentication;
    }

    /**
     * Called first to validate credentials
     * @see IAuthorization::Validate()
     */
    public function Validate($username, $password)
    {
        Log::Debug('MOODLEADV: Validating user');
        $account = $this->GetMoodleUser($username);
        if ($account && $this->user_check_password($password, $account)) {
            return true;
        };
        Log::Debug('MOODLEADV: User not found or wrong password');
        return false;
    }

    /**
     * Called after Validate returns true
     * @see IAuthorization::Login()
     */
    public function Login($username, $loginContext)
    {
        $account = $this->GetMoodleUser($username);
        $this->GetRegistration()->Synchronize(new AuthenticatedUser(
            $account->username,
            $account->email,
            $account->firstname,
            $account->lastname,
            '',
            Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE),
            Configuration::Instance()->GetDefaultTimezone(),
            null,
            null,
            null
        ));
        $repo = new UserRepository();
        $user = $repo->LoadByUsername($username);
        $user->Deactivate();
        $user->Activate();
        $repo->Update($user);
        return $this->authToDecorate->Login($username, $loginContext);
    }

    /**
     * @see IAuthorization::Logout()
     */
    public function Logout(UserSession $user)
    {
        $this->authToDecorate->Logout($user);
    }

    /**
     * @see IAuthorization::AreCredentialsKnown()
     */
    public function AreCredentialsKnown()
    {
        return false;
    }

    /**
     * @param $username
     * @return mixed
     */
    private function GetMoodleUser($username)
    {
        // Connect to Moodle database using settings from configuration
        $moodleDb = new Database(new MySqlConnection(
            $this->options->GetDbUser(),
            $this->options->GetDbPass(),
            $this->options->GetDbHost(),
            $this->options->GetDbName()
        ));

        $prefix = $this->options->GetTablePrefix();

        switch ($this->authmethod) {
            case 'roles':
                if ($m_roles = count($this->moodleRoles)) {
                    $query = 'SELECT u.* FROM ' . $prefix . 'user u JOIN ' . $prefix . 'role_assignments a ';
                    $query .= 'ON u.id=a.userid WHERE u.deleted=0 AND u.suspended=0 ';
                    $query .= 'AND u.username=@user AND a.roleid IN (';
                    $delimiter = '';
                    for ($i = 0; $i < $m_roles; $i++) {
                        $query .= $delimiter . '@role' . $i;
                        $delimiter = ', ';
                    }
                    $query .= ')';
                }
                $command = new AdHocCommand($query);
                $command->AddParameter(new Parameter('@user', $username));
                if ($m_roles) {
                    $rid = 0;
                    foreach ($this->moodleRoles as $role) {
                        $command->AddParameter(new Parameter('@role' . $rid++, $role));
                    }
                }
                break;
            case 'field':
                $query = 'SELECT u.* FROM ' . $prefix . 'user u JOIN ' . $prefix . 'user_info_data a ';
                $query .= 'ON u.id=a.userid WHERE u.deleted=0 AND u.suspended=0 AND a.data=1 ';
                $query .= 'AND u.username=@user AND a.fieldid=@field';
                $command = new AdHocCommand($query);
                $command->AddParameter(new Parameter('@user', $username));
                $command->AddParameter(new Parameter('@field', $this->moodleField));
                break;
            case 'all':
                $query = 'SELECT u.* FROM ' . $prefix . 'user u ';
                $query .= 'WHERE u.deleted=0 AND u.suspended=0 ';
                $query .= 'AND u.username=@user ';
                $command = new AdHocCommand($query);
                $command->AddParameter(new Parameter('@user', $username));
                break;
        }

        $reader = $moodleDb->Query($command);

        if ($row = $reader->GetRow()) {
            $account = new stdClass();
            foreach ($row as $k => $v) {
                $account->$k = $v;
            }
            return $account;
        }

        return false;
    }

    public function AllowUsernameChange()
    {
        return false;
    }

    public function AllowEmailAddressChange()
    {
        return false;
    }

    public function AllowPasswordChange()
    {
        return false;
    }

    public function AllowNameChange()
    {
        return false;
    }

    public function AllowPhoneChange()
    {
        return false;
    }

    public function AllowOrganizationChange()
    {
        return false;
    }

    public function AllowPositionChange()
    {
        return false;
    }

    public function user_check_password($password, $account)
    {
        return password_verify($password, $account->password);
    }
}
