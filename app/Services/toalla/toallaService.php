<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\toalla;


use App\Core\CrudService;
use App\Repositories\toalla\toallaRepository;
use Illuminate\Http\Request;
use App\Models\toalla;
use DateTime;
use App\Models\asig_toalla;
use App\Models\bitatoalla;

/** @property toallaRepository $repository */
class toallaService extends CrudService
{

    protected $name = "toalla";
    protected $namePlural = "toallas";

    public function __construct(toallaRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        $cod_exist=toalla::where('num',$request->num)->first();
        if($cod_exist)
        {
            return response()->json(["error"=>true,"message"=> "La toalla ya existe"],422);
        }
        return parent::_store($request);
    }

    public function _update($id, Request $request)
    {
        $veri=asig_toalla::where('id_toalla',$id)->count();

        //para que no puedan editar por el mismo numero de toalla
        $cod_exist=toalla::where('num',$request->num)->first();
        
        if($cod_exist and $cod_exist->id !=$id)
        {
            return response()->json(["error"=>true,"message"=> "La toalla ya existe"],422);
        }
        
        if($veri>0)
        {
            $fec=new DateTime('now');
            $oasi=asig_toalla::where('id_toalla',$id)->orderby('created_at','DESC')->take(1)->get();
            $ida=$oasi[0]->id;
    
            $cfe=asig_toalla::where('id',$ida)->first();
            $cfe->fec_fin=$fec;
            $cfe->save();
            
            //modificar fecha ultima de bitacora
            $up=bitatoalla::where('id_toalla',$id)->first();
            $up->fec_ult=$fec;
            $up->save();

            //segumiento de toalla
            $bit= new bitatoalla;
            $bit->fec_asig=$fec;
            $bit->id_toalla=$id;
            $bit->sta=$request->status;
            $bit->user_id=$request->user_num;
            $bit->user_name=$request->user_nom;
            $bit->save();
            return parent::_update($id,$request);
        }
        
        else
        {
            $fec=new DateTime('now');
            //segumiento de toalla
            $bit= new bitatoalla;
            $bit->fec_asig=$fec;
            $bit->id_toalla=$id;
            $bit->sta=$request->status;
            $bit->user_id=$request->user_num;
            $bit->user_name=$request->user_nom;
            $bit->fec_ult=$fec;
            $bit->save();
            return parent::_update($id,$request);
        }
        

        
    }

}