SAML (Security Assertion Markup Language) Configuration
=======================================================

**It is important to make sure registration.require.email.activation is
set to false in Application Configuration. If email activation is
enabled users will never be able to log in.**

LibreBooking SAML Introduction
------------------------------

LibreBooking comes with multiple Single Sign On plugins out of the box.
There are many benefits to SSO over standard authentication. For
administrators, having a single point of account credential and access
administration is very valuable. If someone leaves the organization they
don’t have to deactivate accounts in multiple systems. For your normal
user, the benefit is not having to register and remember yet another set
of application credentials.

In this post we’ll cover how to set up SSO with SAML. Most SSO
configurations for LibreBooking are pretty straightforward – you just
update the configuration options for the plugin. But SAML is different.
SAML requires a 3rd party application called
`SimpleSAMLphp <http://web.archive.org/web/20210303172340/https://simplesamlphp.org/>`__
to be running on the same server as LibreBooking.

Install SimpleSAMLphp
---------------------

Our first step is to download the latest version of
`SimpleSAMLphp <http://web.archive.org/web/20210303172340/https://simplesamlphp.org/>`__
and install it on your web server. I recommend installing it outside
your publicly visible directories and set up a subdomain pointing to the
www directory. For example, if you install it to
``/home/username/simplesamlphp`` and you have LibreBooking running out
of ``/home/username/public\_html/librebooking``, then you’d create a
subdomain such as ``saml.librebooking.xpto`` pointing to
``/home/username/simplesamlphp/www``. The reason we do this is because
the only files which need to be publicly visible in SimpleSAMLphp are
located in the www directory. Exposing more than that opens unnecessary
security holes.

Configure SimpleSAMLphp
-----------------------

SimpleSAMLphp has a lot of configuration options. If you’re like me and
far from an expert in SAML, it’s overwhelming. Luckily, since
LibreBooking is a Service Provider it doesn’t need anything special.
I’ll go through each of the settings that need to be updated
individually.

.. note::
   At the time of writing this post, the latest version of SimpleSAMLphp was
   1.18.5. It’s possible that the names of the options will change in future
   versions.

Copy ``/home/username/simplesamlphp/config/config.php.dist`` to
``/home/username/simplesamlphp/config/config.php``

Open up ``/home/username/simplesamlphp/config/config.php`` with a text
editor.

``baseurlpath`` - Set this to the full path of the SimpleSAMLphp WWW
directory. If you followed the above advice and created a subdomain,
this should be something like ``https://saml.yourdomain.com``

``technicalcontact_email`` - Your email address (or anyone responsible
for managing SSO integrations)

``secretsalt`` - Set this to any secure, random value.

``auth.adminpassword`` - Set this to any secure, random value, you will
use this to access the admin page of the web UI for SimpleSAML.

``trusted.url.domains`` - This should be set to an array of domains that
will participate in the SSO handshake. I use
``array('saml.librebooking.com', 'librebooking.com')``

``session.cookie.domain`` - This should be set to the wildcard subdomain
of your primary domain. For example, I use ``.librebooking.com``

``session.cookie.secure`` - This should be set to true, assuming all
traffic is sent over https.

``store.type`` - Set this to ``sql``. This ensures that PHP sessions
from LibreBooking and sessions from SimpleSAMLphp do not conflict.

``store.sql.dsn`` - This should be set to a writable location for the
sqlite database. You **must** have SQLite support in PHP enabled for
this to work. Alternatively, you can set up any PDO supported database
to store session data. Since I use SQLite, I have this set to something
like ``sqlite:/home/username/tmp/sqlitedatabase.sq3``

Exchange Metadata
-----------------

Now that we have the configuration set, we’ll need to exchange metadata.

The first thing to do is get the metadata XML from the Identity Provider
that you’re integrating with. For example in Azure apps you can find
this under Manage -> Single sign-on -> SAML Certificates -> Federation
Metadata XML

SimpleSAMLphp has a handy metadata XML conversion tool, which we’ll use
to finish up our configuration.

