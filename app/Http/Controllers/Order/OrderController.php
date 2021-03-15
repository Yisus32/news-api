<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Order\OrderService;
/** @property OrderService $service */
class OrderController extends CrudController
{
    public function __construct(OrderService $service)
    {
        parent::__construct($service);
    }
}