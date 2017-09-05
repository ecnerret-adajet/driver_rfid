<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contract_code');
            $table->string('contract_description');
            $table->string('contract_reference');
            $table->timestamps();
        });

        Schema::create('contract_truck', function(Blueprint $table) {
            $table->integer('contract_id')->unsigned()->index();
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
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
        Schema::dropIfExists('contract_truck');
        Schema::dropIfExists('contracts');
    }
}
