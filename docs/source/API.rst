LibreBooking API Documentation
==============================

A dynamically generated API documentation Page can be found by opening
``<librebooking-url>/Web/Services/index.php`` (API has to be enabled in
config)

-  `Getting Started With The API <#getting-started-with-the-api>`__
-  `Accessories <#accessories>`__
-  `Accounts <#accounts>`__
-  `Attributes <#attributes>`__
-  `Authentication <#authentication>`__
-  `Groups <#groups>`__
-  `Reservations <#reservations>`__
-  `Resources <#resources>`__
-  `Schedules <#schedules>`__
-  `Users <#users>`__

Getting Started With the API
----------------------------

Authenticating to LibreBooking
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

For all of the secure service calls it is required to be
`Authenticated <#authenticate>`__. The basic steps are:

1. Make a request to the `Authenticate <#authenticate>`__ POST API
   endpoint. The POST data must be sent as JSON
2. The result from the `Authenticate <#authenticate>`__ POST API call,
   if successful, will contain the two values: ``sessionToken`` and
   ``userId``
3. When making secure service calls the following headers must be set:

   1. ``X-Booked-SessionToken`` set to the value of ``sessionToken``
      returned by the `Authenticate <#authenticate>`__ API call.
   2. ``X-Booked-UserId`` set to the value of ``userId`` returned by the
      `Authenticate <#authenticate>`__ API call.

POST Requests
~~~~~~~~~~~~~

When making POST API requests it is required to send the POST data as
JSON

Accessories
-----------

POST Services
~~~~~~~~~~~~~

Not applicable.

GET Services
~~~~~~~~~~~~

GetAllAccessories
^^^^^^^^^^^^^^^^^

| **Description:**
| Loads all accessories.
| CreditApplicability of 1 is per slot, 2 is per reservation

| **Route:** ``/Web/Services/index.php/Accessories/``
| *This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "accessories": [
           {
               "id": 1,
               "name": "accessoryName",
               "quantityAvailable": 3,
               "associatedResourceCount": 10,
               "creditCount": 1,
               "peakCreditCount": 2,
               "creditApplicability": 1,
               "creditsChargedAllSlots": null,
               "links": [],
               "message": null
           }
       ],
       "links": [],
       "message": null
   }

GetAccessory
^^^^^^^^^^^^

| **Description:**
| Loads a specific accessory by id. CreditApplicability of 1 is per
  slot, 2 is per reservation

**Route:** ``/Web/Services/index.php/Accessories/:accessoryId`` *This
service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "id": 1,
       "name": "accessoryName",
       "quantityAvailable": 10,
       "associatedResources": [
           {
               "resourceId": 1,
               "minQuantity": 4,
               "maxQuantity": 10,
               "links": [],
               "message": null
           }
       ],
       "creditCount": 1,
       "peakCreditCount": 2,
       "creditApplicability": 1,
       "links": [],
       "message": null
   }

Accounts
--------

.. _post-services-1:

POST Services
~~~~~~~~~~~~~

CreateAccount
^^^^^^^^^^^^^

| **Description:**
| Creates a user account. This does not authenticate

**Route:** ``/Web/Services/index.php/Accounts/``

.. note::

   It is required for the route to end with the ``/`` character, or it will
   fail.

**Response:**

Unstructured response of type *AccountCreatedResponse*

**Request:**

.. code:: json

   {
       "password": "plaintextpassword",
       "acceptTermsOfService": true,
       "firstName": "FirstName",
       "lastName": "LastName",
       "emailAddress": "email@address.com",
       "userName": "username",
       "language": "en_us",
       "timezone": "America/Chicago",
       "phone": "phone",
       "organization": "organization",
       "position": "position",
       "customAttributes": [
           {
               "attributeId": 1,
               "attributeValue": "attribute value"
           }
       ]
   }

UpdateAccount
^^^^^^^^^^^^^

| **Description:**
| Updates an existing user account

**Route:** ``/Web/Services/index.php/Accounts/:userId``

*This service is secure and requires authentication*

| **Response:**
| Unstructured response of type *AccountUpdatedResponse*

**Request:**

.. code:: json

   {
       "firstName": "FirstName",
       "lastName": "LastName",
       "emailAddress": "email@address.com",
       "userName": "username",
       "language": "en_us",
       "timezone": "America/Chicago",
       "phone": "phone",
       "organization": "organization",
       "position": "position",
       "customAttributes": [
           {
               "attributeId": 1,
               "attributeValue": "attribute value"
           }
       ]
   }

UpdatePassword
^^^^^^^^^^^^^^

**Description:**

Updates the password for an existing user

**Route:** ``/Web/Services/index.php/Accounts/:userId/Password``

*This service is secure and requires authentication*

**Response:**

Unstructured response of type *AccountUpdatedResponse*

**Request:**

.. code:: json

   {
       "currentPassword": "plain.text.current.password",
       "newPassword": "plain.text.new.password"
   }

.. _get-services-1:

GET Services
~~~~~~~~~~~~

GetAccount
^^^^^^^^^^

**Description:**

Gets the currently authenticated users’s account information

**Route:** ``/Web/Services/index.php/Accounts/:userId``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "userId": 1,
       "firstName": "first",
       "lastName": "last",
       "emailAddress": "email@address.com",
       "userName": "username",
       "language": "en_us",
       "timezone": "America/Chicago",
       "phone": "phone",
       "organization": "organization",
       "position": "position",
       "customAttributes": [
           {
               "id": 123,
               "label": "label",
               "value": "value",
               "links": [],
               "message": null
           }
       ],
       "icsUrl": "webcal://path-to-calendar",
       "links": [],
       "message": null
   }

Attributes
----------

.. _post-services-2:

POST Services
~~~~~~~~~~~~~

CreateCustomAttribute
^^^^^^^^^^^^^^^^^^^^^

**Description:**

| Creates a new custom attribute.
| Allowed values for type: 1 (single line), 2 (multi line), 3 (select
  list), 4 (checkbox), 5 (datetime)
| Allowed values for categoryId: 1 (reservation), 2 (user), 4
  (resource), 5 (resource type)
| appliesToIds only allowed for category 2, 4, 5 and must match the id
  of corresponding entities
| secondaryCategoryId and secondaryEntityIds only applies to category 1
  and must match the id of the corresponding entities

**Route:** ``/Web/Services/index.php/Attributes/``

