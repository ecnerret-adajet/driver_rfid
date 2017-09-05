<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profit_center')->unsigned();
            $table->integer('cost_center')->unsigned();
            $table->string('origin');
            $table->timestamps();
        });

        Schema::create('base_truck', function (Blueprint $table) {
            $table->integer('base_id')->unsigned();
            $table->foreign('base_id')->references('id')->on('bases')->onDelete('cascade');
            $table->integer('truck_id')->unsigned();
            $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('cascade');
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('base_truck');
        Schema::dropIfExists('bases');
    }
}
