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
use App\Models\Teetime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use App\Http\Mesh\NotificationService;

//use Illuminate\Queue\Queue;
//use Illuminate\Support\Facades\Queue as FacadesQueue;

/** @property Reservation $model */
class ReservationRepository extends CrudRepository
{

    public function __construct(Reservation $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        $query = Reservation::select(['reservations.*', 'holes.name as hole_name', 'teetimes.max_capacity', 
                    'teetimes.min_capacity', 'teetimes.start_date as teetime_date_start', 'teetimes.end_date as teetime_date_end',
                    'teetimes.start_hour as teetime_hour_start', 'teetimes.end_hour as teetime_hour_end',
                    DB::raw('array_agg(guests.full_name) as guests_fullname'), DB::raw('array_agg(guests.email) as saved_guests_email')])
                ->join('holes', 'holes.id', '=', 'reservations.hole_id')
                ->join('teetimes', 'teetimes.id', '=', 'reservations.teetime_id')
                ->Leftjoin('guests', 'guests.id', '=', DB::raw("ANY(reservations.guests)"))
                ->groupBy('reservations.id')
                ->groupBy('holes.name')        
                ->groupBy('teetimes.id')
                ->orderBy('reservations.id');

        if ($request->header('role') == "admin") {
            $reservations = $query->get();
                   
        }else{
            if (!isset($request->owner)) {
                abort(400, "el id del usuario es requerido");
            }
            $reservations = $query->where('owner', '=', "$request->owner")
                            ->get();
        }
        
        if (count($reservations) > 0) {
            foreach ($reservations as $reservation) {
                $teetime = Teetime::find($reservation->teetime_id);
    
                $date = $reservation->date . " " . $reservation->start_hour;
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
                $reservation->available_time = $start->subHours($teetime->available)->format('Y-m-d H:i:s');
    
                $date = $reservation->date . " " . $reservation->start_hour;
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
                $reservation->cancel_time = $start->subHours($teetime->cancel_time)->format('Y-m-d H:i:s');
    
                $partners = $reservation->partners;
                $partners = str_replace("{", '', $partners);
                $partners = str_replace("}", '', $partners);
                $partners = explode(',', $partners);
                $reservation->partners = $partners;
    
                $guests = $reservation->guests;
                $guests = str_replace("{", '', $guests);
                $guests = str_replace("}", '', $guests);
                $guests = explode(',', $guests);
                $reservation->guests = $guests;

                $guests_fullname = $reservation->guests_fullname;
                $guests_fullname = str_replace("{", '', $guests_fullname);
                $guests_fullname = str_replace("}", '', $guests_fullname);
                $guests_fullname = explode(',', $guests_fullname);
                $reservation->guests_fullname = $guests_fullname;

                $saved_guests_email = $reservation->saved_guests_email;
                $saved_guests_email = str_replace("{", '', $saved_guests_email);
                $saved_guests_email = str_replace("}", '', $saved_guests_email);
                $saved_guests_email = explode(',', $saved_guests_email);
                $reservation->saved_guests_email = $saved_guests_email;

                $reservation->teetime_start = $reservation->teetime_date_start . ' '. $reservation->teetime_hour_start;
                $reservation->teetime_end = $reservation->teetime_date_end . ' '. $reservation->teetime_hour_end;
                    
            }
    
        }
        
        return $reservations;
    }

    public function _show($id)
    {

        $reservation = Reservation::select(['reservations.*', 'holes.name as hole_name', 'teetimes.max_capacity', 
        'teetimes.min_capacity', 'teetimes.start_date as teetime_date_start', 'teetimes.end_date as teetime_date_end',
        'teetimes.start_hour as teetime_hour_start', 'teetimes.end_hour as teetime_hour_end',
        DB::raw('array_agg(guests.full_name) as guests_fullname'), DB::raw('array_agg(guests.email) as saved_guests_email')])
        ->join('holes', 'holes.id', '=', 'reservations.hole_id')
        ->join('teetimes', 'teetimes.id', '=', 'reservations.teetime_id')
        ->Leftjoin('guests', 'guests.id', '=', DB::raw("ANY(reservations.guests)"))
        ->groupBy('reservations.id')
        ->groupBy('holes.name')        
        ->groupBy('teetimes.id')
        ->find($id);

        if ($reservation) {
            
            $teetime = Teetime::find($reservation->teetime_id);

            $date = $reservation->date . " " . $reservation->start_hour;
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
            $reservation->available_time = $start->subHours($teetime->available)->format('Y-m-d H:i:s');

            $date = $reservation->date . " " . $reservation->start_hour;
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
            $reservation->cancel_time = $start->subHours($teetime->cancel_time)->format('Y-m-d H:i:s');

            $partners = $reservation->partners;
            $partners = str_replace("{", '', $partners);
            $partners = str_replace("}", '', $partners);
            $partners = explode(',', $partners);
            $reservation->partners = $partners;

            $guests = $reservation->guests;
            $guests = str_replace("{", '', $guests);
            $guests = str_replace("}", '', $guests);
            $guests = explode(',', $guests);
            $reservation->guests = $guests;

            $saved_guests_email = $reservation->saved_guests_email;
            $saved_guests_email = str_replace("{", '', $saved_guests_email);
            $saved_guests_email = str_replace("}", '', $saved_guests_email);
            $saved_guests_email = explode(',', $saved_guests_email);
            $reservation->saved_guests_email = $saved_guests_email;

            $reservation->teetime_start = $reservation->teetime_date_start . ' '. $reservation->teetime_hour_start;
            $reservation->teetime_end = $reservation->teetime_date_end . ' '. $reservation->teetime_hour_end;
        }

        return $reservation;

    }

