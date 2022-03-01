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
                            {merchant? : The merchant name}
                            {--slug= : The merchant dev name}
                            {--skip-provider : Do not associate a provider with the merchant}
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
     * The merchant's provider relationships to be saved.
     *
     * @var string $providers
     * @var string $defaultProvider
     */
    protected $providers, $defaultProvider;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
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
                    'providers' => $this->providers,
                    'defaultProvider' => $this->defaultProvider,
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
        $this->name = trim(
            $this->argument('merchant') ??
            $this->ask('What payment merchant would you like to add?')
        );

        $this->slug = PaymentMerchant::slugify(
            $this->option('slug') ?? 
            $this->ask("What slug would you like to use for the {$this->name} payment merchant?", PaymentMerchant::slugify($this->name))
        );

        if ($this->option('skip-provider') || ($providers = PaymentProvider::all())->isEmpty()) {
            $this->providers = '';
            $this->defaultProvider = '';

            return;
        }

        $selectedProviders = $this->choice(
            "Which payment providers will the {$this->name} merchant be using? (First chosen will be default)",
            $providers->pluck('slug')->toArray(),
            null,
            null,
            true
        );

        $this->providers = collect($selectedProviders)->join("', '");
        $this->defaultProvider = $selectedProviders[0];
    }
}
