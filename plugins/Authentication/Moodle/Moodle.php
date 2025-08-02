<?php

define('ABORT_AFTER_CONFIG', true);
define('NO_OUTPUT_BUFFERING', true);
define('IGNORE_COMPONENT_CACHE', true);

define('MOODLE_MANAGER_ROLE_ID', 1);
define('MOODLE_EDITING_TEACHER_ROLE_ID', 3);
define('MOODLE_TEACHER_ROLE_ID', 4);
define('DOCENTEN_GROUP_ID', 2);

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'plugins/Authentication/Moodle/namespace.php');
require_once(ROOT_DIR . 'lib/Database/MySQL/namespace.php');

/**
 * Provides Moodle authentication/synchronization for phpScheduleIt
 * @see IAuthorization
 */
class Moodle extends Authentication implements IAuthentication
{
    /**
     * @var IAuthentication
     */
    private $authToDecorate;

    /**
     * @var MoodleOptions
     */
    private $options;

    /**
     * @var IRegistration
     */
    private $_registration;

    /**
     * @var string
     */
    private $password;

    /** @var moodle user */
    private $user;

    public function SetRegistration($registration)
    {
        $this->_registration = $registration;
    }

    private function GetRegistration()
    {
        if ($this->_registration == null) {
            $this->_registration = new Registration();
        }

        return $this->_registration;
    }

    /**
     * @param IAuthentication $authentication Authentication class to decorate
     */
    public function __construct(IAuthentication $authentication)
    {
        Log::Debug('Moodle authentication plugin - __construct');

        $this->authToDecorate = $authentication;

        if (!ServiceLocator::GetServer()->GetUserSession()->IsLoggedIn()) {
            $this->CheckForMoodleSession();
        }
    }

    private function CheckForMoodleSession()
    {
        global $CFG;

        Log::Debug('Moodle authentication plugin - in CheckForMoodleSession');

        $this->options = new MoodleOptions();

        // phpScheduleIt can only 'see' Moodle cookies if they are on the same (main) domain
        // from Moodle 2.6 this can be set in Site Administration>Server>Session Handling (admin/settings.php?section=sessionhandling)
        $server = ServiceLocator::GetServer();
        if ($moodleCookie = $server->GetCookie($this->options->GetMoodleCookieId())) {
            $moodlesid = $_COOKIE[$this->options->GetMoodleCookieId()];

            Log::Debug('Moodle authentication plugin - using Moodle session: ' . $moodleCookie);

            require_once($this->options->GetPath() . 'config.php');

            Log::Debug('Moodle authentication plugin - Moodle config loaded from ' . $this->options->GetPath() . 'config.php');

            // get Moodle username from existing Moodle session in database
            $moodledb = new Database(new MySqlConnection($CFG->dbuser, $CFG->dbpass, $CFG->dbhost, $CFG->dbname));
            $reader = $moodledb->Query(new GetMoodleSessionCommand($moodlesid));
            if ($moodleuser = (object) $reader->GetRow()) {
                Log::Debug('Moodle authentication plugin - valid Moodle user found: ' . $moodleuser->username);

                $userRepository = new UserRepository();
                if ($this->user = $userRepository->LoadByUsername($moodleuser->username)) {
                    $loginData = new LoginData();
                    $webLoginContext = new WebLoginContext($loginData);

                    $userSession = $this->authToDecorate->Login($moodleuser->username, $webLoginContext);
                    if ($userSession->IsLoggedIn()) {
                        $server->SetUserSession($userSession);
                        return true;
                    }
                }
            }
        } else {
            Log::Debug('Moodle authentication plugin - no Moodle session found');
        }

        return false;
    }

