<?php

namespace App\Http\Controllers\Activity;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Activity\ActivityService;
/** @property ActivityService $service */
class ActivityController extends CrudController
{
    public function __construct(ActivityService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            'name' => 'required'

        ];

        $this->messages = [
            'name.required' => 'El nombre de la actividad es requerido.'

        ];
    }
}