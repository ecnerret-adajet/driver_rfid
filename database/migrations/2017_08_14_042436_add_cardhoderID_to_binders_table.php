<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCardhoderIDToBindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('binders', function (Blueprint $table) {
            $table->integer('cardholder_id')->unsigned()->nullable()->after('rfid_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('binders', function (Blueprint $table) {
            $table->dropColumn('cardholder_id');
        });
    }
}
