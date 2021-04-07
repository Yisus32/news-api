<?php


namespace App\Http\Mesh;


use Illuminate\Support\Facades\Log;

class InventoryService extends ServicesMesh
{
    

    public function __construct()
    {
        parent::__construct(env('INVENTORY_URL'));
    }

    public function checkProduct($product_id, $store_id){
        $endpoint = 'products/'.$product_id;

        try {
            $options = $this->getOptions($this->getHeaders($this->getRequest()));
            $response = $this->client->get($endpoint,$options);

       //     if ($response->getStatusCode() !== 200){
         //       Log::critical($response->getStatusCode() . ":   " .  $response->getBody());
           //     return [];
            //}

            $product = json_decode($response->getBody(),true);

            if ($product->product) {
                $data = $product->product;
                if ($data->available == false) {
                    return response()->json(['error' => true, 'message'=> 'El producto no se encuentra disponible'], 400);
                }
                if ($data->account != $store_id) {
                    return response()->json(['error' => true, 'message'=> 'El id del producto no pertenece a la tienda del pedido'], 400);
                }

            }else{
                return response()->json(['error' => true, 'message'=> 'El producto no existe en el inventario'], 404);
            }

            return 0;

        }catch (\Exception $exception){
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return [];
        }
    }
}