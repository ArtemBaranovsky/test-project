<?php

return [
    'paypal' => [
        'client_id'  => env('PAYPAL_CLIENT_ID'),
        'secret'     => env('PAYPAL_SECRET'),

    ],
    'uapay'  => [
        'client_id'  => env('UAPAY_CLIENT_ID'),
        'secret'     => env('UAPAY_SECRET'),
    ],
    'waypay' => [
        'client_id'  => env('WAYPAY_CLIENT_ID'),
        'secret'     => env('WAYPAY_SECRET'),
    ],
];