*This service is secure and requires authentication*

*This service is only available to application administrators*

.. note::

   It is required for the route to end with the ``/`` character, or it will
   fail.

**Response:**

.. code:: json

   {
     "attributeId": 1,
     "links": [
       {
         "href": "http://url/to/attribute",
         "title": "get_custom_attribute"
       },
       {
         "href": "http://url/to/update/attribute",
         "title": "update_custom_attribute"
       }
     ],
     "message": null
   }

**Request:**

.. code:: json

   {
       "label": "attribute name",
       "type": 1,
       "categoryId": 1,
       "regex": "validation regex",
       "required": true,
       "possibleValues": [
           "possible",
           "values",
           "only valid for select list"
       ],
       "sortOrder": 100,
       "appliesToIds": [
           10
       ],
       "adminOnly": true,
       "isPrivate": true,
       "secondaryCategoryId": 1,
       "secondaryEntityIds": [
           1,
           2
       ]
   }

UpdateCustomAttribute
^^^^^^^^^^^^^^^^^^^^^

**Description:**

| Updates and existing custom attribute
| Allowed values for type: 1 (single line), 2 (multi line), 3 (select
  list), 4 (checkbox), 5 (datetime)
| Allowed values for categoryId: 1 (reservation), 2 (user), 4
  (resource), 5 (resource type)
| appliesToIds only allowed for category 2, 4, 5 and must match the id
  of corresponding entities
| secondaryCategoryId and secondaryEntityIds only applies to category 1
  and must match the id of the corresponding entities

**Route:** ``/Web/Services/index.php/Attributes/:attributeId``

*This service is secure and requires authentication*

*This service is only available to application administrators*

**Response:**

.. code:: json

   {
       "attributeId": 1,
       "links": [
           {
               "href": "http://url/to/attribute",
               "title": "get_custom_attribute"
           },
           {
               "href": "http://url/to/update/attribute",
               "title": "update_custom_attribute"
           }
       ],
       "message": null
   }

**Request:**

.. code:: json

   {
       "label": "attribute name",
       "type": 1,
       "categoryId": 1,
       "regex": "validation regex",
       "required": true,
       "possibleValues": [
           "possible",
           "values",
           "only valid for select list"
       ],
       "sortOrder": 100,
       "appliesToIds": [
           10
       ],
       "adminOnly": true,
       "isPrivate": true,
       "secondaryCategoryId": 1,
       "secondaryEntityIds": [
           1,
           2
       ]
   }

.. _get-services-2:

GET Services
~~~~~~~~~~~~

GetCategoryAttributes
^^^^^^^^^^^^^^^^^^^^^

**Description:**

| Gets all custom attribute definitions for the requested category
| Categories are RESERVATION = 1, USER = 2, RESOURCE = 4

**Route:** ``/Web/Services/index.php/Attributes/Category/:categoryId``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "attributes": [
           {
               "id": 1,
               "label": "display label",
               "type": "Allowed values for type: 4 (checkbox), 2 (multi line), 3 (select list), 1 (single line)",
               "categoryId": "Allowed values for category: 1 (reservation), 4 (resource), 5 (resource type), 2 (user)",
               "regex": "validation regex",
               "required": true,
               "possibleValues": [
                   "possible",
                   "values"
               ],
               "sortOrder": 100,
               "appliesToIds": [
                   10
               ],
               "adminOnly": true,
               "isPrivate": true,
               "secondaryCategoryId": 1,
               "secondaryEntityIds": [
                   1,
                   2
               ],
               "links": [],
               "message": null
           }
       ],
       "links": [],
       "message": null
   }

GetAttribute
^^^^^^^^^^^^

**Description:**

Gets all custom attribute definitions for the requested attribute

**Route:** ``/Web/Services/index.php/Attributes/:attributeId``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "id": 1,
       "label": "display label",
       "type": "Allowed values for type: 4 (checkbox), 2 (multi line), 3 (select list), 1 (single line)",
       "categoryId": "Allowed values for category: 1 (reservation), 4 (resource), 5 (resource type), 2 (user)",
       "regex": "validation regex",
       "required": true,
       "possibleValues": [
           "possible",
           "values"
       ],
       "sortOrder": 100,
       "appliesToIds": [
           10
       ],
       "adminOnly": true,
       "isPrivate": true,
       "secondaryCategoryId": 1,
       "secondaryEntityIds": [
           1,
           2
       ],
       "links": [],
       "message": null
   }

DeleteCustomAttribute
^^^^^^^^^^^^^^^^^^^^^

**Description:**

Deletes an existing custom attribute

**Route:** ``/Web/Services/index.php/Attributes/:attributeId``

*This service is secure and requires authentication*

*This service is only available to application administrators*

**Response:**

.. code:: json

   {
       "links": [],
       "message": "The item was deleted"
   }

Authentication
--------------

.. _post-services-3:

POST Services
~~~~~~~~~~~~~

SignOut
^^^^^^^

**Description:**

invalidate Authenication Token

**Route:** ``/Web/Services/index.php/Authentication/SignOut``

**Response:**

No response

**Request:**

.. code:: json

   {
       "userId": null,
       "sessionToken": null
   }

Authenticate
^^^^^^^^^^^^

**Description:**

Authenticates an existing LibreBooking user

**Route:** ``/Web/Services/index.php/Authentication/Authenticate``

**Response:**

.. code:: json

   {
       "sessionToken": "sessiontoken",
       "sessionExpires": "2021-03-08T09:56:04+0000",
       "userId": 123,
       "isAuthenticated": true,
       "version": "1.0",
       "links": [],
       "message": null
   }

**Request:**

.. code:: json

   {
       "username":null,
       "password":null
   }

.. _get-services-3:

GET Services
~~~~~~~~~~~~

na.

Groups
------

.. _post-services-4:

POST Services
~~~~~~~~~~~~~

CreateGroup
^^^^^^^^^^^

**Description:**

Creates a new group

**Route:** ``/Web/Services/index.php/Groups/``

*This service is secure and requires authentication*

*This service is only available to application administrators*

.. note::

   It is required for the route to end with the ``/`` character, or it will
   fail.

**Response:**

.. code:: json

   {
       "attributeId": 1,
       "links": [
           {
               "href": "http://url/to/attribute",
               "title": "get_custom_attribute"
           },
           {
               "href": "http://url/to/update/attribute",
               "title": "update_custom_attribute"
           }
       ],
       "message": null
   }

