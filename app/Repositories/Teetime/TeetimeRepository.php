<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Teetime;

use App\Core\CrudRepository;
use App\Models\Break_time;
use App\Models\Reservation;
use App\Models\Teetime;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $teetimes = DB::select('SELECT t.id, t.start_date, t.end_date, t.min_capacity,t.max_capacity,t.time_interval,t.available,
        t.cancel_time,t.start_hour,t.end_hour,t.target,t.days,t.user_id,t.user_name,t.created_at,t.updated_at,array_agg(h.name) holes_names 
        FROM teetimes t JOIN holes h ON h.id = ANY(t.target) GROUP BY t.id');

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
        $teetime = DB::select("SELECT t.id, t.start_date, t.end_date, t.min_capacity,t.max_capacity,t.time_interval,t.available,t.cancel_time,
        t.start_hour,t.end_hour,t.target,t.days,t.user_id,t.user_name,t.created_at,t.updated_at,array_agg(h.name) holes_names 
        FROM teetimes t JOIN holes h ON h.id = ANY(t.target) WHERE t.id = $id GROUP BY t.id");

        if ($teetime) {
            $teetime = $teetime[0];
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

    public function available($id, Request $request){

        $teetime = Teetime::find($id);

        $days = $teetime->days;

        $days = str_replace('{', '', $days);
        $days = str_replace('}', '', $days);
        $days = explode(",", $days);

        $holes = $teetime->target;

        $holes = str_replace('{', '', $holes);
        $holes = str_replace('}', '', $holes);
        $holes = explode(",", $holes);

        $teetime->slot = $this->create_reservations($teetime, $holes, $days);

        return $teetime;
    }

    //funcion para crear las reservaciones sin reservar de un teetime
    private function create_reservations(Teetime $request, $holes, $days){
        $reservation = array();
        $day_name = array(0 => "Sunday", 1 => "Monday", 2 => "Tuesday", 3 => "Wednesday", 4 => "Thursday", 5 => "Friday", 6 => "Saturday");
        //ciclo para dejar los dias que no hay servicio
        foreach ($days as $day) {
            $day_name[$day] = "0";
        }

        $i = 0;

        // crear reservaciones para cada hoyo
        foreach ($holes as $hole) {
            
            // se crea la fecha inicial y final para crear reservaciones
            $date = $request->start_date . " " . $request->start_hour;
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
            $end_date = $request->end_date . " " . $request->end_hour;
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $end_date, env('APP_TIMEZONE'));
            do {

                $name_day = $start->format('l');

                //checar para no añadir los dias no laborables del teetime
                $check_day = array_search("$name_day", $day_name);

                if ($check_day > 0 ) {
                    $start = $start->addDay();
                }

                if ($start > $end) {
                    break;
                }
                
                if ($check_day == 0) {
                    // se crea la hora final del dia para la comprobacion en el segundo do while
                    $date_time = explode(' ', $start);
                    $date_end_time = $date_time[0] . ' ' . $request->end_hour;
                    $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $date_end_time, env('APP_TIMEZONE'));
                    do {
                        //se crea una reservacion
                        $date_save = explode(' ', $start);

                        $reservation_exist = Reservation::where('hole_id', '=', "$hole")
                                                        ->where('date', '=', "$date_save[0]")
                                                        ->where('start_hour', '=', "$date_save[1]")
                                                        ->where('status', '=', 'registrado')
                                                        ->orWhere('status', '=', 'registrado')
                                                        ->where('hole_id', '=', "$hole")
                                                        ->where('date', '=', "$date_save[0]")
                                                        ->where('start_hour', '=', "$date_save[1]")
                                                        ->first();

                        if (!$reservation_exist) {
                            $reservation[$i]["hole_id"] = $hole;
                            $reservation[$i]["date"] = $date_save[0];
                            $reservation[$i]["start_hour"] = $date_save[1];
                        }
                
                        //se le añaden los minutos del intervalo en cada recorrido
                        $start->addMinutes($request->time_interval);
                        $i++;

                        if (isset($request->break_times)) {
                            $check = explode(' ', $start);
                            foreach ($request->break_times as $break) {
                                if($check[1] >= $break["start_hour"] and $check[1] <= $break["end_hour"] ){
                                    $after_break = $check[0] . " " . $break["end_hour"];
                                    $start = $start = Carbon::createFromFormat('Y-m-d H:i:s', $after_break, env('APP_TIMEZONE'));
                                }
                            }
                        }
                    } while ($start <= $end_time);

                    // se le sube un dia al inicio para recorrer el while y se se crea la fecha de nuevo para reiniciar la hora
                    $start = $start->addDay();
                    $date_start_time = explode(' ', $start);
                    $date_start_time = $date_start_time[0] . ' ' . $request->start_hour;
                    $start = Carbon::createFromFormat('Y-m-d H:i:s', $date_start_time, env('APP_TIMEZONE'));
                    $i++;
                }
                
                
            } while ($start <= $end);
            
        }
        return $reservation;
    }

    

}