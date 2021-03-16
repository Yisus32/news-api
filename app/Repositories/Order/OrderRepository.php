<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Order;

use App\Core\CrudRepository;
use App\Models\Order;

/** @property Order $model */
class OrderRepository extends CrudRepository
{

    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getByUser($user_id){
        
        $order = Order::where('msa_account', $user_id)->get();
        
        if ($order->count() < 1) {
            return response()->json([
                'status' => 404,
                'message'=> 'Pedido no encontrado'
            ], 404);
        }
        
        return $order;
    }
}