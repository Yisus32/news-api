<?php


namespace App\Http\Mesh;

use App\Traits\ApiResponser;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class UserService extends ServicesMesh
{
   // use ApiResponser;

    public function __construct()
    {
        parent::__construct(env('USERS_API'));
    }

   
    public function getUsersById($id) 
    {
        try {
            $client = new Client();
            $response = $client->get(env('USERS_API').'get/user/' . $id);

            if ($response->getStatusCode() !== 200){
                Log::critical($response->getStatusCode() . ":   " .  $response->getBody());
                return [];
            }

            $user = json_decode($response->getBody())->value;

            return $user ?? [];

        }catch (Exception $exception){
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return [];
        }
    }

    /**
     * Returns a Client from API-Customers, by id
     * @param $id
     * @return array
     */
    public function getUserById($id)
    {
        $uri = env('USERS_API') . 'get/user/' . $id ;

        try {
            $options = $this->getOptions($this->getHeaders($this->getRequest()));
            $response = $this->client->get($uri, $options);

            if ($response->getStatusCode() !== 200) {
                Log::critical($response->getStatusCode() . ":   " . $response->getBody());
                return ["id" => null];
            }

            $client = json_decode($response->getBody(), true);

            return $client['Usuario'] ?? ["id" => null];

        } catch (Exception $exception) {
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return ["id" => null];
        }
    }

}
