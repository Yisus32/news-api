<?php

namespace App\Http\Controllers\bitatoalla;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\bitatoalla\bitatoallaService;
/** @property bitatoallaService $service */
class bitatoallaController extends CrudController
{
    public function __construct(bitatoallaService $service)
    {
        parent::__construct($service);
    }
}