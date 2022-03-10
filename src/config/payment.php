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
    | Here you may provide the location of your provider's gateway implementation
    | by specifying the 'gateway' key for each provider and mapping the class to
    | it, you may also add any provider specific configuration you might have.
    |
    */
    'providers' => [
        // 'example' => [
        //     'gateway' => App\Services\Payment\ExamplePaymentGateway::class,
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
