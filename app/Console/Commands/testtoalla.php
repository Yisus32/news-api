<?php

namespace App\Console\Commands;

use App\Models\asig_toalla;
use App\Models\toalla;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class testtoalla extends Command
{
 /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statoalla';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'verifica el estado de las toallas';

   /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */

     public function handle()
     {
           //consulta para traer todas las tollas asignadas que no tenga fecha de devolucion
            $fec=asig_toalla::where('fec_fin',null)->get()->toarray();
            //obtener la fecha actual
            $now= new DateTime('now');
            $f=asig_toalla::where('fec_fin',null)->count();//contar cuantas toallas traen null
            $x=0;//contador
            foreach ($fec as $i)//recorres los filas de la primera consulta
            {
                while($x!=$f)//sacar los numeros de que de recorrer
                {
                    $con=$fec[$x]['fec_ini'];
                    $id=$fec[$x]['id_toalla'];
                    $fed=new DateTime($con);//convertit las fechas string a datetime
                    $dife=$now->diff($fed);//sacar la diferencia entre la actual y la que esta revisando
                    if($dife->d >=1)//validacion para saber si la toalla esta perdida
                    {
                        //actualizar el estado de la toalla a perdida
                        $cam=toalla::where('id',$id)->first();
                        $cam->status='perdida';
                        $cam->save();

                    }
                    $x++;
                }
                    
            }
            
       
     }

}
