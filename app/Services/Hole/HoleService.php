<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Hole;


use App\Core\CrudService;
use App\Models\Hole;
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

}