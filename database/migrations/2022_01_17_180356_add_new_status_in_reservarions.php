<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewStatusInReservarions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->enum('status',['no reservado','reservado','registrado','apartado'])->default("no reservado");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservarions', function (Blueprint $table) {
            //
        });
    }
}
