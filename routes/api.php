<?php

/*
|--------------------------------------------------------------------------
| Application $router->|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Http\Mesh\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Router;

/*
* ALL THE METHODS WITH A _ BEFORE THOSE NAME GOES DIRECTLY TO REPOSITORY THROUGH CRUD METHODS
* TODOS LOS METODOS CON UN _ EN EL PREFIJO DEL NOMBRE VAN DIRECTAMENTE AL REPOSITORIO POR MEDIO DE LOS METODOS DE CRUD
*/

$router->group(['prefix' => 'api'], function (Router $router) {

    $router->get('/', function () use ($router) {

        return response()->json([
            "version"=> $router->app->version(),
            "time"   => Carbon::now(env('APP_TIMEZONE'))->toDateTime(),
            "php"    =>  phpversion()
        ]);
    });
    
        /** routes para News **/ 
     
    $router->get('news', 'News\NewsController@_index');
    $router->get('news/{id}', 'News\NewsController@_show');
    $router->post('news', 'News\NewsController@_store');
    $router->put('news/{id}', 'News\NewsController@_update');
    $router->delete('news/{id}', 'News\NewsController@_delete');
     
    /** routes para Type **/ 
     
    $router->get('types', 'Type\TypeController@_index');
    $router->get('types/{id}', 'Type\TypeController@_show');
    $router->post('types', 'Type\TypeController@_store');
    $router->put('types/{id}', 'Type\TypeController@_update');
    $router->delete('types/{id}', 'Type\TypeController@_delete');
       
}); 

 


 

