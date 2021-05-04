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
        
        /** routes para Schedule **/ 
        
        $router->get('schedules', 'Schedule\ScheduleController@_index');
        $router->get('schedules/{id}', 'Schedule\ScheduleController@_show');
        $router->post('schedules', 'Schedule\ScheduleController@_store');
        $router->put('schedules/{id}', 'Schedule\ScheduleController@_update');
        $router->delete('schedules/{id}', 'Schedule\ScheduleController@_destroy');
        
        /** routes para Branch **/ 
        
        $router->get('branches', 'Branch\BranchController@_index');
        $router->get('branches/{id}', 'Branch\BranchController@_show');
        $router->post('branches', 'Branch\BranchController@_store');
        $router->put('branches/{id}', 'Branch\BranchController@_update');
        $router->delete('branches/{id}', 'Branch\BranchController@_destroy');
        
        /** routes para Sector **/ 
        
        $router->get('sectors', 'Sector\SectorController@_index');
        $router->get('sectors/{id}', 'Sector\SectorController@_show');
        $router->post('sectors', 'Sector\SectorController@_store');
        $router->put('sectors/{id}', 'Sector\SectorController@_update');
        $router->delete('sectors/{id}', 'Sector\SectorController@_destroy');
        
        /** routes para Bank_account **/ 
        
        $router->get('bank_accounts', 'Bank_account\Bank_accountController@_index');
        $router->get('bank_accounts/{id}', 'Bank_account\Bank_accountController@_show');
        $router->post('bank_accounts', 'Bank_account\Bank_accountController@_store');
        $router->put('bank_accounts/{id}', 'Bank_account\Bank_accountController@_update');
        $router->delete('bank_accounts/{id}', 'Bank_account\Bank_accountController@_destroy');
        
        /** routes para Aplication **/ 
        
        $router->get('aplications', 'Aplication\AplicationController@_index');
        $router->get('aplications/{id}', 'Aplication\AplicationController@_show');
        $router->post('aplications', 'Aplication\AplicationController@_store');
        $router->put('aplications/{id}', 'Aplication\AplicationController@_update');
        $router->delete('aplications/{id}', 'Aplication\AplicationController@_destroy');

        $router->group(['middleware' => ['authorize']],function () use ($router) {

            $router->group(['namespace' => '\Rap2hpoutre\LaravelLogViewer'], function() use ($router) {
                $router->get('logs', 'LogViewerController@index');
            });

        });
    });
    
});
 

 

 
 
/** routes para Bank **/ 
 
$router->get('banks', 'Bank\BankController@_index');
$router->get('banks/{id}', 'Bank\BankController@_show');
$router->post('banks', 'Bank\BankController@_store');
$router->put('banks/{id}', 'Bank\BankController@_update');
$router->delete('banks/{id}', 'Bank\BankController@_destroy');
