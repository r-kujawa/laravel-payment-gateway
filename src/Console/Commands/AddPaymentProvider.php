<?php

namespace rkujawa\LaravelPaymentGateway\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use InvalidArgumentException;
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
                            {--skip-migration : Do not run the migration}
                            {--test : Generates a gateway to be used for testing purposes}';

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

        if (! $this->option('test')) {
            $this->generateProviderMigration($studlySlug);
        }

        $this->putFile(
            app_path("Services/Payment/{$studlySlug}PaymentGateway.php"),
            $this->makeFile(__DIR__ . '/../stubs/payment-gateway.stub', ['name' => $studlySlug])
        );

        $this->putFile(
            app_path("Services/Payment/{$studlySlug}PaymentResponse.php"),
            $this->makeFile(__DIR__ . '/../stubs/payment-response.stub', ['name' => $studlySlug])
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

        $this->slug = PaymentProvider::slugify(
            $this->option('slug') ??
            $this->ask("What slug would you like to use for the {$this->name} payment provider?", PaymentProvider::slugify($this->name))
        );
    }

    private function generateProviderMigration($studlySlug)
    {
        $migrationClass = "Add{$studlySlug}PaymentProvider";

        if ($this->classExists($migrationClass, $this->getMigrationPath())) {
            throw new InvalidArgumentException("{$migrationClass}::class already exists.");
        }

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
