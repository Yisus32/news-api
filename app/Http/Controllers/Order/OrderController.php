<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\Order;
use App\Services\Order\OrderService;

/** @property OrderService $service */
class OrderController extends CrudController
{
    public function __construct(OrderService $service)
    {
        parent::__construct($service);
    }

    public function delete($id){
        
        $order = Order::find($id);

        if(!$order){
            return response()->json([
                'status' => 404,
                'message' => 'Pedido no encontrado'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'status' => 206,
            'message' => 'Pedido Eliminado'
        ], 206);
    }

    public function getByUser($user_id){
        return $this->service->getByUser($user_id);
    }

    public function _store(Request $request)
    {
       return $this->service->_store($request);
    }

    public function _update($id, Request $request)
    {
        return $this->service->_update($id, $request);
    }
}