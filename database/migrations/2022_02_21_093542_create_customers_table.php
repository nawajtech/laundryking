<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('salutation');
            $table->string('name');
            $table->string('dob');
            $table->string('email')->unique()->nullable();
            $table->string('country_code');
            $table->string('phone');
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->integer('membership')->nullable();
            $table->timestamps('membership_start_date')->nullable();
            $table->timestamps('membership_end_date')->nullable();
            $table->text('auth_token')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('gst')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_address')->nullable();
            $table->string('locality')->nullable();
            $table->integer('pin')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('rating')->nullable();
            $table->longText('address')->nullable();
            $table->string('refer_code')->nullable();
            $table->integer('referrel_customer_id')->nullable();
            $table->string('device_token')->nullable();
            $table->integer('is_active')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
