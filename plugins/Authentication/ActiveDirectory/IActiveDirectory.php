<?php

interface IActiveDirectory
{
    /**
     * @return bool If connection was successful
     */
    public function Connect();

    /**
     * @return bool If authentication was successful
     */
    public function Authenticate($username, $password);

    /**
     * @return ActiveDirectoryUser|null The details for the user, or null if they could not be loaded
     */
    public function GetLdapUser($username);
}
