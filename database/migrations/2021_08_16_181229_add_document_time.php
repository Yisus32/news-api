<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'expiration_date',
                'emission'
            ]);  
        });
       Schema::table('documents', function (Blueprint $table) {
            $table->string('expiration')->nullable()->default('');
            $table->string('emission')->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
