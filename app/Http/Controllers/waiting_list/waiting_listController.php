<?php

namespace App\Http\Controllers\waiting_list;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Http\Mesh\UsuService;
use App\Models\waiting_list;
use App\Services\waiting_list\waiting_listService;
use Illuminate\Support\Facades\DB;

/** @property waiting_listService $service */
class waiting_listController extends CrudController
{
    public function __construct(waiting_listService $service)
    {
        parent::__construct($service);
    }

    public function filter_by_date(Request $request)
    {
        $r=$request->get('fecha');
        $f=$request->get('fin');
        if($r==0 or $f==0)
        {
            return ["list"=>[],'total'=>0];
        }
        elseif( $fill=waiting_list::whereBetween(DB::Raw('cast(created_at as date)'), array($r, $f))->count()==0)
        {
            return ["list"=>[],'total'=>0];
        }
        else
        {
            $fill=waiting_list::whereBetween(DB::Raw('cast(waiting_list.created_at as date)'), array($r, $f))->get();
            return  ["list"=>$fill,'total'=>count($fill)];
        }
       
    }

    public function notireserva($date,$hour)
    {
        $client=new UsuService();
        $espera=waiting_list::where('date',$date)->where('start_hour',$hour)->get();
        $tite="notificacion de reserva";
        $cuerpo="se cancelo una reservacion en la fecha:'.$date.' y hora:'.$hour.' la puedes tomar";
        $se= $client->_sendNotification(28,$tite,$cuerpo);
        dd($se);
        foreach ($espera as $key) 
        {
            $id=$key->user_id;
           // dd($id);
          $se= $client->_sendNotification(28,$tite,$cuerpo);
        }

       

    }
}