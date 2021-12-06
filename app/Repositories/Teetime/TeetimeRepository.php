<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Teetime;

use App\Core\CrudRepository;
use App\Models\Break_time;
use App\Models\Hole;
use App\Models\Reservation;
use App\Models\Teetime;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/** @property Teetime $model */
class TeetimeRepository extends CrudRepository
{

    public function __construct(Teetime $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
      /*  $teetimes = DB::select('SELECT t.id, t.start_date, t.end_date, t.min_capacity,t.max_capacity,t.time_interval,t.available,
        t.cancel_time,t.start_hour,t.end_hour,t.target,t.days,t.user_id,t.user_name,t.created_at,t.updated_at,array_agg(h.name) holes_names 
        FROM teetimes t JOIN holes h ON h.id = ANY(t.target) GROUP BY t.id');*/

        $teetimes = Teetime::select(['teetimes.*', DB::raw('array_agg(holes.name) as holes_names')])
        ->join('holes', 'holes.id', '=', DB::raw('ANY(teetimes.target)'))
        ->groupBy('teetimes.id')
        ->orderBy('teetimes.start_date')
        ->get();

        foreach ($teetimes as $teetime) {
            $days = $teetime->days;
            $days = str_replace("{", '', $days);
            $days = str_replace("}", '', $days);
            $days = explode(',', $days);
            $teetime->days = $days;

            $target = $teetime->target;
            $target = str_replace("{", '', $target);
            $target = str_replace("}", '', $target);
            $target = explode(',', $target);
            $teetime->target = $target;

            $holes_names = $teetime->holes_names;
            $holes_names = str_replace("{", '', $holes_names);
            $holes_names = str_replace("}", '', $holes_names);
            $holes_names = explode(',', $holes_names);
            $teetime->holes_names = $holes_names;

            $teetime->break_times = Break_time::where('teetime_id', '=', "$teetime->id")->get();

        }

        return $teetimes;
    }

    public function _show($id)
    {
 /*       $teetime = DB::select("SELECT t.id, t.start_date, t.end_date, t.min_capacity,t.max_capacity,t.time_interval,t.available,t.cancel_time,
        t.start_hour,t.end_hour,t.target,t.days,t.user_id,t.user_name,t.created_at,t.updated_at,array_agg(h.name) holes_names 
        FROM teetimes t JOIN holes h ON h.id = ANY(t.target) WHERE t.id = $id GROUP BY t.id");*/

        $teetime = Teetime::select(['teetimes.*', DB::raw('array_agg(holes.name) as holes_names')])
        ->join('holes', 'holes.id', '=', DB::raw('ANY(teetimes.target)'))
        ->groupBy('teetimes.id')
        ->find($id);
   
        if ($teetime) {
            $days = $teetime->days;
            $days = str_replace("{", '', $days);
            $days = str_replace("}", '', $days);
            $days = explode(',', $days);
            $teetime->days = $days;

            $target = $teetime->target;
            $target = str_replace("{", '', $target);
            $target = str_replace("}", '', $target);
            $target = explode(',', $target);
            $teetime->target = $target;

            $holes_names = $teetime->holes_names;
            $holes_names = str_replace("{", '', $holes_names);
            $holes_names = str_replace("}", '', $holes_names);
            $holes_names = explode(',', $holes_names);
            $teetime->holes_names = $holes_names;

            $teetime->break_times = Break_time::where('teetime_id', '=', "$teetime->id")->get();

            return $teetime;
        }

        return null;
    }

    public function _store(Request $data)
    {
        if (isset($data["target"])){
            $holes = $data["target"];
            $data["target"] = $this->model->formatTypeArray($data["target"]);
        }
        if (isset($data["days"])){
            $days = $data["days"];
            $data["days"] = $this->model->formatTypeArray($data["days"]);
        }

        $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $data['start_date'].' '.$data['start_hour']);
        $data['available_time'] = Carbon::parse($start_date->modify('-'.$data['available'].' hours'))
                                        ->format('Y-m-d H:i:s',env('APP_TIMEZONE'));
        $teetime = parent::_store($data);

