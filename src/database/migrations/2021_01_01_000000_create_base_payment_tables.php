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
            $table->timestamps();
        });

        Schema::create('payment_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('payment_provider_id');
            $table->string('token', 255);
            $table->timestamps();

            $table->foreign('payment_provider_id')->references('id')->on('payment_providers')->onDelete('cascade');
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_customer_id');
            $table->string('token', 255);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->char('last_digits', 4);
            $table->char('exp_month', 2);
            $table->char('exp_year', 4);
            $table->string('type', 16);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('payment_customer_id')->references('id')->on('payment_customers')->onDelete('cascade');
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
        Schema::dropIfExists('payment_customers');
        Schema::dropIfExists('payment_providers');
    }
}
