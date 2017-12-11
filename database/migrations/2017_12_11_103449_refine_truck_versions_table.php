<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefineTruckVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('versions', function (Blueprint $table) {
            $table->dropColumn('reg_number');
            $table->dropColumn('vendor_number');
            $table->dropColumn('subvendor_number');
            $table->dropColumn('contract_code');
            $table->dropColumn('contract_description');
            $table->dropColumn('vendor_description');
            $table->dropColumn('subvendor_description');

            // add new fields
            $table->string('hauler')->nullable();
            $table->integer('card_id')->unsigned()->nullable();
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
        Schema::table('versions', function (Blueprint $table) {
            $table->dropColumn('hauler');
            $table->dropColumn('card_id');
            $table->dropColumn('cardholder_id');
        });
    }
}
