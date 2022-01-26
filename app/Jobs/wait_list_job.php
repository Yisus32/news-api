<?php

namespace App\Jobs;

use App\Http\Mesh\UsuService;
use App\Models\waiting_list;

class wait_list_job extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $date;
    protected $hour;
    public function __construct($dat,$hou)
    {
        $this->date=$dat;
        $this->hour=$hou;


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client=new UsuService;
        $espera=waiting_list::where('date',$this->date)->where('start_hour',$this->hour)->get();
        $tite="notificacion de reserva";
        $cuerpo="se cancelo una reservacion en la fecha:'$this->date' y hora:'$this->hour' la puedes tomar";

        foreach ($espera as $key) 
        {
          $id=$key->user_id;
          $se= $client->_sendNotification($id,$tite,$cuerpo);
        }

        return ["se envio una notificacion al usuario id:"=>$key];
    }
}
