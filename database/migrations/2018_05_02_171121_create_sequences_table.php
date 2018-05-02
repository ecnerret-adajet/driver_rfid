<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSequencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sequences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('log_id')->unsigned(); 
            $table->integer('cardholder_id')->unsigned();
            $table->integer('driver_id')->unsigned(); 
            $table->integer('driverqueue_id')->unsigned(); // queue location
            $table->string('gate_queue');  // queue from gate monitor
            $table->string('temp_queue'); // incomplete details
            $table->string('shipment_queue'); // queue based from shipment event
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
        Schema::dropIfExists('sequences');
    }
}
