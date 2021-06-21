<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Teetime;


use App\Core\CrudService;
use App\Models\Hole;
use App\Models\Reservation;
use App\Models\Teetime;
use App\Repositories\Teetime\TeetimeRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

/** @property TeetimeRepository $repository */
class TeetimeService extends CrudService
{

    protected $name = "teetime";
    protected $namePlural = "teetimes";

    public function __construct(TeetimeRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        if (isset($request->time_interval)) {
            if (($request->time_interval % 5) != 0) {
                return response()->json(['error' => true, 'message' => 'El intervalo de tiempo debe ser multiplo de 5'],400);
            }
        }

        foreach ($request->target as $hole) {
            $hole_exist = Hole::find($hole);
            if (!$hole_exist) {
                return response()->json(['error' => true, 'message' => "El hoyo con id $hole no existe"],400);
            }
        }


        return parent::_store($request);
    }

    public function _update($id, Request $request)
    {
        if (isset($request->time_interval)) {
            if (($request->time_interval % 5) != 0) {
                return response()->json(['error' => true, 'message' => 'El intervalo de tiempo debe ser multiplo de 5'],400);
            }
        }
        $reservation_exist = Reservation::where('teetime_id', '=', "$id")->where("status", "=", "registrado")->first();
        if ($reservation_exist) {
            return response()->json(['error' => true, "message" => 'Existen reservaciones asociadas a la programación'],409);
        }
        
        return parent::_update($id, $request);
    }

    public function _delete($id)
    {   
        $reservation_exist = Reservation::where('teetime_id', '=', "$id")->where("status", "=", "registrado")->first();
        if ($reservation_exist) {
            return response()->json(['error' => true, "message" => 'Existen reservaciones asociadas a la programación'],409);
        }

        $teetime = Teetime::find($id);
        $teetime->reservations()->delete();
        $teetime->break_times()->delete();

        return parent::_delete($id);
    }

}