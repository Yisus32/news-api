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
            $router->get('/game_log', 'game_log\game_logController@report');
            $router->get('/alq_car', 'alq_car\alq_carController@report');
            $router->post('/automatic', 'ReportController@automatic');
        });
    });

    
    $router->get('teetime_types', 'Teetime_type\Teetime_typeController@_index');

    $router->group(['middleware' => ['authorize']],function () use ($router) {

    });

    $router->group(['middleware' => ['cors']],function () use ($router) {
        /** routes para Teetime_type **/ 

        $router->get('teetime_types/{id}', 'Teetime_type\Teetime_typeController@_show');
        $router->post('teetime_types', 'Teetime_type\Teetime_typeController@_store');
        $router->put('teetime_types/{id}', 'Teetime_type\Teetime_typeController@_update');
        $router->delete('teetime_types/{id}', 'Teetime_type\Teetime_typeController@_delete');
        
        /** routes para Hole **/ 
        
        $router->get('holes', 'Hole\HoleController@_index');
        $router->get('holes/{id}', 'Hole\HoleController@_show');
        $router->post('holes', 'Hole\HoleController@_store');
        $router->put('holes/{id}', 'Hole\HoleController@_update');
        $router->delete('holes/{id}', 'Hole\HoleController@_delete');

        /** routes para Teetime **/ 

        $router->get('teetimes/available', 'Teetime\TeetimeController@available');
        $router->get('teetimes', 'Teetime\TeetimeController@_index');
        $router->get('teetimes/{id}', 'Teetime\TeetimeController@_show');
        $router->post('teetimes', 'Teetime\TeetimeController@_store');
        $router->put('teetimes/{id}', 'Teetime\TeetimeController@_update');
        $router->delete('teetimes/{id}', 'Teetime\TeetimeController@_delete');
        $router->get('teetim/day', 'Teetime\TeetimeController@day');

        /** routes para Reservation **/ 
    
        $router->get('reservations', 'Reservation\ReservationController@_index');
        $router->get('reservations/{id}', 'Reservation\ReservationController@_show');
        $router->post('reservations', 'Reservation\ReservationController@_store');
        $router->put('reservations/{id}', 'Reservation\ReservationController@_update');
        $router->delete('reservations/{id}', 'Reservation\ReservationController@_delete');

        $router->post('reservations/take/{id}', 'Reservation\ReservationController@take');
        $router->post('reservations/register/{id}', 'Reservation\ReservationController@reservation_register');

        /** routes para Guest **/ 
    
        $router->get('guests', 'Guest\GuestController@_index');
        $router->get('guests/{id}', 'Guest\GuestController@_show');
        $router->post('guests', 'Guest\GuestController@_store');
        $router->post('guests/email', 'Guest\GuestController@email');
        $router->put('guests/{id}', 'Guest\GuestController@_update');
        $router->delete('guests/{id}', 'Guest\GuestController@_delete');

            /** routes para group **/ 
        $router->get('groups/psearch', 'group\groupController@psearch');
        $router->get('groups', 'group\groupController@_index');
        $router->get('groups/{id}', 'group\groupController@_show');
        $router->post('groups', 'group\groupController@_store');
        $router->put('groups/{id}', 'group\groupController@_update');
        $router->delete('groups/{id}', 'group\groupController@_delete');
        
        /** routes para cars_golf **/ 
        $router->get('cars_golfs/psearch','cars_golf\cars_golfController@psearch');
        $router->get('cars_golfs', 'cars_golf\cars_golfController@_index');
        $router->get('cars_golfs/{id}', 'cars_golf\cars_golfController@_show');
        $router->post('cars_golfs', 'cars_golf\cars_golfController@_store');
        $router->put('cars_golfs/{id}', 'cars_golf\cars_golfController@_update');
        $router->delete('cars_golfs/{id}', 'cars_golf\cars_golfController@_delete');
        
        /** routes para game_log **/ 
        $router->get('game_logs/group', 'game_log\game_logController@list_by_group');
        $router->get('game_logs', 'game_log\game_logController@_index');
        $router->get('game_logs/{id}', 'game_log\game_logController@_show');
        $router->post('game_logs', 'game_log\game_logController@sav');
        $router->get('game_logs/date/hour','game_log\game_logController@filter_by_date');
        $router->put('game_logs/{id}', 'game_log\game_logController@_update');
        $router->delete('game_logs/{id}', 'game_log\game_logController@_delete');
       
        $router->get('game_logs/full/index', 'game_log\game_logController@indexfull');

        /** routes para waiting_list **/ 
        $router->get('waiting_lists/date/hour','waiting_list\waiting_listController@filter_by_date');
        $router->get('waiting_lists', 'waiting_list\waiting_listController@_index');
        $router->get('waiting_lists/{id}', 'waiting_list\waiting_listController@_show');
        $router->post('waiting_lists', 'waiting_list\waiting_listController@_store');
        $router->put('waiting_lists/{id}', 'waiting_list\waiting_listController@_update');
        $router->delete('waiting_lists/{id}', 'waiting_list\waiting_listController@_delete');

        /** routes para toalla **/ 
        $router->get('toallas/search', 'toalla\toallaController@psearch');
        $router->get('toallas', 'toalla\toallaController@_index');
        $router->get('toallas/{id}', 'toalla\toallaController@_show');
        $router->post('toallas', 'toalla\toallaController@_store');
        $router->put('toallas/{id}', 'toalla\toallaController@_update');
        $router->delete('toallas/{id}', 'toalla\toallaController@_delete');
        $router->post('toallas/{id}', 'toalla\toallaController@upsta');
        
        /** routes para asig_toalla **/ 
        
        $router->get('asig_toallas', 'asig_toalla\asig_toallaController@_index');
        $router->get('asig_toallas/{id}', 'asig_toalla\asig_toallaController@_show');
        $router->post('asig_toallas', 'asig_toalla\asig_toallaController@_store');
        $router->put('asig_toallas/{id}', 'asig_toalla\asig_toallaController@_update');
        $router->delete('asig_toallas/{id}', 'asig_toalla\asig_toallaController@_delete');

        /** routes para Document **/ 
 
        $router->get('documents',           'Document\DocumentController@_index');
        $router->get('documents/{id}',      'Document\DocumentController@_show');
        $router->post('documents',          'Document\DocumentController@_store');
        $router->put('documents/{id}',      'Document\DocumentController@_update');
        $router->delete('documents/{id}',   'Document\DocumentController@_delete');
        $router->post('documents/validate', 'Document\DocumentController@_validate');

        /** routes para bitatoalla **/ 
        $router->get('bitatoallas/toalla', 'bitatoalla\bitatoallaController@bita');
        $router->post('bitatoallas/obs', 'bitatoalla\bitatoallaController@reception');
        $router->get('bitatoallas/recep/full', 'bitatoalla\bitatoallaController@recepci');
        $router->get('bitatoallas', 'bitatoalla\bitatoallaController@_index');
        $router->get('bitatoallas/{id}', 'bitatoalla\bitatoallaController@_show');
        $router->post('bitatoallas', 'bitatoalla\bitatoallaController@_store');
        $router->put('bitatoallas/{id}', 'bitatoalla\bitatoallaController@_update');
        $router->delete('bitatoallas/{id}', 'bitatoalla\bitatoallaController@_delete');

        /** routes para alq_car **/ 
 
        $router->get('alq_cars', 'alq_car\alq_carController@_index');
        $router->get('alq_cars/{id}', 'alq_car\alq_carController@_show');
        $router->get('alq_cars/fill/date','alq_car\alq_carController@filter_by_date');
        $router->post('alq_cars', 'alq_car\alq_carController@sav');
        $router->put('alq_cars/{id}', 'alq_car\alq_carController@_update');
        $router->delete('alq_cars/{id}', 'alq_car\alq_carController@_delete');

        // invitation

        $router->get('accept/invitation/{id}', 'Invitation\InvitationController@accept_invitation');

    });

    

        
}); 

 
/** routes para Break_time **/ 
 
$router->get('break_times', 'Break_time\Break_timeController@_index');
$router->get('break_times/{id}', 'Break_time\Break_timeController@_show');
$router->post('break_times', 'Break_time\Break_timeController@_store');
$router->put('break_times/{id}', 'Break_time\Break_timeController@_update');
$router->delete('break_times/{id}', 'Break_time\Break_timeController@_delete');
 

 

 

 

 

 

 
/** routes para Invitation **/ 
 
$router->get('invitations', 'Invitation\InvitationController@_index');
$router->get('invitations/{id}', 'Invitation\InvitationController@_show');
$router->post('invitations', 'Invitation\InvitationController@_store');
$router->put('invitations/{id}', 'Invitation\InvitationController@_update');
$router->delete('invitations/{id}', 'Invitation\InvitationController@_destroy');
 

 