**Request:**

.. code:: json

   {"name":"group name","isDefault":true}

UpdateGroup
^^^^^^^^^^^

**Description:**

Updates and existing group

**Route:** ``/Web/Services/index.php/Groups/:groupId``

*This service is secure and requires authentication*

*This service is only available to application administrators*

**Response:**

.. code:: json

   {
       "groupId": 1,
       "links": [
           {
               "href": "http://url/to/group",
               "title": "get_group"
           },
           {
               "href": "http://url/to/update/group",
               "title": "update_group"
           },
           {
               "href": "http://url/to/delete/group",
               "title": "delete_group"
           }
       ],
       "message": null
   }

**Request:**

.. code:: json

   {
       "name": "group name",
       "isDefault": true
   }

ChangeGroupRoles
~~~~~~~~~~~~~~~~

**Description:**

| Updates the roles for an existing group
| roleIds : 1 (Group Administrator), 2 (Application Administrator), 3
  (Resource Administrator), 4 (Schedule Administrator)

**Route:** ``/Web/Services/index.php/Groups/:groupId/Roles``

*This service is secure and requires authentication*

*This service is only available to application administrators*

**Response:**

.. code:: json

   {
       "groupId": 1,
       "links": [
           {
               "href": "http://url/to/group",
               "title": "get_group"
           },
           {
               "href": "http://url/to/update/group",
               "title": "update_group"
           },
           {
               "href": "http://url/to/delete/group",
               "title": "delete_group"
           }
       ],
       "message": null
   }

**Request:**

No request

ChangeGroupPermissions
~~~~~~~~~~~~~~~~~~~~~~

**Description:**

Updates the permissions for an existing group

**Route:** ``/Web/Services/index.php/Groups/:groupId/Permissions`` *This
service is secure and requires authentication*

*This service is only available to application administrators*

**Response:**

.. code:: json

   {
       "groupId": 1,
       "links": [
           {
               "href": "http://url/to/group",
               "title": "get_group"
           },
           {
               "href": "http://url/to/update/group",
               "title": "update_group"
           },
           {
               "href": "http://url/to/delete/group",
               "title": "delete_group"
           }
       ],
       "message": null
   }

**Request:**

No request

ChangeGroupUsers
~~~~~~~~~~~~~~~~

**Description:**

Updates the permissions for an existing group

**Route:** ``/Web/Services/index.php/Groups/:groupId/Users``

*This service is secure and requires authentication*

*This service is only available to application administrators*

**Response:**

.. code:: json

   {
       "groupId": 1,
       "links": [
           {
               "href": "http://url/to/group",
               "title": "get_group"
           },
           {
               "href": "http://url/to/update/group",
               "title": "update_group"
           },
           {
               "href": "http://url/to/delete/group",
               "title": "delete_group"
           }
       ],
       "message": null
   }

**Request:**

No request

.. _get-services-4:

GET Services
~~~~~~~~~~~~

GetAllGroups
^^^^^^^^^^^^

**Description:**

Loads all groups

**Route:** ``/Web/Services/index.php/Groups/``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "groups": [
           {
               "id": 1,
               "name": "group name",
               "isDefault": true,
               "links": [],
               "message": null
           }
       ],
       "links": [],
       "message": null
   }

GetGroup
^^^^^^^^

**Description:**

Loads a specific group by id

**Route:** ``/Web/Services/index.php/Groups/:groupId``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "id": 123,
       "name": "group name",
       "adminGroup": "http://url/to/group",
       "permissions": [
           "http://url/to/resource"
       ],
       "viewPermissions": [
           "http://url/to/resource"
       ],
       "users": [
           "http://url/to/user"
       ],
       "roles": [
           1,
           2
       ],
       "isDefault": true,
       "links": [],
       "message": null
   }

DeleteGroup
^^^^^^^^^^^

**Description:**

Deletes an existing group

**Route:** ``/Web/Services/index.php/Groups/:groupId``

*This service is secure and requires authentication*

*This service is only available to application administrators*

**Response:**

.. code:: json

   {
       "links": [],
       "message": "The item was deleted"
   }

Reservations
------------

.. _post-services-5:

POST Services
~~~~~~~~~~~~~

CreateReservation
^^^^^^^^^^^^^^^^^

**Description:**

Creates a new reservation

**Route:** ``/Web/Services/index.php/Reservations/``

*This service is secure and requires authentication*

.. note::

   It is required for the route to end with the ``/`` character, or it will
   fail.

**Response:**

.. code:: json

   {
       "referenceNumber": "referenceNumber",
       "isPendingApproval": true,
       "links": [
           {
               "href": "http://url/to/reservation",
               "title": "get_reservation"
           },
           {
               "href": "http://url/to/update/reservation",
               "title": "update_reservation"
           }
       ],
       "message": null
   }

**Request:**

.. code:: json

   {
       "accessories": [
           {
               "accessoryId": 1,
               "quantityRequested": 2
           }
       ],
       "customAttributes": [
           {
               "attributeId": 2,
               "attributeValue": "some value"
           }
       ],
       "description": "reservation description",
       "endDateTime": "2021-03-08T09:56:04+0000",
       "invitees": [
           1,
           2,
           3
       ],
       "participants": [
           1,
           2
       ],
       "participatingGuests": [
           "participating.guest@email.com"
       ],
       "invitedGuests": [
           "invited.guest@email.com"
       ],
       "recurrenceRule": {
           "type": "daily|monthly|none|weekly|yearly",
           "interval": 3,
           "monthlyType": "dayOfMonth|dayOfWeek|null",
           "weekdays": [
               0,
               1,
               2,
               3,
               4,
               5,
               6
           ],
           "repeatTerminationDate": "2021-03-08T09:56:04+0000"
       },
       "resourceId": 1,
       "resources": [
           2,
           3
       ],
       "startDateTime": "2021-03-08T09:56:04+0000",
       "title": "reservation title",
       "userId": 1,
       "startReminder": {
           "value": 15,
           "interval": "hours or minutes or days"
       },
       "endReminder": null,
       "allowParticipation": true,
       "retryParameters": [
           {
               "name": "name",
               "value": "value"
           }
       ],
       "termsAccepted": true
   }

UpdateReservation
^^^^^^^^^^^^^^^^^

