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
        Schema::create('payment_providers', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

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
            $table->string('token', 255);
            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('payment_providers')->onDelete('cascade');
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wallet_id');
            $table->string('token', 255);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->char('last_digits', 4);
            $table->char('exp_month', 2);
            $table->char('exp_year', 4);
            $table->unsignedSmallInteger('type_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('payment_types')->onDelete('cascade');
        });

        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('provider_transaction_id');
            $table->string('order_id');
            $table->unsignedBigInteger('amount_cents');
            $table->unsignedBigInteger('payment_method_id');
            //$table->unsignedSmallInteger('payment_provider_id'); this can be obtained from the payment method
            $table->integer('status_code');
            $table->json('payload');
            $table->timestamp('created_at')->useCurrent();

            //$table->foreign('payment_provider_id')->references('id')->on('payment_providers');
            $table->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->onDelete('set null');
        });

        Schema::create('payment_refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('provider_refund_id');
            $table->unsignedBigInteger('payment_transaction_id');
            $table->unsignedBigInteger('amount_cents');
            $table->enum('type', ['void', 'refund']);
            $table->integer('status_code');
            $table->json('payload');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('payment_transaction_id')
                ->references('id')
                ->on('payment_transactions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('payment_types');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('payment_providers');
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('payment_refunds');
    }
}
