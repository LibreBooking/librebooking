Custom Plugins
==============

LibreBooking plugins decorate existing application services. A plugin usually
wraps the default implementation, adds custom behavior before or after it, and
then returns control to the base service.

.. note::

   This documentation was generated with assistance from an AI tool and may
   contain errors. If you find an error, please submit a pull request with a
   correction when possible. If a pull request is not practical, please open an
   issue with the details.

.. warning::

   LibreBooking does not guarantee a stable plugin API. Plugin interfaces,
   constructor arguments, method signatures, loaded services, and domain objects
   may change in future releases. Test custom plugins before upgrading
   LibreBooking, and expect that existing plugins may need code changes after an
   upgrade.

Plugin Layout
-------------

Plugins are loaded by convention from the ``plugins`` directory:

.. code-block:: text

   plugins/<PluginType>/<PluginName>/<PluginName>.php

For example, a pre-reservation plugin named ``MyRule`` must be located at:

.. code-block:: text

   plugins/PreReservation/MyRule/MyRule.php

The class name must match the plugin name. The plugin manager instantiates the
class and passes the default implementation into the constructor, allowing the
plugin to decorate the normal behavior.

Supported plugin types include:

- ``Authentication``
- ``Authorization``
- ``Export``
- ``Permission``
- ``PostRegistration``
- ``PreReservation``
- ``PostReservation``
- ``Styling``

Plugin Types
------------

Choose the plugin type based on the part of LibreBooking you need to change.

``Authentication``
  Controls how users prove their identity and how login/logout behaves. This is
  useful for integrating LDAP, SAML, CAS, Shibboleth, WordPress, Moodle, web
  server authentication, or another external identity provider. Authentication
  plugins can validate credentials, create the user session, hide or show login
  form fields, and control whether users can change account details that are
  managed by an external system.

``Authorization``
  Controls high-level role and delegation decisions after a user is known. This
  is useful when administrator roles, approval rights, or "reserve for someone
  else" rules come from an external policy source instead of LibreBooking
  groups. Authorization plugins answer questions such as whether a user is an
  application administrator, resource administrator, group administrator, or can
  approve/reserve for another user.

``Permission``
  Controls resource access decisions. This is useful when resource visibility or
  booking permission must be calculated from external data, dynamic rules, or a
  custom entitlement system. Permission plugins answer whether a user can access,
  book, or view a specific resource.

``PreReservation``
  Runs before reservation actions are saved. This is useful for custom business
  rules that should block or allow reservation creation, updates, deletion,
  approval, check-in, or check-out. Examples include checking custom attribute
  values, preventing reservations outside department policy, enforcing external
  quotas, or restricting check-in/check-out to specific users.

``PostReservation``
  Runs after reservation actions are saved. This is useful for side effects and
  integrations that should happen only after LibreBooking has accepted the
  change. Examples include sending custom notifications, calling a webhook,
  syncing reservations to another system, writing an audit record, or replacing
  the default reservation email behavior.

``PostRegistration``
  Runs after a user completes self-registration. This is useful for changing
  what happens to newly registered users, such as sending custom activation
  messages, integrating with an external onboarding system, assigning initial
  state, or changing redirect behavior after registration.

``Export``
  Controls extra data in reservation exports. This is useful when iCalendar
  subscriptions need custom classification or additional iCalendar lines for
  downstream systems. Export plugins can mark events as ``PUBLIC``, ``PRIVATE``,
  or another supported iCalendar classification and can append custom event
  metadata.

``Styling``
  Adds custom presentation behavior. This is useful when the schedule should
  visually distinguish reservations based on attributes, resource state, or
  organization-specific rules. Styling plugins can provide an additional CSS
  file and add CSS classes to reservation items.

Enabling a Plugin
-----------------

Edit ``config/config.php`` and set the matching key in the ``plugins`` section.
The value is the plugin class name, which is also the plugin directory and file
name.

.. code-block:: php

   'plugins' => [
       'prereservation' => 'MyRule',
       'postreservation' => 'MyNotifier',
   ],

Only enable plugins that exist in the matching plugin type directory.

You can also enable plugins through the web admin interface at **Application
Configuration** (``/Web/admin/manage_configuration.php``). The plugin fields
accept any plugin class name; built-in plugin names appear as suggestions.

Manual Installation
-------------------

For a manual installation:

1. Copy the plugin directory into the matching ``plugins/<PluginType>``
   directory.
