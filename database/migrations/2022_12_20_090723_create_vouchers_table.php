<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->integer('no_of_users')->nullable() ->comment('how many users can use');
            $table->integer('each_user_useable')->nullable() ->comment('how many times use for each user');
            $table->integer('total_useable')->nullable() ->comment('total useable times');
            $table->integer('total_used')->nullable() ->comment('no of used by the customers');
            $table->integer('discount_type')->nullable()->comment('1=Flat Discount, 0=Percentage Discount');
            $table->double('discount_amount',10,2)->nullable();
            $table->double('cutoff_amount',10,2)->default(0.00)->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_to')->nullable();
            $table->text('details')->nullable();
            $table->integer('is_active')->nullable()->comment('1=active, 0=inactive');
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
        Schema::dropIfExists('vouchers');
    }
}