**Description:**

| Updates an existing reservation.
| Pass an optional updateScope query string parameter to restrict
  changes. Possible values for updateScope are this|full|future

**Route:** ``/Web/Services/index.php/Reservations/:referenceNumber``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "referenceNumber": "referenceNumber",
       "isPendingApproval": true,
       "links": [
           {
               "href": "http://url/to/reservation",
               "title": "get_reservation"
           },
           {
               "href": "http://url/to/update/reservation",
               "title": "update_reservation"
           }
       ],
       "message": null
   }

**Request:**

.. code:: json

   {
       "accessories": [
           {
               "accessoryId": 1,
               "quantityRequested": 2
           }
       ],
       "customAttributes": [
           {
               "attributeId": 2,
               "attributeValue": "some value"
           }
       ],
       "description": "reservation description",
       "endDateTime": "2021-03-08T09:56:04+0000",
       "invitees": [
           1,
           2,
           3
       ],
       "participants": [
           1,
           2
       ],
       "participatingGuests": [
           "participating.guest@email.com"
       ],
       "invitedGuests": [
           "invited.guest@email.com"
       ],
       "recurrenceRule": {
           "type": "daily|monthly|none|weekly|yearly",
           "interval": 3,
           "monthlyType": "dayOfMonth|dayOfWeek|null",
           "weekdays": [
               0,
               1,
               2,
               3,
               4,
               5,
               6
           ],
           "repeatTerminationDate": "2021-03-08T09:56:04+0000"
       },
       "resourceId": 1,
       "resources": [
           2,
           3
       ],
       "startDateTime": "2021-03-08T09:56:04+0000",
       "title": "reservation title",
       "userId": 1,
       "startReminder": {
           "value": 15,
           "interval": "hours or minutes or days"
       },
       "endReminder": null,
       "allowParticipation": true,
       "retryParameters": [
           {
               "name": "name",
               "value": "value"
           }
       ],
       "termsAccepted": true
   }

ApproveReservation
^^^^^^^^^^^^^^^^^^

**Description:**

Approves a pending reservation.

**Route:**
``/Web/Services/index.php/Reservations/:referenceNumber/Approval``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "referenceNumber": "referenceNumber",
       "isPendingApproval": true,
       "links": [
           {
               "href": "http://url/to/reservation",
               "title": "get_reservation"
           },
           {
               "href": "http://url/to/update/reservation",
               "title": "update_reservation"
           }
       ],
       "message": null
   }

**Request:**

No request

CheckinReservation
^^^^^^^^^^^^^^^^^^

**Description:**

Checks in to a reservation.

**Route:**
``/Web/Services/index.php/Reservations/:referenceNumber/CheckIn``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "referenceNumber": "referenceNumber",
       "isPendingApproval": true,
       "links": [
           {
               "href": "http://url/to/reservation",
               "title": "get_reservation"
           },
           {
               "href": "http://url/to/update/reservation",
               "title": "update_reservation"
           }
       ],
       "message": null
   }

**Request:**

No request

CheckoutReservation
^^^^^^^^^^^^^^^^^^^

**Description:**

Checks out of a reservation.

**Route:**
``/Web/Services/index.php/Reservations/:referenceNumber/CheckOut``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "referenceNumber": "referenceNumber",
       "isPendingApproval": true,
       "links": [
           {
               "href": "http://url/to/reservation",
               "title": "get_reservation"
           },
           {
               "href": "http://url/to/update/reservation",
               "title": "update_reservation"
           }
       ],
       "message": null
   }

**Request:**

No request

.. _get-services-5:

GET Services
~~~~~~~~~~~~

GetReservations
^^^^^^^^^^^^^^^

**Description:**

| Gets a list of reservations for the specified parameters.
| Optional query string parameters: userId, resourceId, scheduleId,
  startDateTime, endDateTime.
| If no dates are provided, reservations for the next two weeks will be
  returned.
| If dates do not include the timezone offset, the timezone of the
  authenticated user will be assumed.

**Route:** ``/Web/Services/index.php/Reservations/``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "reservations": [
           {
               "referenceNumber": "refnum",
               "startDate": "2021-03-08T09:56:04+0000",
               "endDate": "2021-03-08T09:56:04+0000",
               "firstName": "first",
               "lastName": "last",
               "resourceName": "resourcename",
               "title": "reservation title",
               "description": "reservation description",
               "requiresApproval": true,
               "isRecurring": true,
               "scheduleId": 22,
               "userId": 11,
               "resourceId": 123,
               "duration": "1 hours 45 minutes",
               "bufferTime": "1 minutes",
               "bufferedStartDate": "2021-03-08T09:56:04+0000",
               "bufferedEndDate": "2021-03-08T09:56:04+0000",
               "participants": [
                   "participant name"
               ],
               "invitees": [
                   "invitee name"
               ],
               "participatingGuests": [],
               "invitedGuests": [],
               "startReminder": 10,
               "endReminder": 10,
               "color": "#FFFFFF",
               "textColor": "#000000",
               "checkInDate": "2021-03-08T09:56:04+0000",
               "checkOutDate": "2021-03-08T09:56:04+0000",
               "originalEndDate": "2021-03-08T09:56:04+0000",
               "isCheckInEnabled": true,
               "autoReleaseMinutes": 1,
               "resourceStatusId": null,
               "creditsConsumed": 15,
               "links": [],
               "message": null
           }
       ],
       "startDateTime": null,
       "endDateTime": null,
       "links": [],
       "message": null
   }

GetReservation
^^^^^^^^^^^^^^

**Description:**

Loads a specific reservation by reference number

