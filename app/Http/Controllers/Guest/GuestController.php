<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Guest\GuestService;
/** @property GuestService $service */
class GuestController extends CrudController
{
    public function __construct(GuestService $service)
    {
        parent::__construct($service);
    }
}