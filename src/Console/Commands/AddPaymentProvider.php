<?php

namespace rkujawa\LaravelPaymentGateway\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\Traits\GeneratesMigrations;

class AddPaymentProvider extends Command
{
    use GeneratesMigrations;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:add-provider
                            {provider? : The payment provider name}
                            {--slug= : The payment provider dev name}
                            {--manager : Generate class for payment management}
                            {--processor : Generate class for payment processing}
                            {--skip-migration : Do not run the migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new payment provider and scaffold it\'s implementation.';

    /**
     * The payment provider attributes to be saved.
     *
     * @var string $name
     * @var string $slug
     */
    protected $name, $slug;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->setProperties();

        $studlySlug = Str::studly($this->slug);

        $this->putFile(
            app_path("Services/Payment/{$studlySlug}PaymentGateway.php"),
            $this->makeFile(__DIR__ . '/../stubs/payment-gateway.stub', ['name' => $studlySlug])
        );

        $all = ! ($this->option('manager') || $this->option('processor'));

        if ($all || $this->option('manager')) {
            $this->putFile(
                app_path("Services/Payment/{$studlySlug}PaymentManager.php"),
                $this->makeFile(__DIR__ . '/../stubs/payment-manager-service.stub', ['name' => $studlySlug])
            );
        }

        if ($all || $this->option('processor')) {
            $this->putFile(
                app_path("Services/Payment/{$studlySlug}PaymentProcessor.php"),
                $this->makeFile(__DIR__ . '/../stubs/payment-processor-service.stub', ['name' => $studlySlug])
            );
        }

        $migrationClass = "Add{$studlySlug}PaymentProvider";

        if (! $this->classExists($migrationClass, $this->getMigrationPath())) {
            $this->putFile(
                $this->generateMigrationFilePath($migrationClass),
                $this->makeFile(
                    __DIR__ . '/../stubs/payment-provider-migration.stub',
                    [
                        'class' => $migrationClass,
                        'name' => addslashes($this->name),
                        'slug' => $this->slug,
                    ]
                )
            );

            $this->info('The migration to add ' . $this->name . ' payment provider has been generated.');

            if ((! $this->option('skip-migration')) && $this->confirm('Would you like to run the migration?', true)) {
                $this->call('migrate', ['--force']);
            }
        }
    }

    /**
     * Format the payment provider's properties.
     *
     * @return void
     */
    protected function setProperties()
    {
        $this->name = trim(
            $this->argument('provider') ?? 
            $this->ask('What provider would you like to add?')
        );

        $this->slug = PaymentProvider::slugify(
            $this->option('slug') ?? 
            $this->ask("What slug would you like to use for the {$this->name} provider?", PaymentProvider::slugify($this->name))
        );
    }
}
