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

To connect LibreBooking with your Oauth2 IdP, add the following settings to
the ``authentication`` section of your ``config/config.php`` file. This example
uses authentik as the IdP with the URL ``authentik.io``.

.. code-block:: php

   return [
       'settings' => [
           'authentication' => [
               'oauth2.login.enabled' => true,
               'oauth2.name' => 'authentik',
               'oauth2.strip.trailing.slash' => false,
               'oauth2.url.authorize' => 'https://authentik.io/application/o/authorize/',
               'oauth2.url.token' => 'https://authentik.io/application/o/token/',
               'oauth2.url.userinfo' => 'https://authentik.io/application/o/userinfo/',
               'oauth2.client.id' => 'c3zzBXq9Qw3K9KErd9ta6tQgvVhr6wT3rkQaInz8',
               'oauth2.client.secret' => '13246zgtfd4t456zhg8rdgf98g789df7gFG56z5zhb',
               'oauth2.client.uri' => '/Web/oauth2-auth.php',
           ],
       ],
   ];

Trailing Slash Handling
^^^^^^^^^^^^^^^^^^^^^^^

By default, LibreBooking strips the trailing slash from the configured
``oauth2.url.authorize`` URL. Some identity providers require the trailing slash
to be preserved. To keep the trailing slash as configured, set:

.. code-block:: php

   'oauth2.strip.trailing.slash' => false,

This setting only affects the authorize URL. The token and userinfo URLs are not
modified.

To hide the internal LibreBooking login prompt, also set:

.. code-block:: php

   return [
       'settings' => [
           'authentication' => [
               'hide.login.prompt' => true,
           ],
       ],
   ];
