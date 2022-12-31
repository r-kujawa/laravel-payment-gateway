<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Illuminate\Support\Str;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;

class AddPaymentProviderCommandTest extends TestCase
{
    /** @test */
    public function add_payment_provider_command_will_prompt_for_missing_arguments()
    {
        $provider = PaymentProvider::factory()->make();

        $this->artisan('payment:add-provider')
            ->expectsQuestion('What payment provider would you like to add?', $provider->name)
            ->expectsQuestion("How would you like to identify the {$provider->name} payment provider?", $provider->id)
            ->assertExitCode(0);

        $this->assertGatewayExists($provider->id);
    }

    /** @test */
    public function add_payment_provider_command_completes_without_asking_questions_when_providing_the_arguments()
    {
        $provider = PaymentProvider::factory()->make();

        $this->artisan('payment:add-provider', [
                'provider' => $provider->name,
                '--id' => $provider->id,
            ])
            ->assertExitCode(0);

        $this->assertGatewayExists($provider->id);
    }

    /** @test */
    public function add_payment_provider_command_with_fake_argument_generates_fake_gateway()
    {
        $arguments = [
            '--fake' => true,
        ];

        $this->artisan('payment:add-provider', $arguments)
            ->assertExitCode(0);

        $this->assertGatewayExists('fake');
    }

    private function assertGatewayExists(string $id)
    {
        $provider = Str::studly($id);

        $servicePath = app_path('Services/Payment');

        $this->assertTrue(file_exists("{$servicePath}/{$provider}PaymentGateway.php"));
        $this->assertTrue(file_exists("{$servicePath}/{$provider}PaymentResponse.php"));
    }
}
