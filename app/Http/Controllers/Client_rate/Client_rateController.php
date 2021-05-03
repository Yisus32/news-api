<?php

namespace App\Http\Controllers\Client_rate;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Client_rate\Client_rateService;
/** @property Client_rateService $service */
class Client_rateController extends CrudController
{
    public function __construct(Client_rateService $service)
    {
        parent::__construct($service);
    }
}