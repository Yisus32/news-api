<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTeetimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teetimes', function (Blueprint $table) {
            $table->id();
            $table->integer('type_id');
            $table->date('start_date')->index();
            $table->date('end_date');
            $table->integer('min_capacity')->default(2);
            $table->integer('max_capacity')->default(4);
            $table->integer('time_interval')->nullable();    
            $table->integer('available')->nullable();
            $table->integer('cancel_time');
            $table->time('start_hour');
            $table->time('end_hour');
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('teetime_types')->onDelete('restrict');
        });
        DB::connection()->getPdo()->exec("alter table teetimes add column target integer[] DEFAULT '{}'");
        DB::connection()->getPdo()->exec("alter table teetimes add column days integer[] DEFAULT '{}'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teetimes');
    }
}
