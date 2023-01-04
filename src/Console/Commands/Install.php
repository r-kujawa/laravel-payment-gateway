<?php

namespace rkujawa\LaravelPaymentGateway\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use rkujawa\LaravelPaymentGateway\Traits\GeneratesFiles;
use rkujawa\LaravelPaymentGateway\Traits\Questionable;

class Install extends Command
{
    use Questionable, GeneratesFiles;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and configure payments within the application.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--provider' => 'rkujawa\LaravelPaymentGateway\PaymentServiceProvider']);

        $this->call('payment:add-provider', ['--fake' => true]);

        $providers = collect([]);

        do {
            $providers->push([
                'name' => $name = $this->askName('provider'),
                'id' => $id = $this->askId('provider', $name),
                'provider' => Str::studly($id),
            ]);

            $this->call('payment:add-provider', ['provider' => $name, '--id' => $id]);
        } while ($this->confirm('Would you like to add another payment provider?', false));

        $defaultProvider = $this->choice('Which provider will be used as default?', $providers->pluck('id')->all());

        $this->putFile(
            config_path('payment.php'),
            $this->makeFile(
                __DIR__ . '/../../stubs/config/payment.stub',
                [
                    'provider' => $defaultProvider,
                    'providers' => $providers->reduce(function ($config, $provider) {
                        return $config . $this->makeFile(__DIR__ . '/../../stubs/config/provider.stub', $provider);
                    }, ""),
                ]
            )
        );
    }
}
