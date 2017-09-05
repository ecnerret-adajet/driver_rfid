<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDriverFieldToMonitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitors', function (Blueprint $table) {
            $table->integer('driver_id')->unsigned()->nullable()->after('log_ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monitors', function (Blueprint $table) {
            $table->dropColumn('driver_id');
        });
    }
}
