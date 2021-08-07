<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\alq_car;

use App\Core\CrudRepository;
use App\Models\alq_car;
use Illuminate\Support\Facades\DB;

/** @property alq_car $model */
class alq_carRepository extends CrudRepository
{

    public function __construct(alq_car $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
{
    $game=DB::table('alq_car')
    ->join('group','group.id','=','alq_car.gro_id')
    ->join('cars_golf','cars_golf.id','=','alq_car.car_id')
    ->join('holes','holes.id','=','alq_car.id_hole')
    ->select('group.cod as codegroup','cars_golf.cod as numcar','holes.name as namehole','alq_car.user_id','alq_car.user_num','alq_car.user_name','alq_car.car_id','alq_car.hol_id','alq_car.gro_id','alq_car.fecha','alq_car.id_hole','alq_car.obs','alq_car.tipo_p','alq_car.can_p')->get();  
    return $game;
}

public function _show($id)
{
    $veri=alq_car::where('id',$id)->count();

    if($veri>0)
    {
    $game=DB::table('alq_car')->where('alq_car.id',$id)
    ->join('group','group.id','=','alq_car.gro_id')
    ->join('cars_golf','cars_golf.id','=','alq_car.car_id')
    ->join('holes','holes.id','=','alq_car.id_hole')
    ->select('group.cod as codegroup','cars_golf.cod as numcar','holes.name as namehole','alq_car.user_id','alq_car.user_num','alq_car.user_name','alq_car.car_id','alq_car.hol_id','alq_car.gro_id','alq_car.fecha','alq_car.id_hole','alq_car.obs','alq_car.tipo_p','alq_car.can_p')->get();  
    return $game;
    }
    
     else
     {
        return parent::_show($id);
     }
 }

}