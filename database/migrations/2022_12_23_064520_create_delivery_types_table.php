<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_types', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_name')->nullable();
            $table->string('type')->comment('flat,percentage')->nullable();
            $table->integer('amount')->default(0);
            $table->integer('cut_off_amount')->default(0);
            $table->integer('cut_off_charge')->default(0);
            $table->integer('delivery_in_days')->default(0);
            $table->integer('delivery_in_days')->default(0);
            $table->string('pickup_time_from')->nullable();
            $table->string('pickup_time_to')->nullable();
            $table->string('delivery_time_from')->nullable();
            $table->string('delivery_time_to')->nullable();
            $table->integer('is_active')->comment('1=active,0=inactive')->default(1);
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
        Schema::dropIfExists('delivery_types');
    }
}
