# Getting Started
## Installation
Install the package via Composer:
```
composer require r-kujawa/laravel-payment-gateway
```
Then, publish the config:
```
php artisan vendor:publish --tag=payment-config
```
Optionaly, you may also publish the migration file:
```
php artisan vendor:publish --tag=payment-migration
```

## Configuring your environment
### Payment Method Types
To provide support for payment method types you can run `php artisan payment:add-type 'Your Type'`. This will generate a migration to add the payment type.

### Payment Providers
You must also add at least one payment provider using `php artisan payment:add-provider` and follow the instructions. This will scaffold the boilerplate code that will help you with your implementation and will also generate a migration to add the provider.

### Payment Merchants
You must specify one or more merchants depending on your application's needs, to do this you may run `php artisan payment:add-merchant` and follow the instructions. This will generate a migration to add the merchant and relate it to the payment providers it will support.

## The payment config
You must specify the default provider and merchant you will be processing payments with in the `'defaults'` array of the `payment.php` config file.