2. Ensure the web server can read the plugin files.
3. Enable the plugin in ``config/config.php`` or through the web admin
   configuration page.
4. Clear any opcode cache if PHP OPcache is enabled.
5. Test the workflow that loads the plugin.

Docker Installation
-------------------

For Docker deployments, plugin files must be present inside the running
container. Common approaches are:

- Build a custom image that copies the plugin directory into ``/app/plugins`` or
  the LibreBooking application directory used by your image.
- Mount the plugin directory as a volume into the matching
  ``plugins/<PluginType>/<PluginName>`` path.
- Keep ``config/config.php`` persistent and set the plugin key there, or provide
  the matching configuration through your deployment's supported configuration
  mechanism.

After changing mounted plugin files, restart the application container if PHP
OPcache or the web server does not pick up the change.

Authentication Plugins
----------------------

Authentication plugins control login behavior. They implement
``IAuthentication`` and decorate the default ``Authentication`` service.

The built-in examples include:

.. code-block:: text

   plugins/Authentication/Apache/Apache.php
   plugins/Authentication/Ldap/Ldap.php
   plugins/Authentication/Shibboleth/Shibboleth.php

Important methods:

``Validate($username, $password)``
  Checks whether the supplied credentials are valid. External authentication
  plugins often validate against LDAP, SAML, a web server variable, or another
  identity provider.

``Login($username, $loginContext)``
  Loads or creates the LibreBooking user session after validation succeeds. Most
  plugins delegate to the base authentication service once they have determined
  the LibreBooking username.

``Logout(UserSession $user)``
  Runs when a user logs out. Use this to clear external sessions or delegate to
  the base logout behavior.

``AreCredentialsKnown()``
  Returns ``true`` when credentials are already known before the login form is
  submitted, such as with web server authentication.

``HandleLoginFailure(IAuthenticationPage $loginPage)``
  Controls what happens when login fails.

``ShowUsernamePrompt()`` and ``ShowPasswordPrompt()``
  Control whether the login form asks for username and password. Single sign-on
  plugins often return ``false``.

``ShowPersistLoginPrompt()`` and ``ShowForgotPasswordPrompt()``
  Control whether the remember-me and forgot-password prompts are shown.

Profile field permission methods
  Control which profile fields users can edit when account data is managed by
  an external system. These include ``AllowUsernameChange()``,
  ``AllowEmailAddressChange()``, ``AllowPasswordChange()``,
  ``AllowNameChange()``, ``AllowPhoneChange()``,
  ``AllowOrganizationChange()``, and ``AllowPositionChange()``.

Optional URL hook methods
  ``GetRegistrationUrl()`` and ``GetPasswordResetUrl()`` are not part of the
  ``IAuthentication`` interface. ``WebAuthentication`` checks for them with
  ``method_exists()`` at runtime. Add them only when registration or password
  reset should link to an external system.

Minimal example:

.. code-block:: php

   <?php

   require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

   class MyAuth implements IAuthentication
   {
       private IAuthentication $base;

       public function __construct(IAuthentication $base)
       {
           $this->base = $base;
       }

       public function Validate($username, $password)
       {
           // Validate against an external identity provider here.
           return $this->base->Validate($username, $password);
       }

       public function Login($username, $loginContext)
       {
           return $this->base->Login($username, $loginContext);
       }

       public function Logout(UserSession $user)
       {
           $this->base->Logout($user);
       }

       public function AreCredentialsKnown()
       {
           return false;
       }

       public function HandleLoginFailure(IAuthenticationPage $loginPage)
       {
           $this->base->HandleLoginFailure($loginPage);
       }

       public function ShowUsernamePrompt()
       {
           return $this->base->ShowUsernamePrompt();
       }

       public function ShowPasswordPrompt()
       {
           return $this->base->ShowPasswordPrompt();
       }

       public function ShowPersistLoginPrompt()
       {
           return $this->base->ShowPersistLoginPrompt();
       }

       public function ShowForgotPasswordPrompt()
       {
           return $this->base->ShowForgotPasswordPrompt();
       }

       public function AllowUsernameChange()
       {
           return $this->base->AllowUsernameChange();
       }

       public function AllowEmailAddressChange()
       {
           return $this->base->AllowEmailAddressChange();
       }

       public function AllowPasswordChange()
       {
           return $this->base->AllowPasswordChange();
       }

       public function AllowNameChange()
       {
           return $this->base->AllowNameChange();
       }

       public function AllowPhoneChange()
       {
           return $this->base->AllowPhoneChange();
       }

       public function AllowOrganizationChange()
       {
           return $this->base->AllowOrganizationChange();
       }

       public function AllowPositionChange()
       {
           return $this->base->AllowPositionChange();
       }

       public function GetRegistrationUrl()
       {
           return method_exists($this->base, 'GetRegistrationUrl') ?
                   $this->base->GetRegistrationUrl() :
                   '';
       }

       public function GetPasswordResetUrl()
       {
           return method_exists($this->base, 'GetPasswordResetUrl') ?
                   $this->base->GetPasswordResetUrl() :
                   '';
       }
   }

