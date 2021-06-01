<?php

namespace App\Http\Controllers\Teetime;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Teetime\TeetimeService;
/** @property TeetimeService $service */
class TeetimeController extends CrudController
{
    public function __construct(TeetimeService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            'type_id' => 'required', 
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
}