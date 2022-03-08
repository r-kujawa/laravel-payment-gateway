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
        'merchant' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Provider Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may specify the service class path for each provider ('path') or,
    | if they differ from the default paths, you may also specify the exact class
    | path for each of the services ('processor', 'manager').
    |
    */
    'providers' => [
        // '' => [
        //     'path' => 'App\\Services\\Payment'
        // ],
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
    //     \rkujawa\LaravelPaymentGateway\Models\PaymentMethod::class => \App\Models\Payment\PaymentMethod::class,
    //     \rkujawa\LaravelPaymentGateway\Models\Wallet::class => \App\Models\Payment\Wallet::class,
    // ],

];
