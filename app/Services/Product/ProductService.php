<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Product;


use App\Core\CrudService;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;


/** @property ProductRepository $repository */
class ProductService extends CrudService
{

    protected $name = "product";
    protected $namePlural = "products";

    public function __construct(ProductRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        
        $order = Order::find($request->order_id);

        $quantity = 1;

        if (isset($request->quantity)) {
            $quantity = $request->quantity; 
        }
        
        $order->total_amount = $order->total_amount + ($request->price * $quantity); 
        $order->quantity = $order->quantity + $quantity;
       
        $order->save();

        return $this->repository->_store($request);
    }

    public function _update($id, Request $request)
    {
        if (isset($request->quantity) || isset($request->price)) {

            $product = Product::where('id', $id);  

            if (isset($request->quantity) && isset($request->price)) {
                $changeprice = $request->quantity * $request->price;     
            } else if (isset($request->quantity)){
                $changeprice = $request->quantity * $product->price;
            } else if (isset($request->price)){
                $changeprice = $request->quantity * $product->price;
            }

            $this->updateOrder($changeprice, $product);
        }
    }

    public function _delete($id){
        $product = Product::where('id', $id);
        
        $order = Order::where('id', $product->order_id);
        
        $order->total_amount = $order->total_amount - ($product->price * $product->quantity);

        $product->delete();
    }

    private function updateOrder($changeprice, $product){
                  
        $order = Order::where('id', $product->order_id);
           
        $price = abs(($changeprice) - ($product->quantity * $product->price));

        $order->total_amount = $order->total_amount + $price;

        $order->save();
    }

}