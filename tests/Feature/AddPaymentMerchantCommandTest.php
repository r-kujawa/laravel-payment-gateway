<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Exception;
use InvalidArgumentException;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;

class PaymentMerchantCommandTest extends CommandTestCase
{
    /** @test */
    public function add_payment_merchant_command_will_prompt_for_missing_arguments()
    {
        $merchant = PaymentMerchant::factory()->make();

        $this->artisan('payment:add-merchant')
            ->expectsQuestion($this->getArgumentQuestion('merchant'), $merchant->name)
            ->expectsQuestion($this->getOptionQuestion('slug', 'merchant', $merchant->name), $merchant->slug)
            ->expectsOutput($this->getMigrationOutput('merchant', $merchant->name))
            ->expectsConfirmation($this->getMigrationConfirmation(), 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('payment_merchants', ['slug' => $merchant->slug]);
    }

    /** @test */
    public function add_payment_merchant_command_makes_migration()
    {
        $merchant = PaymentMerchant::factory()->make();

        $this->artisan('payment:add-merchant', [
                'merchant' => $merchant->name,
                '--slug' => $merchant->slug,
            ])
            ->expectsOutput($this->getMigrationOutput('merchant', $merchant->name))
            ->expectsConfirmation($this->getMigrationConfirmation())
            ->assertExitCode(0);

        $this->assertDatabaseMissing('payment_merchants', ['slug' => $merchant->slug]);

        $this->artisan('migrate');

        $this->assertDatabaseHas('payment_merchants', ['slug' => $merchant->slug]);
    }

    /** @test */
    public function add_payment_merchant_command_fails_if_migration_already_exists()
    {
        $merchant = PaymentMerchant::factory()->make();

        $arguments = [
            'merchant' => $merchant->name,
            '--slug' => $merchant->slug,
            '--skip-migration' => true,
        ];

        $this->artisan('payment:add-merchant', $arguments)
            ->expectsOutput($this->getMigrationOutput('merchant', $merchant->name))
            ->assertExitCode(0);

        try {
            $this->artisan('payment:add-merchant', $arguments)
                ->doesntExpectOutput($this->getMigrationOutput('merchant', $merchant->name))
                ->assertExitCode(0);
        } catch (Exception $e) {
            $this->assertEquals(InvalidArgumentException::class, get_class($e));
        }
    }

    /** @test */
    public function add_payment_merchant_command_will_prompt_to_choose_related_providers_if_exists()
    {
        $provider = PaymentProvider::factory()->create();
        $merchant = PaymentMerchant::factory()->make();

        $this->artisan('payment:add-merchant', [
                'merchant' => $merchant->name,
                '--slug' => $merchant->slug,
            ])
            ->expectsChoice(
                $this->getProvidersChoice($merchant->name),
                [$provider->slug],
                PaymentProvider::all()->pluck('slug')->toArray(),
            )
            ->expectsOutput($this->getMigrationOutput('merchant', $merchant->name))
            ->expectsConfirmation($this->getMigrationConfirmation(), 'yes')
            ->assertExitCode(0);
        
        $this->assertDatabaseHas('payment_merchants', ['slug' => $merchant->slug]);
        $this->assertDatabaseHas('payment_merchant_provider', [
            'provider_id' => $provider->id,
            'merchant_id' => PaymentMerchant::where('slug', $merchant->slug)->first()->id,
            'is_default' => true,
        ]);
    }

    protected function getProvidersChoice($merchant)
    {
        return "Which payment providers will the {$merchant} merchant be using? (First chosen will be default)";
    }
}
