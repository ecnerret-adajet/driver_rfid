<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterHaulersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('haulers', function (Blueprint $table) {
            $table->dropColumn('vendor_name');
            $table->dropColumn('name1');
            $table->dropColumn('name2');
            $table->string('subconvendor_name')->nullable();
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
            $table->dropColumn('subconvendor_name');
        });
    }
}
