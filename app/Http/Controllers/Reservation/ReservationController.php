<?php

namespace App\Http\Controllers\Reservation;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Reservation\ReservationService;
use Illuminate\Support\Facades\Validator;

/** @property ReservationService $service */
class ReservationController extends CrudController
{
    protected $validateRegister = [];

    public function __construct(ReservationService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            'teetime_id' => 'required', 
            'hole_id' => 'required',
 //           'date' => 'required',
   //         'start_hour' => 'required',
     //       'end_hour' => 'required',
            'owner' => 'required'
        ];

        $this->validateRegister = [
            'owner' => 'required',
            'status' => 'required'
        ];

        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }

    public function take($id,Request $request){

        return $this->service->take($id, $request);
    }

    public function reservation_register($id, Request $request){

        $validator = Validator::make($request->all(), array_merge($this->validateRegister, $this->validateDefault),$this->messages);
        if ($validator->fails()) {
            return response()->json(["error"=>true,"message"=>$this->parseMessageBag($validator->getMessageBag())],422);
        }
        return $this->service->reservation_register($id, $request);
    }
}