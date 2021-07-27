<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\cars_golf;


use App\Core\CrudService;
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

    public function _delete($id)
    {
        $exis=game_log::where('car_id',$id)->first();
        if ($exis) {
            return response()->json(['error' => true, "message" => 'Existen juegos asociados a este carrito'],409);
        }
    }
}