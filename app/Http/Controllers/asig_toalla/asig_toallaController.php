<?php

namespace App\Http\Controllers\asig_toalla;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\asig_toalla;
use App\Services\asig_toalla\asig_toallaService;
use App\Models\toalla;
use DateTime;
/** @property asig_toallaService $service */
class asig_toallaController extends CrudController
{
    public function __construct(asig_toallaService $service)
    {
        parent::__construct($service);
        $this->validateStore = [
            'id_toalla' => 'required',
            'user_id' => 'required'

            ];
    
        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }

    
}