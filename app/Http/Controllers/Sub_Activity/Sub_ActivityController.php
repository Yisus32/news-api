<?php

namespace App\Http\Controllers\Sub_Activity;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Sub_Activity\Sub_ActivityService;
/** @property Sub_ActivityService $service */
class Sub_ActivityController extends CrudController
{
    public function __construct(Sub_ActivityService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            'name' => 'required'
        ];

        $this->messages = [
            'name.required' => 'El nombre de la sub actividad es requerido.'

        ];
    }
}