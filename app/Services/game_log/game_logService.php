<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\game_log;


use App\Core\CrudService;
use App\Repositories\game_log\game_logRepository;
use Illuminate\Http\Request;
use App\Models\game_log;
use Carbon\Carbon;

/** @property game_logRepository $repository */
class game_logService extends CrudService
{

    protected $name = "game_log";
    protected $namePlural = "game_logs";

    public function __construct(game_logRepository $repository)
    {
        parent::__construct($repository);
    }
    
    public function _store(Request $request)
    {
        $user=$request->user_id;
        $inv=$request->inv_id;
        $asoc=$request->auser_id;
        $ainv=$request->ainv_id;
      

        if($user==null and $inv==null)
        {
            return response()->json(["error" => true, "message" => "No se puede crear un juego sin un socio o un invitado"], 400);
        }

        elseif($user==null and $asoc!==null)
        {
            return response()->json(["error" => true, "message" => "No se puede crear un juego sin un socio principal"], 400);
        }
        
        elseif($inv!==null and $ainv!==null and $inv==$ainv)
        {
            return response()->json(["error" => true, "message" => "No se puede crear un juego con el mismo invitado"], 400);
        }
        
        

        elseif($user!==null and $asoc!==null and $inv!==null and $ainv!==null)
        {
            return response()->json(["error" => true, "message" => "No se puede crear un juego con mas de dos personas"], 400);
        }

        elseif($user!==null and $asoc!==null and $inv!==null)
        {
            return response()->json(["error" => true, "message" => "No se puede crear un juego con mas de dos personas"], 400);
        }

        elseif($user!==null and $asoc!==null and $user==$asoc)
        {
            return response()->json(["error" => true, "message" => "No se puede crear un juego con el mismo socio"], 400);
        }

        else
        {
            return parent::_store($request);
        }

    }

}