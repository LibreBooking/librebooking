<?php

interface IRegistration
{
    /**
     * @param string $login
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $password unencrypted password
     * @param string|null $timezone name of user timezone; empty for the
     *        configured default timezone
     * @param string $language preferred language code
     * @param int|null $homepageId lookup id of the page to redirect the user to on login; null for the default page
     * @param array $additionalFields key value pair of additional fields to use during registration
     * @param array|AttributeValue[] $attributeValues
     * @param null|UserGroup[] $groups
     * @param bool $acceptTerms
     * @return User
     */
    public function Register($login, $email, $firstName, $lastName, $password, $timezone, $language, $homepageId, $additionalFields = [], $attributeValues = [], $groups = null, $acceptTerms = false);

    /**
     * @param string $loginName
     * @param string $emailAddress
     * @return bool if the user exists or not
     */
    public function UserExists($loginName, $emailAddress);

    /**
     * Add or update a user who has already been authenticated
     * @param AuthenticatedUser $user
     * @param bool $insertOnly
     * @param bool $overwritePassword
     * @return void
     */
    public function Synchronize(AuthenticatedUser $user, $insertOnly = false, $overwritePassword = true);
}
