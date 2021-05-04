<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->integer('client_id')->index();
            $table->integer('msa_account');
            $table->string('code')->index();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('coordinate')->nullable();
            $table->string('image')->nullable();
            $table->string('phones')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('sector_id')->index()->nullable();
            $table->boolean('deleted')->default(false);
            $table->timestamps();

            
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict');
            
            $table->foreign('sector_id')->references('id')->on('sectors')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
