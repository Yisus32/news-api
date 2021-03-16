<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Product;

use App\Core\CrudRepository;
use App\Models\Product;

/** @property Product $model */
class ProductRepository extends CrudRepository
{

    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function _store($data)
    {
        return parent::_store($data);
    }

    public function getByOrder($order_id){
        
        $product = Product::where('order_id', $order_id)->get();
        
        if ($product->count() >= 1 ){
            return $product;
        }else{
            return response()->json([
                'status' => 404,
                'message'=> 'Productos de pedido '. $order_id .' no encontrados'
            ], 404);
        }
        
    }

}