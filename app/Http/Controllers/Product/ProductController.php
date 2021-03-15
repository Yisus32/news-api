<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Product\ProductService;
/** @property ProductService $service */
class ProductController extends CrudController
{
    public function __construct(ProductService $service)
    {
        parent::__construct($service);
    }
}