        $break_times = $data['break_times'] ?? [];

        $teetime->break_times()->createMany($break_times);

        $teetime->break_times = $break_times;

     //  $reservations = $this->create_reservations($teetime, $holes, $days);

       //$teetime->reservations()->createMany($reservations);
        
        return  $teetime;
    }

    public function _update($id, $data)
    {
        if (isset($data["target"])){
            $data["target"] = $this->model->formatTypeArray($data["target"]);
        }
        if (isset($data["days"])){
            $data["days"] = $this->model->formatTypeArray($data["days"]);
        }
        
        $teetime = parent::_update($id, $data);

        if ($id and isset($data['break_times'])) {
            foreach ($data['break_times'] as $break) {
                isset($break['id'])
                    ? $teetime->break_times()->where('id',$break['id'])->update($break)
                    : $teetime->break_times()->create($break);
            }
        }

        $teetime->reservations()->delete();

        $holes = $teetime->target;

        $holes = str_replace('{', '', $holes);
        $holes = str_replace('}', '', $holes);
        $holes = explode(",", $holes);

        $days = $teetime->days;

        $days = str_replace('{', '', $days);
        $days = str_replace('}', '', $days);
        $days = explode(",", $days);

    //    $reservations = $this->create_reservations($teetime, $holes, $days);

      //  $teetime->reservations()->createMany($reservations);

        return $teetime;
    }

    //devuelve espacios disponibles en un teetime
    //comentario para commit

    public function paginate_days(){
        
        $start_day = Carbon::now(env('APP_TIMEZONE'))->format('Y-m-d');
        $end_day = Teetime::max('end_date');

        $period = CarbonPeriod::create($start_day, $end_day);

        foreach ($period as $p) {
            $dates[] = $p->format('Y-m-d');
        }

        return $dates;
    }

    public function available(Request $request){
        //new------------------------------------
        $start_day = $request->date;
        $end_day = $request->date;
        //--------------------------------------

        if (isset($start_day) and isset($end_day)) {
            $teetimes = Teetime::select('teetimes.*',
                                        'break_times.start_hour as bt_start_hour',
                                        'break_times.end_hour as bt_end_hour')
                                ->whereBetween('start_date', [$start_day, $end_day])
                                ->OrwhereBetween('end_date', [$start_day, $end_day])
                                ->Orwhere('start_date', '<', $start_day)
                                ->where('end_date', '>', $start_day)
                                ->Orwhere('start_date', '<', $end_day)
                                ->where('end_date', '>', $end_day)
                                ->leftjoin('break_times','break_times.teetime_id','=','teetimes.id')
                                ->orderBy('start_date')
                                ->get();

    
            foreach ($teetimes as $teetime) {
                //new---------------------------------------------------------------------------------
                $fecha = Carbon::createFromFormat('Y-m-d H:i:s',$teetime['start_date'].' '.$teetime['start_hour']);
                
                $diferencia = $fecha->subHour($teetime['available'])->format('Y-m-d H:i:s');

                
                //$disponibilidad = Carbon::createFromFormat('Y-m-d H:i:s',$teetime['available_time']);
                //-------------------------------------------------------------------------------------
                
              //  $teetime = Teetime::find(17);
                if ($teetime->start_date < $start_day) {
                    $teetime->start_date = $start_day;
                }

                /**if ($teetime->end_date > $end_day) {
                    $teetime->end_date = $end_day;
                }**/

                $days = $teetime->days;

                $days = str_replace('{', '', $days);
                $days = str_replace('}', '', $days);
                $days = explode(",", $days);

                $holes = $teetime->target;

                $holes = str_replace('{', '', $holes);
                $holes = str_replace('}', '', $holes);
                $holes = explode(",", $holes);

                $array = [];
                foreach ($holes as $hole) {
                    $hol = Hole::find($hole);
                    $array[$hole] = $hol->name;

                }
                $teetime->holes_name = $array;

               if ($request->header('role') != "admin") {
                    $end = Carbon::now($request->header("timezone"));
                    $end->addHours($teetime->available);
                    
                    $teetime->slot = $this->create_reservations($teetime, $holes, $days, $end);
                }else{
                    $teetime->slot = $this->create_reservations($teetime, $holes, $days);
                }
             //   return $teetime;

            }
                
            if (isset($diferencia) && $diferencia <= Carbon::now()->format('Y-m-d H:i:s')) {
                return $teetimes;
            }else{
                return abort(404,"No hay teetimes disponibles aun");
            }
            
        }
            
        
        return Response()->json(["error"=>true, "message"=>"no existen registros de teetime"], 404);
        
       
    }

    public function create_reservations(Teetime $request,$holes,$days,$limit = null){
       
       
        $start_hour = Carbon::createFromFormat('H:i:s',$request->start_hour,env('APP_TIMEZONE'));  
        $end_hour = Carbon::createFromFormat('H:i:s',$request->end_hour);
        $bt_start_hour = $request->bt_start_hour != null ? Carbon::createFromFormat('H:i:s',$request->bt_start_hour,env('APP_TIMEZONE')) : 0;
        $bt_end_hour = $request->bt_end_hour != null ? Carbon::createFromFormat('H:i:s',$request->bt_end_hour,env('APP_TIMEZONE')) : 0;
        $interval = $request->time_interval;
        
        $n_holes = count($holes);
        $n_days = count($days);
        
        $start_date = Carbon::createFromFormat('Y-m-d H:i:s',$request->start_date.' '.$request->start_hour,env('APP_TIMEZONE'));
        $x = Carbon::createFromFormat('Y-m-d H:i:s',$request->start_date.' '.$request->start_hour,env('APP_TIMEZONE'));
        $cancel_time = $x->subHours($request->cancel_time)->format('Y-m-d H:i:s');



        
         //AGREGAR INTERVALO A CADA FECHA IMPORTANTE
         //AGREGAR ARRAY DE BREAK_TIMES AL FINAL DE CADA ARRAY
         //   
        $diff_services_hours = $end_hour->diffInHours($start_hour,true);

        if ($bt_start_hour && $bt_end_hour) {
            
            $diff_break_hours = $bt_end_hour->diffInHours($bt_start_hour,true);

        }else{

            $diff_break_hours = 0;
        }
        

        $slots = abs(((($diff_services_hours*60)/$interval) - (($diff_break_hours*60)/$interval)));

        for ($i=0; $i < $slots ; $i++) { 

            if ($bt_start_hour && ($start_date->format('H:i:s') < $bt_start_hour->format('H:i:s'))) {
                 $start_date = $start_date; 
            }

            if ($bt_start_hour && ($start_date->format('H:i:s') >= $bt_start_hour->format('H:i:s'))){
                 $start_date = Carbon::createFromFormat('Y-m-d H:i:s',$request->start_date.' '.$request->bt_end_hour,env('APP_TIMEZONE'))->addMinutes($interval);
            }
            
            for ($j=0; $j <$n_holes ; $j++) { 
            
                $reservation[] = [
                                  "hole_id" => $holes[$j],
                                  "date" => $start_date->format('Y-m-d'),
                                  "start_hour" => $start_date->format('H:i:s'),
                                  "available_time" => $request->available_time,
                                  "cancel_time" => $cancel_time
                                ]; 
            }
            
            //SUBIR CAMBIOS 
            if ($bt_start_hour && ($start_date->format('H:i:s') < $bt_start_hour->format('H:i:s'))) {
                 $start_date->addMinutes($interval);
            }

            if (!$bt_start_hour && !$bt_end_hour) {
                $start_date->addMinutes($interval);
            }

            
                                          
        }

        return $reservation;
    }


    //funcion para crear las reservaciones sin reservar de un teetime
    /**private function create_reservations(Teetime $request, $holes, $days, $limit = null){
        $reservation = array();
        $day_name = array(0 => "Sunday", 1 => "Monday", 2 => "Tuesday", 3 => "Wednesday", 4 => "Thursday", 5 => "Friday", 6 => "Saturday");
        //ciclo para dejar los dias que no hay servicio
        foreach ($days as $day) {
            $day_name[$day] = "0";
        }

        $day_name = implode(",", $day_name);

        $i = 0;

        // crear reservaciones para cada hoyo
        
            
        // se crea la fecha inicial y final para crear reservaciones
        $date = $request->start_date . " " . $request->start_hour;
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
        $end_date = $request->end_date . " " . $request->end_hour;
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $end_date, env('APP_TIMEZONE'));
        do {

            $name_day = $start->format('l');

            //checar para no añadir los dias no laborables del teetime
            $check_day = str_contains($day_name, $name_day);
            //$check_day = array_search("$name_day", $day_name);

            if ($check_day == true ) {
                $start = $start->addDay();
            }

            if ($start > $end) {
                break;
            }
            
            if ($check_day == false) {
                // se crea la hora final del dia para la comprobacion en el segundo do while
                $date_time = explode(' ', $start); // start = "2021-06-06 08:00:00
                $date_end_time = $date_time[0] . ' ' . $request->end_hour;
                $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $date_end_time, env('APP_TIMEZONE'));
                do {
                    

                    if (isset($request->break_times)) {
                        $check = explode(' ', $start);
                        foreach ($request->break_times as $break) {
                            //chequea tiempos de descanso
                            if($check[1] >= $break["start_hour"] and $check[1] <= $break["end_hour"] ){
                                $after_break = $check[0] . " " . $break["end_hour"];
                                $start = Carbon::createFromFormat('Y-m-d H:i:s', $after_break, env('APP_TIMEZONE'));
                        
                            }
                        }
                    }
                    if ($limit != null and $start > $limit) {
                        break;
                    }
        
                    //se crea una reservacion
                    $date_save = explode(' ', $start); // $start= "2021-06-06 04:00:00 " -> $date_save[0] = "2021-06-16" [1] = "04:00:00"

                    //se hace el chequeo de la disponibilidad de cada hoyo en la fecha a mostrar
                    foreach ($holes as $hole) {
                        $reservation_exist = Reservation::where('hole_id', '=', "$hole")
                                                    ->where('date', '=', "$date_save[0]")
                                                    ->where('start_hour', '=', "$date_save[1]")
                                                    ->where('status', '=', 'registrado')
                                                    ->where('hole_id', '=', "$hole")
                                                    ->where('date', '=', "$date_save[0]")
                                                    ->where('start_hour', '=', "$date_save[1]")
                                                    ->first();

                        

                        if (!$reservation_exist) {
                            $reservation[$i]["hole_id"] = $hole;
                            $reservation[$i]["date"] = $date_save[0];
                            $reservation[$i]["start_hour"] = $date_save[1];

                            $date_available = $date_save[0] . " " . $date_save[1];
                            $start_available = Carbon::createFromFormat('Y-m-d H:i:s', $date_available);
                            $reservation[$i]["available_time"] = $start_available->subHours($request->available)->format('Y-m-d H:i:s');

                            $date_available = $date_save[0] . " " . $date_save[1];
                            $start_available = Carbon::createFromFormat('Y-m-d H:i:s', $date_available);
                            $reservation[$i]["cancel_time"] = $start_available->subHours($request->cancel_time)->format('Y-m-d H:i:s');

                            $i++;
                        }
                    }
                    
            
                    //se le añaden los minutos del intervalo en cada recorrido
                    $start->addMinutes($request->time_interval);
                    

                    
                } while ($start <= $end_time);

                // se le sube un dia al inicio para recorrer el while y se se crea la fecha de nuevo para reiniciar la hora
                
                $start = $start->addDay();
                $date_start_time = explode(' ', $start);
                $date_start_time = $date_start_time[0] . ' ' . $request->start_hour;
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $date_start_time, env('APP_TIMEZONE'));
                
            }
            
            
        } while ($start <= $end);
            
        
        return $reservation;
    }**/ 

}