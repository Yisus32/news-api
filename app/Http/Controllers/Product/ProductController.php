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

    public function delete($id){
        return $this->service->delete($id);
    }

    public function getByOrder($order_id){
       
        return $this->service->getByOrder($order_id);
    }
}