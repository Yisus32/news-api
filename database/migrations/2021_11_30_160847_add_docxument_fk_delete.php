<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocxumentFkDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {   
            $table->dropForeign('documents_guest_id_foreign');
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['guest_id']); 
        });
        Schema::table('documents', function (Blueprint $table) {   
            $table->integer('guest_id')->nullable()->unsigned()->index();
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
