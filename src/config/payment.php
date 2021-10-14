<?php

return [
    'defaults' => [
        'provider' => 'authorize',
    ],

    'providers' => [
        'authorize' => [
            'class' => '',
            'merchants' => [
                'merchant1' => [
                    'id' => '',
                    'secret' => '',
                    'server' => '',
                ],
                'merchant2' => [
                    'id' => '',
                    'secret' => '',
                    'server' => '',
                ],
                'merchantN' => [
                    'id' => '',
                    'secret' => '',
                    'server' => '',
                ],
            ],
            'sandbox' => [
                'id' => '',
                'secret' => '',
                'server' => '',
            ],
            'defaults' => [
                'merchant' => 'merchantN',
            ]
        ],

        'braintree' => [
            'class' => '',
            'merchants' => [
                'incfile' => [
                    'id' => '',
                    'secret' => '',
                ],
            ],
            'sandbox' => [
                'id' => '',
                'secret' => '',
            ],
            'defaults' => [
                'merchant' => 'merchantN',
            ]
        ],
    ],
];
