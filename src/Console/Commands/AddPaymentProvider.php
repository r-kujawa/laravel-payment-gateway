<?php

namespace rkujawa\LaravelPaymentGateway\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use rkujawa\LaravelPaymentGateway\Traits\GeneratesFiles;

class AddPaymentProvider extends Command
{
    use GeneratesFiles;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:add-provider
                            {provider? : The payment provider name}
                            {--id= : The payment provider identifier}
                            {--test : Generates a gateway to be used for testing purposes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold a new payment provider\'s gateway and response classes.';

    /**
     * The payment provider attributes to be saved.
     *
     * @var string $name
     * @var string $id
     */
    protected $name, $id;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->setProperties();

        $provider = Str::studly($this->id);

        $this->putFile(
            app_path("Services/Payment/{$provider}PaymentGateway.php"),
            $this->makeFile(__DIR__ . '/../stubs/payment-gateway.stub', ['name' => $provider])
        );

        $this->putFile(
            app_path("Services/Payment/{$provider}PaymentResponse.php"),
            $this->makeFile(__DIR__ . '/../stubs/payment-response.stub', ['name' => $provider])
        );
    }

    /**
     * Format the payment provider's properties.
     *
     * @return void
     */
    protected function setProperties()
    {
        $this->name = trim(
            $this->argument('provider') ?? (
                $this->option('test', false)
                    ? 'Test'
                    : $this->ask('What payment provider would you like to add?')
            )
        );

        $this->id =
            $this->option('id') ??
            $this->ask(
                "How would you like to identify the {$this->name} payment provider?",
                preg_replace('/[^a-z0-9]+/i', '_', strtolower($this->name))
            );
    }
}
