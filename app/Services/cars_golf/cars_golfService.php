<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\cars_golf;


use App\Core\CrudService;
use App\Models\cars_golf;
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
}