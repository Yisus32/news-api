<?php
namespace App\Http\Mesh;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;

class UsuService extends ServicesMesh 
{
    public $pach;
    public $dataForPost;
    public $client;
    public $headers;

    public function __construct(){
        $this->pach = env('USERS_API');
         $this->headers = [
            'Authorization' => '',
            'Accept'        => 'application/json',
            'Cache-Control' => 'no-cache',
            'x-timezone'    => 'UTC, -5:00'
        ];
        $this->client = new \GuzzleHttp\Client(['verify' => false]);
    }
    public function _get($id){
        try{
            $url = $this->pach.'/get/user/'.$id;
            $response = $this->client->request('GET', $url);
            return json_decode($response->getBody());
        }catch (Exception $e) {
            Log::critical('_get- '.$e);
            return false;
        }
    }
    public function _sendNotification($id_user, $title, $body){
        try{
            $url = $this->pach.'/nt/send/tokens';
            $request  = [
                'form_params' => [
                        'id_user' => $id_user,
                        'title'   => $title,
                        'body'    => $body
                    ]
                ];
            $request_data = json_encode($request);
            $response = $this->client->request('POST', $url, $request);
            return json_decode($response->getBody());
        }catch (Exception $e) {
            Log::critical('ApiUser _sendNotification '.$e);
            return false;
        }
    }
}