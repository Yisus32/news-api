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
    /** routes para Client **/ 
 
    $router->get('clients', 'Client\ClientController@_index');
    $router->get('clients/{id}', 'Client\ClientController@_show');
    $router->post('clients', 'Client\ClientController@_store');
    $router->put('clients/{id}', 'Client\ClientController@_update');
    $router->patch('clients/{id}', 'Client\ClientController@_destroy');
    $router->delete('clients/{id}', 'Client\ClientController@_delete');
    
    $router->post('/search/clients', 'Client\ClientController@searchByRif');

    /** routes para Activity **/ 
    
    $router->get('activities', 'Activity\ActivityController@_index');
    $router->get('activities/{id}', 'Activity\ActivityController@_show');
    $router->post('activities', 'Activity\ActivityController@_store');
    $router->put('activities/{id}', 'Activity\ActivityController@_update');
    $router->patch('activities/{id}', 'Activity\ActivityController@_destroy');
    $router->delete('activities/{id}', 'Activity\ActivityController@_delete');
    
    /** routes para Schedule **/ 
    
    $router->get('schedules', 'Schedule\ScheduleController@_index');
    $router->get('schedules/{id}', 'Schedule\ScheduleController@_show');
    $router->post('schedules', 'Schedule\ScheduleController@_store');
    $router->put('schedules/{id}', 'Schedule\ScheduleController@_update');
    $router->patch('schedules/{id}', 'Schedule\ScheduleController@_destroy');
    $router->delete('schedules/{id}', 'Schedule\ScheduleController@_delete');
    
    /** routes para Branch **/ 
    
    $router->get('branches', 'Branch\BranchController@_index');
    $router->get('branches/{id}', 'Branch\BranchController@_show');
    $router->post('branches', 'Branch\BranchController@_store');
    $router->put('branches/{id}', 'Branch\BranchController@_update');
    $router->patch('branches/{id}', 'Branch\BranchController@_destroy');
    $router->delete('branches/{id}', 'Branch\BranchController@_delete');
    $router->get('sector/branches[/{sector_id}]', 'Branch\BranchController@getBySector');

    /** routes para Sector **/ 
    
    $router->get('sectors', 'Sector\SectorController@_index');
    $router->get('sectors/{id}', 'Sector\SectorController@_show');
    $router->post('sectors', 'Sector\SectorController@_store');
    $router->put('sectors/{id}', 'Sector\SectorController@_update');
    $router->patch('sectors/{id}', 'Sector\SectorController@_destroy');
    $router->delete('sectors/{id}', 'Sector\SectorController@_delete');
    
    /** routes para Bank_account **/ 
    
    $router->get('bank_accounts', 'Bank_account\Bank_accountController@_index');
    $router->get('bank_accounts/{id}', 'Bank_account\Bank_accountController@_show');
    $router->post('bank_accounts', 'Bank_account\Bank_accountController@_store');
    $router->put('bank_accounts/{id}', 'Bank_account\Bank_accountController@_update');
    $router->patch('bank_accounts/{id}', 'Bank_account\Bank_accountController@_destroy');
    $router->delete('bank_accounts/{id}', 'Bank_account\Bank_accountController@_delete');
    
    /** routes para Aplication **/ 
    
    $router->get('aplications', 'Aplication\AplicationController@_index');
    $router->get('aplications/{id}', 'Aplication\AplicationController@_show');
    $router->post('aplications', 'Aplication\AplicationController@_store');
    $router->put('aplications/{id}', 'Aplication\AplicationController@_update');
    $router->patch('aplications/{id}', 'Aplication\AplicationController@_destroy');
    $router->delete('aplications/{id}', 'Aplication\AplicationController@_delete');

    $router->group(['middleware' => ['authorize']],function () use ($router) {

        $router->group(['namespace' => '\Rap2hpoutre\LaravelLogViewer'], function() use ($router) {
            $router->get('logs', 'LogViewerController@index');
        });

    });
});

    /** routes para Sub_Activity **/ 

    $router->get('sub_activities', 'Sub_Activity\Sub_ActivityController@_index');
    $router->get('sub_activities/{id}', 'Sub_Activity\Sub_ActivityController@_show');
    $router->post('sub_activities', 'Sub_Activity\Sub_ActivityController@_store');
    $router->put('sub_activities/{id}', 'Sub_Activity\Sub_ActivityController@_update');
    $router->patch('sub_activities/{id}', 'Sub_Activity\Sub_ActivityController@_destroy');
    $router->delete('sub_activities/{id}', 'Sub_Activity\Sub_ActivityController@_delete');
    
    /** routes para Coin **/ 
    
    $router->get('coins', 'Coin\CoinController@_index');
    $router->get('coins/{id}', 'Coin\CoinController@_show');
    $router->post('coins', 'Coin\CoinController@_store');
    $router->put('coins/{id}', 'Coin\CoinController@_update');
    $router->patch('coins/{id}', 'Coin\CoinController@_destroy');
    $router->delete('coins/{id}', 'Coin\CoinController@_delete');
    
    /** routes para Bank **/ 
    
    $router->get('banks', 'Bank\BankController@_index');
    $router->get('banks/{id}', 'Bank\BankController@_show');
    $router->post('banks', 'Bank\BankController@_store');
    $router->put('banks/{id}', 'Bank\BankController@_update');
    $router->patch('banks/{id}', 'Bank\BankController@_destroy');
    $router->delete('banks/{id}', 'Bank\BankController@_delete');
    
    /** routes para Client_rate **/ 
    
    $router->get('client_rates', 'Client_rate\Client_rateController@_index');
    $router->get('client_rates/{id}', 'Client_rate\Client_rateController@_show');
    $router->post('client_rates', 'Client_rate\Client_rateController@_store');
    $router->put('client_rates/{id}', 'Client_rate\Client_rateController@_update');
    $router->patch('client_rates/{id}', 'Client_rate\Client_rateController@_destroy');
    $router->delete('client_rates/{id}', 'Client_rate\Client_rateController@_delete');
    $router->group(['middleware' => ['auth']],function () use ($router) {

        

    
});
 