    public function _store(Request $data)
    {
     /*   if (isset($data["partners"])){
            $data["partners"] = $this->model->formatTypeArray($data["partners"]);
        }
        if (isset($data["guests"])){
            $data["guests"] = $this->model->formatTypeArray($data["guests"]);
        }*/

        if (!isset($data->status)) {
            $data->status = "reservado";
            $data["status"] = "reservado";
        }

        if (isset($data->id) and !empty($data->id)) {
            $reservation = Reservation::find($data->id);
            if (!$reservation) {
                abort(409, "id no existe");
            }
            $data = $data->all();
            $data["created_at"] = Carbon::now();
            $reservation->update($data);
            return $reservation;
        }
        
        return parent::_store($data);

    }

    public function _update($id, $data)
    {
        if (isset($data["partners"])){
            $data["partners"] = $this->model->formatTypeArray($data["partners"]);
        }
        if (isset($data["guests"])){
            
            foreach ($data["guests"] as $guest) {
                    $invitation = new Invitation();
                    $invitation->reservation_id = $id;
                    $invitation->guest = $guest;
                    $invitation->save();
                }

            $data["guests"] = $this->model->formatTypeArray($data["guests"]);
        }
        
        $reservation = parent::_update($id, $data);

        return $reservation;
    }

    public function take($id, Request $request){

        $reservation = Reservation::find($id);
        $reservation->status = "reservado";
        $reservation->owner = $request->owner;
        $reservation->update();

        return $reservation;
    }

    public function reservation_register($id, Request $request){

        $reservation = Reservation::findOrFail($id);
        if (isset($request["partners"])){
   
            $request["partners"] = $this->model->formatTypeArray($request["partners"]);
        }
        if (isset($request["guests"])){
            $guest = $request["guests"];
            $request["guests"] = $this->model->formatTypeArray($request["guests"]);
        }

        $data = $request->all();
        $reservation->update($data);
        if($reservation){
         
            Queue::push(new GuestEmail($id));
               
            
            
            return $reservation;
        }else{
            return null;
        }    
    }

    public function resendMail($id, Request $request){
        $reservation = Reservation::select('reservations.id as reservid','guests.id as guestid','guests.email','guests.full_name','reservations.owner_name','reservations.date','reservations.start_hour')
                                    ->leftjoin('guests', 'guests.id', '=', DB::raw("ANY(reservations.guests)"))
                                    ->groupBy('reservations.id','guests.id')
                                    ->where('reservations.id',$id)
                                    ->where('guests.email',$request->email)
                                    ->first();
        
        if ($reservation) {
            $invitation = Invitation::where('reservation_id',$reservation->reservid)
                                ->where('guest',$reservation->guestid)
                                ->first();
    
            $date = $reservation->date;
            $time = $reservation->start_hour;
            $name = $reservation->full_name;
            $partner = $reservation->owner_name;
            $receipt_url = 'https://qarubick2teetime.zippyttech.com/accept/invitation/' . $invitation->id;
            $subject = "Invitación Teetime";
        
            $message = "Estimado $name 
                        El socio $partner lo ha invitado a un juego en el club de golf de Panamá el día ". Carbon::parse($date)->format('d-m-Y')." a las ".Carbon::parse($time)->format('h:i A').". Para aceptar la solicitud solo debe hacer click al siguiente enlace <br> <br> <a href='".$receipt_url."' target='_blank'>Haga click para aceptar la invitación</a>";
        
            if (filter_var($request->email,FILTER_VALIDATE_EMAIL)) {
                $mailer = new NotificationService;
                $mailer->sendEmail($request->email,$subject,$message,6,"notificaciones@zippyttech.com");
            }

            return response()->json(["status" => 200, "message" => "se ha reenviado la invitacion"],200);   
        }else{

            $reservation = Reservation::select('reservations.id as reservid','guests.id as guestid','guests.email','guests.full_name','reservations.owner_name','reservations.date','reservations.start_hour','reservations.owner','reservations.hole_id')
                                    ->leftjoin('guests', 'guests.id', '=', DB::raw("ANY(reservations.guests)"))
                                    ->groupBy('reservations.id','guests.id')
                                    ->where('reservations.id',$id)
                                    ->first();
            
            $date = $reservation->date;
            $time = $reservation->start_hour;
            $name = $reservation->full_name;
            $partner = $reservation->owner_name;
            $receipt_url = 'https://qarubick2.zippyttech.com/guest/register-guest/%20/'.$request->email.'/'.$reservation->hole_id.'/'.$reservation->owner_name.'/'.$reservation->owner.'/'.$reservation->reservid;

            $subject = "Invitación Teetime";

            $message = "Usted ha sido invitado por el socio $partner a un juego en el club de golf de Panamá el día ". Carbon::parse($date)->format('d-m-Y')." a las ".Carbon::parse($time)->format('h:i A').". Para aceptar la solicitud debe registrarse en nuestra plataforma <br> <br> <a href='".$receipt_url."' target='_blank'>Haga click aquí para registrarse</a>";

             if (filter_var($request->email,FILTER_VALIDATE_EMAIL)) {
                $mailer = new NotificationService;
                $mailer->sendEmail($request->email,$subject,$message,6,"notificaciones@zippyttech.com");
            }

            return response()->json(["status" => 200, "message" => "se ha reenviado la invitacion"],200);  
        }
    }
}