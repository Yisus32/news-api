<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\asig_toalla;

use App\Core\CrudRepository;
use App\Models\asig_toalla;
use App\Models\toalla;
use DateTime;
use Illuminate\Support\Facades\DB;

/** @property asig_toalla $model */
class asig_toallaRepository extends CrudRepository
{

    public function __construct(asig_toalla $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        $game=DB::table('asig_toalla')->where('fec_fin',null)
        ->join('toalla','toalla.id','=','asig_toalla.id_toalla')
        ->select('asig_toalla.id','toalla.num as num_toalla','asig_toalla.id_toalla','asig_toalla.fec_ini','asig_toalla.fec_fin','asig_toalla.created_at','asig_toalla.updated_at','asig_toalla.user_name','asig_toalla.user_id','asig_toalla.obs')->get();  
   
        return $game;
    }
  
   public function _show($id)
   {
     $veri=asig_toalla::where('id',$id)->count();
     if($veri>0)
     {
        $game=DB::table('asig_toalla')->where('asig_toalla.id',$id)
        ->join('toalla','toalla.id','=','asig_toalla.id_toalla')
        ->select('asig_toalla.id','toalla.num as num_toalla','asig_toalla.id_toalla','asig_toalla.fec_ini','asig_toalla.fec_fin','asig_toalla.created_at','asig_toalla.updated_at','asig_toalla.user_name','asig_toalla.user_id','asig_toalla.obs')->get();  
        return $game;
     }

     else
     {
        return parent::_show($id);
     }
     return parent::_show($id);
   }

}