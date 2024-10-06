<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('order_number')->nullable();
            $table->integer('pickup_option')->nullable();
            $table->integer('delivery_option')->nullable();
            $table->integer('outlet_id')->nullable();
            $table->integer('delivery_outlet_id')->nullable();
            $table->integer('workstation_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->dateTime('order_date')->nullable();
            $table->integer('delivery_type_id')->nullable();
            $table->string('delivery_type')->nullable();
            $table->dateTime('ready_date')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->dateTime('delivered_date')->nullable();
            $table->dateTime('pickup_date')->nullable();
            $table->integer('pickup_address_id')->nullable();
            $table->string('pickup_flat_number')->nullable();
            $table->string('pickup_area')->nullable();
            $table->string('pickup_address')->nullable();
            $table->string('pickup_route_suggestion')->nullable();
            $table->string('pickup_address_type')->nullable();
            $table->string('pickup_other')->nullable();
            $table->string('pickup_latitude')->nullable();
            $table->string('pickup_longitude')->nullable();
            $table->integer('pickup_pincode')->nullable();
            $table->integer('delivery_address_id')->nullable();
            $table->string('delivery_flat_number')->nullable();
            $table->string('delivery_area')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('delivery_route_suggestion')->nullable();
            $table->string('delivery_address_type')->nullable();
            $table->string('delivery_other')->nullable();
            $table->string('delivery_latitude')->nullable();
            $table->string('delivery_longitude')->nullable();
            $table->string('delivery_pincode')->nullable();
            $table->integer('pickup_driver_id')->nullable();
            $table->integer('delivery_driver_id')->nullable();
            $table->integer('pickup_time')->nullable();
            $table->integer('delivery_time')->nullable();
            $table->double('sub_total',15,2)->default(0);
            $table->double('addon_total',15,2)->default(0);
            $table->double('delivery_charge',15,2)->default(0);
            $table->double('express_charge',15,2)->default(0);
            $table->double('discount',15,2)->default(0);
            $table->double('tax_percentage',15,2)->default(0);
            $table->double('tax_amount',15,2)->default(0);
            $table->double('total',15,2)->default(0);
            $table->longText('note')->nullable();
            $table->longText('instruction')->nullable();
            $table->integer('voucher_id');
            $table->string('voucher_code');
            $table->double('voucher_discount');
            $table->double('cashback_amount');
            $table->integer('cashback_flag');
            $table->integer('status')->default(0);
            $table->integer('flag')->default(0);
            $table->integer('rating')->nullable();
            $table->string('feedback')->nullable();
            $table->integer('order_type')->nullable();
            $table->integer('cancel_request')->default(0);
            $table->string('cancel_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('financial_year_id')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
