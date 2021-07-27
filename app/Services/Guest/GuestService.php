<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Guest;


use App\Core\CrudService;
use App\Models\game_log;
use App\Repositories\Guest\GuestRepository;
use Illuminate\Http\Request;
use App\Models\Guest;
use GameLog;

/** @property GuestRepository $repository */
class GuestService extends CrudService
{

    protected $name = "guest";
    protected $namePlural = "guests";

    public function __construct(GuestRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request){
        if (isset($request->identifier)) {
            $guest_exist = Guest::whereRaw('LOWER(identifier) like ?', strtolower($request->identifier))->first();

            if($guest_exist){
                return response()->json(["error"=>true,"message"=> "El numero de cedula $request->identifier ya se encuentra registrado"],422);
            }
        }
        if (isset($request->email)) {
            $guest_exist = Guest::whereRaw('LOWER(email) like ?', strtolower($request->email))->first();

            if($guest_exist){
                return response()->json(["error"=>true,"message"=> "El email $request->email ya se encuentra registrado"],422);
            }
        }
        

        return parent::_store($request);
    }
    public function _update($id, Request $request){
        if (isset($request->identifier)) {
            $guest_exist = Guest::whereRaw('LOWER(identifier) like ?', strtolower($request->identifier))->first();

            if($guest_exist and $guest_exist->id != $id){
                return response()->json(["error"=>true,"message"=> "El numero de cedula $request->identifier ya se encuentra registrado"],422);
            }
        }
        if (isset($request->email)) {
            $guest_exist = Guest::whereRaw('LOWER(email) like ?', strtolower($request->email))->first();

            if($guest_exist and $guest_exist->id != $id){
                return response()->json(["error"=>true,"message"=> "El email $request->email ya se encuentra registrado"],422);
            }
        }

        return parent::_update($id, $request);
    }

    public function _delete($id)
    {
        $game_exist = game_log::where('inv_id', '=', "$id")->first();

        if ($game_exist) {
            return response()->json(["error"=>true,"message"=> "El invitado tiene juegos registrados"],422);
        }

        return parent::_delete($id);

    }

}