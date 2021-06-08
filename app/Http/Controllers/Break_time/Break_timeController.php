<?php

namespace App\Http\Controllers\Break_time;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Break_time\Break_timeService;
/** @property Break_timeService $service */
class Break_timeController extends CrudController
{
    public function __construct(Break_timeService $service)
    {
        parent::__construct($service);
    }
}