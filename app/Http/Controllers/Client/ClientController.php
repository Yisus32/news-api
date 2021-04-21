<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Client\ClientService;
/** @property ClientService $service */
class ClientController extends CrudController
{
    public function __construct(ClientService $service)
    {
        parent::__construct($service);
    }
}