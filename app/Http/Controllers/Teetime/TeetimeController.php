<?php

namespace App\Http\Controllers\Teetime;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Teetime\TeetimeService;
use Carbon\Carbon;

/** @property TeetimeService $service */
class TeetimeController extends CrudController
{
    public function __construct(TeetimeService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            'start_date' => 'required',
            'end_date' => 'required',
            'min_capacity' => 'required',
            'max_capacity' => 'required',
            'time_interval' => 'required',
            'available' => 'required',
            'cancel_time' => 'required',
            'start_hour' => 'required',
            'end_hour' => 'required',
            'target' => 'required',
            'days' => 'required'
        ];
        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }

    public function _store(Request $request)
    {
        if (isset($request->target)) {
            if (!is_array($request->target)) {
                return response()->json(["error" => true, "message" => "la variable target debe ser array"], 400);
            }
        }

        if (isset($request->days)) {
            if (!is_array($request->days)) {
                return response()->json(["error" => true, "message" => "la variable days debe ser array"], 400);
            }
        }

        return parent::_store($request);
    }

    public function _update($id, Request $request)
    {
        if (isset($request->target)) {
            if (!is_array($request->target)) {
                return response()->json(["error" => true, "message" => "la variable target debe ser array"], 400);
            }
        }

        if (isset($request->days)) {
            if (!is_array($request->days)) {
                return response()->json(["error" => true, "message" => "la variable days debe ser array"], 400);
            }
        }

        return parent::_update($id, $request);
    }

    public function available(Request $request){

        return $this->service->available($request);
    }

    public function day(Request $request){
        return Carbon::today(env('APP_TIMEZONE'));

        $end = Carbon::createFromFormat('Y-m-d H:i:s', "2021-09-01 08:00:00", env('APP_TIMEZONE'));

        $day_name = array("Sunday","Monday", "Tuesday","Wednesday", "Thursday", "Friday", "Saturday");

        $day_name[5] = "0";

        $day_name[6] = "0";

        return response()->json([$end->format('l'), $day_name], 200);
    }
}