Authorization Plugins
---------------------

Authorization plugins control role and delegation decisions. They implement
``IAuthorizationService`` and decorate the default ``AuthorizationService``.

Important methods:

``CanReserveForOthers(UserSession $reserver)``
  Determines whether the current user can reserve on behalf of other users.

``CanReserveFor(UserSession $reserver, $reserveForId)``
  Determines whether the current user can reserve for a specific user.

``CanApproveFor(UserSession $approver, $approveForId)``
  Determines whether the current user can approve reservations for a specific
  user.

``CanEditForResource(UserSession $user, IResource $resource)``
  Determines whether the current user can edit reservations for a resource.

``CanApproveForResource(UserSession $user, IResource $resource)``
  Determines whether the current user can approve reservations for a resource.

``IsApplicationAdministrator(User $user)``
  Determines whether a user has application administrator privileges.

``IsResourceAdministrator(User $user)``
  Determines whether a user is a resource administrator.

``IsGroupAdministrator(User $user)``
  Determines whether a user is a group administrator.

``IsScheduleAdministrator(User $user)``
  Determines whether a user is a schedule administrator.

``IsAdminFor(UserSession $userSession, $otherUserId)``
  Determines whether a user administers another user.

Minimal example:

.. code-block:: php

   <?php

   require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');

   class MyAuthorization implements IAuthorizationService
   {
       private IAuthorizationService $base;

       public function __construct(IAuthorizationService $base)
       {
           $this->base = $base;
       }

       public function CanReserveForOthers(UserSession $reserver)
       {
           return $this->base->CanReserveForOthers($reserver);
       }

       public function CanReserveFor(UserSession $reserver, $reserveForId)
       {
           return $this->base->CanReserveFor($reserver, $reserveForId);
       }

       public function CanApproveFor(UserSession $approver, $approveForId)
       {
           return $this->base->CanApproveFor($approver, $approveForId);
       }

       public function CanEditForResource(UserSession $user, IResource $resource)
       {
           return $this->base->CanEditForResource($user, $resource);
       }

       public function CanApproveForResource(UserSession $user, IResource $resource)
       {
           return $this->base->CanApproveForResource($user, $resource);
       }

       public function IsApplicationAdministrator(User $user)
       {
           // Replace this with an external role lookup if needed.
           return $this->base->IsApplicationAdministrator($user);
       }

       public function IsResourceAdministrator(User $user)
       {
           return $this->base->IsResourceAdministrator($user);
       }

       public function IsGroupAdministrator(User $user)
       {
           return $this->base->IsGroupAdministrator($user);
       }

       public function IsScheduleAdministrator(User $user)
       {
           return $this->base->IsScheduleAdministrator($user);
       }

       public function IsAdminFor(UserSession $userSession, $otherUserId)
       {
           return $this->base->IsAdminFor($userSession, $otherUserId);
       }
   }

Permission Plugins
------------------

Permission plugins control resource access. They implement
``IPermissionService`` and decorate the default ``PermissionService``.

Important methods:

``CanAccessResource(IPermissibleResource $resource, UserSession $user)``
  Determines whether the user has full access to a resource.

``CanBookResource(IPermissibleResource $resource, UserSession $user)``
  Determines whether the user can create reservations for a resource.

``CanViewResource(IPermissibleResource $resource, UserSession $user)``
  Determines whether the user can see a resource in view-only contexts.

Minimal example:

.. code-block:: php

   <?php

   require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');

   class MyPermission implements IPermissionService
   {
       private IPermissionService $base;

       public function __construct(IPermissionService $base)
       {
           $this->base = $base;
       }

       public function CanAccessResource(IPermissibleResource $resource, UserSession $user)
       {
           return $this->base->CanAccessResource($resource, $user);
       }

       public function CanBookResource(IPermissibleResource $resource, UserSession $user)
       {
           // Add custom resource booking checks here.
           return $this->base->CanBookResource($resource, $user);
       }

       public function CanViewResource(IPermissibleResource $resource, UserSession $user)
       {
           return $this->base->CanViewResource($resource, $user);
       }
   }

