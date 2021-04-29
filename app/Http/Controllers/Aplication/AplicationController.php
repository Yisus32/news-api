<?php

namespace App\Http\Controllers\Aplication;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Aplication\AplicationService;
/** @property AplicationService $service */
class AplicationController extends CrudController
{
    public function __construct(AplicationService $service)
    {
        parent::__construct($service);
    }
}