**Route:** ``/Web/Services/index.php/Reservations/:referenceNumber``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "referenceNumber": "refnum",
       "startDate": "2021-03-08T09:56:04+0000",
       "endDate": "2021-03-08T09:56:04+0000",
       "title": "reservation title",
       "description": "reservation description",
       "requiresApproval": true,
       "isRecurring": true,
       "scheduleId": 123,
       "resourceId": 123,
       "owner": {
           "userId": 123,
           "firstName": "first",
           "lastName": "last",
           "emailAddress": "email@address.com",
           "links": [],
           "message": null
       },
       "participants": [
           {
               "userId": 123,
               "firstName": "first",
               "lastName": "last",
               "emailAddress": "email@address.com",
               "links": [],
               "message": null
           }
       ],
       "invitees": [
           {
               "userId": 123,
               "firstName": "first",
               "lastName": "last",
               "emailAddress": "email@address.com",
               "links": [],
               "message": null
           }
       ],
       "customAttributes": [
           {
               "id": 123,
               "label": "label",
               "value": "value",
               "links": [],
               "message": null
           }
       ],
       "recurrenceRule": {
           "type": "daily|monthly|none|weekly|yearly",
           "interval": 3,
           "monthlyType": "dayOfMonth|dayOfWeek|null",
           "weekdays": [
               0,
               1,
               2,
               3,
               4,
               5,
               6
           ],
           "repeatTerminationDate": "2021-03-08T09:56:04+0000"
       },
       "attachments": [
           {
               "url": "http://example/attachments/url"
           }
       ],
       "resources": [
           {
               "id": 123,
               "name": "resource name",
               "type": null,
               "groups": null,
               "links": [],
               "message": null
           }
       ],
       "accessories": [
           {
               "id": 1,
               "name": "Example",
               "quantityAvailable": 12,
               "quantityReserved": 3,
               "links": [],
               "message": null
           }
       ],
       "startReminder": {
           "value": 15,
           "interval": "hours or minutes or days"
       },
       "endReminder": {
           "value": 15,
           "interval": "hours or minutes or days"
       },
       "allowParticipation": null,
       "checkInDate": null,
       "checkOutDate": null,
       "originalEndDate": null,
       "isCheckInAvailable": null,
       "isCheckoutAvailable": null,
       "autoReleaseMinutes": null,
       "links": [],
       "message": null
   }

DeleteReservation
^^^^^^^^^^^^^^^^^

**Description:**

| Deletes an existing reservation.
| Pass an optional updateScope query string parameter to restrict
  changes. Possible values for updateScope are this|full|future

**Route:** ``/Web/Services/index.php/Reservations/:referenceNumber``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "links": [],
       "message": "The item was deleted"
   }

Resources
---------

.. _post-services-6:

POST Services
~~~~~~~~~~~~~

CreateResource
^^^^^^^^^^^^^^

**Description:**

Creates a new resource

**Route:** ``/Web/Services/index.php/Resources/``

*This service is secure and requires authentication*

*This service is only available to application administrators*

.. note::

   It is required for the route to end with the ``/`` character, or it will
   fail.

**Response:**

.. code:: json

   {
       "resourceId": 1,
       "links": [
           {
               "href": "http://url/to/resource",
               "title": "get_resource"
           },
           {
               "href": "http://url/to/update/resource",
               "title": "update_resource"
           }
       ],
       "message": null
   }

**Request:**

.. code:: json

   {
       "name": "resource name",
       "location": "location",
       "contact": "contact information",
       "notes": "notes",
       "minLength": "1d0h0m",
       "maxLength": "3600",
       "requiresApproval": true,
       "allowMultiday": true,
       "maxParticipants": 100,
       "minNotice": "86400",
       "maxNotice": "0d12h30m",
       "description": "description",
       "scheduleId": 10,
       "autoAssignPermissions": true,
       "customAttributes": [
           {
               "attributeId": 1,
               "attributeValue": "attribute value"
           }
       ],
       "sortOrder": 1,
       "statusId": 1,
       "statusReasonId": 2,
       "autoReleaseMinutes": 15,
       "requiresCheckIn": true,
       "color": "#ffffff",
       "credits": 3,
       "peakCredits": 6,
       "creditApplicability": 1,
       "creditsChargedAllSlots": 1,
       "maxConcurrentReservations": 1
   }

UpdateResource
^^^^^^^^^^^^^^

**Description:**

Updates an existing resource

**Route:** ``/Web/Services/index.php/Resources/:resourceId``

*This service is secure and requires authentication*

*This service is only available to application administrators*

**Response:**

.. code:: json

   {
       "resourceId": 1,
       "links": [
           {
               "href": "http://url/to/resource",
               "title": "get_resource"
           },
           {
               "href": "http://url/to/update/resource",
               "title": "update_resource"
           }
       ],
       "message": null
   }

**Request:**

.. code:: json

   {
       "name": "resource name",
       "location": "location",
       "contact": "contact information",
       "notes": "notes",
       "minLength": "1d0h0m",
       "maxLength": "3600",
       "requiresApproval": true,
       "allowMultiday": true,
       "maxParticipants": 100,
       "minNotice": "86400",
       "maxNotice": "0d12h30m",
       "description": "description",
       "scheduleId": 10,
       "autoAssignPermissions": true,
       "customAttributes": [
           {
               "attributeId": 1,
               "attributeValue": "attribute value"
           }
       ],
       "sortOrder": 1,
       "statusId": 1,
       "statusReasonId": 2,
       "autoReleaseMinutes": 15,
       "requiresCheckIn": true,
       "color": "#ffffff",
       "credits": 3,
       "peakCredits": 6,
       "creditApplicability": 1,
       "creditsChargedAllSlots": 1,
       "maxConcurrentReservations": 1
   }

.. _get-services-6:

GET Services
~~~~~~~~~~~~

GetStatuses
^^^^^^^^^^^

**Description:**

Returns all available resource statuses

**Route:** ``/Web/Services/index.php/Resources/Status``

**Response:**

.. code:: json

   {
       "statuses": [
           {
               "id": 0,
               "name": "Hidden"
           },
           {
               "id": 1,
               "name": "Available"
           },
           {
               "id": 2,
               "name": "Unavailable"
           }
       ],
       "links": [],
       "message": null
   }

GetAllResources
^^^^^^^^^^^^^^^

**Description:**

Loads all resources

**Route:** ``/Web/Services/index.php/Resources/``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "resources": [
           {
               "resourceId": 123,
               "name": "resource name",
               "location": "location",
               "contact": "contact",
               "notes": "notes",
               "minLength": "2 minutes",
               "maxLength": "2 minutes",
               "requiresApproval": true,
               "allowMultiday": true,
               "maxParticipants": 10,
               "minNoticeAdd": "2 minutes",
               "minNoticeUpdate": "2 minutes",
               "minNoticeDelete": "2 minutes",
               "maxNotice": "2 minutes",
               "description": "resource description",
               "scheduleId": 123,
               "icsUrl": null,
               "statusId": 1,
               "statusReasonId": 3,
               "customAttributes": [
                   {
                       "id": 123,
                       "label": "label",
                       "value": "value",
                       "links": [],
                       "message": null
                   }
               ],
               "typeId": 2,
               "groupIds": null,
               "bufferTime": "1 hours 30 minutes",
               "autoReleaseMinutes": 15,
               "requiresCheckIn": true,
               "color": "#ffffff",
               "credits": 3,
               "peakCredits": 6,
               "creditApplicability": 1,
               "creditsChargedAllSlots": true,
               "maxConcurrentReservations": 1,
               "links": [],
               "message": null
           }
       ],
       "links": [],
       "message": null
   }

