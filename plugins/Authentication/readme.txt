=======================
Creating an Authentication plugin
=======================

LibreBooking was designed to make authentication pluggable so that new credential stores can be without changes to the LibreBooking source code.
LibreBooking ships with an LDAP authentication plugin.  This should serve as a good example because it is sufficiently complex and covers a typical scenario.

To create a new Authentication plugin:

- Create a new folder with the name of your plugin within /plugins/Auth
- Create a PHP source file with the same folder name within this folder.  For example, /plugins/Auth/Ldap contains Ldap.php.
    This source file is what will be called during the authentication process.
- Within this source file, require the Authentication namespace and implement the IAuthentication interface
	<?php
	require_once(ROOT_DIR . 'lib/Authentication/namespace.php');
	class Ldap implements Authentication { ... }
	?>
- In the LibreBooking configuration file, set $conf['settings']['plugins']['Authentication'] to the folder name. For example, $conf['settings']['plugins']['Authentication'] = 'Ldap';

This class is intended to decorate an IAuthentication instance, so in most cases you would want to create a new Authentication() class in the constructor if one isn't supplied as a parameter.
This class must support parameterless instantiation.

There are two methods which need to be implemented:

1) Validate() accepts $username and $password as parameters.
This method should go to the credential store and validate that this user has proper access.
This method returns true or false depending on if the user is authorized or not.

2) Login() accepts $username and $loginContext as parameters.
This method is only called if the result of Validate() was true.  Typically, this method would synchronize data between the source store and Booked Scheduler,
although this is not required if the user account data already exists in LibreBooking.
If synchronization is necessary, the account should be registered if it does not exist or updated if it does. The Ldap plugin illustrates how to accomplish this.
The Login method should always call to the decorated Authentication classes Login() method. This ensures that any functionality required by LibreBooking is executed.

Persistent sign-on through cookies ("Remember Me") has been removed. Plugins no longer implement CookieLogin(), and the
Persist value of the login context is ignored. ShowPersistLoginPrompt() must still be implemented, but its result is not used.