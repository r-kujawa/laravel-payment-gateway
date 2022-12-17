<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Exception;
use InvalidArgumentException;
use rkujawa\LaravelPaymentGateway\Models\PaymentType;

class AddPaymentTypeCommandTest extends CommandTestCase
{
    /** @test */
    public function add_payment_type_command_prompts_for_input_when_not_provided()
    {
        $type = PaymentType::factory()->make();

        $this->artisan('payment:add-type')
            ->expectsQuestion($this->getArgumentQuestion('type'), $type->name)
            ->expectsQuestion($this->getOptionQuestion('display name', 'type', $type->name), $type->display_name)
            ->expectsQuestion($this->getOptionQuestion('slug', 'type', $type->name), $type->slug)
            ->expectsOutput($this->getMigrationOutput('type', $type->name))
            ->expectsConfirmation($this->getMigrationConfirmation(), 'yes')
            ->assertExitCode(0);

            $this->assertDatabaseHas('payment_types', ['slug' => $type->slug]);
    }

    public function add_payment_type_command_doesnt_prompt_for_display_name_when_type_argument_is_present()
    {
        $type = PaymentType::factory()->make();

        $this->artisan('payment:add-type', [
                'type' => $type->name,
            ])
            ->expectsQuestion($this->getOptionQuestion('slug', 'type', $type->name), $type->slug)
            ->expectsOutput($this->getMigrationOutput('type', $type->name))
            ->expectsConfirmation($this->getMigrationConfirmation(), 'yes')
            ->assertExitCode(0);

            $this->assertDatabaseHas('payment_types', ['slug' => $type->slug]);
    }

    /** @test */
    public function add_payment_type_command_makes_migration()
    {
        $type = PaymentType::factory()->make();

        $this->artisan('payment:add-type', [
                'type' => $type->name,
                '--slug' => $type->slug,
            ])
            ->expectsOutput($this->getMigrationOutput('type', $type->name))
            ->expectsConfirmation($this->getMigrationConfirmation())
            ->assertExitCode(0);

        $this->assertDatabaseMissing('payment_types', ['slug' => $type->slug]);

        $this->artisan('migrate');

        $this->assertDatabaseHas('payment_types', ['slug' => $type->slug]);
    }

    /** @test */
    public function add_payment_type_command_fails_if_migration_already_exists()
    {
        $type = PaymentType::factory()->make();

        $arguments = [
            'type' => $type->name,
            '--slug' => $type->slug,
            '--skip-migration' => true,
        ];

        $this->artisan('payment:add-type', $arguments)
            ->expectsOutput($this->getMigrationOutput('type', $type->name))
            ->assertExitCode(0);

        try {
            $this->artisan('payment:add-type', $arguments)
                ->doesntExpectOutput($this->getMigrationOutput('type', $type->name))
                ->assertExitCode(0);
        } catch (Exception $e) {
            $this->assertEquals(InvalidArgumentException::class, get_class($e));
        }
    }
}