-  Open the admin page from the subdomain for SimpleSAMLphp in a browser
   (https://saml.librebooking.com/admin was what I used).
-  You’ll be prompted to enter the *auth.adminpassword* that you set in
   your config.php
-  Click on the *Federation* tab
-  then the *XML to SimpleSAMLphp metadata converter* link.
-  Paste in the XML or, if you have it saved to a file, upload it.
-  SimpleSAMLphp will output at least one PHP version of that metadata.
-  For each one of those, create a file with that name plus ``.php`` in
   the folder ``/home/username/simplesamlphp/metadata``. The file should
   contain ``<?php`` followed by the PHP structured metadata provided by
   the website in the previous step.
-  Copy the value of the ``entityid`` (usually found on the 3rd line of
   that file). It’s a full URL, eg.
   https://sts.windows.net/1111111-1111-1111-1111-111111111111/
-  Open up ``/simplesamlphp/config/authsources.php``
-  Find the ``idp`` setting, and paste the value of the remote
   ``entityid`` into the ``idp`` field.
-  Then set the local ``entityid`` field to a value of your choice.
   Usually the URL of the website you are creating SSO for. Remember
   this value as you will need this value in a later step when you
   configure the remote single sign on provider.

Update SAML Configuration in LibreBooking
-----------------------------------------

Whew, almost done! The last few settings are in LibreBooking.

First, open up ``/your-librebooking-directory/config/config.php`` and
set the authentication:

``$conf['settings']['plugins']['Authentication'] = 'Saml';``

Then go to the folder
``/your-librebooking-directory/plugins/Authentication/Saml``

Then copy ``Saml.config.dist.php`` to ``Saml.config.php``.

Open ``Saml.config.php`` in an editor:

``simplesamlphp.lib`` - set this to the root filesystem directory of
SimpleSAMLphp. If you’re using the settings I described here, this would
be ``/home/username/simplesamlphp``.

``simplesamlphp.config`` - Set this to the config filesystem directory
for SimpleSAMLphp. In this case ``/home/username/simplesamlphp/config``

Most of the remaining settings are attribute maps. SAML will send over
user attributes, but often with obscure names. LibreBooking needs to
know which attribute maps to the proper user field in LibreBooking.

There are only 2 absolutely required fields to map – username/userid and
email. For example, if the username is being sent across in the SAML
payload as ``urn:oid:0.1.2.3`` you’d set ``simplesamlphp.username`` to
this value like
``$conf['settings']['simplesamlphp.username'] = 'urn:oid:0.1.2.3';``

This is the same for all the other attributes. If you don’t know the
attributes coming across then you can add the following line to
plugins/Authentication/Saml/SamlUser.php as the first line in the
constructor:
``Log::Debug('Saml attributes are: %s', var_export($saml_attributes, true));``
Enable Logging in LibreBooking and try to log in. We’ll write out the
attributes to the log file and you can copy the names into the
LibreBooking SAML configuration file.

Configuring the other end
-------------------------

You will need to configure the other end. For example the Azure
Application Saml SSO settings.

First of all, you need to set the identifier ID, which is the value you
used for your local entityId at the end of the section titled “Exchange
Metadata”.

Then you need to tell it the URL to send data back to. This is called
the ACS or Assertion Consumer Service URL or Reply URL. Set it to
https://your-simplesaml-url/module.php/saml/sp/saml2-acs.php/default-sp

You probably also want to set a logout URL which should be:
https://your-simplesaml-url/module.php/saml/sp/saml2-logout.php/default-sp

Some Restrictions
-----------------

A couple important notes with SAML enabled:

.. warning::

    You will no longer be able to log into LibreBooking with any other
    credentials. There is no “back door” – so every authentication request will
    be routed through SAML.

.. warning::

    You will not be able to use any authenticated method from the API. SAML
    performs a series of browser redirects in order to complete the
    authentication process. When using the API you are not within the context
    of a browser, so authentication will fail.

Logging In
----------

Once all the mapping is complete, you should be able to log into
LibreBooking via your organization’s federated log in page. Your users
will no longer have to remember another set of credentials and your
account management just got one step easier!
