<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSapToVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('versions', function (Blueprint $table) {
            $table->dropColumn('key');
            $table->dropColumn('old_value');
            $table->dropColumn('new_value');

            $table->string('plate_number')->nullable();
            $table->string('reg_number')->nullable();

            $table->string('vendor_number')->nullable();
            $table->string('subvendor_number')->nullable();
            $table->string('contract_code')->nullable();
            $table->string('contract_description')->nullable();
            $table->text('vendor_description')->nullable();
            $table->text('subvendor_description')->nullable();
        });

        Schema::create('revision_truck', function(Blueprint $table) {
            $table->integer('revision_id')->unsigned()->index();
            $table->foreign('revision_id')->references('id')->on('revisions')->onDelete('cascade');
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
        Schema::dropIfExists('revision_truck');
        Schema::table('versions', function (Blueprint $table) {
            $table->dropColumn('plate_number');
            $table->dropColumn('reg_number');
            $table->dropColumn('vendor_number');
            $table->dropColumn('subvendor_number');
            $table->dropColumn('contract_code');
            $table->dropColumn('contract_description');
            $table->dropColumn('vendor_description');
            $table->dropColumn('subvendor_description');
        });
    }
}
