<?php

return [

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
    'test_mode' => false,

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
        'config' => \rkujawa\LaravelPaymentGateway\Drivers\ConfigDriver::class,
        'database' => \rkujawa\LaravelPaymentGateway\Drivers\DatabaseDriver::class,
    ],

];
