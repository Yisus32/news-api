<?php

namespace App\Http\Controllers;

use App\Core\CrudController;
use Illuminate\Http\Request;
use App\Core\TatucoController;
use App\Http\Services\StatusService;

class StatusController extends CrudController
{
    public function __construct(StatusService $service)
    {
        parent::__construct($service);
    }
}