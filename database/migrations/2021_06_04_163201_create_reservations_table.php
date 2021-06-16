<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->integer('teetime_id');
            $table->integer('hole_id');
            $table->date('date');
            $table->time('start_hour');
            $table->integer('owner')->nullable();
            $table->enum('status', ["no reservado", "reservado", "registrado"])->default("no reservado");
            $table->timestamps();

            $table->foreign('teetime_id')->references('id')->on('teetimes')->onDelete('restrict');
            $table->foreign('hole_id')->references('id')->on('holes')->onDelete('restrict');
        });
        DB::connection()->getPdo()->exec("alter table reservations add column guests integer[] DEFAULT '{}'");
        DB::connection()->getPdo()->exec("alter table reservations add column partners integer[] DEFAULT '{}'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
