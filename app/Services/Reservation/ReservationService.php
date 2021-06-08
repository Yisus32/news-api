<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Reservation;


use App\Core\CrudService;
use App\Models\Reservation;
use App\Models\Teetime;
use App\Repositories\Reservation\ReservationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

/** @property ReservationRepository $repository */
class ReservationService extends CrudService
{

    protected $name = "reservation";
    protected $namePlural = "reservations";

    public function __construct(ReservationRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        if (isset($request->teetime_id)) {
            $teetime = Teetime::find($request->teetime_id);
            $now = Carbon::now(env('APP_TIMEZONE'));
         
            $date = $request->date . ' '. $request->start_hour;
            
            $final = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
            $final->subHours($teetime->available);
            //se usa para comprobar que se esta haciendo la reservacion con el tiempo previo de disponibilidad de la programacion
           if ($now->greaterThan($final)) {
               return response()->json(["error" => true, "message" => "La fecha ingresada no cumple con el tiempo de disponibilidad"], 409);
           }

           //verificar numero de jugadores
           if ($this->countPlayers($request->partners, $request->guest) > $teetime->max_capacity) {
            return response()->json(["error" => true, "message" => "El número maximo de jugadores es $teetime->max_capacity"], 409);
           }
           
           if ($this->countPlayers($request->partners, $request->guest) < $teetime->min_capacity) {
            return response()->json(["error" => true, "message" => "El número mínimo de jugadores es $teetime->min_capacity"], 409);
           }

        }else{
            return response()->json(["error" => true, "message" => "La reservación no pertenece a una programación valida"], 409);
        }

        // comprueba que las horas ingresadas no choquen con otra reservacion
        $hour_between = Reservation::where('teetime_id', '=', "$request->teetime_id")
                                    ->where('date', '=', "$request->date")
                                    ->where('hole_id', '=', "$request->hole_id")
                                    ->whereRaw("start_hour BETWEEN '$request->start_hour' and '$request->end_hour' or 
                                    end_hour BETWEEN '$request->start_hour' and '$request->end_hour'")->get();
        
        
        if ($hour_between->count() > 0) {
            return response()->json(["error" => true, "message" => "Las horas ingresadas ya han sido ocupadas por otro cliente"], 409);
        }
        
        return parent::_store($request);
    }

    public function _update($id, Request $request)
    {
        
        return parent::_update($id, $request);
    }

    public function _delete($id)
    {   
        $reservation = Reservation::find($id);
        $teetime = Teetime::find($reservation->teetime_id);

        $date = $reservation->date . ' '. $reservation->start_hour;

        $final = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
        $final->subHours($teetime->cancel_time);

        $now = Carbon::now(env('APP_TIMEZONE'));

        // verificar que se esta eliminando con el tiempo de anticipacion
        if ($now->greaterThan($final)) {
            return response()->json(["error" => true, "message" => "El tiempo para cancelar expiro"], 409);
        }
        
        return parent::_delete($id);
    }

    private function countPlayers($partners = null, $guests = null){

        $number = 1;

        if ($partners != null) {
            $number = $number + count($partners);
        }
        if ($guests != null) {
            $number = $number + count($guests);
        }

        return $number;

    }
}