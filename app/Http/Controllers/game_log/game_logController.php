<?php

namespace App\Http\Controllers\game_log;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\bitatoalla;
use App\Services\game_log\game_logService;
use App\Models\game_log;
use Illuminate\Support\Facades\DB;
use DateTime;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;

/** @property game_logService $service */
class game_logController extends CrudController
{
    public function __construct(game_logService $service)
    {
        parent::__construct($service);
        
        $this->validateStore = [
            'car_id' => 'required',
            'hol_id' => 'required',
            'gro_id' => 'required'
            ];
    
        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }

    public function filter_by_date(Request $request)
    {
        $r=$request->get('fecha');
        $f=$request->get('fin');
        if($r==0 or $f==0)
        {
            return ["list"=>[],'total'=>0];
        }
        elseif( $fill=game_log::whereBetween(DB::Raw('cast(created_at as date)'), array($r, $f))->count()==0)
        {
            return ["list"=>[],'total'=>0];
        }
        else
        {
            $fill=game_log::whereBetween(DB::Raw('cast(game_log.created_at as date)'), array($r, $f))->join('group','group.id','=','game_log.gro_id') ->join('cars_golf','cars_golf.id','=','game_log.car_id')->join('holes','holes.id','=','game_log.id_hole')->select('group.cod as codegroup','game_log.id','game_log.user_id','game_log.auser_id','game_log.car_id','game_log.hol_id','game_log.gro_id','game_log.id_hole','cars_golf.cod as numcar','game_log.user_name','game_log.created_at as fecha','holes.name as namehole','game_log.asoc_name')->get();
            return  ["list"=>$fill,'total'=>count($fill)];
        }
       
    }
     
    public function list_by_group()
    {
        
        $now= new DateTime('now');
        $now=$now->format('Y-m-d H:i:s');
        $group=DB::table('game_log')->whereDate('created_at',$now)->groupBy('gro_id','id')->get();
        return response()->json($group);
    }

    public function indexfull()
    {
       $game=DB::table('game_log')
        ->join('group','group.id','=','game_log.gro_id')
        ->join('cars_golf','cars_golf.id','=','game_log.car_id')
        ->select('group.cod as codegroup','game_log.id','game_log.user_id','game_log.auser_id','game_log.car_id','game_log.hol_id','game_log.gro_id','game_log.id_hole','cars_golf.cod as numcar')->get();  
        return response()->json($game);
    }
    

   
}