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
    

        if($veri>0)
        {
            $fec=new DateTime('now');
            $oasi=asig_toalla::where('id_toalla',$id)->orderby('created_at','DESC')->take(1)->get();
            $ida=$oasi[0]->id;
    
            $cfe=asig_toalla::where('id',$ida)->first();
            $cfe->fec_fin=$fec;
            $cfe->save();
        }
        
        else{
            return parent::_update($id,$request);
        }
        

        
    }

}