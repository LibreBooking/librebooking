<?php

require_once(ROOT_DIR . 'plugins/Authentication/Drupal/DrupalConfigKeys.php');
require_once(ROOT_DIR . 'plugins/Authentication/Drupal/Drupal.config.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'lib/Database/MySQL/namespace.php');

define('DRUPAL_HASH_COUNT', 15);
define('DRUPAL_MIN_HASH_COUNT', 7);
define('DRUPAL_MAX_HASH_COUNT', 30);
define('DRUPAL_HASH_LENGTH', 55);



class Drupal extends Authentication implements IAuthentication
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
     * Needed to register user if they are logging in to Drupal but do not have a LibreBooking account yet
     */
    private function GetRegistration()
    {
        if ($this->_registration == null) {
            $this->_registration = new Registration();
        }

        return $this->_registration;
    }

    private $db;

    private $allowed_roles;

    /**
     * @param IAuthentication $authentication Authentication class to decorate
     */
    public function __construct(IAuthentication $authentication)
    {
        $drupal_config_path = dirname(__FILE__) . '/Drupal.config.php';
        require_once($drupal_config_path);

        $config = Configuration::Instance();
        $config->Register(
            $drupal_config_path,
            '',
            'DRUPAL',
            false,
            DrupalConfigKeys::class
        );

        $drupalDir = $config->File('DRUPAL')->GetKey(DrupalConfigKeys::DRUPAL_ROOT_DIR);
        require_once($drupalDir . '/sites/default/settings.php');

        $this->db = $databases['default']['default'];

        $drupalRoles = $config->File('DRUPAL')->GetKey(DrupalConfigKeys::DRUPAL_ALLOWED_ROLES);
        $this->allowed_roles = $drupalRoles ? explode(',', $drupalRoles) : [];

        $this->authToDecorate = $authentication;
    }

    /**
     * Called first to validate credentials
     * @see IAuthorization::Validate()
     */
    public function Validate($username, $password)
    {
        $account = $this->GetDrupalAccount($username);

        if (!$account) {
            Log::Debug('DRUPAL: Could not find Drupal account for user=%s', $username);
            return false;
        }
        if (!$this->user_check_password($password, $account)) {
            Log::Debug('DRUPAL: Drupal account found but password was incorrect for user=%s', $username);
            return false;
        }

        Log::Debug('DRUPAL: User was found. user=%s, Drupal username=%s, Drupal email=%s, LibreBooking admin email=%s', $username, $account->name, $account->mail, Configuration::Instance()->GetAdminEmail());
        return true;
    }

    /**
     * Called after Validate returns true
     * @see IAuthorization::Login()
     */
    public function Login($username, $loginContext)
    {
        $account = $this->GetDrupalAccount($username);
        $this->GetRegistration()->Synchronize(new AuthenticatedUser(
            $account->name,
            $account->mail,
            '',
            '',
            '',
            $loginContext->GetData()->Language,
            $account->timezone,
            null,
            null,
            null
        ));
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
    private function GetDrupalAccount($username)
    {
        $db = $this->db;

        $dbname = $db['database'];
        if (!empty($db['prefix'])) {
            $dbname = "{$db['prefix']}$dbname";
        }

        // $db['port'] should be passed as a separate argument, per http://php.net/manual/mysqli.construct.php
        $drupalDb = new Database(new MySqlConnection($db['username'], $db['password'], $db['host'], $dbname));

        $query = 'SELECT users_field_data.* FROM users_field_data WHERE name = @user OR mail = @user';
        if ($nb_roles = count($this->allowed_roles)) {
            $query = 'SELECT users_field_data.* FROM users_field_data INNER JOIN user__roles ';
            $query .= 'ON user__roles.entity_id = users_field_data.uid WHERE (name = @user OR mail = @user) ';
            $query .= 'AND bundle = @bundle AND roles_target_id IN (';
            $delimiter = '';
            for ($i = 0; $i < $nb_roles; $i++) {
                $query .= $delimiter . '@role' . $i;
                $delimiter = ', ';
            }
            $query .= ')';
        }

        $command = new AdHocCommand($query);
        $command->AddParameter(new Parameter('@user', $username));
        if ($nb_roles) {
            $command->AddParameter(new Parameter('@bundle', 'user'));
            $rid = 0;
            foreach ($this->allowed_roles as $role) {
                $command->AddParameter(new Parameter('@role' . $rid++, $role));
            }
        }
        $reader = $drupalDb->Query($command);

        if ($row = $reader->GetRow()) {
            $account = new stdClass();
            foreach ($row as $k => $v) {
                $account->$k = $v;
            }
            return $account;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function AllowUsernameChange()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function AllowEmailAddressChange()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function AllowPasswordChange()
    {
        return false;
    }

    /**
     * Copyright Drupal
     * @see \includes\password.inc
     */

    public function user_check_password($password, $account)
    {
        if (substr($account->pass, 0, 2) == 'U$') {
            // This may be an updated password from user_update_7000(). Such hashes
            // have 'U' added as the first character and need an extra md5().
            $stored_hash = substr($account->pass, 1);
            $password = md5($password);
        } else {
            $stored_hash = $account->pass;
        }

        $type = substr($stored_hash, 0, 4);
        // Drupal 10.1.0 uses password_hash(), see https://www.drupal.org/node/3322420
        if ($type == '$2y$') {
            return password_verify($password, $stored_hash);
        }

        $type = substr($stored_hash, 0, 3);
        switch ($type) {
            case '$S$':
                // A normal Drupal 7 password using sha512.
                $hash = $this->_password_crypt('sha512', $password, $stored_hash);
                break;
            case '$H$':
            // phpBB3 uses "$H$" for the same thing as "$P$".
            case '$P$':
                // A phpass password generated using md5.  This is an
                // imported password or from an earlier Drupal version.
                $hash = $this->_password_crypt('md5', $password, $stored_hash);
                break;
            default:
                return false;
        }
        return ($hash && $stored_hash == $hash);
    }

    public function _password_crypt($algo, $password, $setting)
    {
        // Prevent DoS attacks by refusing to hash large passwords.
        if (strlen($password) > 512) {
            return false;
        }
        // The first 12 characters of an existing hash are its setting string.
        $setting = substr($setting, 0, 12);

        if ($setting[0] != '$' || $setting[2] != '$') {
            return false;
        }
        $count_log2 = $this->_password_get_count_log2($setting);
        // Hashes may be imported from elsewhere, so we allow != DRUPAL_HASH_COUNT
        if ($count_log2 < DRUPAL_MIN_HASH_COUNT || $count_log2 > DRUPAL_MAX_HASH_COUNT) {
            return false;
        }
        $salt = substr($setting, 4, 8);
        // Hashes must have an 8 character salt.
        if (strlen($salt) != 8) {
            return false;
        }

        // Convert the base 2 logarithm into an integer.
        $count = 1 << $count_log2;

        // We rely on the hash() function being available in PHP 5.2+.
        $hash = hash($algo, $salt . $password, true);
        do {
            $hash = hash($algo, $hash . $password, true);
        } while (--$count);

        $len = strlen($hash);
        $output = $setting . $this->_password_base64_encode($hash, $len);
        // _password_base64_encode() of a 16 byte MD5 will always be 22 characters.
        // _password_base64_encode() of a 64 byte sha512 will always be 86 characters.
        $expected = 12 + ceil((8 * $len) / 6);
        return (strlen($output) == $expected) ? substr($output, 0, DRUPAL_HASH_LENGTH) : false;
    }

    public function _password_get_count_log2($setting)
    {
        $itoa64 = $this->_password_itoa64();
        return strpos($itoa64, $setting[3]);
    }

    public function _password_base64_encode($input, $count)
    {
        $output = '';
        $i = 0;
        $itoa64 = $this->_password_itoa64();
        do {
            $value = ord($input[$i++]);
            $output .= $itoa64[$value & 0x3f];
            if ($i < $count) {
                $value |= ord($input[$i]) << 8;
            }
            $output .= $itoa64[($value >> 6) & 0x3f];
            if ($i++ >= $count) {
                break;
            }
            if ($i < $count) {
                $value |= ord($input[$i]) << 16;
            }
            $output .= $itoa64[($value >> 12) & 0x3f];
            if ($i++ >= $count) {
                break;
            }
            $output .= $itoa64[($value >> 18) & 0x3f];
        } while ($i < $count);

        return $output;
    }

    public function _password_itoa64()
    {
        return './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    }
}
