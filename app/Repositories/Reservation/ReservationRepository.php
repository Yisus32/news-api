<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Reservation;

use App\Core\CrudRepository;
use App\Models\Reservation;
use Illuminate\Http\Request;

/** @property Reservation $model */
class ReservationRepository extends CrudRepository
{

    public function __construct(Reservation $model)
    {
        parent::__construct($model);
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