<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_rate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('coin_id');
            $table->integer('client_id');
            $table->float('rate');
            $table->string('description')->nullable();
            $table->boolean('deleted')->default(false);
            $table->timestamps();

            $table->foreign('coin_id')->references('id')->on('coins')->onDelete('restrict');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_rate');
    }
}