Post-Registration Plugins
-------------------------

Post-registration plugins run after self-registration. They implement
``IPostRegistration`` and decorate the default ``PostRegistration`` service.

Important method:

``HandleSelfRegistration(User $user, IRegistrationPage $page, ILoginContext $loginContext)``
  Handles the newly registered user. The default behavior logs active users in
  and redirects them to their homepage, or sends activation email for pending
  users. Use this plugin type to customize activation, onboarding, redirects, or
  external registration sync.

Minimal example:

.. code-block:: php

   <?php

   require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

   class MyPostRegistration implements IPostRegistration
   {
       private IPostRegistration $base;

       public function __construct(IPostRegistration $base)
       {
           $this->base = $base;
       }

       public function HandleSelfRegistration(User $user, IRegistrationPage $page, ILoginContext $loginContext)
       {
           // Add custom onboarding or external sync here.
           $this->base->HandleSelfRegistration($user, $page, $loginContext);
       }
   }

Export Plugins
--------------

Export plugins customize reservation export data. They implement
``IExportFactory`` and decorate the default ``ExportFactory``.

The built-in example is:

.. code-block:: text

   plugins/Export/ExportExample/ExportExample.php

Important methods:

``GetIcalendarClassification(IReservedItemView $item)``
  Returns the iCalendar classification for an exported reservation, such as
  ``PUBLIC``, ``PRIVATE``, or ``CONFIDENTIAL``.

``GetIcalendarExtraLines(IReservedItemView $item)``
  Returns additional raw iCalendar lines to add to the event, or ``null`` when
  no extra lines are needed. Return valid iCalendar property lines, with one
  property per line and a trailing newline.

Minimal example:

.. code-block:: php

   <?php

   require_once(ROOT_DIR . 'lib/Application/Export/namespace.php');

   class MyExport implements IExportFactory
   {
       private IExportFactory $base;

       public function __construct(IExportFactory $base)
       {
           $this->base = $base;
       }

       public function GetIcalendarClassification(IReservedItemView $item)
       {
           return $this->base->GetIcalendarClassification($item);
       }

       public function GetIcalendarExtraLines(IReservedItemView $item)
       {
           // Add one valid iCalendar property per line, ending with a newline.
           return "X-LIBREBOOKING-RESOURCE:" . $item->GetResourceName() . "\n";
       }
   }

Styling Plugins
---------------

Styling plugins customize schedule presentation. They implement
``IStylingFactory`` and decorate the default ``StylingFactory``.

The built-in example is:

.. code-block:: text

   plugins/Styling/StylingExample/StylingExample.php

Important methods:

``AdditionalCSS(UserSession $userSession)``
  Returns a server file path to an additional CSS file, or ``null`` when no
  extra CSS should be loaded.

``GetReservationAdditonalCSSClasses(IReservedItemView $item)``
  Returns additional CSS class names for a reservation item. Use this to style
  reservations based on title, resource, attributes, or other reservation data.

Minimal example:

.. code-block:: php

   <?php

   require_once(ROOT_DIR . 'lib/Application/Styling/namespace.php');

   class MyStyling implements IStylingFactory
   {
       private IStylingFactory $base;

       public function __construct(IStylingFactory $base)
       {
           $this->base = $base;
       }

       public function AdditionalCSS(UserSession $userSession)
       {
           $path = realpath(__DIR__ . DIRECTORY_SEPARATOR . 'MyStyling.css');
           return $path === false ? null : $path;
       }

       public function GetReservationAdditonalCSSClasses(IReservedItemView $item)
       {
           $classes = $this->base->GetReservationAdditonalCSSClasses($item) ?? [];

           if (str_starts_with($item->GetTitle(), 'Important')) {
               $classes[] = 'important-reservation';
           }

           return $classes;
       }
   }

Pre-Reservation Plugins
-----------------------

Pre-reservation plugins run during reservation validation. Use them to reject
or allow reservation actions before data is saved.

A pre-reservation plugin implements ``IPreReservationFactory`` and decorates
``PreReservationFactory``. The factory methods correspond to reservation
actions:

