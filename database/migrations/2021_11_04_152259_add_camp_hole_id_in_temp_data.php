<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampHoleIdInTempData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('temp_data', function (Blueprint $table) {
            $table->dropColumn('teetime_id');
        });

        Schema::table('temp_data', function (Blueprint $table) {
            $table->integer('teetime_id')->nullable();
            $table->integer('hole_id')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_data', function (Blueprint $table) {
            //
        });
    }
}
