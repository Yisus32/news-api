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
            $table->string('owner');
            $table->string('code')->index();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('coordinate')->nullable();
            $table->string('image')->nullable();
            $table->string('phones')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();

            
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
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
