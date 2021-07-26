<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Bitatoalla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bitatoalla', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fec_asig');
            $table->integer('id_toalla');
            $table->string('sta');
            $table->datetime('fec_ult');
            $table->string('user_id');
            $table->string('user_name');
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
        Schema::dropIfExists('bitatoalla');
    }
}
