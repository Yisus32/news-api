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
    $router->get('/reporti/reservations', 'Reservation\ReservationController@report');
    /*
     *routes with report prefix
     * rutas con el prefijo report
    */
    $router->group(['prefix' => 'report'], function () use ($router) {
        $router->post('/automatic', 'ReportController@automatic');

    });
    
    $router->group(['middleware' => ['auth']],function () use ($router) {
        //routes para reports
        $router->group(['prefix' => 'report'], function () use ($router) {
            $router->get('/reservations', 'Reservation\ReservationController@report');
            $router->post('/automatic', 'ReportController@automatic');
        });
    });

    $router->get('holes', 'Hole\HoleController@_index');
    $router->get('teetime_types', 'Teetime_type\Teetime_typeController@_index');

    $router->group(['middleware' => ['authorize']],function () use ($router) {
        /** routes para Teetime_type **/ 
 
        
        $router->get('teetime_types/{id}', 'Teetime_type\Teetime_typeController@_show');
        $router->post('teetime_types', 'Teetime_type\Teetime_typeController@_store');
        $router->put('teetime_types/{id}', 'Teetime_type\Teetime_typeController@_update');
        $router->delete('teetime_types/{id}', 'Teetime_type\Teetime_typeController@_delete');
        
        /** routes para Hole **/ 
        
        $router->get('holes/{id}', 'Hole\HoleController@_show');
        $router->post('holes', 'Hole\HoleController@_store');
        $router->put('holes/{id}', 'Hole\HoleController@_update');
        $router->delete('holes/{id}', 'Hole\HoleController@_delete');

        /** routes para Teetime **/ 
 
        $router->get('teetimes', 'Teetime\TeetimeController@_index');
        $router->get('teetimes/{id}', 'Teetime\TeetimeController@_show');
        $router->post('teetimes', 'Teetime\TeetimeController@_store');
        $router->put('teetimes/{id}', 'Teetime\TeetimeController@_update');
        $router->delete('teetimes/{id}', 'Teetime\TeetimeController@_delete');
    });

    /** routes para Reservation **/ 
 
    $router->get('reservations', 'Reservation\ReservationController@_index');
    $router->get('reservations/{id}', 'Reservation\ReservationController@_show');
   // $router->post('reservations', 'Reservation\ReservationController@_store');
    $router->put('reservations/{id}', 'Reservation\ReservationController@_update');
    $router->delete('reservations/{id}', 'Reservation\ReservationController@_delete');

    $router->post('reservations/take/{id}', 'Reservation\ReservationController@take');
    $router->post('reservations/register/{id}', 'Reservation\ReservationController@reservation_register');

    /** routes para Guest **/ 
 
    $router->get('guests', 'Guest\GuestController@_index');
    $router->get('guests/{id}', 'Guest\GuestController@_show');
    $router->post('guests', 'Guest\GuestController@_store');
    $router->put('guests/{id}', 'Guest\GuestController@_update');
    $router->delete('guests/{id}', 'Guest\GuestController@_delete');
    
}); 
 
/** routes para Break_time **/ 
 
$router->get('break_times', 'Break_time\Break_timeController@_index');
$router->get('break_times/{id}', 'Break_time\Break_timeController@_show');
$router->post('break_times', 'Break_time\Break_timeController@_store');
$router->put('break_times/{id}', 'Break_time\Break_timeController@_update');
$router->delete('break_times/{id}', 'Break_time\Break_timeController@_delete');
 

 

