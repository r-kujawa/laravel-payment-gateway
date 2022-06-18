<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Exception;
use Illuminate\Support\Str;
use InvalidArgumentException;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;

class AddPaymentProviderCommandTest extends CommandTestCase
{
    /** @test */
    public function add_payment_provider_command_will_prompt_for_missing_arguments()
    {
        $provider = PaymentProvider::factory()->make();

        $this->artisan('payment:add-provider')
            ->expectsQuestion($this->getArgumentQuestion('provider'), $provider->name)
            ->expectsQuestion($this->getOptionQuestion('slug', 'provider', $provider->name), $provider->slug)
            ->expectsConfirmation($this->getMigrationConfirmation(), 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('payment_providers', ['slug' => $provider->slug]);
    }

    /** @test */
    public function add_payment_provider_command_makes_migration()
    {
        $provider = PaymentProvider::factory()->make();

        $this->artisan('payment:add-provider', [
                'provider' => $provider->name,
                '--slug' => $provider->slug,
            ])
            ->expectsOutput($this->getMigrationOutput('provider', $provider->name))
            ->expectsConfirmation($this->getMigrationConfirmation())
            ->assertExitCode(0);

        $this->assertDatabaseMissing('payment_providers', ['slug' => $provider->slug]);

        $this->artisan('migrate');

        $this->assertDatabaseHas('payment_providers', ['slug' => $provider->slug]);
    }

    /** @test */
    public function add_payment_provider_command_generates_gateway_implementation_files()
    {
        $provider = PaymentProvider::factory()->make();

        $studlySlug = Str::studly($provider->slug);

        $this->artisan('payment:add-provider', [
                'provider' => $provider->name,
                '--slug' => $provider->slug,
                '--skip-migration' => true,
            ])
            ->assertExitCode(0);

        $servicePath = app_path('Services/Payment');

        $this->assertTrue(file_exists("{$servicePath}/{$studlySlug}PaymentGateway.php"));
        $this->assertTrue(file_exists("{$servicePath}/{$studlySlug}PaymentResponse.php"));
    }

    /** @test */
    public function add_payment_provider_command_fails_if_migration_already_exists()
    {
        $provider = PaymentProvider::factory()->make();

        $arguments = [
            'provider' => $provider->name,
            '--slug' => $provider->slug,
            '--skip-migration' => true,
        ];

        $this->artisan('payment:add-provider', $arguments)
            ->expectsOutput($this->getMigrationOutput('provider', $provider->name))
            ->assertExitCode(0);

        try {
            $this->artisan('payment:add-provider', $arguments)
                ->doesntExpectOutput($this->getMigrationOutput('provider', $provider->name))
                ->assertExitCode(0);
        } catch (Exception $e) {
            $this->assertEquals(InvalidArgumentException::class, get_class($e));
        }
    }

    /** @test */
    public function add_payment_provider_command_skips_migration_when_generating_test_gateway()
    {
        $arguments = [
            '--test' => true,
        ];

        $this->artisan('payment:add-provider', $arguments);

        $servicePath = app_path('Services/Payment');

        $this->assertTrue(file_exists("{$servicePath}/TestPaymentGateway.php"));
        $this->assertTrue(file_exists("{$servicePath}/TestPaymentResponse.php"));

        $this->artisan('migrate');

        $this->assertDatabaseMissing('payment_providers', ['slug' => 'test']);
    }
}
