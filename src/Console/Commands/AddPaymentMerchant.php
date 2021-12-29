<?php

namespace rkujawa\LaravelPaymentGateway\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use InvalidArgumentException;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\Traits\GeneratesMigrations;

class AddPaymentMerchant extends Command
{
    use GeneratesMigrations;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:add-merchant
                            {merchant : The merchant name}
                            {--slug= : The merchant dev name}
                            {provider : The merchant\'s provider slug}
                            {--provider-name= : The merchant\'s provider name}
                            {--skip-migration : Do not run the migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new payment merchant and scaffold it\'s implementation.';

    /**
     * The merchant attributes to be saved.
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
        $provider = PaymentProvider::where('slug', $this->argument('provider'))->first();

        if (is_null($provider)) {
            if (is_null($this->option('provider-name'))) {
                $this->error('Please provide the --provider-name so we can generate the migration for you.');
                return;
            }

            $this->call('payment:add-provider', ['provider' => $this->option('provider-name'), '--slug' => $this->argument('provider'), '--skip-migration' => true]);
        }

        $this->setProperties();

        $studlySlug = Str::studly($this->slug);

        $migrationClass = "Add{$studlySlug}PaymentMerchant";

        if ($this->classExists($migrationClass, $this->getMigrationPath())) {
            throw new InvalidArgumentException("{$migrationClass}::class already exists.");
        }

        $this->putFile(
            $this->generateMigrationFilePath($migrationClass),
            $this->makeFile(
                __DIR__ . '/../stubs/payment-merchant-migration.stub',
                [
                    'class' => $migrationClass,
                    'name' => addslashes($this->name),
                    'slug' => $this->slug,
                    'provider' => $this->argument('provider'),
                ]
            )
        );

        $this->info('The migration to add ' . $this->name . ' payment merchant has been generated.');

        if ((! $this->option('skip-migration')) && $this->confirm('Would you like to run the migration?', true)) {
            $this->call('migrate', ['--force']);
        }
    }

    /**
     * Format the payment provider's properties.
     *
     * @return void
     */
    protected function setProperties()
    {
        $this->name = trim($this->argument('merchant'));
        $this->slug = PaymentMerchant::slugify($this->option('slug') ?? $this->name);
    }
}
