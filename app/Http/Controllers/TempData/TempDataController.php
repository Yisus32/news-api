<?php

namespace App\Http\Controllers\TempData;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\TempData\TempDataService;
/** @property TempDataService $service */
class TempDataController extends CrudController
{
    public function __construct(TempDataService $service)
    {
        parent::__construct($service);
    }
}