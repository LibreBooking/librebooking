Oauth2 Configuration
====================

You can use any IdP (Identity Provider) which supports Oauth2 like
`authentik <https://goauthentik.io>`__ or
`Keycloak <https://www.keycloak.org/>`__ for authentication with
LibreBooking

IdP Configuration
-----------------

First you need to create a Client in your IdP in Confidential mode
(Client ID and Client Secret). The Client need to allow redirects to
``<LibreBooking URL>/Web/oauth2-auth.php`` ex.
``https://librebooking.com/Web/oauth2-auth.php`` and needs the scopes
``openid``, ``email`` and ``profile``.

The mapping of Oauth2 attributes to LibreBooking attributes is:

-  ``email`` -> ``email``
-  ``given_name`` -> ``firstName``
-  ``family_name`` -> ``lastName``
-  ``preferred_username`` -> ``username``
-  ``phone`` -> ``phone_number``
-  ``organization`` -> ``organization``
-  ``title`` -> ``title``

LibreBooking Config
-------------------

To connect LibreBooking with your Oauth2 IdP you need to add the
following vars to your ``config.php``, in this example with authentik as
IdP with the url ``authentik.io``.

.. code:: php

   $conf['settings']['authentication']['allow.oauth2.login'] = 'true';  // Enable Oauth2
   $conf['settings']['authentication']['oauth2.name'] = 'authentik'; // Display name of Oauth2 IdP
   $conf['settings']['authentication']['oauth2.url.authorize'] = 'https://authentik.io/application/o/authorize/'; // Oauth2 Authorization Endpoint
   $conf['settings']['authentication']['oauth2.url.token'] = 'https://authentik.io/application/o/token/'; // Oauth2 Token Endpoint
   $conf['settings']['authentication']['oauth2.url.userinfo'] = 'https://authentik.io/application/o/userinfo/'; // Oauth2 Userinfo Endpoint
   $conf['settings']['authentication']['oauth2.client.id'] = 'c3zzBXq9Qw3K9KErd9ta6tQgvVhr6wT3rkQaInz8';
   $conf['settings']['authentication']['oauth2.client.secret'] = '13246zgtfd4t456zhg8rdgf98g789df7gFG56z5zhb';

To hide the internal LibreBooking Login you can additional add the
following variable.

.. code:: php

   $conf['settings']['authentication']['hide.booked.login.prompt'] = 'true';
