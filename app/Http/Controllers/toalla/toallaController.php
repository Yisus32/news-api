<?php

namespace App\Http\Controllers\toalla;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\toalla\toallaService;
/** @property toallaService $service */
class toallaController extends CrudController
{
    public function __construct(toallaService $service)
    {
        parent::__construct($service);
    }
}