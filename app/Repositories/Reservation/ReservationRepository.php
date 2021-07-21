<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Reservation;

use App\Core\CrudRepository;
use App\Core\ReportService;
use App\Jobs\GuestEmail;
use App\Models\Reservation;
use App\Models\Teetime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;

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
        if ($request->header('role') == "admin") {
            $reservations = Reservation::select(['reservations.*', 'holes.name as hole_name', 'teetimes.max_capacity', 'teetimes.min_capacity'])
                        ->join('holes', 'holes.id', '=', 'reservations.hole_id')
                        ->join('teetimes', 'teetimes.id', '=', 'reservations.teetime_id')
                        ->groupBy('reservations.id')
                        ->groupBy('holes.name')
                        ->groupBy('teetimes.max_capacity')
                        ->groupBy('teetimes.min_capacity')
                        ->get();
        }else{
            if (!isset($request->owner)) {
                abort(400, "el id del usuario es requerido");
            }
            $reservations = Reservation::select(['reservations.*', 'holes.name as hole_name', 'teetimes.max_capacity', 'teetimes.min_capacity'])
                        ->join('holes', 'holes.id', '=', 'reservations.hole_id')
                        ->join('teetimes', 'teetimes.id', '=', 'reservations.teetime_id')
                        ->groupBy('reservations.id')
                        ->groupBy('holes.name')
                        ->groupBy('teetimes.max_capacity')
                        ->groupBy('teetimes.min_capacity')
                        ->where('owner', '=', "$request->owner")
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
               
            }
    
        }
        
        return $reservations;
    }

    public function _show($id)
    {

        $reservation = Reservation::select(['reservations.*', 'holes.name as hole_name', 'teetimes.max_capacity', 'teetimes.min_capacity'])
                        ->join('holes', 'holes.id', '=', 'reservations.hole_id')
                        ->join('teetimes', 'teetimes.id', '=', 'reservations.teetime_id')
                        ->groupBy('reservations.id')
                        ->groupBy('holes.name')
                        ->groupBy('teetimes.max_capacity')
                        ->groupBy('teetimes.min_capacity')
                        ->where('reservations.id', '=', "$id")
                        ->first();

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
        
        return parent::_store($data);

    }

    public function _update($id, $data)
    {
        if (isset($data["partners"])){
            $data["partners"] = $this->model->formatTypeArray($data["partners"]);
        }
        if (isset($data["guests"])){
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

        if (isset($request["partners"])){
            $request["partners"] = $this->model->formatTypeArray($request["partners"]);
        }
        if (isset($request["guests"])){
            $request["guests"] = $this->model->formatTypeArray($request["guests"]);
        }

        $reservation = Reservation::findOrFail($id);
        $data = $request->all();
        $reservation->update($data);
        if($reservation){
            if ($request->guests_email != null) {
                Queue::push(new GuestEmail($request->guests_email, $id));
               
            }
            
            return $reservation;
        }else{
            return null;
        }
            
    }

}