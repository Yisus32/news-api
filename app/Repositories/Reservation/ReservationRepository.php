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
use App\Http\Mesh\UserService;
use App\Models\TempData;

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
            $reservation['created_at'] = Carbon::parse($reservation['created_at'])->format('Y-m-d',env('APP_TIMEZONE'));
            $reservation['updated_at'] = Carbon::parse($reservation['created_at'])->format('Y-m-d',env('APP_TIMEZONE'));
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
            
            $stored = parent::_store($data);
            
            $this->model->createInvitation($stored);
            
            return $stored;
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

            return response()->json(['status'=>200,'message'=>'La reservacion identificada con el id '.$reservation->id.' ha sido cancelada']);
        }else{
            return response()->json(['status'=>400,'message'=>'El tiempo para cancelar la reserva ha expirado'],400);
        }
    }

    public function checkCapacity($partners,$guests,$guests_email,$teetime_id){
        $teetime = Teetime::where('id',$teetime_id)->first();

        if ($guests_email != null) {
            $guests_email = explode(' ', $guests_email);
        }
        
        if ($teetime) {
             $partners = count($partners);
             $guests_email == "" ? $guests_email = [] : $guests_email;
             $guests = count($guests) + count($guests_email);
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
            return response()->json(['status'=>404,'message'=>'Puede que la programacion no exista o no incumple con los parametros'],404);
        }
    }


    public function resendInvitation($id, $reservation_id, Request $request){
        $type = $request->type;
        $exist_mail = Guest::where('email',$request->email)->first();

        $invitation = Invitation::select(['reservations.owner_name',
                                          'teetimes.start_hour as teetime_start_hour',                           
                                          'teetimes.start_date as teetime_start_date',
                                          'invitations.id as invitation_id',
                                          'reservations.owner as reservation_owner_id',
                                          'reservations.owner_name as reservation_owner_name'])
                                ->where('invitations.reservation_id',$reservation_id)
                                ->when($type == 'partner', function ($query,$request) use ($id) {
                                        
                                        $query->where('invitations.partner',$id);
                                    })
                                ->when($type == 'guest', function ($query,$request) use ($id) {
                                      
                                        $query->where('invitations.guest',$id);
                                    })
                                ->leftjoin('reservations','reservations.id','=','invitations.reservation_id')
                                ->leftjoin('teetimes','teetimes.id','=','reservations.teetime_id')
                                ->first();
            

        if ($exist_mail) {
            $receipt_url = 'https://qarubick2teetime.zippyttech.com/accept/invitation/'.$invitation->invitation_id;
        }else{
            
            $email = $request->email;
            $owner_id = $invitation->reservation_owner_id;
            $owner_number = explode(' ', $invitation->reservation_owner_name)[0];
            $owner_name = explode(' ', $invitation->reservation_owner_name)[1];
             
            $receipt_url = 'https://qarubick2.zippyttech.com/guest/register-guest/%20/'.$email.'/'.$owner_id.'/'.$owner_name.'/'.$owner_number.'/'.$reservation_id;
        }

        $date = $invitation->teetime_start_date;
        $time = $invitation->teetime_start_hour;
        $name = $request->name ?? 'invitado';
        $partner = $invitation->owner_name;
        $subject = "Invitación Teetime";
        
        $message = "Estimado $name, el socio $partner lo ha invitado a un juego en el club de golf de Panamá el día ". 
                    Carbon::parse($date)->format('d-m-Y')." a las ".Carbon::parse($time)->format('h:i A').". 
                    Para aceptar la solicitud solo debe hacer click al siguiente enlace <br> <br> <a href='".
                    $receipt_url."' target='_blank'>Haga click para aceptar la invitación</a>";
        

        if (filter_var($request->email,FILTER_VALIDATE_EMAIL)) {
            $mailer = new NotificationService;
            $mailer->sendEmail($request->email,$subject,$message,6,"notificaciones@zippyttech.com");
        }

        return response()->json(["status"=>200,"message"=>"La invitacion ha sido reenviado"],200);

    }

    public function standByTeetime($id,$hole_id){
        
        try {
        $temp_data = new TempData();
        $temp_data->teetime_id = $id;
        $temp_data->hole_id = $hole_id;
        $temp_data->created_at = Carbon::now(env('APP_TIMEZONE'));
        $temp_data->save();

        return response()->json(['status'=>200, 'message'=>'se apartado la reserva por 5 minutos']);   
        
        } catch (\Exception $e) {
            return response()->json(['status'=>400,'message'=>'Este espacio está siendo registrado por otro usuario']);   
        }
    }

    public function restartTeetime($id,$hole_id){
        
        $temp_data = TempData::where('teetime_id',$id)
                             ->where('hole_id',(integer)$hole_id)
                             ->first();
        if ($temp_data) {
            $temp_data->delete();
            return response()->json(['status' => 200, 'message' => 'El espacio está disponible nuevamente']);    
        }else{
            return response()->json(['status'=>400,'message'=>'Verifique el id del teetime']);
        }   
    }
}