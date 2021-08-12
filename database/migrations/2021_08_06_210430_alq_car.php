<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlqCar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alq_car', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('user_num')->nullable();
            $table->string('user_name')->nullable();
            $table->integer('car_id');
            $table->string('hol_id');
            $table->integer('gro_id');
            $table->dateTime('fecha')->nullable();
            $table->integer('id_hole');
            $table->string('obs')->nullable();
            $table->string('tipo_p')->nullable();
            $table->integer('can_p')->nullable();
          
            $table->foreign('car_id')->references('id')->on('cars_golf')->onDelete('restrict');
            $table->foreign('gro_id')->references('id')->on('group')->onDelete('restrict');
            $table->foreign('id_hole')->references('id')->on('holes')->onDelete('restrict');
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
        Schema::dropIfExists('alq_car');
    }
}
