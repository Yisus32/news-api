<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AsigToalla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asig_toalla', function (Blueprint $table) {
            $table->id();
            $table->integer('id_toalla');
            
            $table->dateTime('fec_ini');
            $table->datetime('fec_fin')->nullable();
            $table->timestamps();
            $table->foreign('id_toalla')->references('id')->on('toalla')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asig_toalla');
    }
}
