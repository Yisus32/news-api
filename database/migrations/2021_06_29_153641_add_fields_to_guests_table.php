<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->integer('host_id')->nullable();
            $table->string('host_name')->nullable();
            $table->integer('games_number')->default(0);
            $table->integer('games_number_month')->default(0);
            $table->integer('month_last_game')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn('host_id');
            $table->dropColumn('host_name');
            $table->dropColumn('games_number');
            $table->dropColumn('games_number_month');
            $table->dropColumn('month_last_game');
        });
    }
}
