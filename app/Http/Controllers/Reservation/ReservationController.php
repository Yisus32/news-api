<?php

namespace App\Http\Controllers\Reservation;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Reservation\ReservationService;
/** @property ReservationService $service */
class ReservationController extends CrudController
{
    public function __construct(ReservationService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            'teetime_id' => 'required', 
            'hole_id' => 'required',
            'date' => 'required',
            'start_hour' => 'required',
            'end_hour' => 'required',
            'owner' => 'required'
        ];

        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }
}