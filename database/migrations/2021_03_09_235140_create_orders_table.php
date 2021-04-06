<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('status_id')->index();
            $table->integer('msa_account')->index();
            $table->integer('store_id');
            $table->integer('sale_id');
            $table->integer('quantity')->default(0);
            $table->float('total_amount',11,4)->default(0.0000);
            $table->string('location')->nullable();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
