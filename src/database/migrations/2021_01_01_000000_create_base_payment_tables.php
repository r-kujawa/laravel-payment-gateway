<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasePaymentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('payment.defaults.driver') === 'database') {
            Schema::create('payment_providers', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('name');
                $table->string('request_class');
                $table->string('response_class');
                $table->timestamps();
            });

            Schema::create('payment_merchants', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('name');
                $table->timestamps();
            });

            Schema::create('payment_merchant_provider', function (Blueprint $table) {
                $table->increments('id');
                $table->string('merchant_id');
                $table->string('provider_id');
                $table->boolean('is_default')->default(false);
                $table->json('config')->nullable();
                $table->timestamps();

                $table->foreign('merchant_id')->references('id')->on('payment_merchants')->onDelete('cascade');
                $table->foreign('provider_id')->references('id')->on('payment_providers')->onDelete('cascade');
            });
        }

        Schema::create('payment_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->string('display_name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('billable_id')->nullable();
            $table->string('billable_type')->nullable();
            $table->unsignedSmallInteger('provider_id');
            $table->unsignedMediumInteger('merchant_id');
            $table->string('token');
            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('payment_providers')->onDelete('cascade');
            $table->foreign('merchant_id')->references('id')->on('payment_merchants')->onDelete('cascade');
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wallet_id');
            $table->string('token');
            $table->unsignedSmallInteger('type_id');
            $table->json('details')->nullable();
            $table->timestamps();

            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('payment_types');
        });

        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('provider_id');
            $table->unsignedMediumInteger('merchant_id');
            $table->string('reference');
            $table->unsignedInteger('amount');
            $table->char('currency', 3)->default('USD');
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->unsignedSmallInteger('status_code');
            $table->json('details')->nullable();
            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('payment_providers');
            $table->foreign('merchant_id')->references('id')->on('payment_merchants');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
        });

        Schema::create('payment_transaction_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transaction_id');
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('amount');
            $table->smallInteger('status_code');
            $table->json('details')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('payment_transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_transaction_events');
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('payment_types');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('payment_merchant_provider');
        Schema::dropIfExists('payment_merchants');
        Schema::dropIfExists('payment_providers');
    }
}
