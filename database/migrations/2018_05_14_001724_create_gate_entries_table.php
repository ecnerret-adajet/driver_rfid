<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gate_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gate_number');
            $table->string('driver_name');
            $table->string('avatar');
            $table->string('plate_number');
            $table->string('hauler_name');
            $table->integer('driverqueue_id')->unsigned();
            $table->integer('LogID')->unsigned();
            $table->integer('CardholderID')->unsigned();
            $table->string('shipment_number')->unsigned()->nullable();
            $table->boolean('isShipmentStarted')->default(0);
            $table->char('driver_availability',1)->nullable();
            $table->char('truck_availability',1)->nullable();
            $table->timestamp('LocalTime');
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
        Schema::dropIfExists('gate_entries');
    }
}
