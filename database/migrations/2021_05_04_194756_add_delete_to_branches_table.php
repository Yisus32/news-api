<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDeleteToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->boolean('deleted')->default(false);
            DB::statement('ALTER TABLE sectors ADD deleted Boolean DEFAULT FALSE');
            DB::statement('ALTER TABLE activities ADD deleted Boolean DEFAULT FALSE');
            DB::statement('ALTER TABLE schedules ADD deleted Boolean DEFAULT FALSE');
            DB::statement('ALTER TABLE aplications ADD deleted Boolean DEFAULT FALSE');
            DB::statement('ALTER TABLE sub_activity ADD deleted Boolean DEFAULT FALSE');
            DB::statement('ALTER TABLE bank_account ADD deleted Boolean DEFAULT FALSE');
            DB::statement('ALTER TABLE client_rate ADD deleted Boolean DEFAULT FALSE');
            DB::statement('ALTER TABLE clients ADD deleted Boolean DEFAULT FALSE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            //
        });
    }
}
