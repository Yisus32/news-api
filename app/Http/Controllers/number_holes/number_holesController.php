<?php

namespace App\Http\Controllers\number_holes;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\number_holes\number_holesService;
/** @property number_holesService $service */
class number_holesController extends CrudController
{
    public function __construct(number_holesService $service)
    {
        parent::__construct($service);
    }
}