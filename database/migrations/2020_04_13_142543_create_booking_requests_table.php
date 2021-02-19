<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('order_reference');
            $table->string('order_reference_no');
            $table->timestamp('booking_date');
            $table->string('consignee');
            $table->string('origin');
            $table->string('destination');
            $table->string('van_no');
            $table->string('ship_type'); // transfer or delivery
            $table->string('mode_of_shipment');
            $table->string('plate_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('shippers_name')->nullable();
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
        Schema::dropIfExists('booking_requests');
    }
}
