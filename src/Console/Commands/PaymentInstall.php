<?php

namespace rkujawa\LaravelPaymentGateway\Console\Commands;

use Illuminate\Console\Command;

class PaymentInstall extends Command
{
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
    protected $description = 'Install and configure payments within your application.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->callSilent('vendor:publish', ['--provider' => 'rkujawa\LaravelPaymentGateway\PaymentServiceProvider']);

        $this->call('payment:add-provider', ['--test' => true]);

        do {
            $this->call('payment:add-type', ['--skip-migration' => true]);
        } while ($this->confirm('Would you like to add another payment type?', true));

        $this->call('migrate', ['--force' => true]);

        do {
            $this->call('payment:add-provider', ['--skip-migration' => true]);
        } while ($this->confirm('Would you like to add another payment provider?', true));

        $this->call('migrate', ['--force' => true]);

        do {
            $this->call('payment:add-merchant', ['--skip-migration' => true]);
        } while ($this->confirm('Would you like to add another payment merchant?', true));

        $this->call('migrate', ['--force' => true]);
    }
}
