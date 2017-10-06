<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lineups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('LogID')->unsigned();
			$table->boolean('hustling')->nullable();
			$table->boolean('approval_notif')->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->boolean('status')->nullable();
            $table->string('remarks')->nullable();
            $table->boolean('availability')->default(1);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lineups');
    }
}
