<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GameLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_log', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('auser_id')->nullable();
            $table->integer('car_id');
            $table->integer('hol_id');
            $table->integer('gro_id');
            $table->date('fecha');
            $table->timestamps();
            $table->foreign('car_id')->references('id')->on('cars_golf')->onDelete('restrict');
            $table->foreign('gro_id')->references('id')->on('group')->onDelete('restrict');
            $table->foreign('hol_id')->references('id')->on('number_holes')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_log');
    }
}