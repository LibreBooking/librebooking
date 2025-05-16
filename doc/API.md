# LibreBooking API Documentation

A dynamically generated API documentation Page can be found by opening `<librebooking-url>/Web/Services/index.php` (API has to be enabled in config)

- [Getting Started With The API](#getting-started-with-the-api)
- [Accessories](#accessories)
- [Accounts](#accounts)
- [Attributes](#attributes)
- [Authentication](#authentication)
- [Groups](#groups)
- [Reservations](#reservations)
- [Resources](#resources)
- [Schedules](#schedules)
- [Users](#users)

## Getting Started With the API

### Authenticating to LibreBooking

For all of the secure service calls it is required to be
[Authenticated](#authenticate). The basic steps are:

1. Make a request to the [Authenticate](#authenticate) POST API endpoint. The
   POST data must be sent as JSON
1. The result from the [Authenticate](#authenticate) POST API call, if
   successful, will contain the two values: `sessionToken` and `userId`
1. When making secure service calls the following headers must be set:
    1. `X-Booked-SessionToken` set to the value of `sessionToken`
        returned by the [Authenticate](#authenticate) API call.
    1. `X-Booked-UserId` set to the value of `userId` returned by the
        [Authenticate](#authenticate) API call.

### POST Requests

When making POST API requests it is required to send the POST data as JSON

## Accessories

### POST Services

na.

### GET Services

#### GetAllAccessories

__Description:__  
Loads all accessories.  
CreditApplicability of 1 is per slot, 2 is per reservation

__Route:__ `/Web/Services/index.php/Accessories/`  
_This service is secure and requires authentication_

__Response:__

```json
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
```

#### GetAccessory

__Description:__  
Loads a specific accessory by id. CreditApplicability of 1 is per slot, 2 is per reservation

__Route:__ `/Web/Services/index.php/Accessories/:accessoryId`
_This service is secure and requires authentication_

__Response:__  

```json
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
```

## Accounts

### POST Services

#### CreateAccount

__Description:__  
Creates a user account. This does not authenticate

__Route:__ `/Web/Services/index.php/Accounts/`

__Response:__  

Unstructured response of type _AccountCreatedResponse_

__Request:__  

```json
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
```

#### UpdateAccount

__Description:__  
Updates an existing user account

__Route:__ `/Web/Services/index.php/Accounts/:userId`

_This service is secure and requires authentication_

__Response:__  
Unstructured response of type _AccountUpdatedResponse_

__Request:__

```json
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
```

#### UpdatePassword

__Description:__  

Updates the password for an existing user

__Route:__ `/Web/Services/index.php/Accounts/:userId/Password`

_This service is secure and requires authentication_

__Response:__  

Unstructured response of type _AccountUpdatedResponse_

__Request:__  

```json
{
    "currentPassword": "plain.text.current.password",
    "newPassword": "plain.text.new.password"
}
```

### GET Services

#### GetAccount

__Description:__  

Gets the currently authenticated users's account information

__Route:__ `/Web/Services/index.php/Accounts/:userId`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

## Attributes

### POST Services

#### CreateCustomAttribute

__Description:__  

Creates a new custom attribute.  
Allowed values for type: 1 (single line), 2 (multi line), 3 (select list), 4 (checkbox), 5 (datetime)  
Allowed values for categoryId: 1 (reservation), 2 (user), 4 (resource), 5 (resource type)  
appliesToIds only allowed for category 2, 4, 5 and must match the id of corresponding entities  
secondaryCategoryId and secondaryEntityIds only applies to category 1 and must match the id of the corresponding entities

__Route:__ `/Web/Services/index.php/Attributes/`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
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
```

__Request:__

```json
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
```

#### UpdateCustomAttribute

__Description:__  

Updates and existing custom attribute  
Allowed values for type: 1 (single line), 2 (multi line), 3 (select list), 4 (checkbox), 5 (datetime)  
Allowed values for categoryId: 1 (reservation), 2 (user), 4 (resource), 5 (resource type)  
appliesToIds only allowed for category 2, 4, 5 and must match the id of corresponding entities  
secondaryCategoryId and secondaryEntityIds only applies to category 1 and must match the id of the corresponding entities

__Route:__ `/Web/Services/index.php/Attributes/:attributeId`  

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
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
```

__Request:__  

```json
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
```

### GET Services

#### GetCategoryAttributes

__Description:__  

Gets all custom attribute definitions for the requested category  
Categories are RESERVATION = 1, USER = 2, RESOURCE = 4

__Route:__ `/Web/Services/index.php/Attributes/Category/:categoryId`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### GetAttribute

__Description:__  

Gets all custom attribute definitions for the requested attribute

__Route:__ `/Web/Services/index.php/Attributes/:attributeId`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### DeleteCustomAttribute

__Description:__  

Deletes an existing custom attribute

__Route:__ `/Web/Services/index.php/Attributes/:attributeId`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
{
    "links": [], 
    "message": "The item was deleted"
}
```

## Authentication

### POST Services

#### SignOut

__Description:__  

invalidate Authenication Token

__Route:__ `/Web/Services/index.php/Authentication/SignOut`

__Response:__  

No response

__Request:__  

```json
{
    "userId": null, 
    "sessionToken": null
}
```

#### Authenticate

__Description:__  

Authenticates an existing LibreBooking user

__Route:__ `/Web/Services/index.php/Authentication/Authenticate`

__Response:__  

```json
{
    "sessionToken": "sessiontoken", 
    "sessionExpires": "2021-03-08T09:56:04+0000", 
    "userId": 123, 
    "isAuthenticated": true, 
    "version": "1.0", 
    "links": [], 
    "message": null
}
```

__Request:__  

```json
{
    "username":null,
    "password":null
}
```

### GET Services

na.

## Groups

### POST Services

#### CreateGroup

__Description:__  

Creates a new group

__Route:__  `/Web/Services/index.php/Groups/`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
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
```

__Request:__  

```json
{"name":"group name","isDefault":true}
```

#### UpdateGroup

__Description:__  

Updates and existing group

__Route:__ `/Web/Services/index.php/Groups/:groupId`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
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
```

__Request:__  

```json
{
    "name": "group name", 
    "isDefault": true
}
```

### ChangeGroupRoles

__Description:__  

Updates the roles for an existing group  
roleIds : 1 (Group Administrator), 2 (Application Administrator), 3 (Resource Administrator), 4 (Schedule Administrator)

__Route:__ `/Web/Services/index.php/Groups/:groupId/Roles`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
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
```

__Request:__  

No request

### ChangeGroupPermissions

__Description:__  

Updates the permissions for an existing group

__Route:__  `/Web/Services/index.php/Groups/:groupId/Permissions`
_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
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
```

__Request:__  

No request

### ChangeGroupUsers

__Description:__  

Updates the permissions for an existing group

__Route:__ `/Web/Services/index.php/Groups/:groupId/Users`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
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
```

__Request:__  

No request

### GET Services

#### GetAllGroups

__Description:__  

Loads all groups

__Route:__ `/Web/Services/index.php/Groups/`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### GetGroup

__Description:__  

Loads a specific group by id

__Route:__ `/Web/Services/index.php/Groups/:groupId`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### DeleteGroup

__Description:__  

Deletes an existing group

__Route:__ `/Web/Services/index.php/Groups/:groupId`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
{
    "links": [], 
    "message": "The item was deleted"
}
```

## Reservations

### POST Services

#### CreateReservation

__Description:__  

Creates a new reservation

__Route:__ `/Web/Services/index.php/Reservations/`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

__Request:__  

```json
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
```

#### UpdateReservation

__Description:__  

Updates an existing reservation.  
Pass an optional updateScope query string parameter to restrict changes. Possible values for updateScope are this|full|future

__Route:__  `/Web/Services/index.php/Reservations/:referenceNumber`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

__Request:__  

```json
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
```

#### ApproveReservation

__Description:__  

Approves a pending reservation.

__Route:__ `/Web/Services/index.php/Reservations/:referenceNumber/Approval`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

__Request:__  

No request

#### CheckinReservation

__Description:__  

Checks in to a reservation.

__Route:__ `/Web/Services/index.php/Reservations/:referenceNumber/CheckIn`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

__Request:__  

No request

#### CheckoutReservation

__Description:__  

Checks out of a reservation.

__Route:__  `/Web/Services/index.php/Reservations/:referenceNumber/CheckOut`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

__Request:__  

No request

### GET Services

#### GetReservations

__Description:__  

Gets a list of reservations for the specified parameters.  
Optional query string parameters: userId, resourceId, scheduleId, startDateTime, endDateTime.  
If no dates are provided, reservations for the next two weeks will be returned.  
If dates do not include the timezone offset, the timezone of the authenticated user will be assumed.

__Route:__ `/Web/Services/index.php/Reservations/`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### GetReservation

__Description:__  

Loads a specific reservation by reference number

__Route:__ `/Web/Services/index.php/Reservations/:referenceNumber`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### DeleteReservation

__Description:__  

Deletes an existing reservation.  
Pass an optional updateScope query string parameter to restrict changes. Possible values for updateScope are this|full|future

__Route:__  `/Web/Services/index.php/Reservations/:referenceNumber`

_This service is secure and requires authentication_

__Response:__  

```json
{
    "links": [], 
    "message": "The item was deleted"
}
```

## Resources

### POST Services

#### CreateResource

__Description:__  

Creates a new resource

__Route:__  `/Web/Services/index.php/Resources/`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
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
```

__Request:__  

```json
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
```

#### UpdateResource

__Description:__  

Updates an existing resource

__Route:__ `/Web/Services/index.php/Resources/:resourceId`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
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
```

__Request:__  

```json
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
```

### GET Services

#### GetStatuses

__Description:__  

Returns all available resource statuses

__Route:__ `/Web/Services/index.php/Resources/Status`

__Response:__  

```json
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
```

#### GetAllResources

__Description:__  

Loads all resources

__Route:__ `/Web/Services/index.php/Resources/`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### GetStatusReasons

__Description:__  

Returns all available resource status reasons

__Route:__ `/Web/Services/index.php/Resources/Status/Reasons`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### GetAvailability

__Description:__  

Returns resource availability for the requested resource (optional). "availableAt" and "availableUntil" will include availability through the next 7 days  
Optional query string parameter: dateTime. If no dateTime is requested the current datetime will be used.

__Route:__ `/Web/Services/index.php/Resources/Availability`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### GetGroups

__Description:__  

Returns the full resource group tree

__Route:__  `/Web/Services/index.php/Resources/Groups`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### GetResource

__Description:__  

Loads a specific resource by id

__Route:__ `/Web/Services/index.php/Resources/:resourceId`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

GetAvailability

__Description:__  

Returns resource availability for the requested resource (optional). "availableAt" and "availableUntil" will include availability through the next 7 days  
Optional query string parameter: dateTime. If no dateTime is requested the current datetime will be used.

__Route:__ `/Web/Services/index.php/Resources/:resourceId/Availability`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### DeleteResource

__Description:__  

Deletes an existing resource

__Route:__ `/Web/Services/index.php/Resources/:resourceId`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
{
    "links": [], 
    "message": "The item was deleted"
}
```

## Schedules

### POST Services

na.

### GET Services

#### GetAllSchedules

__Description:__  

Loads all schedules

__Route:__ `/Web/Services/index.php/Schedules/`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### GetSchedule

__Description:__  

Loads a specific schedule by id

__Route:__  `/Web/Services/index.php/Schedules/:scheduleId`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### GetSlots

__Description:__  

Loads slots for a specific schedule  
Optional query string parameters: resourceId, startDateTime, endDateTime.  
If no dates are provided the default schedule dates will be returned.  
If dates do not include the timezone offset, the timezone of the authenticated user will be assumed.

__Route:__ `/Web/Services/index.php/Schedules/:scheduleId/Slots`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

## Users

### POST Services

#### CreateUser

__Description:__  

Creates a new user

__Route:__  `/Web/Services/index.php/Users/`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__

```json
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
```

__Request:__  

```json
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
```

#### UpdateUser

__Description:__  

Updates an existing user

__Route:__  `/Web/Services/index.php/Users/:userId`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
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
```

__Request:__  

```json
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
```

#### UpdatePassword

__Description:__  

Updates the password for an existing user

__Route:__ `/Web/Services/index.php/Users/:userId/Password`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
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
```

__Request:__  

```json
{
    "password":"plaintext password"
}
```

### GET Services

#### GetAllUsers

__Description:__  

Loads all users that the current user can see.  
Optional query string parameters: username, email, firstName, lastName, phone, organization, position and any custom attributes.  
If searching on custom attributes, the query string parameter has to be in the format att#=value.  
For example, Users/?att1=ExpectedAttribute1Value

__Route:__  `/Web/Services/index.php/Users/`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### GetUser

__Description:__  

Loads the requested user by Id

__Route:__ `/Web/Services/index.php/Users/:userId`

_This service is secure and requires authentication_

__Response:__  

```json
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
```

#### DeleteUser

__Description:__  

Deletes an existing user

__Route:__ `/Web/Services/index.php/Users/:userId`

_This service is secure and requires authentication_

_This service is only available to application administrators_

__Response:__  

```json
{
    "links": [], 
    "message": "The item was deleted"
}
```
