<?php

namespace App\Http\Controllers\asig_toalla;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\asig_toalla\asig_toallaService;
/** @property asig_toallaService $service */
class asig_toallaController extends CrudController
{
    public function __construct(asig_toallaService $service)
    {
        parent::__construct($service);
    }
}