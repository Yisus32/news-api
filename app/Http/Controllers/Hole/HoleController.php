<?php

namespace App\Http\Controllers\Hole;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Hole\HoleService;
/** @property HoleService $service */
class HoleController extends CrudController
{
    public function __construct(HoleService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            'code' => 'required',
            'name' => 'required'
            ];
    
        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }
}