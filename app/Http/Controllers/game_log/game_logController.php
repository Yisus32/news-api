<?php

namespace App\Http\Controllers\game_log;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\bitatoalla;
use App\Services\game_log\game_logService;
use App\Models\game_log;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\Core\ReportService;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;
use Carbon\Carbon;

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
            if($user!==null and $inv!==null)
            {
                $now = Carbon::now()->timezone("America/Panama");
                //guardar socio 
                $gu= new game_log;
                $gu->user_id=$user;
                $gu->car_id=$request->car_id;
                $gu->hol_id=$request->hol_id;
                $gu->gro_id=$request->gro_id;
                $gu->id_hole=$request->id_hole;
                $gu->can_p=$request->can_p;
                $gu->user_name=$request->user_name;
                $gu->obs=$request->obs;
                $gu->save();
                //guardar invitado
                $gi= new game_log;
                $gi->inv_id=$inv;
                $gi->car_id=$request->car_id;
                $gi->hol_id=$request->hol_id;
                $gi->gro_id=$request->gro_id;
                $gi->id_hole=$request->id_hole;
                $gi->can_p=$request->can_p;
                $gi->tipo_p=$request->tipo_p;
                $gi->inv_name=$request->inv_name;
                $gi->obs=$request->obs;
                $gi->save();

                return response()->json([
                    "status" => 201,
                    "data"=>$gu.$gi],
                    201);
            }
            elseif($inv!==null and $user==null and $asoc==null and $ainv==null)
            {
                
            }
            else
            {
                $now = Carbon::now()->timezone("America/Panama");
                //guardar socio o invitado 
                $gu= new game_log;
                $gu->user_id=$user;
                $gu->inv_id=$inv;
                $gu->car_id=$request->car_id;
                $gu->hol_id=$request->hol_id;
                $gu->gro_id=$request->gro_id;
                $gu->id_hole=$request->id_hole;
                $gu->can_p=$request->can_p;
                $gu->user_name=$request->user_name;
                $gu->obs=$request->obs;
                $gu->tipo_p=$request->tipo_p;
                $gu->inv_name=$request->inv_name;
                $gu->save();
                //guardar invitado o socio adicional
                $gi= new game_log;
                $gi->ainv_id=$inv;
                $gi->auser_id=$asoc;
                $gi->car_id=$request->car_id;
                $gi->hol_id=$request->hol_id;
                $gi->gro_id=$request->gro_id;
                $gi->id_hole=$request->id_hole;
                $gi->can_p=$request->can_p;
                $gi->tipo_p=$request->tipo_p;
                $gi->ainv_name=$request->inv_name;
                $gi->obs=$request->obs;
                $gi->asoc_name=$request->asoc_name;
                $gi->save();
                return response()->json([
                    "status" => 201,
                    "data"=>$gu.$gi],
                    201);
            }
        }

    }
   
}