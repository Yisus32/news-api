<?php

namespace App\Http\Controllers\game_log;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\game_log\game_logService;
use App\Models\game_log;
use Illuminate\Support\Facades\DB;
use DateTime;
/** @property game_logService $service */
class game_logController extends CrudController
{
    public function __construct(game_logService $service)
    {
        parent::__construct($service);
        
        $this->validateStore = [
            'user_id' => 'required',
            'car_id' => 'required',
            'hol_id' => 'required',
            'gro_id' => 'required'
            ];
    
        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }

    public function filter_by_date($fecha)
    {
        $fec=date('Y-m-d',strtotime($fecha));
        $fill=game_log::where('fecha',$fec)->get();
        return response()->json($fill);
    }
     
    public function list_by_group()
    {
        
        $now= new DateTime('now');
        $now=$now->format('Y-m-d');
        $group=DB::table('game_log')->where('fecha',$now)->groupBy('gro_id','id')->get();
        return response()->json($group);
    }

    public function indexfull()
    {
        $game=DB::table('game_log')
        ->join('group','group.id','=','game_log.gro_id')
        ->select('group.cod','game_log.id','game_log.auser_id','game_log.car_id','game_log.hol_id','game_log.gro_id','game_log.id_hole')
        ->get();     
        return response()->json($game);
    }
    
}