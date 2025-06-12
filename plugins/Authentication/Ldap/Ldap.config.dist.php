<?php

// see http://pear.php.net/manual/en/package.networking.net-ldap2.connecting.php
//     https://www.php.net/manual/en/function.ldap-connect.php

$conf['settings']['host'] = 'ldap://localhost'; // comma separated list of ldap servers such as ldap://mydomain1,ldap://localhost
$conf['settings']['port'] = '389';      // default ldap port 389 or 636 for ssl.
$conf['settings']['version'] = '3';		// LDAP protocol version
$conf['settings']['starttls'] = 'false';	// TLS is started after connecting
$conf['settings']['binddn'] = '';	// The distinguished name to bind as (username). If you don't supply this, an anonymous bind will be established.
$conf['settings']['bindpw'] = '';	// Password for the binddn. If the credentials are wrong, the bind will fail server-side and an anonymous bind will be established instead. An empty bindpw string requests an unauthenticated bind.
$conf['settings']['basedn'] = '';	// LDAP base name (eg. dc=example,dc=com)
$conf['settings']['filter'] = '';	// Default search filter
$conf['settings']['scope'] = '';	// Search scope (eg. uid)
$conf['settings']['required.group'] = '';	// Required group (empty if not necessary) (eg. cn=MyGroup,cn=Groups,dc=example,dc=com)
$conf['settings']['database.auth.when.ldap.user.not.found'] = 'false';	// if ldap auth fails, authenticate against LibreBooking database
$conf['settings']['ldap.debug.enabled'] = 'false';	// if LDAP2 should use debug logging
$conf['settings']['attribute.mapping'] = 'sn=sn,givenname=givenname,mail=mail,telephonenumber=telephonenumber,physicaldeliveryofficename=physicaldeliveryofficename,title=title';	// mapping of required attributes to attribute names in your directory
$conf['settings']['user.id.attribute'] = 'uid';	// the attribute name for user identification
$conf['settings']['sync.groups'] = 'false';
$conf['settings']['prevent.clean.username'] = 'false';	// If the username is an email address or contains the domain, clean it
