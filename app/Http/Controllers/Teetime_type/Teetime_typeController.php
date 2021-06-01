<?php

namespace App\Http\Controllers\Teetime_type;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Teetime_type\Teetime_typeService;
/** @property Teetime_typeService $service */
class Teetime_typeController extends CrudController
{
    public function __construct(Teetime_typeService $service)
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