``CreatePreAddService``
  Runs before a new reservation is created. Use this to block or allow new
  reservations based on custom business rules.

``CreatePreUpdateService``
  Runs before an existing reservation is updated. Use this to validate changes
  to time, resources, participants, accessories, custom attributes, or other
  reservation details.

``CreatePreDeleteService``
  Runs before a reservation is deleted. Use this to prevent deletion unless a
  custom condition is met, such as approval from an external system.

``CreatePreApprovalService``
  Runs before a pending reservation is approved. Use this to enforce custom
  approval rules before the reservation status changes.

``CreatePreCheckinService``
  Runs before a reservation check-in is accepted. Use this to restrict check-in
  by role, location, time window, resource state, or external validation.

``CreatePreCheckoutService``
  Runs before a reservation check-out is accepted. Use this to enforce checkout
  policy or verify external state before the checkout is saved.

The built-in example is:

.. code-block:: text

   plugins/PreReservation/PreReservationExample/PreReservationExample.php

Minimal example:

.. code-block:: php

   <?php

   require_once(dirname(__FILE__) . '/MyRuleValidation.php');

   class MyRule implements IPreReservationFactory
   {
       private PreReservationFactory $factoryToDecorate;

       public function __construct(PreReservationFactory $factoryToDecorate)
       {
           $this->factoryToDecorate = $factoryToDecorate;
       }

       public function CreatePreAddService(UserSession $userSession)
       {
           // Wrap validation for new reservations.
           $base = $this->factoryToDecorate->CreatePreAddService($userSession);
           return new MyRuleValidation($base);
       }

       public function CreatePreUpdateService(UserSession $userSession)
       {
           // Delegate unchanged actions to LibreBooking's default validation.
           return $this->factoryToDecorate->CreatePreUpdateService($userSession);
       }

       public function CreatePreDeleteService(UserSession $userSession)
       {
           return $this->factoryToDecorate->CreatePreDeleteService($userSession);
       }

       public function CreatePreApprovalService(UserSession $userSession)
       {
           return $this->factoryToDecorate->CreatePreApprovalService($userSession);
       }

       public function CreatePreCheckinService(UserSession $userSession)
       {
           return $this->factoryToDecorate->CreatePreCheckinService($userSession);
       }

       public function CreatePreCheckoutService(UserSession $userSession)
       {
           return $this->factoryToDecorate->CreatePreCheckoutService($userSession);
       }
   }

Validation service example:

.. code-block:: php

   <?php

   class MyRuleValidation implements IReservationValidationService
   {
       private IReservationValidationService $serviceToDecorate;

       public function __construct(IReservationValidationService $serviceToDecorate)
       {
           $this->serviceToDecorate = $serviceToDecorate;
       }

       public function Validate($series, $retryParameters = null)
       {
           $result = $this->serviceToDecorate->Validate($series, $retryParameters);

           // Preserve any validation failures from the base service.
           if (!$result->CanBeSaved()) {
               return $result;
           }

           // Add custom validation here.
           $isValid = true;

           if ($isValid) {
               return new ReservationValidationResult();
           }

           return new ReservationValidationResult(false, 'Reservation is not allowed.');
       }
   }

Post-Reservation Plugins
------------------------

Post-reservation plugins run after reservation persistence. Use them for custom
notifications, integrations, webhooks, or side effects.

A post-reservation plugin implements ``IPostReservationFactory`` and decorates
``PostReservationFactory``. The factory methods correspond to reservation
actions:

``CreatePostAddService``
  Runs after a new reservation is saved. Use this for custom notifications,
  webhooks, external calendar sync, audit logging, or follow-up processing.

``CreatePostUpdateService``
  Runs after an existing reservation is updated. Use this to sync changed
  reservation details to another system or send custom update notifications.

``CreatePostDeleteService``
  Runs after a reservation is deleted. Use this to notify external systems,
  clean up related records, or send custom cancellation messages.

``CreatePostApproveService``
  Runs after a pending reservation is approved. Use this to notify approvers,
  owners, or external systems that the reservation is now confirmed.

``CreatePostCheckinService``
  Runs after a reservation check-in is saved. Use this to update access control
  systems, notify staff, or record custom attendance information.

``CreatePostCheckoutService``
  Runs after a reservation check-out is saved. Use this to release external
  resources, close related work orders, or record custom usage data.

The built-in example is:

.. code-block:: text

   plugins/PostReservation/PostReservationExample/PostReservationExample.php

Minimal example:

