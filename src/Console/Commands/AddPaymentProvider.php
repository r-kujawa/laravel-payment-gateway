<?php

namespace rkujawa\LaravelPaymentGateway\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use InvalidArgumentException;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;

class AddPaymentProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:add-provider
                            {type : The payment provider name}
                            {--slug= : The payment provider dev name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new payment provider and scaffold it\'s implementation.';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The name of the migration class to be generated.
     *
     * @var string
     */
    protected $className;

    /**
     * The payment provider attributes to be saved.
     *
     * @var string $name
     * @var string $slug
     */
    protected $name, $slug;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->setProperties();

        $migrationPath = $this->getMigrationPath();

        $this->ensureMigrationDoesntAlreadyExist($migrationPath);

        $this->files->put(
            $this->getMigrationFilePath($migrationPath),
            $this->getMigrationFileContents($this->getStubFile(), $this->getStubVariables())
        );

        $this->info('The migration to add ' . $this->name . ' payment provider has been generated.');

        if ($this->confirm('Would you like to run your migration?', true)) {
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
        $this->slug = PaymentProvider::getSlug($this->option('slug') ?? $this->name);

        $this->className = $this->getClassName();
    }

    /**
     * Get the class name of a migration name.
     *
     * @return string
     */
    protected function getClassName()
    {
        return 'Add' . Str::studly($this->slug) . 'PaymentProvider';
    }

    /**
     * Get the user's migrations directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        return $this->laravel->databasePath() . DIRECTORY_SEPARATOR . 'migrations';
    }

    /**
     * Ensure that a migration with the given name doesn't already exist.
     *
     * @param  string  $name
     * @param  string  $migrationPath
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function ensureMigrationDoesntAlreadyExist($migrationPath)
    {
        if (! empty($migrationPath)) {
            $migrationFiles = $this->files->glob($migrationPath.'/*.php');

            foreach ($migrationFiles as $migrationFile) {
                $this->files->requireOnce($migrationFile);
            }
        }

        if (class_exists($this->className)) {
            throw new InvalidArgumentException("{$this->className}::class already exists.");
        }
    }

    /**
     * Get the full migration path and file name.
     *
     * @param string $migrationPath
     * @return void
     */
    protected function getMigrationFilePath($migrationPath)
    {
        $fileName = now()->format('Y_m_d_His') . '_' . Str::snake($this->className) . '.php';

        return $migrationPath . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * Get the contents of the migration file.
     *
     * @param string $stubFile
     * @param array $stubVariables
     * @return void
     */
    protected function getMigrationFileContents($stubFile, $stubVariables)
    {
        $file = file_get_contents($stubFile);

        foreach ($stubVariables as $search => $replace)
        {
            $file = Str::replace('{{ ' . $search . ' }}', $replace, $file);
        }

        return $file;
    }

    /**
     * Get the stub file's full path.
     *
     * @return string
     */
    protected function getStubFile()
    {
        return __DIR__ . '/../stubs/payment-provider-migration.stub';
    }

    /**
     * Get the variables to fill the stub file.
     *
     * @return array
     */
    protected function getStubVariables()
    {
        return [
            'class' => $this->className,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