GetStatusReasons
^^^^^^^^^^^^^^^^

**Description:**

Returns all available resource status reasons

**Route:** ``/Web/Services/index.php/Resources/Status/Reasons``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "reasons": [
           {
               "id": 1,
               "description": "reason description",
               "statusId": 2
           }
       ],
       "links": [],
       "message": null
   }

GetAvailability
^^^^^^^^^^^^^^^

**Description:**

| Returns resource availability for the requested resource (optional).
  “availableAt” and “availableUntil” will include availability through
  the next 7 days
| Optional query string parameter: dateTime. If no dateTime is requested
  the current datetime will be used.

**Route:** ``/Web/Services/index.php/Resources/Availability``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "resources": [
           {
               "available": true,
               "resource": {
                   "resourceId": 1,
                   "name": "resource name",
                   "scheduleId": 2,
                   "statusId": 1,
                   "statusReasonId": 123,
                   "links": [
                       {
                           "href": "http://get-resource-url",
                           "title": "get_resource"
                       },
                       {
                           "href": "http://get-schedule-url",
                           "title": "get_schedule"
                       }
                   ],
                   "message": null
               },
               "availableAt": "2021-03-08T09:56:04+0000",
               "availableUntil": "2021-03-08T09:56:04+0000",
               "links": [
                   {
                       "href": "http://get-user-url",
                       "title": "get_user"
                   },
                   {
                       "href": "http://get-reservation-url",
                       "title": "get_reservation"
                   }
               ],
               "message": null
           }
       ],
       "links": [],
       "message": null
   }

GetGroups
^^^^^^^^^

**Description:**

Returns the full resource group tree

**Route:** ``/Web/Services/index.php/Resources/Groups``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "groups": [
           {
               "id": 0,
               "name": "Resource Group 1",
               "label": "Resource Group 1",
               "parent": null,
               "parent_id": null,
               "children": [
                   {
                       "type": "resource",
                       "group_id": 0,
                       "resource_name": "Resource 1",
                       "id": "resource-0-1",
                       "label": "Resource 1",
                       "resource_id": 1,
                       "resourceAdminGroupId": null,
                       "scheduleId": 2,
                       "statusId": 1,
                       "scheduleAdminGroupId": null,
                       "requiresApproval": false,
                       "isCheckInEnabled": true,
                       "isAutoReleased": true,
                       "autoReleaseMinutes": 30,
                       "minLength": 10,
                       "resourceTypeId": 1,
                       "color": "#ffffff",
                       "textColor": "#000000",
                       "maxConcurrentReservations": 2,
                       "requiredResourceIds": [
                           2
                       ]
                   },
                   {
                       "id": 1,
                       "name": "Resource Group 2",
                       "label": "Resource Group 2",
                       "parent": null,
                       "parent_id": 0,
                       "children": [
                           {
                               "type": "resource",
                               "group_id": 1,
                               "resource_name": "Resource 2",
                               "id": "resource-1-1",
                               "label": "Resource 2",
                               "resource_id": 1,
                               "resourceAdminGroupId": null,
                               "scheduleId": 2,
                               "statusId": 1,
                               "scheduleAdminGroupId": null,
                               "requiresApproval": true,
                               "isCheckInEnabled": false,
                               "isAutoReleased": false,
                               "autoReleaseMinutes": null,
                               "minLength": null,
                               "resourceTypeId": 2,
                               "color": "#000000",
                               "textColor": "#FFFFFF",
                               "maxConcurrentReservations": 1,
                               "requiredResourceIds": [
                                   1
                               ]
                           }
                       ],
                       "type": "group"
                   }
               ],
               "type": "group"
           }
       ],
       "links": [],
       "message": null
   }

GetResourceTypes
^^^^^^^^^^^^^^^^

**Description:**

Returns all available resource types

**Route:** ``/Web/Services/index.php/Resources/Types``

*This service is secure and requires authentication*

**Response:**

.. code:: json

    {
        "links": [],
        "message": null,
        "types": [
            {
                "id": 1,
                "description": "description"
            }
        ]
    }

GetResource
^^^^^^^^^^^

**Description:**

Loads a specific resource by id

**Route:** ``/Web/Services/index.php/Resources/:resourceId``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "resourceId": 123,
       "name": "resource name",
       "location": "location",
       "contact": "contact",
       "notes": "notes",
       "minLength": "2 minutes",
       "maxLength": "2 minutes",
       "requiresApproval": true,
       "allowMultiday": true,
       "maxParticipants": 10,
       "minNoticeAdd": "2 minutes",
       "minNoticeUpdate": "2 minutes",
       "minNoticeDelete": "2 minutes",
       "maxNotice": "2 minutes",
       "description": "resource description",
       "scheduleId": 123,
       "icsUrl": null,
       "statusId": 1,
       "statusReasonId": 3,
       "customAttributes": [
           {
               "id": 123,
               "label": "label",
               "value": "value",
               "links": [],
               "message": null
           }
       ],
       "typeId": 2,
       "groupIds": null,
       "bufferTime": "1 hours 30 minutes",
       "autoReleaseMinutes": 15,
       "requiresCheckIn": true,
       "color": "#ffffff",
       "credits": 3,
       "peakCredits": 6,
       "creditApplicability": 1,
       "creditsChargedAllSlots": true,
       "maxConcurrentReservations": 1,
       "links": [],
       "message": null
   }

GetAvailability

**Description:**

| Returns resource availability for the requested resource (optional).
  “availableAt” and “availableUntil” will include availability through
  the next 7 days
| Optional query string parameter: dateTime. If no dateTime is requested
  the current datetime will be used.