.. code-block:: php

   <?php

   class MyNotifier implements IPostReservationFactory
   {
       private PostReservationFactory $factoryToDecorate;

       public function __construct(PostReservationFactory $factoryToDecorate)
       {
           $this->factoryToDecorate = $factoryToDecorate;
       }

       public function CreatePostAddService(UserSession $userSession)
       {
           // Wrap notification handling after new reservations are saved.
           $base = $this->factoryToDecorate->CreatePostAddService($userSession);
           return new MyNotifierService($base);
       }

       public function CreatePostUpdateService(UserSession $userSession)
       {
           // Delegate unchanged actions to LibreBooking's default notifications.
           return $this->factoryToDecorate->CreatePostUpdateService($userSession);
       }

       public function CreatePostDeleteService(UserSession $userSession)
       {
           return $this->factoryToDecorate->CreatePostDeleteService($userSession);
       }

       public function CreatePostApproveService(UserSession $userSession)
       {
           return $this->factoryToDecorate->CreatePostApproveService($userSession);
       }

       public function CreatePostCheckinService(UserSession $userSession)
       {
           return $this->factoryToDecorate->CreatePostCheckinService($userSession);
       }

       public function CreatePostCheckoutService(UserSession $userSession)
       {
           return $this->factoryToDecorate->CreatePostCheckoutService($userSession);
       }
   }

Notification service example:

.. code-block:: php

   <?php

   class MyNotifierService implements IReservationNotificationService
   {
       private IReservationNotificationService $base;

       public function __construct(IReservationNotificationService $base)
       {
           $this->base = $base;
       }

       public function Notify($reservationSeries)
       {
           // Run custom post-reservation behavior here.

           // Call the base service to keep LibreBooking's default notifications.
           $this->base->Notify($reservationSeries);
       }
   }

Plugin Configuration Files
--------------------------

If a plugin needs its own settings, place a config file in the plugin directory
and register it in the plugin constructor. New plugin configuration should use
``PluginConfigKeys`` definitions so values can be validated, converted, and
managed consistently.

.. code-block:: php

   require_once(ROOT_DIR . 'lib/Config/namespace.php');

   class MyRuleConfigKeys extends PluginConfigKeys
   {
       public const CONFIG_ID = 'MyRule';

       public const MAX_VALUE = [
           'key' => 'max.value',
           'type' => 'integer',
           'default' => 10,
           'label' => 'Maximum Value',
           'description' => 'Maximum value accepted by the custom reservation rule',
           'section' => 'myrule',
       ];
   }

The plugin config file must return a ``settings`` array:

.. code-block:: php

   <?php

   return [
       'settings' => [
           'myrule' => [
               'max.value' => 10,
           ],
       ],
   ];

Register the plugin config file with the config file path, optional env file
path, config ID, overwrite flag, and config key class:

.. code-block:: php

   require_once(dirname(__FILE__) . '/MyRuleConfigKeys.php');

   Configuration::Instance()->Register(
       dirname(__FILE__) . '/MyRule.config.php',
       __DIR__ . '/.env',
       MyRuleConfigKeys::CONFIG_ID,
       false,
       MyRuleConfigKeys::class
   );

The plugin can later read its settings with:

.. code-block:: php

   $configFile = Configuration::Instance()->File(MyRuleConfigKeys::CONFIG_ID);
   $maxValue = $configFile->GetKey(MyRuleConfigKeys::MAX_VALUE);

If a plugin needs to inspect arbitrary raw values that are not represented by a
``PluginConfigKeys`` definition, read the configuration array with
``GetValues()``:

.. code-block:: php

   $values = Configuration::Instance()->File(MyRuleConfigKeys::CONFIG_ID)->GetValues();
   $myRuleValues = $values['myrule'] ?? [];

For current plugin configuration examples, see
``plugins/Authentication/Ldap/LdapConfigKeys.php`` and
``plugins/Authentication/Ldap/LdapOptions.php``.

Troubleshooting
---------------

If a plugin does not load:

- Confirm the path, file name, and class name all match the configured plugin
  name.
- Confirm the plugin is in the correct plugin type directory.
- Confirm the plugin is enabled with the correct key in ``config/config.php``.
- Check the LibreBooking logs for ``Loading plugin`` or ``Error loading
  plugin`` messages.
- Clear PHP OPcache or restart the web server/container after changing plugin
  code.
- Confirm all required files are included with ``require_once`` from the plugin
  entry file.
