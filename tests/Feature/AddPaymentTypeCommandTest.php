<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Exception;
use InvalidArgumentException;
use rkujawa\LaravelPaymentGateway\Models\PaymentType;

class AddPaymentTypeCommandTest extends TestCase
{
    /** @test */
    public function add_payment_type_command_makes_migration()
    {
        $paymentType = PaymentType::factory()->make();

        $this->artisan('payment:add-type', ['type' => $paymentType->name, '--slug' => $paymentType->slug])
            ->expectsOutput('The migration to add ' . $paymentType->name . ' payment type has been generated.')
            ->expectsConfirmation('Would you like to run the migration?')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('payment_types', ['slug' => $paymentType->slug]);

        $this->artisan('migrate');

        $this->assertDatabaseHas('payment_types', ['slug' => $paymentType->slug]);
    }

    /** @test */
    public function add_payment_type_command_runs_migration_when_prompted()
    {
        $paymentType = PaymentType::factory()->make();

        $this->artisan('payment:add-type', ['type' => $paymentType->name, '--slug' => $paymentType->slug])
            ->expectsConfirmation('Would you like to run the migration?', 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('payment_types', ['slug' => $paymentType->slug]);
    }

    /** @test */
    public function add_payment_type_command_fails_if_migration_already_exists()
    {
        $paymentType = PaymentType::factory()->make();

        $this->artisan('payment:add-type', ['type' => $paymentType->name, '--slug' => $paymentType->slug])
            ->expectsConfirmation('Would you like to run the migration?')
            ->assertExitCode(0);

        try {
            $this->artisan('payment:add-type', ['type' => $paymentType->name, '--slug' => $paymentType->slug])
                ->doesntExpectOutput('The migration to add ' . $paymentType->name . ' payment type has been generated.');
        } catch (Exception $e) {
            $this->assertEquals(InvalidArgumentException::class, get_class($e));
        }
    }

    /** @test */
    public function add_payment_type_command_prompts_for_input_when_not_provided()
    {
        $paymentType = PaymentType::factory()->make();

        $this->artisan('payment:add-type')
            ->expectsQuestion('What payment type would you like to add?', $paymentType->name)
            ->expectsQuestion('How would you display the payment type to the end user?', $paymentType->display_name)
            ->expectsQuestion("What slug would you like to use for the {$paymentType->name} payment type?", $paymentType->slug)
            ->expectsConfirmation('Would you like to run the migration?', 'yes')
            ->assertExitCode(0);

            $this->assertDatabaseHas('payment_types', ['slug' => $paymentType->slug]);
    }
}
