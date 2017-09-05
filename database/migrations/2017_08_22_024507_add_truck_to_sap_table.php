<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTruckToSapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('trucks', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('capacity_id')->nullable()->unsigned();
            $table->integer('vendor_number')->nullable()->unsigned();
            $table->integer('subvendor_number')->nullable()->unsigned();
            
            $table->string('reg_number')->nullable();
            $table->string('contract_code')->nullable();
            $table->string('contract_description')->nullable();
            $table->text('vendor_description')->nullable();
            $table->text('subvendor_description')->nullable();

            $table->timestamp('validity_start_date')->nullable();
            $table->timestamp('validity_end_date')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('capacity_id')->references('id')->on('capacities')->onDelete('cascade');
            $table->foreign('vendor_number')->references('id')->on('haulers');
            $table->foreign('subvendor_number')->references('id')->on('haulers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropForeign(['user_id','capacity_id','vendor_number','subvendor_number']);
            $table->dropColumn('reg_number');
            $table->dropColumn('contract_code');
            $table->dropColumn('contract_description');
            $table->dropColumn('vendor_description');
            $table->dropColumn('subvendor_description');
            $table->dropColumn('validity_start_date');
            $table->dropColumn('validity_end_date');
        });
    }
}
