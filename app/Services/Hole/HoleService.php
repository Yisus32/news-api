<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Hole;


use App\Core\CrudService;
use App\Models\alq_car;
use App\Models\Hole;
use App\Models\Reservation;
use App\Repositories\Hole\HoleRepository;
use Illuminate\Http\Request;

/** @property HoleRepository $repository */
class HoleService extends CrudService
{

    protected $name = "hole";
    protected $namePlural = "holes";

    public function __construct(HoleRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        if (isset($request->code)) {
            $hole_exist = Hole::whereRaw('LOWER(code) like ?', strtolower($request->code))->first();

            if($hole_exist){
                return response()->json(["error"=>true,"message"=> "El código de hoyo ya se encuentra registrado"],422);
            }
        }
        if (isset($request->name)) {
            $hole_exist = Hole::whereRaw('LOWER(name) like ?', strtolower($request->name))->first();

            if($hole_exist){
                return response()->json(["error"=>true,"message"=> "El nombre de hoyo ya se encuentra registrado"],422);
            }
        }

        return parent::_store($request);
    }

    public function _update($id, Request $request)
    {
        if (isset($request->code)) {
            $hole_exist = Hole::whereRaw('LOWER(code) like ?', strtolower($request->code))->first();

            if($hole_exist and $hole_exist->id != $id){
                return response()->json(["error"=>true,"message"=> "El código de hoyo ya se encuentra registrado"],422);
            }
        }
        if (isset($request->name)) {
            $hole_exist = Hole::whereRaw('LOWER(name) like ?', strtolower($request->name))->first();

            if($hole_exist and $hole_exist->id != $id){
                return response()->json(["error"=>true,"message"=> "El nombre de hoyo ya se encuentra registrado"],422);
            }
        }

        return parent::_update($id, $request);
    }
    

    public function _delete($id)
    {
        $alq=alq_car::where('id',$id)->count();
        $res=Reservation::where('id',$id)->count();
        if($alq>0 or $res>0)
        {
            return response()->json(["error"=>true,"message"=> "Este hoyo tiene rondas o asignaciones en curso no se puede eliminar "],422);
        }
        else
        {
            return parent::_delete($id);
        }
    }

}