<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->integer('reservation_id');
            $table->integer('guest')->nullable();
            $table->integer('partner')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('status')->default('En espera');
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
