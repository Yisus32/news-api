<?php

namespace App\Http\Controllers;

use App\Core\CrudController;
use Illuminate\Http\Request;
use App\Core\TatucoController;
use App\Http\Services\ProductService;

class ProductController extends CrudController
{
    public function __construct(ProductService $service)
    {
        parent::__construct($service);
    }
}