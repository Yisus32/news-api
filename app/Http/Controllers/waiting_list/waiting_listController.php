<?php

namespace App\Http\Controllers\waiting_list;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\waiting_list\waiting_listService;
/** @property waiting_listService $service */
class waiting_listController extends CrudController
{
    public function __construct(waiting_listService $service)
    {
        parent::__construct($service);
    }
}