**Route:**
``/Web/Services/index.php/Resources/:resourceId/Availability``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "resources": [
           {
               "available": true,
               "resource": {
                   "resourceId": 1,
                   "name": "resource name",
                   "scheduleId": 2,
                   "statusId": 1,
                   "statusReasonId": 123,
                   "links": [
                       {
                           "href": "http://get-resource-url",
                           "title": "get_resource"
                       },
                       {
                           "href": "http://get-schedule-url",
                           "title": "get_schedule"
                       }
                   ],
                   "message": null
               },
               "availableAt": "2021-03-08T09:56:04+0000",
               "availableUntil": "2021-03-08T09:56:04+0000",
               "links": [
                   {
                       "href": "http://get-user-url",
                       "title": "get_user"
                   },
                   {
                       "href": "http://get-reservation-url",
                       "title": "get_reservation"
                   }
               ],
               "message": null
           }
       ],
       "links": [],
       "message": null
   }

DeleteResource
^^^^^^^^^^^^^^

**Description:**

Deletes an existing resource

**Route:** ``/Web/Services/index.php/Resources/:resourceId``

*This service is secure and requires authentication*

*This service is only available to application administrators*

**Response:**

.. code:: json

   {
       "links": [],
       "message": "The item was deleted"
   }

Schedules
---------

.. _post-services-7:

POST Services
~~~~~~~~~~~~~

Not applicable.

.. _get-services-7:

GET Services
~~~~~~~~~~~~

GetAllSchedules
^^^^^^^^^^^^^^^

**Description:**

Loads all schedules

**Route:** ``/Web/Services/index.php/Schedules/``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "schedules": [
           {
               "daysVisible": 5,
               "id": 123,
               "isDefault": true,
               "name": "schedule name",
               "timezone": "timezone_name",
               "weekdayStart": 0,
               "availabilityBegin": "2021-03-08T09:56:04+0000",
               "availabilityEnd": "2021-03-28T09:56:04+0000",
               "maxResourcesPerReservation": 10,
               "totalConcurrentReservationsAllowed": 0,
               "links": [],
               "message": null
           }
       ],
       "links": [],
       "message": null
   }

GetSchedule
^^^^^^^^^^^

**Description:**

Loads a specific schedule by id

**Route:** ``/Web/Services/index.php/Schedules/:scheduleId``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "daysVisible": 5,
       "id": 123,
       "isDefault": true,
       "name": "schedule name",
       "timezone": "timezone_name",
       "weekdayStart": 0,
       "icsUrl": "webcal://url/to/calendar",
       "availabilityStart": "2021-03-08T09:56:04+0000",
       "availabilityEnd": "2021-03-08T09:56:04+0000",
       "maxResourcesPerReservation": 10,
       "totalConcurrentReservationsAllowed": 0,
       "periods": [
           [
               {
                   "start": "2021-03-08T09:56:04+0000",
                   "end": "2021-03-08T09:56:04+0000",
                   "label": "label",
                   "startTime": "09:56:04",
                   "endTime": "09:56:04",
                   "isReservable": true
               }
           ],
           [
               {
                   "start": "2021-03-08T09:56:04+0000",
                   "end": "2021-03-08T09:56:04+0000",
                   "label": "label",
                   "startTime": "09:56:04",
                   "endTime": "09:56:04",
                   "isReservable": true
               }
           ],
           [
               {
                   "start": "2021-03-08T09:56:04+0000",
                   "end": "2021-03-08T09:56:04+0000",
                   "label": "label",
                   "startTime": "09:56:04",
                   "endTime": "09:56:04",
                   "isReservable": true
               }
           ],
           [
               {
                   "start": "2021-03-08T09:56:04+0000",
                   "end": "2021-03-08T09:56:04+0000",
                   "label": "label",
                   "startTime": "09:56:04",
                   "endTime": "09:56:04",
                   "isReservable": true
               }
           ],
           [
               {
                   "start": "2021-03-08T09:56:04+0000",
                   "end": "2021-03-08T09:56:04+0000",
                   "label": "label",
                   "startTime": "09:56:04",
                   "endTime": "09:56:04",
                   "isReservable": true
               }
           ],
           [
               {
                   "start": "2021-03-08T09:56:04+0000",
                   "end": "2021-03-08T09:56:04+0000",
                   "label": "label",
                   "startTime": "09:56:04",
                   "endTime": "09:56:04",
                   "isReservable": true
               }
           ],
           [
               {
                   "start": "2021-03-08T09:56:04+0000",
                   "end": "2021-03-08T09:56:04+0000",
                   "label": "label",
                   "startTime": "09:56:04",
                   "endTime": "09:56:04",
                   "isReservable": true
               }
           ]
       ],
       "links": [],
       "message": null
   }

GetSlots
^^^^^^^^

**Description:**

| Loads slots for a specific schedule
| Optional query string parameters: resourceId, startDateTime,
  endDateTime.
| If no dates are provided the default schedule dates will be returned.
| If dates do not include the timezone offset, the timezone of the
  authenticated user will be assumed.

**Route:** ``/Web/Services/index.php/Schedules/:scheduleId/Slots``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "dates": [
           {
               "date": "2021-03-08T09:56:04+0000",
               "resources": [
                   {
                       "slots": [
                           {
                               "slotSpan": 4,
                               "isReserved": true,
                               "label": "username",
                               "isReservable": false,
                               "color": "#ffffff",
                               "startDateTime": "2021-03-08T09:56:04+0000",
                               "endDateTime": "2021-03-08T09:56:04+0000",
                               "reservation": {
                                   "referenceNumber": "refnum",
                                   "startDate": "2021-03-08T09:56:04+0000",
                                   "endDate": "2021-03-08T09:56:04+0000",
                                   "firstName": "first",
                                   "lastName": "last",
                                   "resourceName": "resourcename",
                                   "title": "reservation title",
                                   "description": "reservation description",
                                   "requiresApproval": true,
                                   "isRecurring": true,
                                   "scheduleId": 22,
                                   "userId": 11,
                                   "resourceId": 123,
                                   "duration": "1 hours 45 minutes",
                                   "bufferTime": "1 minutes",
                                   "bufferedStartDate": "2021-03-08T09:56:04+0000",
                                   "bufferedEndDate": "2021-03-08T09:56:04+0000",
                                   "participants": [
                                       "participant name"
                                   ],
                                   "invitees": [
                                       "invitee name"
                                   ],
                                   "participatingGuests": [],
                                   "invitedGuests": [],
                                   "startReminder": 10,
                                   "endReminder": 10,
                                   "color": "#FFFFFF",
                                   "textColor": "#000000",
                                   "checkInDate": "2021-03-08T09:56:04+0000",
                                   "checkOutDate": "2021-03-08T09:56:04+0000",
                                   "originalEndDate": "2021-03-08T09:56:04+0000",
                                   "isCheckInEnabled": true,
                                   "autoReleaseMinutes": 1,
                                   "resourceStatusId": null,
                                   "creditsConsumed": 15,
                                   "links": [],
                                   "message": null
                               },
                               "links": [],
                               "message": null
                           }
                       ],
                       "resourceId": 1,
                       "resourceName": "resourcename",
                       "links": [],
                       "message": null
                   }
               ],
               "links": [],
               "message": null
           }
       ],
       "links": [],
       "message": null
   }

