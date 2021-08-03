<?php 
    
namespace App\Http\Mesh;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
   
    class NotificationService
    {
        public $client;    
        private $headers = [ "Accept" => "application/json", "Cache-Control" => "no-cache"];
        
         public function __construct()
        {
            $this->client = new Client();
        }

        /**
        * @param $email
        * @param $subject
        * @param $message
        * @param $account
        * @param null $sender
        * @return bool|\Psr\Http\Message\StreamInterface|null
        */
        public function sendEmail($email, $subject, $message, $account,$sender=null)
        {
            $content = 
            [
                "email" => $email,
                "address" => $sender,
                "subject" => $subject,
                "message" => $message,
                "name" => "Club de Golf Panama"
            ];
            
            Log::critical(json_encode($content));

            try
            {
              $response = $this->client->post(env('NOTIFICATIONS_API').'create/email',['headers'=>$this->headers,'json'=>$content]);

                return ($response->getStatusCode() == 200) ? $response->getBody() : false;
            }catch (ClientException $exception){
                 Log::critical($exception->getMessage());

                 return null;
            }
            
            return $content;
        }
    }
    

