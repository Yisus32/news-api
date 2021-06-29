<?php

namespace App\Http\Controllers\cars_golf;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\cars_golf\cars_golfService;
/** @property cars_golfService $service */
class cars_golfController extends CrudController
{
    public function __construct(cars_golfService $service)
    {
        parent::__construct($service);
    }
}