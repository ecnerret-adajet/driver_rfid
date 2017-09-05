<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHaulerToSapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('haulers', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('server_id')->unsigned()->nullable();
            $table->string('vendor_number')->nullable();
            $table->text('vendor_description')->nullable();
            $table->string('subvendor_number')->nullable();
            $table->text('subcon_description')->nullable();
            $table->string('vendor_customer_code')->nullable();
            $table->string('vat_reg_tin')->nullable();

            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
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
            $table->dropForeign(['user_id','server_id']);
            $table->dropColumn('vendor_number');
            $table->dropColumn('name1');
            $table->dropColumn('name2');
            $table->dropColumn('vendor_customer_code');
            $table->dropColumn('vat_reg_tine');
        });
    }
}
