<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->string('outlet_code')->nullable();
            $table->string('outlet_name')->nullable();
            $table->integer('workstation_id')->nullable();
            $table->text('outlet_address');
            $table->string('outlet_phone')->nullable();
            $table->string('outlet_latitude')->nullable();
            $table->string('outlet_longitude')->nullable();
            $table->text('google_map')->nullable();
            $table->string('google_reviews')->nullable();
            $table->string('qr_code')->nullable();
            $table->integer('is_active')->default('1');    
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
        Schema::dropIfExists('outlets');
    }
}
