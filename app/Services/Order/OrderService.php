<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Order;


use App\Core\CrudService;
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
        if($request->hasHeader('Authorization')){
            $token = $request->header('Authorization');
        }
        if ($request->has('token')){
            $token = "Bearer " .  $request->input('token');
        }
        $user = $this->getEmail($token);
         
        $email = $user['username'];
        
     //   return $email;
     
    return $this->repository->_store($request);

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
}