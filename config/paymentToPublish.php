<?php

use Authnetjson\AuthnetApiFactory;
use rkujawa\LaravelPaymentGateway\Providers\Authorize\AuthorizeGateway;
use rkujawa\LaravelPaymentGateway\Providers\Braintree\BrainTreeGateway;

return [
    'defaults' => [
        'provider' => 'authorize',
    ],

    'providers' => [
        'authorize' => [
            'class' => AuthorizeGateway::class,
            'merchants' => [
                'merchant1' => [
                    'id' => '',
                    'secret' => '',
                    'server' => AuthnetApiFactory::USE_PRODUCTION_SERVER,
                ],
                'merchant2' => [
                    'id' => '',
                    'secret' => '',
                    'server' => AuthnetApiFactory::USE_PRODUCTION_SERVER,
                ],
                'merchantN' => [
                    'id' => '',
                    'secret' => '',
                    'server' => AuthnetApiFactory::USE_PRODUCTION_SERVER,
                ],
            ],
            'sandbox' => [
                'id' => '',
                'secret' => '',
                'server' => AuthnetApiFactory::USE_DEVELOPMENT_SERVER,
            ],
            'defaults' => [
                'merchant' => 'merchantN',
            ]
        ],

        'braintree' => [
            'class' => BrainTreeGateway::class,
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
