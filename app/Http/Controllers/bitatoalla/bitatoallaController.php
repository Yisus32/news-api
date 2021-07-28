<?php

namespace App\Http\Controllers\bitatoalla;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\bitatoalla;
use App\Models\toalla;
use App\Services\bitatoalla\bitatoallaService;
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
        $bus=bitatoalla::where('id_toalla',$r)->get();
        return  ["list"=>$bus,'total'=>count($bus)];
    }

    public function reception(Request $request)
    {
      $id=$request->toalla_id;
      $cobs=bitatoalla::where('id_toalla',$id)->orderby('created_at','DESC')->take(1)->get();
      $robs=bitatoalla::where('id',$cobs[0]->id)->first();
      $robs->obs=$request->obs;
      $robs->save();

      $toal=toalla::where('id',$id)->first();
      $toal->status='En stock';
      $toal->save();

      return response()->json([
        'status' => 200,
        'message'=>'Toalla recibida'
    ], 200)->setStatusCode(200, "Registro Actualizado");

      
    }
}