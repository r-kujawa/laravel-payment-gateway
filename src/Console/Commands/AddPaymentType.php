<?php

namespace rkujawa\LaravelPaymentGateway\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use InvalidArgumentException;
use rkujawa\LaravelPaymentGateway\Models\PaymentType;
use rkujawa\LaravelPaymentGateway\Traits\GeneratesMigrations;

class AddPaymentType extends Command
{
    use GeneratesMigrations;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:add-type
                            {type : The payment type name}
                            {--displayName= : The payment type pretty name}
                            {--slug= : The payment type dev name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new payment type to the database';

    /**
     * The payment type attributes to be saved.
     *
     * @var string $name
     * @var string $displayName
     * @var string $slug
     */
    protected $name, $displayName, $slug;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->setProperties();

        $studlySlug = Str::studly($this->slug);

        $migrationClass = "Add{$studlySlug}PaymentProvider";

        if ($this->classExists($migrationClass, $this->getMigrationPath())) {
            throw new InvalidArgumentException("{$migrationClass}::class already exists.");
        }

        $this->putFile(
            $this->generateMigrationFilePath($migrationClass),
            $this->makeFile(
                __DIR__ . '/../stubs/payment-type-migration.stub',
                [
                    'class' => $migrationClass,
                    'name' => addslashes($this->name),
                    'displayName' => addslashes($this->displayName),
                    'slug' => $this->slug,
                ]
            )
        );

        $this->info('The migration to add ' . $this->name . ' payment type has been generated.');

        if ($this->confirm('Would you like to run the migration?', true)) {
            $this->call('migrate', ['--force']);
        }
    }

    /**
     * Format the payment type's properties.
     *
     * @return void
     */
    protected function setProperties()
    {
        $this->name = trim($this->argument('type'));
        $this->displayName = $this->option('displayName') ?? $this->name;
        $this->slug = PaymentType::slugify($this->option('slug') ?? $this->name);
    }
}
