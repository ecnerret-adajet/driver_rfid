<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubconvendorNumberFieldToHaulers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('haulers', function (Blueprint $table) {
            $table->string('subconvendor_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('haulers', function (Blueprint $table) {
            $table->dropColumn('subconvendor_number');
        });
    }
}
