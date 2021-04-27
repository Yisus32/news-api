<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sectors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch_id');
            $table->string('country');
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('sector')->nullable();
            $table->float('price', 11, 4)->default(0.00);
            $table->string('geofence')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sectors');
    }
}
