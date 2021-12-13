<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentEmissioonExpiration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::table('documents', function (Blueprint $table) {   
            $table->string('emission_aux')->nullable()->default('');
            $table->string('expiration_aux')->nullable()->default('');
        });

        Schema::table('documents', function (Blueprint $table) {    
            \DB::raw('UPDATE `documents` SET emission_aux=emission');
            \DB::raw('UPDATE `documents` SET expiration_aux=expiration');
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['emission']);
            $table->dropColumn(['expiration']); 
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->string('emission')->nullable()->default('');
            $table->string('expiration')->nullable()->default('');
        });
        Schema::table('documents', function (Blueprint $table) {    
            \DB::raw('UPDATE `documents` SET emission = emission_aux');
            \DB::raw('UPDATE `documents` SET expiration = expiration_aux');
        }); 
        Schema::table('documents', function (Blueprint $table) {
             $table->dropColumn(['emission_aux']);
            $table->dropColumn(['expiration_aux']); 
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
