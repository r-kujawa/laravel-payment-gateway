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
        'driver' => 'database',
        'provider' => '',
        'merchant' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Test Mode
    |--------------------------------------------------------------------------
    |
    | When set to true, it will pass the provider & merchant into the testing
    | gateway so you can mock your requests as you wish. This is very
    | usefull when you are running tests in a CI/CD environment.
    |
    */
    'test_mode' => env('PAYMENT_TEST_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Payment Test Configuration
    |--------------------------------------------------------------------------
    |
    | The following configurations will only be considered when test_mode
    | is set to true. Here you may set any configurations needed in
    | order to have your tests running smoothly.
    |
    */
    'test' => [
        'gateway' => \App\Services\Payment\TestPaymentGateway::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Service Drivers
    |--------------------------------------------------------------------------
    |
    | You may register custom payment drivers and/or remove the default ones.
    | Please note that in order for the driver to be compatible it must extend
    | the \rkujawa\LaravelPaymentGateway\PaymentServiceDriver::class.
    |
    */
    'drivers' => [
        'database' => \rkujawa\LaravelPaymentGateway\Drivers\DatabaseDriver::class,
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
        //     'gateway' => \App\Services\Payment\ExamplePaymentGateway::class,
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
