<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogToServesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('serves', function (Blueprint $table) {
             $table->integer('driverqueue_id')->unsigned()->nullable();
             $table->integer('log_id')->unsigned()->nullable();
             $table->integer('cardholder_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('serves', function (Blueprint $table) {
            $table->dropColumn('driverqueue_id');
            $table->dropColumn('log_id');
            $table->dropColumn('cardholder_id');
        });
    }
}
