<?php

namespace App\Http\Controllers\game_log;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\game_log\game_logService;
/** @property game_logService $service */
class game_logController extends CrudController
{
    public function __construct(game_logService $service)
    {
        parent::__construct($service);
        
        $this->validateStore = [
            'user_id' => 'required',
            'car_id' => 'required',
            'hol_id' => 'required',
            'gro_id' => 'required'
            ];
    
        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }
}