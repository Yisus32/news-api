<?php

namespace App\Http\Controllers\alq_car;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\alq_car;
use App\Services\alq_car\alq_carService;
use Illuminate\Support\Facades\DB;
use App\Core\ReportService;
use Carbon\Carbon;
/** @property alq_carService $service */
class alq_carController extends CrudController
{
    public function __construct(alq_carService $service)
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
        elseif( $fill=alq_car::whereBetween(DB::Raw('cast(alq_car.fecha as date)'), array($r, $f))->count()==0)
        {
            return ["list"=>[],'total'=>0];
        }
        else
        {
            $fill=alq_car::whereBetween(DB::Raw('cast(alq_car.fecha as date)'), array($r, $f))->join('group','group.id','=','alq_car.gro_id') ->join('cars_golf','cars_golf.id','=','alq_car.car_id')->join('holes','holes.id','=','alq_car.id_hole')->select('group.cod as codegroup','game_log.id','game_log.user_id','game_log.auser_id','game_log.car_id','game_log.hol_id','game_log.gro_id','game_log.id_hole','cars_golf.cod as numcar','game_log.user_name','game_log.created_at as fecha','holes.name as namehole','game_log.asoc_name')->get();
            return  ["list"=>$fill,'total'=>count($fill)];
        }
       
    }

    public function report(Request $request){

        if (empty($request->star)) {
            return Response()->json(["error" => true, "message" => "la fecha es requerida"],400);
        }
        $r=$request->get('star');
        $f=$request->get('end');
        $alqu= $game=DB::table('game_log')->whereBetween(DB::Raw('cast(game_log.created_at as date)'), array($r, $f))
        ->join('group','group.id','=','game_log.gro_id')
        ->join('cars_golf','cars_golf.id','=','game_log.car_id')
        ->join('holes','holes.id','=','game_log.id_hole')
        ->select(DB::Raw('cast(game_log.created_at as date) as fecha'),'group.cod as codegroup','game_log.id','game_log.user_id','game_log.auser_id','game_log.car_id','game_log.hol_id','game_log.gro_id','game_log.id_hole','cars_golf.cod as numcar','game_log.user_name','holes.name as namehole','game_log.inv_id','game_log.inv_name','game_log.asoc_name','game_log.ainv_id','game_log.ainv_name','game_log.obs','game_log.tipo_p','game_log.can_p')->get();  
        
        $headers = ["Authorization" => $request->input('token')];
        $index=[
            'Fecha'=>'fecha',
            'Hora de incio'=>'codegroup',
            'Hoyo de salida'=>'namehole',
            'N° de socio'=>'user_id',
            'Nombre de socio'=>'user_name',
            'Socio adicional'=>'asoc_name',
            'Invitado'=>'inv_name',
            'Invitado adicional'=>'ainv_name',
            'Socio/Invitado/REC.'=>'tipo_p',
            'Grupo ronda(cantidad de personas que juegan en la ronda)'=>'can_p',
            'Cantidad de hoyos jugados'=>'hol_id',
            'Observaciones'=>'obs'

        ];
        $info []=$alqu;
        $report = new ReportService();
        $report->indexPerSheet([$index]);
        $report->dataPerSheet($info);
        $report->index($index);
        $report->data($alqu);
        //$report->external();
        return $report->report("automatic","Alquiler de carritos",null,null,false,1);
    }


    public function sav(Request $request)
    {
        $now = Carbon::now()->timezone("America/Panama");
        //para guardar un solo usuario 
        if($request->user_id==null and $request->auser_id==null)
        {
            return response()->json(["error" => true, "message" => "No se puede crear un juego sin un socio o un invitado"], 400);
        }
        if($request->user_id!==null and $request->auser_id==null)
        {
            $gu= new alq_car;
            $gu->user_id=$request->user_id;
            $gu->user_name=$request->user_name;
            $gu->user_num=$request->user_num;
            $gu->car_id=$request->car_id;
            $gu->hol_id=$request->hol_id;
            $gu->gro_id=$request->gro_id;
            $gu->fecha=$now;
            $gu->id_hole=$request->id_hole;
            $gu->obs=$request->obs;
            $gu->tipo_p=$request->tipo_p;
            $gu->can_p=$request->can_p;
            $gu->save();
            return response()->json([
                "status" => 201,
                "data"=>$gu],
                201);
        }

        else
        {
            $gu= new alq_car;
            $gu->user_id=$request->user_id;
            $gu->user_name=$request->user_name;
            $gu->user_num=$request->user_num;
            $gu->car_id=$request->car_id;
            $gu->hol_id=$request->hol_id;
            $gu->gro_id=$request->gro_id;
            $gu->fecha=$now;
            $gu->id_hole=$request->id_hole;
            $gu->obs=$request->obs;
            $gu->tipo_p=$request->tipo_p;
            $gu->can_p=$request->can_p;
            $gu->save();


            $gi= new alq_car;
            $gi->user_id=$request->auser_id;
            $gi->user_name=$request->auser_name;
            $gi->user_num=$request->auser_num;
            $gi->car_id=$request->car_id;
            $gi->hol_id=$request->hol_id;
            $gi->gro_id=$request->gro_id;
            $gi->fecha=$now;
            $gi->id_hole=$request->id_hole;
            $gi->obs=$request->obs;
            $gi->tipo_p=$request->tipo_p;
            $gi->can_p=$request->can_p;
            $gi->save();
            return response()->json([
                "status" => 201,
                "data1"=>$gu,
                "data2"=>$gi],
                201);
        }
    }
}