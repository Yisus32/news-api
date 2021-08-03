<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\group;


use App\Core\CrudService;
use App\Models\group;
use App\Repositories\group\groupRepository;
use Illuminate\Http\Request;

/** @property groupRepository $repository */
class groupService extends CrudService
{

    protected $name = "group";
    protected $namePlural = "groups";

    public function __construct(groupRepository $repository)
    {
        parent::__construct($repository);
    }
    
    public function _store(Request $request)
    {
        $cod_exist=group::where('cod',$request->cod)->first();
        if($cod_exist)
        {
            return response()->json(["error"=>true,"message"=> "El grupo ya existe"],422);
        }
        return parent::_store($request);
    }

    public function _update($id, Request $request)
    {
        $cod_exist=group::where('cod',$request->cod)->first();
        if($cod_exist and $cod_exist->id !=$id) 
        {
            return response()->json(["error"=>true,"message"=> "Esta hora de juego ya existe"],422);
        }

        else
        {
            return parent::_update($id,$request);
        }
    }
}