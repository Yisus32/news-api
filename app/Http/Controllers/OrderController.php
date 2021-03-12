<?php

namespace App\Http\Controllers;

use App\Core\CrudController;
use Illuminate\Http\Request;
use App\Http\Services\OrderService;

class OrderController extends CrudController
{
    public function __construct(OrderService $service)
    {
        parent::__construct($service);
    }
}