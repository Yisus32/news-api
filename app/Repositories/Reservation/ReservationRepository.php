<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Reservation;

use App\Core\CrudRepository;
use App\Core\ReportService;
use App\Jobs\GuestEmail;
use App\Models\Invitation;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Teetime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use App\Http\Mesh\NotificationService;

//use Illuminate\Queue\Queue;
//use Illuminate\Support\Facades\Queue as FacadesQueue;
//commit de reposicion

/** @property Reservation $model */
class ReservationRepository extends CrudRepository
{

    public function __construct(Reservation $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null){
        $owner = $request->owner;
        $reservations = Reservation::select(['reservations.*', 
                                      'holes.name as hole_name', 
                                      'teetimes.max_capacity', 
                                      'teetimes.min_capacity', 
                                      'teetimes.start_date as teetime_date_start', 
                                      'teetimes.end_date as teetime_date_end',
                                      'teetimes.start_hour as teetime_hour_start', 
                                      'teetimes.end_hour as teetime_hour_end',
                                      'teetimes.cancel_time as teetime_cancel_time'])
                                ->when($owner, function ($query,$owner) {
                                    return $query->where('reservations.owner',$owner);
                                })
                                ->join('holes', 'holes.id', '=', 'reservations.hole_id')
                                ->join('teetimes', 'teetimes.id', '=', 'reservations.teetime_id')
                                ->get();

        foreach ($reservations as $reservation) {
            $reservation['guests'] = json_decode($reservation['guests']);
            $reservation['partners'] = json_decode($reservation['partners']);
            $reservation['partners_name'] = json_decode($reservation['partners_name']);
            
            $reservation['teetime_cancel_time'] = $this->model->setCancelDate(
            $reservation['teetime_date_start'], $reservation['teetime_hour_start'],
            $reservation['teetime_cancel_time']);
        }

        return $reservations;
    }

    public function _store(Request $data){

        $data['guests'] = json_encode($data['guests']);
        $data['partners'] = json_encode($data['partners']);
        $data['partners_name'] = json_encode($data['partners_name']);
        $data['status'] = 'registrado';

        $check = $this->model->checkPartners($data['owner'], $data['partners']);

        if (is_int($check)) {
            return response()->json(['status'=>400, 'message'=> 'El dueño del juego no puede ser seleccionado como socio'],400);
        }else {
            return parent::_store($data);
        }  
    }

    public function _update($id, $data){
        $data['guests'] = json_encode($data['guests']);
        $data['partners'] = json_encode($data['partners']);
        $data['partners_name'] = json_encode($data['partners_name']);

        $check = $this->model->checkPartners($data['owner'], $data['partners']);

        if (is_int($check)) {
            return response()->json(['status'=>400, 'message'=> 'El dueño del juego no puede ser seleccionado como socio'],400);
        }else {
            return parent::_update($id,$data);
        }  
    }

    public function _delete($id){
        return parent::_delete($id);
    }
    
    //Por culminar
    public function cancelReservation($id){
        $reservation = Reservation::select(['reservations.*',
                                            'teetimes.cancel_time as teetime_cancel_time',
                                            'teetimes.start_date as teetime_start_date',
                                            'teetimes.start_hour as teetime_start_hour'])
                                    ->join('teetimes', 'teetimes.id', '=', 'reservations.teetime_id')
                                    ->where('reservations.id',$id)
                                    ->first();
       
        

        $cancel_time = $this->model->setCancelDate($reservation['teetime_start_date'],
                                                   $reservation['teetime_start_hour'],
                                                   $reservation['teetime_cancel_time']);


        $now = Carbon::now(env('APP_TIMEZONE'));

        if ($now < $cancel_time) {
            $reservation->status = 'cancelado';
            $reservation->save();

            return $reservation;
        }else{
            return response()->json(['status'=>400,'message'=>'El tiempo para cancelar la reserva a expirado'],400);
        }
    }

    public function checkCapacity($partners,$guests,$teetime_id){
        $teetime = Teetime::where('id',$teetime_id)->first();

        if ($teetime) {
             $partners = count($partners);
             $guests = count($guests);
             $players = $partners + $guests + 1;
        
            switch ($players) {
                case $players > $teetime['max_capacity']:
                    return response()->json(['status'=>400,'message'=>'La cantidad de jugadores es mayor a la capacidad maxima'],400);
                    break;

                case $players < $teetime['min_capacity']:
                    return response()->json(['status'=>400,'message'=>'La cantidad de jugadores es menor a la capacidad minima'],400);
                    break;
                
                default:
                    return 1;
                    break;
            }
        }else{
            return response()->json(['status'=>404,'message'=>'La programacion no existe o no tiene definidos todos los parametros'],404);
        }
    }
}