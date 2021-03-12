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


        $router->get('examples', 'ExampleController@_index');
        $router->get('examples/{id}', 'ExampleController@_show');
        $router->post('examples', 'ExampleController@_store');
        $router->put('examples/{id}', 'ExampleController@_update');
        $router->delete('examples/{id}', 'ExampleController@_destroy');
        $router->group(['middleware' => ['authorize']],function () use ($router) {

            $router->group(['namespace' => '\Rap2hpoutre\LaravelLogViewer'], function() use ($router) {
                $router->get('logs', 'LogViewerController@index');
            });

        });
    });

});
 
/** routes para Order **/ 
 
$router->get('orders', 'OrderController@_index');
$router->get('orders/{id}', 'OrderController@_show');
$router->post('orders', 'OrderController@_store');
$router->put('orders/{id}', 'OrderController@_update');
$router->delete('orders/{id}', 'OrderController@_destroy');
 
/** routes para Product **/ 
 
$router->get('products', 'ProductController@_index');
$router->get('products/{id}', 'ProductController@_show');
$router->post('products', 'ProductController@_store');
$router->put('products/{id}', 'ProductController@_update');
$router->delete('products/{id}', 'ProductController@_destroy');
 
/** routes para Status **/ 
 
$router->get('statuses', 'StatusController@_index');
$router->get('statuses/{id}', 'StatusController@_show');
$router->post('statuses', 'StatusController@_store');
$router->put('statuses/{id}', 'StatusController@_update');
$router->delete('statuses/{id}', 'StatusController@_destroy');
