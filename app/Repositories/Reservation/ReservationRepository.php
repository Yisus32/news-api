<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Reservation;

use App\Core\CrudRepository;
use App\Core\ReportService;
use App\Models\Reservation;
use App\Models\Teetime;
use Carbon\Carbon;
use Illuminate\Http\Request;

/** @property Reservation $model */
class ReservationRepository extends CrudRepository
{

    public function __construct(Reservation $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        $reservations = parent::_index($request, $user);

        foreach ($reservations as $reservation) {
            $teetime = Teetime::find($reservation->teetime_id);

            $date = $reservation->date . " " . $reservation->start_hour;
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
            $reservation->available_time = $start->subHours($teetime->available);

            $date = $reservation->date . " " . $reservation->start_hour;
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
            $reservation->cancel_time = $start->subHours($teetime->cancel_time);
        }

        return $reservations;
    }

    public function _show($id)
    {
        $reservation = parent::_show($id);

        $teetime = Teetime::find($reservation->teetime_id);

        $date = $reservation->date . " " . $reservation->start_hour;
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
        $reservation->available_time = $start->subHours($teetime->available);

        $date = $reservation->date . " " . $reservation->start_hour;
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $date, env('APP_TIMEZONE'));
        $reservation->cancel_time = $start->subHours($teetime->cancel_time);

        return $reservation;

    }

    public function _store(Request $data)
    {
        if (isset($data["partners"])){
            $data["partners"] = $this->model->formatTypeArray($data["partners"]);
        }
        if (isset($data["guests"])){
            $data["guests"] = $this->model->formatTypeArray($data["guests"]);
        }
        $reservation = parent::_store($data);
        
        return  $reservation;
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
        if($reservation)
            return $reservation;
        else
            return null;
    }

}