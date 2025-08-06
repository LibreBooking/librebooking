<?php

return [
    'settings' => [
        'admincheckonly' => [
            'attribute.checkin.id' => 5,
            'attribute.checkout.id' => 6,
            'message.checkin' => 'Only Admin has permission to Check In on these resource(s).',
            'message.checkout' => 'Only Admin has permission to Check Out on these resource(s).',
            'message.checkin.resource.conflict' => 'There is at least one resource in this reservation that needs to be Checked In by Admin.',
            'message.checkout.resource.conflict' => 'There is at least one resource in this reservation that needs to be Checked Out by Admin.',
        ],
    ],
];
