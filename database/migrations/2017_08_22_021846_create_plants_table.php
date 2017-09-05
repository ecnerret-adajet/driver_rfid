<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plant_code')->unsigned();
            $table->string('plant_name');
            $table->integer('profit_center')->unsigned();
            $table->integer('cost_center')->unsigned();
            $table->string('origin');
            $table->integer('company_code')->unsigned();
            $table->string('business_area');
            $table->string('tax_code');
            $table->string('company_server');
            $table->timestamps();
        });

        Schema::create('plant_truck', function(Blueprint $table) {
            $table->integer('plant_id')->unsigned()->index();
            $table->foreign('plant_id')->references('id')->on('plants')->onDelete('cascade');
            $table->integer('truck_id')->unsigned()->index();
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
        Schema::dropIfExists('plants');
    }
}