Users
-----

.. _post-services-8:

POST Services
~~~~~~~~~~~~~

CreateUser
^^^^^^^^^^

**Description:**

Creates a new user

**Route:** ``/Web/Services/index.php/Users/``

*This service is secure and requires authentication*

*This service is only available to application administrators*

.. note::

   It is required for the route to end with the ``/`` character, or it will
   fail.

**Response:**

.. code:: json

   {
       "userId": null,
       "links": [
           {
               "href": "http://url/to/user",
               "title": "get_user"
           },
           {
               "href": "http://url/to/update/user",
               "title": "update_user"
           }
       ],
       "message": null
   }

**Request:**

.. code:: json

   {
       "password": "unencrypted password",
       "language": "en_us",
       "firstName": "first",
       "lastName": "last",
       "emailAddress": "email@address.com",
       "userName": "username",
       "timezone": "America/Chicago",
       "phone": "123-456-7989",
       "organization": "organization",
       "position": "position",
       "customAttributes": [
           {
               "attributeId": 99,
               "attributeValue": "attribute value"
           }
       ],
       "groups": [
           1,
           2,
           4
       ]
   }

UpdateUser
^^^^^^^^^^

**Description:**

Updates an existing user

**Route:** ``/Web/Services/index.php/Users/:userId``

*This service is secure and requires authentication*

*This service is only available to application administrators*

**Response:**

.. code:: json

   {
       "userId": null,
       "links": [
           {
               "href": "http://url/to/user",
               "title": "get_user"
           },
           {
               "href": "http://url/to/update/user",
               "title": "update_user"
           }
       ],
       "message": null
   }

**Request:**

.. code:: json

   {
       "firstName": "first",
       "lastName": "last",
       "emailAddress": "email@address.com",
       "userName": "username",
       "timezone": "America/Chicago",
       "phone": "123-456-7989",
       "organization": "organization",
       "position": "position",
       "customAttributes": [
           {
               "attributeId": 99,
               "attributeValue": "attribute value"
           }
       ],
       "groups": [
           1,
           2,
           4
       ]
   }

.. _updatepassword-1:

UpdatePassword
^^^^^^^^^^^^^^

**Description:**

Updates the password for an existing user

**Route:** ``/Web/Services/index.php/Users/:userId/Password``

*This service is secure and requires authentication*

*This service is only available to application administrators*

**Response:**

.. code:: json

   {
       "userId": null,
       "links": [
           {
               "href": "http://url/to/user",
               "title": "get_user"
           },
           {
               "href": "http://url/to/update/user",
               "title": "update_user"
           }
       ],
       "message": null
   }

**Request:**

.. code:: json

   {
       "password":"plaintext password"
   }

.. _get-services-8:

GET Services
~~~~~~~~~~~~

GetAllUsers
^^^^^^^^^^^

**Description:**

| Loads all users that the current user can see.
| Optional query string parameters: username, email, firstName,
  lastName, phone, organization, position and any custom attributes.
| If searching on custom attributes, the query string parameter has to
  be in the format att#=value.
| For example, Users/?att1=ExpectedAttribute1Value

**Route:** ``/Web/Services/index.php/Users/``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "users": [
           {
               "id": 1,
               "userName": "username",
               "firstName": "first",
               "lastName": "last",
               "emailAddress": "email@address.com",
               "phoneNumber": "phone",
               "dateCreated": "2021-03-08T09:56:04+0000",
               "lastLogin": "2021-03-08T09:56:04+0000",
               "statusId": "statusId",
               "timezone": "timezone",
               "organization": "organization",
               "position": "position",
               "language": "language_code",
               "customAttributes": [
                   {
                       "id": 123,
                       "label": "label",
                       "value": "value",
                       "links": [],
                       "message": null
                   }
               ],
               "currentCredits": "2.50",
               "reservationColor": "#000000",
               "links": [],
               "message": null
           }
       ],
       "links": [],
       "message": null
   }

GetUser
^^^^^^^

**Description:**

Loads the requested user by Id

**Route:** ``/Web/Services/index.php/Users/:userId``

*This service is secure and requires authentication*

**Response:**

.. code:: json

   {
       "id": 1,
       "userName": "username",
       "firstName": "first",
       "lastName": "last",
       "emailAddress": "email@address.com",
       "phoneNumber": "phone",
       "lastLogin": "2021-03-08T09:56:04+0000",
       "statusId": "statusId",
       "timezone": "timezone",
       "organization": "organization",
       "position": "position",
       "language": "language_code",
       "icsUrl": "webcal://url/to/calendar",
       "defaultScheduleId": 1,
       "currentCredits": "2.50",
       "reservationColor": "#000000",
       "customAttributes": [
           {
               "id": 123,
               "label": "label",
               "value": "value",
               "links": [],
               "message": null
           }
       ],
       "permissions": [
           {
               "id": 123,
               "name": "resource name",
               "type": null,
               "groups": null,
               "links": [],
               "message": null
           }
       ],
       "groups": [
           {
               "id": 1,
               "name": "group name",
               "isDefault": null,
               "roleIds": null,
               "links": [],
               "message": null
           }
       ],
       "links": [],
       "message": null
   }

DeleteUser
^^^^^^^^^^

**Description:**

Deletes an existing user

**Route:** ``/Web/Services/index.php/Users/:userId``

*This service is secure and requires authentication*

*This service is only available to application administrators*

**Response:**

.. code:: json

   {
       "links": [],
       "message": "The item was deleted"
   }
