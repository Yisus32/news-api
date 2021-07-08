<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Reservation;


use App\Core\CrudService;
use App\Models\Guest;
use App\Models\Hole;
use App\Models\Reservation;
use App\Models\Teetime;
use App\Repositories\Reservation\ReservationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** @property ReservationRepository $repository */
class ReservationService extends CrudService
{

    protected $name = "reservation";
    protected $namePlural = "reservations";

    public function __construct(ReservationRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _index(Request $request)
    {
        $reservations = Reservation::where('status', '=', 'reservado')->get();
        
        if (count($reservations) > 0) {
            $now = Carbon::now();

            foreach ($reservations as $reservation) {

                $check = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->updated_at);
                $check->addMinutes(5);

                if ($now > $check) {
                    $reservation->status = 'no reservado';
                    $reservation->update();
                }

            }

        }

        return parent::_index($request);

    }

    public function _store(Request $request)
    {
        $reservations = Reservation::where('status', '=', 'reservado')->get();
        
        if (count($reservations) > 0) {
            $now = Carbon::now();

            foreach ($reservations as $reservation) {

                $check = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->updated_at);
                $check->addMinutes(5);

                if ($now > $check) {
                    $reservation->status = 'no reservado';
                    $reservation->update();
                }

            }

        }

        $exist = Reservation::where('date', '=', "$request->date")->where('start_hour', '=', "$request->start_hour")->
                            where('hole_id', '=', "$request->hole_id")->
                            where('status', '=', "registrado")->
                            orWhere('status', '=', 'reservado')->
                            first();

        if ($exist) {
            return response()->json(["error" => true, "message" => "La hora ingresada ya ha sido ocupada por otro jugador"], 409);
        }

        //checar tiempo de disponibilidad

        $teetime = Teetime::find($request->teetime_id);

        $date = $request->date . ' '. $request->start_hour;
        $final = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
        $final->subHours($teetime->available_time);
        
        $now = Carbon::now(env('APP_TIMEZONE'));

        // verificar que se esta eliminando con el tiempo de anticipacion
        if ($final->greaterThan($now)) {
            return response()->json(["error" => true, "message" => "Debe reservar máximo $teetime->available horas antes"], 409);
        }

        //checar que el hoyo exista 

        $hole_exist = Hole::find($request->hole_id);

        if (!$hole_exist) {
            return response()->json(["error" => true, "message" => "El hoyo ingresado no es valido"], 409);
        }

        return parent::_store($request);
    }

    public function reservation_register($id, Request $request){

        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(["error" => true, "message" => "Reservacion no encontrada"], 404); 
        }

        $teetime = Teetime::find($reservation->teetime_id);

        //checar que no se haya excedido los 5 minutos

        $now = Carbon::now();
        $check = Carbon::createFromFormat('Y-m-d H:i:s', $reservation->updated_at);
        $check->addMinutes(5);

        if ($now > $check) {

            $reservation->status = 'no reservado';

            $reservation->update();

            return response()->json(["error" => true, "message" => "Se excedieron los 5 minutos para registrar la reservación"], 409);    
        }

        //checar tiempo de disponibilidad para registrar una reservacion

        $now = Carbon::now(env('APP_TIMEZONE'));
         
        $date = $reservation->date . ' '. $reservation->start_hour;
        
        $final = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));

        $final->subHours($teetime->available);

        //se usa para comprobar que se esta haciendo la reservacion con el tiempo previo de disponibilidad de la programacion
        if ($now->greaterThan($final)) {
            return response()->json(["error" => true, "message" => "La fecha ingresada no cumple con el tiempo de disponibilidad"], 409);
        }


        //checar numero de jugadores
        $number_players = 1;

        if (isset($request->partners)) {
            $number_players = $number_players + $this->countPlayers($request->partners);
        }

        if (isset($request->guests)) {
            $number_players = $number_players + $this->countPlayers($request->guests);
        }

        if (isset($request->guests_email)) {
            $guests_email = explode(',', $request->guests_email);
            $number_players = $number_players + $this->countPlayers($guests_email);
        }
        

        if ($number_players > $teetime->max_capacity) {
            return response()->json(["error" => true, "message" => "El número máximo de jugadores es $teetime->max_capacity"], 409);
        }elseif ($number_players < $teetime->min_capacity) {
            return response()->json(["error" => true, "message" => "El número mínimo de jugadores es $teetime->min_capacity"], 409);
        }

        // checar número de juegos en el mes de los invitados

        if ($request->guests != NULL) {
            foreach ($request->guests as $guest) {
                $guest_exist = Guest::find($guest);
                
                if (!$guest_exist) {
                    return response()->json(["error" => true, "message" => "Un invitado ingresado no es valido"], 409);
                }
                // checar reinicio de mes
                if ($guest_exist->games_number_month > 0) {
                    $now = Carbon::now();
                    $month = $now->month;
                    if ($guest_exist->month_last_game != $month) {
                        $guest_exist->games_number_month = 0;
                        $guest_exist->month_last_game = $month;
                    }
                }
    
                if ($guest_exist->games_number_month >= 2) {
    
                    return response()->json(["error" => true, "message" => "El invitado $guest_exist->full_name ya ha superado el limite de juegos por mes"], 409);
                }
    
    
                $now = Carbon::now();
                $guest_exist->month_last_game = $now->month;
                $guest_exist->games_number = $guest_exist->games_number + 1;
                $guest_exist->games_number_month = $guest_exist->games_number_month + 1;
    
                $guest_exist->save();
                
            }
        }

        

        // comienza a guardar los datos
        try{
            DB::beginTransaction();
            $this->object = $this->repository->reservation_register($id, $request);
            DB::commit();
            if($this->object){
                Log::info('Registrado');
                return response()->json([
                    "status" => 201,
                    $this->name => $this->object],
                    201);
            }
        }catch (\Exception $e){
            DB::rollBack();
            return $this->errorException($e);
        }
        
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

    public function take($id, Request $request){

        $reservation = Reservation::find($id);

        if ($reservation->status == 'reservado' || $reservation->status == 'registrado') {
            return response()->json(["error" => true, "message" => "La hora ingresada ya ha sido ocupada por otro cliente"], 409);
        }

        try {

            $this->object = $this->repository->_show($id);

            if (!$this->object) {
                return response()->json(['status' => 404,
                    'message' => $this->name . ' no existe'
                ], 404);
            }

            if (!$this->repository->take($id, $request)){
                return response()->json([
                    'message'=>'No se pudo Modificar',
                    $this->name => $this->object
                ], 200);
            }

            return response()->json([
                'status' => 200,
                'message'=>'Reservado con exito',
                $this->name=> $request->all()
            ], 200)->setStatusCode(200, "Registro Actualizado");

        }catch(\Exception $e){
            return $this->errorException($e);
        }
      
    }

    private function countPlayers($guests = null){


        if ($guests != null) {
            $number = count($guests);
        }

        return $number;

    }
}