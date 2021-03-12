<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
           'name'=>'Por Confirmar',
            'code'=> 1
        ]);

        DB::table('statuses')->insert([
           'name'=>'Confirmado',
            'code'=> 2
        ]);
        DB::table('statuses')->insert([
           'name'=>'Anulado',
            'code'=> 3
        ]);
    }
}
