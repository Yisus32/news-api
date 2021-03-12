<?php

namespace App\Http\Controllers\Status;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Status\StatusService;
/** @property StatusService $service */
class StatusController extends CrudController
{
    public function __construct(StatusService $service)
    {
        parent::__construct($service);
    }
}