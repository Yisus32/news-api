<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Product;


use App\Core\CrudService;
use App\Http\Mesh\InventoryService;
use App\Models\Order;
use App\Models\Product;
use App\Models\Status;
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
        
        $check = new InventoryService;

        $response = $check->checkProduct($request->product_id, $order->store_id);

        if ($response != 0) {
            return $response;
        }

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

            $product = Product::find($id);  

            if (isset($request->quantity) && isset($request->price)) {
                $changeprice = $request->quantity * $request->price;     
            } else if (isset($request->quantity)){
                $changeprice = $request->quantity * $product->price;
            } else if (isset($request->price)){
                $changeprice = $request->quantity * $product->price;
            }

            $this->updateOrder($changeprice, $product);
        }

        return $this->repository->_update($id, $request);
    }

    public function delete($id){
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => $this->name . ' no existe'
            ], 404);
        }
        $order = Order::find($product->order_id);
        
        $order->total_amount = $order->total_amount - ($product->price * $product->quantity);

        $order->save();

        $product->delete();

        return response()->json([
            'status' => 206,
            'message' => $this->name . ' Eliminado'
        ], 206);
    }

    private function updateOrder($changeprice, $product){
                  
        $order = Order::find($product->order_id);
           
        $order->total_amount = $order->total_amount - ($product->quantity * $product->price);

        $order->total_amount = $order->total_amount + $changeprice;

        $order->save();
    }

    public function getByOrder($order_id){
        $order = Order::where('id', $order_id)->get();
        if ($order->count() == 0) {
            return response()->json([
                'status' => 404,
                'message'=> 'Id de pedido no encontrado'
            ], 404);
        }
        return $this->repository->getByOrder($order_id);
    }

}