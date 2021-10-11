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
            $table->morphs('billable');
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
    }
}
