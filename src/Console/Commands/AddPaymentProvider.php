<?php

namespace rkujawa\LaravelPaymentGateway\Console\Commands;

use Illuminate\Support\Str;
use rkujawa\LaravelPaymentGateway\Console\Commands\FilesystemCommand as Command;
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
                            {provider : The payment provider name}
                            {--slug= : The payment provider dev name}
                            {--manager : Generate class for payment management}
                            {--processor : Generate class for payment processing}';

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
        $migrationPath = database_path('migrations');

        if ($this->classExists($migrationClass, $migrationPath)) {
            $this->info('Skipping the migration because ' . $this->name . ' payment provider already exists.');
        } else {
            $this->putFile(
                $this->generateMigrationFilePath($migrationClass, $migrationPath),
                $this->makeFile(
                    __DIR__ . '/../stubs/payment-provider-migration.stub',
                    [
                        'class' => $migrationClass,
                        'name' => $this->name,
                        'slug' => $this->slug,
                    ]
                )
            );

            $this->info('The migration to add ' . $this->name . ' payment provider has been generated.');

            if ($this->confirm('Would you like to run your migration?', true)) {
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
        $this->name = trim($this->argument('provider'));
        $this->slug = PaymentProvider::slugify($this->option('slug') ?? $this->name);
    }
}
