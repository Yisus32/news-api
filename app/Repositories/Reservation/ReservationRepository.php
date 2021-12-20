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
                                ->with('invitations')
                                ->get();

        foreach ($reservations as $reservation) {
            $reservation['guests'] = json_decode($reservation['guests']);
            $reservation['partners'] = json_decode($reservation['partners']);
            $reservation['partners_name'] = json_decode($reservation['partners_name']);
            
            $reservation['teetime_cancel_time'] = $this->model->setCancelDate(
            $reservation['teetime_date_start'], $reservation['teetime_hour_start'],
            $reservation['teetime_cancel_time']);
            $reservation['created_at'] = Carbon::parse($reservation['created_at'])->format('Y-m-d H:i:s',env('APP_TIMEZONE'));
            $reservation['updated_at'] = Carbon::parse($reservation['created_at'])->format('Y-m-d H:i:s',env('APP_TIMEZONE'));
            $reservation['teetime_date_start'] = Carbon::parse($reservation['teetime_date_start'].' '.$reservation['teetime_hout_start'])->format('d-m-Y h:i A');
        }

        return $reservations;
    }

    public function _show($id){
        $reservations = Reservation::select(['reservations.*', 
                                      'holes.name as hole_name', 
                                      'teetimes.max_capacity', 
                                      'teetimes.min_capacity', 
                                      'teetimes.start_date as teetime_date_start', 
                                      'teetimes.end_date as teetime_date_end',
                                      'teetimes.start_hour as teetime_hour_start', 
                                      'teetimes.end_hour as teetime_hour_end',
                                      'teetimes.cancel_time as teetime_cancel_time'])
                                ->where('reservations.id',$id)
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
        
        $operator = $data->header("id");
        $is_admin = $data->header("is_admin");

        $check = $this->model->checkPartners($data['owner'], $data['partners']);
        $check2 = $this->model->isAdmin($operator,$data['owner'],$data['partners'],$data['guests']);

        if (is_int($check)) {

            return response()->json(['status'=>400, 'message'=> 'El dueño del juego no puede ser seleccionado como socio'],400);

        }elseif(is_int($check2) && $is_admin){

            return response()->json(['status'=>400, 'message'=> 'El usuario administrador no puede participar en la partida'],400);

        }else {
            
            $checkingExistence = Reservation::where('teetime_id',$data["teetime_id"])
                                            ->where('hole_id',$data["hole_id"])
                                            ->where('date',$data["date"])
                                            ->where('start_hour',$data["start_hour"])
                                            ->first();

         
            if (!$checkingExistence) {
                $stored = parent::_store($data);
            
                $this->model->createInvitation($stored);

                $this->multiSendInvitation($stored);
            
                return response()->json(['status' => 200, 'stored' => $stored]);
            }else{
               return response()->json(['status'=>400, 'message'=> 'Usted está reservando un espacio que ya no se encuentra disponible, por favor refresque la página'],400); 
            }
            
        }  
    }

    public function _update($id, $data){
        $data['guests'] = json_encode($data['guests']);
        $data['partners'] = json_encode($data['partners']);
        $data['partners_name'] = json_encode($data['partners_name']);

        $operator = $data->header("id");
        $is_admin = $data->header("is_admin");

        $check = $this->model->checkPartners($data['owner'], $data['partners']);
        $check2 = $this->model->isAdmin($operator,$data['owner'],$data['partners'],$data['guests']);

        if (is_int($check)) {

            return response()->json(['status'=>400, 'message'=> 'El dueño del juego no puede ser seleccionado como socio'],400);

        }elseif(is_int($check2) && $is_admin){

            return response()->json(['status'=>400, 'message'=> 'El usuario administrador no puede participar en la partida'],400);

        }else {

            $checkingExistence = Reservation::where('teetime_id',$data["teetime_id"])
                                            ->where('hole_id',$data["hole_id"])
                                            ->where('date',$data["date"])
                                            ->where('start_hour',$data["start_hour"])
                                            ->first();
            
            if ((isset($checkingExistence) && $checkingExistence->id == $id) || !$checkingExistence) {
                $updated = parent::_update($id,$data);

                $this->model->createInvitation($updated);

                $this->multiSendInvitation($updated);

                return response()->json(['status' => 200, 'stored' => $updated]);
            }else{
               return response()->json(['status'=>400, 'message'=> 'Usted está reservando un espacio que ya no se encuentra disponible, por favor refresque la página'],400); 
            }
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
            $guests_email = explode(',', $guests_email);
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

    public function multiSendInvitation($stored){
         
         $pattern = "/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i";
         $mailer = new NotificationService;

        if ($stored['partners_name'] != null && $stored['partners_name'] != '""') {
            foreach (explode(',',$stored['partners_name']) as $partner) {
             preg_match ($pattern,$partner,$matches);
             $emails[] = $matches[0];
            }

        }

        if ($stored['guests_name'] != null && $stored['guests_name'] != '""') {     
             foreach (explode(',',$stored['guests_name']) as $guest) {
             preg_match ($pattern,$guest,$matches);
             $emails[] = $matches[0];
            }
        }

        if ($stored['guests_email'] != null && $stored['guests_email'] != '""') {     
             foreach (explode(',',$stored['guests_email']) as $_guest) {
             $_guests[] = $_guest;
            }
        }

        if (isset($emails)) {
            foreach ($emails as $email){
                $invitation = Invitation::where('reservation_id',$stored->id)
                                    ->where('guest_email',$email)
                                    ->first();
        
                $receipt_url = env('APP_URL').'/api/accept/invitation/'.$invitation->id;

                $subject = "invitacion a teetime";
                $message = "Estimado $email, el socio $stored->owner_name lo ha invitado a un juego en el club de golf de Panamá el día ".Carbon::parse($stored->date)->format('d-m-Y')." a las ".Carbon::parse($stored->start_hour)->format('h:i A').". Para aceptar la solicitud solo debe hacer click al siguiente enlace <br> <br> <a href='".
                    $receipt_url."' target='_blank'>Haga click para aceptar la invitación</a>";
        
                
                if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
                    $mailer->sendEmail($email,$subject,$message,6,"notificaciones@zippyttech.com");
                }
            }   
        }

        if (isset($_guests)) {
            foreach ($_guests as $other){
                $invitation = Invitation::where('reservation_id',$stored->id)
                                    ->where('guest_email',$other)
                                    ->first();
        
                $email = $other;
                $_owner_id = $stored->owner;
                $_owner_number = explode(' ', $stored->owner_name)[0];
                $_owner_name = explode(' ', $stored->owner_name)[1];
                $reservation_id = $stored->id;

                $receipt_url = 'https://'.env('FRONT_URL').'/guest/register-guest/%20/'.$email.'/'.$_owner_id.'/'.$_owner_name.'/'.$_owner_number.'/'.$reservation_id;

                $subject = "invitacion a teetime";
                $message = "Estimado $email, el socio $stored->owner_name lo ha invitado a un juego en el club de golf de Panamá el día ".Carbon::parse($stored->date)->format('d-m-Y')." a las ".Carbon::parse($stored->start_hour)->format('h:i A').". Para aceptar la solicitud solo debe hacer click al siguiente enlace <br> <br> <a href='".
                    $receipt_url."' target='_blank'>Haga click para aceptar la invitación</a>";
        
               
                if (filter_var($other,FILTER_VALIDATE_EMAIL)) {
                    $mailer->sendEmail($other,$subject,$message,6,"notificaciones@zippyttech.com");
                }
            } 

        }
    }

    public function resendInvitation($reservation_id, Request $request){
    
        $type = $request->type;
        $exist_mail = Guest::where('email',$request->email)->first();
        
        if ($exist_mail) {
            $invitation = Invitation::where('reservation_id',$reservation_id)
                                    ->where('guest_email',$request->email)
                                    ->first();
        
            $receipt_url = 'https://'.env('APP_URL').'/api/accept/invitation/'.$invitation->id;
        }else{
            
            $email = $request->email;
            $_owner_id = $request->owner_id;
            $_owner_number = explode(' ', $request->owner_name)[0];
            $_owner_name = explode(' ', $request->owner_name)[1];
             
            $receipt_url = 'https://'.env('FRONT_URL').'/guest/register-guest/%20/'.$email.'/'.$_owner_id.'/'.$_owner_name.'/'.$_owner_number.'/'.$reservation_id;
        }

        $date = $request->teetime_start_date;
        $time = $request->teetime_start_hour;
        $name = $request->name ?? 'invitado';
        $partner = $request->owner_name;
        $hole = $request->hole_name;
        $subject = "Invitación Teetime";
        
        $message = "Estimado $name, el socio $partner lo ha invitado a un juego en el club de golf de Panamá
                    en el $hole el día ".Carbon::parse($date)->format('d-m-Y')." a las ".Carbon::parse($time)->format('h:i A').". Para aceptar la solicitud solo debe hacer click al siguiente enlace <br> <br> <a href='".
                    $receipt_url."' target='_blank'>Haga click para aceptar la invitación</a>";
        
                
        if (filter_var($request->email,FILTER_VALIDATE_EMAIL)) {
            $mailer = new NotificationService;
            $mailer->sendEmail($request->email,$subject,$message,6,"notificaciones@zippyttech.com");
        }

        return response()->json(["status"=>200,"message"=>"La invitacion ha sido reenviado"],200);

    }

    public function standByTeetime(Request $request, $id,$hole_id){
        
        $date = str_replace('-','',$request->date);
        $time = str_replace(':','',$request->start_hour);

        try {
        $temp_data = new TempData();
        $temp_data->teetime_id = $id;
        $temp_data->hole_id = $hole_id;
        $temp_data->ref_data = $date.''.$time; 
        $temp_data->created_at = Carbon::now(env('APP_TIMEZONE'));
        $temp_data->save();

        return response()->json(['status'=>200, 'message'=>'se apartado la reserva por 5 minutos'],200);   
        
        } catch (\Exception $e) {
            return response()->json(['status'=>400,'message'=>'Este espacio está siendo registrado por otro usuario'],400);   
        }
    }

    public function restartTeetime(Request $request,$id,$hole_id){
        
        $date = str_replace('-','',$request->date);
        $time = str_replace(':','',$request->start_hour);
        $ref_data = $date.''.$time;
        
       
        $temp_data = TempData::where('teetime_id',$id)
                             ->where('hole_id',(integer)$hole_id)
                             ->where('ref_data',$ref_data)
                             ->first();

        if ($temp_data) {
            $temp_data->delete();
            return response()->json(['status' => 200, 'message' => 'El espacio está disponible nuevamente'],200);    
        }else{
            return response()->json(['status'=>400,'message'=>'Verifique el id del teetime'],400);
        }   
    }

    public function advanceFilter(Request $request){
        $teetime  = Reservation::select(['reservations.*', 
                                      'holes.name as hole_name', 
                                      'teetimes.max_capacity', 
                                      'teetimes.min_capacity', 
                                      'teetimes.start_date as teetime_date_start', 
                                      'teetimes.end_date as teetime_date_end',
                                      'teetimes.start_hour as teetime_hour_start', 
                                      'teetimes.end_hour as teetime_hour_end',
                                      'teetimes.cancel_time as teetime_cancel_time'])
                                ->leftjoin('teetimes','teetimes.id','=','reservations.teetime_id')
                                ->leftjoin('holes', 'holes.id', '=', 'reservations.hole_id')
                                ->when($request->t_date, function ($query,$t_date){
                                    $start_date = explode('_', $t_date)[0];
                                    $end_date = explode('_', $t_date)[1];

                                    return $query->where('teetimes.start_date','>=',$start_date)
                                                 ->where('teetimes.start_date','<=',$end_date);

                                })
                                ->when($request->r_date, function ($query,$r_date){
                                    $r_start_date = explode('_', $r_date)[0];
                                    $r_end_date = explode('_',$r_date)[1];

                                    return $query->where('reservations.created_at','>=',$r_start_date.' 00:00:00')
                                                 ->where('reservations.created_at','<=',$r_end_date.' 23:59:59');
                                })
                                ->when($request->owner, function ($query,$owner) {
                                    return $query->where('reservations.owner',$owner);
                                })
                                ->when($request->partner, function ($query, $partner) {
                                    return $query->where('reservations.partners_name','ILIKE','%'.$partner.'%');
                                })
                                ->when($request->guest, function ($query,$guest) {
                                    return $query->where('reservations.guests_name','ILIKE','%'.$guest.'%')
                                                 ->orWhere('reservations.guests_email','ILIKE','%'.$guest.'%');
                                })
                                ->with('invitations')
                                ->get();

            foreach ($teetime as $t) {
                $t['teetime_cancel_time'] = $this->model->setCancelDate(
                $t['teetime_date_start'], $t['teetime_hour_start'],
                $t['teetime_cancel_time']);
            }
            
            

        return ["list"=>$teetime,"total"=>count($teetime)];

    }
}