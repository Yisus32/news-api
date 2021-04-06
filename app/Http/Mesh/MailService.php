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
        try {
            $response = $client->request('POST', "$url", [
                'headers' => ['Accept' => 'application/json', 'Content-Type'=> 'application/json'],
                'body' => ['subscribers' => $email, 'message' => $message, 'subject' => "Pedido",
                'sender_mail'=> "notificaciones@zippyttech.com"]
            ]);

            if ($response->getStatusCode() !== 200){
                Log::critical($response->getStatusCode() . ":   " .  $response->getBody());
                return [];
            }

            return 0;

        }catch (\Exception $exception){
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return [];
        }
    }
}