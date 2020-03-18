<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaggingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baggings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shipment_number');
            $table->date('ts_date_in');
            $table->time('ts_time_in');
            $table->integer('counter');
            $table->string('shipping_point');
            $table->date('pick_date');
            $table->date('loading_date');
            $table->string('customer_code');
            $table->double('net_weight');
            $table->string('sales_document');
            $table->string('item');
            $table->string('material');
            $table->text('description');
            $table->double('delivery_qty');
            $table->string('sales_unit');
            $table->double('qty');
            $table->string('base_unit');
            $table->string('driver');
            $table->string('plate_number');
            $table->string('plate_number2');
            $table->string('plate_number3');
            $table->string('service_agent');
            $table->string('truck_scale_number');
            $table->double('tare_weight');
            $table->string('truck_scale_number2');
            $table->double('final_weight');
            $table->double('delivery_qty2');
            $table->string('storage_location2');
            $table->double('delivery_qty3');
            $table->string('storage_location');
            $table->string('indicator');
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
        Schema::dropIfExists('baggings');
    }
}
