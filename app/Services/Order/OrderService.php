<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Order;


use App\Core\CrudService;
use App\Http\Mesh\MailService;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\Order\OrderRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

/** @property OrderRepository $repository */
class OrderService extends CrudService
{

    protected $name = "order";
    protected $namePlural = "orders";

    public function __construct(OrderRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getByUser($user_id){
        return $this->repository->getByUser($user_id);
    }

    public function _store(Request $request)
    {
        
     
    return $this->repository->_store($request);

    }

    public function _update($id, Request $request)
    {
        
        if ($request->status_id == 2) {
            
           if($request->hasHeader('Authorization')){
                $token = $request->header('Authorization');
            }
            if ($request->has('token')){
                $token = "Bearer " .  $request->input('token');
            }
            $user = $this->getEmail($token);
             
            $email = $user['username'];
            $order = Order::find($id);
            $products = Product::where('order_id', $id)->get();
            
            $message = $this->Message($order, $products);

            $mail = new MailService();

            return $mail->sendMail($email, $message);
            return $email;
        }
        

        return $this->repository->_update($id, $request);
    }

    private function getEmail($token){
        
        $client = new Client();
        
        $header = [
            "Authorization" => $token,
            "Accept" => "application/json",
            "Cache-Control" => "no-cache",
            "Content-Type" => "application/json"
        ];
        
        try {
            $response = $client->get(env('USERS_API') . 'validate',['headers' => $header]);
            $data = $response->getBody();
            $data = json_decode($data, true);
            
            foreach ($data as $index)
            {
                return $index;
            }
           // return $dato;
            
            
        } catch (\Throwable $th) {
            return 'error';
        }
    }

    private function Message($order, $products){
        $message = "<h1>El pedido numero $order->id ha sido aceptado</h1>
                    <p>Monto: $order->total_amount </p>
                    <p>Destino: $order->location </p>
                    <p>Productos: </p>";
        
        foreach ($products as $product) {
            $message = $message . '<tr>
                            <td style="text-align:right"><?= floatval('. $product->quantity .') ?></td>
                            <td style="width: 275px;"><?=' . $product->product_name ?? null . '?></td>
                            <td style="text-align:right">
                                ' .  $product->price . '
                            </td>
                            <td style="text-align:right"> ' . ($product->price * $product->quantity) . '</td>
                        </tr>';
        }
    }
}