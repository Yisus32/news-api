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

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Router;

/*
* ALL THE METHODS WITH A _ BEFORE THOSE NAME GOES DIRECTLY TO REPOSITORY THROUGH TATUCO METHODS
* TODOS LOS METODOS CON UN _ EN EL PREFIJO DEL NOMBRE VAN DIRECTAMENTE AL REPOSITORIO POR MEDIO DE LOS METODOS DE TATUCO
*/

$router->group(['prefix' => 'api'], function (Router $router) {

    $router->get('/', function () use ($router) {

        return response()->json([
            "version"=> $router->app->version(),
            "time"   => Carbon::now()->toDateTime(),
            "php"    =>  phpversion()
        ]);
    });

    /*
     *routes with report prefix
     * rutas con el prefijo report
    */
    $router->group(['prefix' => 'report'], function () use ($router) {
        $router->post('/automatic', 'ReportController@automatic');

    });

    $router->group(['middleware' => ['auth']],function () use ($router) {

        
        $router->group(['middleware' => ['authorize']],function () use ($router) {

            $router->group(['namespace' => '\Rap2hpoutre\LaravelLogViewer'], function() use ($router) {
                $router->get('logs', 'LogViewerController@index');
            });

        });
    });
    
});
 

 

 
/** routes para Client **/ 
 
$router->get('clients', 'Client\ClientController@_index');
$router->get('clients/{id}', 'Client\ClientController@_show');
$router->post('clients', 'Client\ClientController@_store');
$router->put('clients/{id}', 'Client\ClientController@_update');
$router->delete('clients/{id}', 'Client\ClientController@_destroy');
 
/** routes para Activity **/ 
 
$router->get('activities', 'Activity\ActivityController@_index');
$router->get('activities/{id}', 'Activity\ActivityController@_show');
$router->post('activities', 'Activity\ActivityController@_store');
$router->put('activities/{id}', 'Activity\ActivityController@_update');
$router->delete('activities/{id}', 'Activity\ActivityController@_destroy');