    public function Validate($username, $password)
    {
        global $CFG;

        Log::Debug('Moodle authentication plugin - Validate');

        if ($username == 'api') {
            return $this->authToDecorate->Validate($username, $password);
        }

        Log::Debug('Attempting to authenticate user against Moodle. User=%s', $username);

        require_once($this->options->GetPath() . 'config.php');

        Log::Debug('Moodle authentication plugin - Moodle config loaded from ' . $this->options->GetPath() . 'config.php');

        $moodledb = new Database(new MySqlConnection($CFG->dbuser, $CFG->dbpass, $CFG->dbhost, $CFG->dbname));

        // Note: only teachers are allowed to login using their Moodle account
        // This is checked by looking at mdl_role_assignments for users with a role_id = 3
        $reader = $moodledb->Query(new GetMoodleUserCommand($username));
        if ($moodleuser = (object) $reader->GetRow()) {
            require_once($this->options->GetPath() . 'lib/moodlelib.php');

            if (validate_internal_user_password($moodleuser, $password)) {
                Log::Debug('Moodle authentication successful. User=%s', $username);
                $this->user = $moodleuser;
                $this->password = $password;
                return true;
            } else {
                Log::Debug('Moodle authentication failed. User=%s', $username);
                if ($this->options->RetryAgainstDatabase()) {
                    Log::Debug('Moodle authentication retrying against internal database');
                    return $this->authToDecorate->Validate($username, $password);
                }
            }
        }

        return false;
    }

    public function Login($username, $loginContext)
    {
        Log::Debug('Moodle authentication plugin - Login in with username: %s', $username);
        if ($this->UserExists()) {
            Log::Debug('Running Moodle user synchronization for username: %s', $username);
            $this->Synchronize();
        } else {
            Log::Debug('Skipping Moodle user synchronization, user not loaded');
        }

        return $this->authToDecorate->Login($username, $loginContext);
    }

    public function Logout(UserSession $user)
    {
        Log::Debug('Moodle authentication plugin - Logout');

        $this->authToDecorate->Logout($user);
    }

    public function AreCredentialsKnown()
    {
        Log::Debug('Moodle authentication plugin - AreCredentialsKnown');

        return false;
    }

    private function UserExists()
    {
        Log::Debug('Moodle authentication plugin - UserExists');

        return ($this->user != null) && $this->user->exists();
    }

    private function Synchronize()
    {
        Log::Debug('Moodle authentication plugin - Synchronize');
        // add the user
        $registration = $this->GetRegistration();
        $registration->Synchronize(new AuthenticatedUser(
            $this->user->username,
            $this->user->email,
            $this->user->firstname,
            $this->user->lastname,
            $this->password,
            Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE),
            Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_TIMEZONE),
            null,
            null,
            null
        ));

        $userRepository = new UserRepository();
        $user = $userRepository->LoadByUsername($this->user->username);

        // grab the Docenten group
        $groupRepository = new GroupRepository();
        if ($group = $groupRepository->LoadById(DOCENTEN_GROUP_ID)) {
            // add user to group Docenten
            $group->AddUser($user->Id());
            $groupRepository->Update($group);
        }
    }
}

class GetMoodleUserCommand extends SqlCommand
{
    public function __construct($username)
    {
        parent::__construct('SELECT u.* FROM mdl_user u JOIN mdl_role_assignments a ON u.id=a.userid WHERE u.deleted=0 AND u.suspended=0 AND (a.roleid=@roleid0 OR a.roleid=@roleid1 OR a.roleid=@roleid2) AND u.username=@username');
        $this->AddParameter(new Parameter('@roleid0', MOODLE_MANAGER_ROLE_ID));
        $this->AddParameter(new Parameter('@roleid1', MOODLE_EDITING_TEACHER_ROLE_ID));
        $this->AddParameter(new Parameter('@roleid2', MOODLE_TEACHER_ROLE_ID));
        $this->AddParameter(new Parameter('@username', $username));
    }
}

class GetMoodleSessionCommand extends SqlCommand
{
    // NB nog uitbreiden met check op ja/nee teacher of admin

    public function __construct($sid)
    {
        parent::__construct('SELECT u.* FROM mdl_sessions s JOIN mdl_user u ON s.userid=u.id AND NOT s.userid=0 WHERE s.state=0 AND s.sid=@sid AND u.suspended=0 AND u.deleted=0');
        $this->AddParameter(new Parameter('@sid', $sid));
    }
}
