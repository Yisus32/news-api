<?php


namespace App\Http\Mesh;


use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Log;

class AccountService 
{


    public function __construct()
    {
     
    }

    /**
     * @param $id
     * @return null[]
     */
    public function getAccount() 
    {
        try {
            $client = new Client();
            $response = $client->get(env('USERS_API').'/us/ac/list/');

            if ($response->getStatusCode() !== 200){
                Log::critical($response->getStatusCode() . ":   " .  $response->getBody());
                return [];
            }

            $account = json_decode($response->getBody())->value;

            return $account[0] ?? [];

        }catch (Exception $exception){
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return [];
        }


    }

  

}
