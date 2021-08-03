<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\asig_toalla;


use App\Core\CrudService;
use App\Models\bitatoalla;
use App\Models\toalla;
use App\Repositories\asig_toalla\asig_toallaRepository;
use Illuminate\Http\Request;

/** @property asig_toallaRepository $repository */
class asig_toallaService extends CrudService
{

    protected $name = "asig_toalla";
    protected $namePlural = "asig_toallas";

    public function __construct(asig_toallaRepository $repository)
    {
        parent::__construct($repository);
    }
     

    
    public function _store(Request $request)
    {
         $tosta=toalla::where('id',$request->id_toalla)->get();
       
        if($tosta[0]->status=="En uso")
        {
            return response()->json(["error"=>true,"message"=> "esta toalla ya esta en uso"],422);
        }

        elseif($tosta[0]->status=="Perdida")
        {
            return response()->json(["error"=>true,"message"=> "esta toalla se encuentra perdida y no puede ser asignada"],422);
        }

        else
        {
        $date=$request->all();
        $toalla=toalla::where('id',$date['id_toalla'])->first();
        $toalla->fec=$date['fec_ini'];
        $toalla->user_id=$date['user_id'];
        $toalla->status='En uso';
        $toalla->user_name=$date['user_name'];
        $toalla->save();

        $bit= new bitatoalla;
        $bit->fec_asig=$date['fec_ini'];
        $bit->id_toalla=$date['id_toalla'];
        $bit->sta='En uso';
        $bit->user_id=$date['user_id'];
        $bit->user_name=$date['user_name'];
        $bit->save();
        }
        
        
        return parent::_store($request);
    }


    public function _update($id, Request $request)
    {
        $tosta=toalla::where('id',$request->id_toalla)->get();
     
         if(count($tosta)==0)
         {
            return response()->json(["error"=>true,"message"=> "esta toalla no existe"],422);
         }
        if($tosta[0]->status=="En uso")
        {
            return response()->json(["error"=>true,"message"=> "esta toalla ya esta en uso"],422);
        }

        elseif($tosta[0]->status=="Perdida")
        {
            return response()->json(["error"=>true,"message"=> "esta toalla se encuentra perdida y no puede ser asignada"],422);
        }

        else
        {
          return parent::_update($id,$request);
        }
    }
}

