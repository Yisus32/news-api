<?php

namespace App\Http\Controllers\bitatoalla;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\asig_toalla;
use App\Models\bitatoalla;
use App\Models\toalla;
use App\Services\bitatoalla\bitatoallaService;
use DateTime;
/** @property bitatoallaService $service */
class bitatoallaController extends CrudController
{
    public function __construct(bitatoallaService $service)
    {
        parent::__construct($service);
    }

    public function bita(Request $request)
    {
        $r=$request->get('id');
        $bus=bitatoalla::where('id_toalla',$r)->join('toalla','toalla.id','=','bitatoalla.id_toalla')->select('toalla.num as num_toalla','bitatoalla.id','bitatoalla.id_toalla','bitatoalla.fec_asig','bitatoalla.sta','bitatoalla.fec_ult','bitatoalla.user_id','bitatoalla.user_name')->get();
        return  ["list"=>$bus,'total'=>count($bus)];
    }

    public function reception(Request $request)
    {
      $id=$request->toalla_id;$request->toalla_id;
      $fec=new DateTime('now');
      $cobs=bitatoalla::where('id_toalla',$id)->orderby('created_at','DESC')->take(1)->get();
      $robs=bitatoalla::where('id',$cobs[0]->id)->first();
      $robs->fec_ult=$fec;
      $robs->obs="Asignacion terminada";
      $robs->save();

      $toal=toalla::where('id',$id)->first();
      $toal->status='En stock';
      $toal->save();

     
      $oasi=asig_toalla::where('id_toalla',$id)->orderby('created_at','DESC')->take(1)->get();
      $ida=$oasi[0]->id;
    
      $cfe=asig_toalla::where('id',$ida)->first();
      $cfe->fec_fin=$fec;
      $cfe->save();

      $bit= new bitatoalla;
      $bit->fec_asig=$fec;
      $bit->id_toalla=$id;
      $bit->sta='En stock';
      $bit->user_id=$request->user_id;
      $bit->user_name=$request->user_name;
      $bit->obs=$request->obs;
      $bit->fec_ult=$fec;
      $bit->save();

      return response()->json([
        'status' => 200,
        'message'=>'Toalla recibida'
    ], 200)->setStatusCode(200, "Registro Actualizado");

      
    }
}