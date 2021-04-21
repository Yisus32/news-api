<?php

namespace App\Http\Controllers\Schedule;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Schedule\ScheduleService;
/** @property ScheduleService $service */
class ScheduleController extends CrudController
{
    public function __construct(ScheduleService $service)
    {
        parent::__construct($service);
    }
}