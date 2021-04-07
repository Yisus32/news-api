<?php


namespace App\Http\Mesh;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MailService extends ServicesMesh
{
    

    public function __construct()
    {
        parent::__construct(env('NOTIFICATIONS_API'));
    }

    public function sendMail($email, $message){
        //$endpoint = 'sends';
        
        $url = env('NOTIFICATIONS_API');
        $client = new Client();
        $email = 'pluvet01@gmail.com';
        
        try {
            $response = $client->request('post',"$url", [
                'headers' => ['Accept' => 'application/json', 'Content-Type'=> 'application/json'],
                'body' => ['subscribers' => $email, 'message' => $message, 'subject' => "Pedido", "account" => 1,
                'sender_mail'=> "notificaciones@zippyttech.com", 'type' => 'direct']
            ]);

            if ($response->getStatusCode() !== 200){
                Log::critical($response->getStatusCode() . ":   " .  $response->getBody());
                return [];
            }

            return $response->getBody();

        }catch (\Exception $exception){
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return [];
        }
    }
}