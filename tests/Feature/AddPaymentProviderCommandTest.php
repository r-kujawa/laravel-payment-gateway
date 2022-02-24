<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Illuminate\Support\Str;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;

class AddPaymentProviderCommandTest extends TestCase
{
    /** @test */
    public function add_payment_provider_command_makes_migration()
    {
        $paymentProvider = PaymentProvider::factory()->make();

        $this->artisan('payment:add-provider', ['provider' => $paymentProvider->name, '--slug' => $paymentProvider->slug])
            ->expectsOutput('The migration to add ' . $paymentProvider->name . ' payment provider has been generated.')
            ->expectsConfirmation('Would you like to run the migration?')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('payment_providers', ['slug' => $paymentProvider->slug]);

        $this->artisan('migrate');

        $this->assertDatabaseHas('payment_providers', ['slug' => $paymentProvider->slug]);
    }

    /** @test */
    public function add_payment_provider_command_runs_migration_when_prompted()
    {
        $paymentProvider = PaymentProvider::factory()->make();

        $this->artisan('payment:add-provider', ['provider' => $paymentProvider->name, '--slug' => $paymentProvider->slug])
            ->expectsConfirmation('Would you like to run the migration?', 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('payment_providers', ['slug' => $paymentProvider->slug]);
    }

    /** @test */
    public function add_payment_provider_command_generates_gateway_implementation_files()
    {
        $paymentProvider = PaymentProvider::factory()->make();

        $studlySlug = Str::studly($paymentProvider->slug);

        $this->artisan('payment:add-provider', ['provider' => $paymentProvider->name, '--slug' => $paymentProvider->slug])
            ->expectsConfirmation('Would you like to run the migration?')
            ->assertExitCode(0);

        $servicePath = app_path('Services/Payment');

        $this->assertTrue(file_exists("{$servicePath}/{$studlySlug}PaymentGateway.php"));
    }

    /** @test */
    public function add_payment_provider_command_will_not_prompt_to_run_migration_when_passing_skip_migration_option()
    {
        $paymentProvider = PaymentProvider::factory()->make();

        $this->artisan('payment:add-provider', ['provider' => $paymentProvider->name, '--slug' => $paymentProvider->slug, '--skip-migration' => true])
            ->assertExitCode(0);

        $this->artisan('migrate');

        $this->assertDatabaseHas('payment_providers', ['slug' => $paymentProvider->slug]);
    }

    /** @test */
    public function add_payment_provider_command_will_prompt_for_missing_arguments()
    {
        $paymentProvider = PaymentProvider::factory()->make();

        $this->artisan('payment:add-provider')
            ->expectsQuestion('What provider would you like to add?', $paymentProvider->name)
            ->expectsQuestion('What slug would you like to use for the '.$paymentProvider->name.' provider?', $paymentProvider->slug)
            ->expectsConfirmation('Would you like to run the migration?', 'yes')
            ->assertExitCode(0);
        
        $this->assertDatabaseHas('payment_providers', ['slug' => $paymentProvider->slug]);
    }
}
