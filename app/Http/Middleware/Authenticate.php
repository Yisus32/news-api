<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 28/01/19
 * Time: 02:40 PM
 */

namespace App\Http\Middleware;


use App\Traits\ManageRoles;
use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;

class Authenticate
{
    use ManageRoles;
    protected $client;

    public function __construct()
    {
        $this->client= new Client();
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
       /* $token = '';
        if($request->hasHeader('Authorization')){
            $tokenA = $request->header('Authorization');
            $tokenA = explode(" ", $tokenA);
            $token = $tokenA[1];
        }
        if ($request->has('token')){
            $token = $request->input('token');
        }
        $header = [
            "Authorization" => $token,
            "Accept" => "application/json",
            "Cache-Control" => "no-cache"
        ];
        try{
            $response = $this->client->get(env('USERS_API') . 'get/auth/'. $token,['headers' => $header]);
        }catch (ClientException $exception){
            $response = $exception->getResponse();
            return response()->json(["error"=>true,"message"=>'unauthenticated '],$response->getStatusCode());
        }catch (ServerException $exception){
            $response = $exception->getResponse();
            return response()->json(["error"=>true,"message"=>"Users Internal Error"],$response->getStatusCode());
        }

        $user = json_decode($response->getBody());

        if ($user->success == false) {
           return response()->json(["error"=>true,"message"=>'unauthenticated '], 403);
        }

        $request->attributes->add(['user' => $user]);*/

        return $next($request);
    }
}
