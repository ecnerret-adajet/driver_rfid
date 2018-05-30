<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueueEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return voidstring
     */
    public function up()
    {
        Schema::create('queue_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('queue_number');
            $table->integer('truck_id')->unsigned()->nullable();
            $table->string('driver_name')->nullable();
            $table->string('avatar')->default('drivers/avatar.png');
            $table->string('plate_number')->nullable();
            $table->string('hauler_name')->nullable();;
            $table->integer('driverqueue_id')->unsigned();

            $table->string('shipment_number')->nullable();
            $table->integer('LogID')->unsigned();
            $table->integer('CardholderID')->unsigned();
            $table->timestamp('LocalTime');
            
            $table->string('isDRCompleted')->nullable();
            $table->char('driver_availability',1)->nullable();
            $table->char('truck_availability',1)->nullable();
            $table->char('isTappedGateFirst',1)->nullable();
            $table->char('isSecondDelivery',1)->nullable();
            $table->char('isShipmentStarted',1)->nullable();
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
        Schema::dropIfExists('queue_entries');
    }
}
