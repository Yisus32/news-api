<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\cars_golf;


use App\Core\CrudService;
use App\Models\alq_car;
use App\Models\cars_golf;
use App\Models\game_log;
use App\Repositories\cars_golf\cars_golfRepository;
use Illuminate\Http\Request;

/** @property cars_golfRepository $repository */
class cars_golfService extends CrudService
{

    protected $name = "cars_golf";
    protected $namePlural = "cars_golves";

    public function __construct(cars_golfRepository $repository)
    {
        parent::__construct($repository);
    }
    
    public function _store(Request $request)
    {
        $cod_exist=cars_golf::where('cod',$request->cod)->first();
        if($cod_exist)
        {
            return response()->json(["error"=>true,"message"=> "El carrito de golf ya existe"],422);
        }
        return parent::_store($request);
    }
     
    public function _update($id, Request $request)
    {
        $cod_exist=cars_golf::where('cod',$request->cod)->first();
        if($cod_exist and $cod_exist->id !=$id) 
        {
            return response()->json(["error"=>true,"message"=> "El carrito de golf ya existe"],422);
        }

        else
        {
            return parent::_update($id,$request);
        }
    }
    public function _delete($id)
    {
        $exis=alq_car::where('car_id',$id)->first();
        if ($exis) {
            return response()->json(['error' => true, "message" => 'Existen juegos asociados a este carrito'],409);
        }
        else
        {
            return parent::_delete($id);
        }
    }
}