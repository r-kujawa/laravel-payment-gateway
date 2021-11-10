<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default payment "provider" for your application.
    | You may set these defaults as required, you should set your primary payments
    | provider here, as it will be the provider to be injected automatically.
    |
    */
    'defaults' => [
        'provider' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Payment Providers
    |--------------------------------------------------------------------------
    |
    | This option defines the payment providers that your application supports along with
    | their merchants. Specify the providers manually once they are ready for production.
    | You may also specify the service class paths if they differ from the default paths
    |
    */
    'providers' => [
        '' => [
            'merchants' => [
                '',
            ],
        ],
    ],

];
