<?php

namespace App\Http\Controllers\game_log;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\game_log\game_logService;
use App\Models\game_log;
use Illuminate\Support\Facades\DB;
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
        $group=DB::table('game_log')->groupBy('gro_id','id')->get();
        return response()->json($group);
    }
   
    
}