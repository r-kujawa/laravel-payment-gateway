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

    
    /*
    |--------------------------------------------------------------------------
    | Payment Models
    |--------------------------------------------------------------------------
    |
    | This option allows you to override the models to be used for managing payments.
    | You may define your own models that extend the ones of the package in order to
    | keep your relationships in sync with your own models.
    |
    */
    // 'models' => [
    //     \rkujawa\LaravelPaymentGateway\Models\Wallet::class => App\Models\Payment\Wallet::class,
    //     \rkujawa\LaravelPaymentGateway\Models\PaymentMethod::class => App\Models\Payment\PaymentMethod::class,
    // ],

];
