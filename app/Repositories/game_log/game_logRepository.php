<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\game_log;

use App\Core\CrudRepository;
use App\Events\waitlist;
use App\Models\game_log;
use Illuminate\Support\Facades\DB;

/** @property game_log $model */
class game_logRepository extends CrudRepository
{

    public function __construct(game_log $model)
    {
        parent::__construct($model);
    }
public function _index($request = null, $user = null)
{
   event(new waitlist($request));
}

public function _show($id)
{
    $veri=game_log::where('id',$id)->count();

    if($veri>0)
    {
    $game=DB::table('game_log')->where('game_log.id',$id)
    ->join('group','group.id','=','game_log.gro_id')
    ->join('cars_golf','cars_golf.id','=','game_log.car_id')
    ->join('holes','holes.id','=','game_log.id_hole')
    ->select(DB::Raw('cast(game_log.created_at as date) as fecha'),'group.cod as codegroup','game_log.id','game_log.user_id','game_log.auser_id','game_log.car_id','game_log.hol_id','game_log.gro_id','game_log.id_hole','cars_golf.cod as numcar','game_log.user_name','holes.name as namehole','game_log.inv_id','game_log.inv_name','game_log.asoc_name','game_log.ainv_id','game_log.ainv_name','game_log.obs','game_log.tipo_p','game_log.can_p')->get(); 
    return $game;
    }
    
     else
     {
        return parent::_show($id);
     }
 }
}