<?php

namespace rkujawa\LaravelPaymentGateway\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;

class AddPaymentProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:add-provider
                            {provider : The payment provider name}
                            {--slug= : The payment provider dev name}
                            {--full : Generate single gateway class}
                            {--manager : Generate class for payment management}
                            {--processor : Generate class for payment processing}';

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

        $studlySlug = Str::studly($this->slug);

        if ($this->option('full') || !($this->option('manager') || $this->option('processor'))) {
            $this->putFile(
                app_path("Services/Payment/{$studlySlug}PaymentGateway.php"),
                $this->makeFile(__DIR__ . '/../stubs/payment-gateway-service.stub', ['name' => $studlySlug])
            );
        }

        if ($this->option('manager')) {
            $this->putFile(
                app_path("Services/Payment/{$studlySlug}PaymentManager.php"),
                $this->makeFile(__DIR__ . '/../stubs/payment-manager-service.stub', ['name' => $studlySlug])
            );
        }

        if ($this->option('processor')) {
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
        $this->slug = PaymentProvider::getSlug($this->option('slug') ?? $this->name);
    }

    /**
     * Ensure that the given class doesn't exist in the provided directory.
     *
     * @param  string  $class
     * @param  string  $directory
     * @return boolean
     */
    protected function classExists($class, $directory)
    {
        $files = $this->files->glob($directory.'/*.php');

        foreach ($files as $file) {
            $this->files->requireOnce($file);
        }

        return class_exists($class);
    }

    /**
     * Get the contents of the file.
     *
     * @param string $stub
     * @param array $data
     * @return string
     */
    protected function makeFile($stub, $data)
    {
        $file = file_get_contents($stub);

        foreach ($data as $search => $replace)
        {
            $file = Str::replace('{{ ' . $search . ' }}', $replace, $file);
        }

        return $file;
    }

    /**
     * Put the given file in the specified path.
     *
     * @param string $path
     * @param string $file
     * @return void
     */
    protected function putFile($path, $file)
    {
        $directory = collect(explode('/', $path, -1))->join('/');
        $this->files->ensureDirectoryExists($directory);

        $this->files->put($path, $file);
    }

    /**
     * Generate the full migration file path.
     *
     * @param string $migrationClass
     * @param string $migrationPath
     * @return string
     */
    protected function generateMigrationFilePath($class, $path)
    {
        $fileName = now()->format('Y_m_d_His') . '_' . Str::snake($class) . '.php';

        return "{$path}/{$fileName}";
    }
}
