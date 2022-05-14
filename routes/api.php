<?php

use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\ManagerAccountController;
use App\Http\Controllers\Admin\StudentAccountController;
use App\Http\Controllers\Admin\ServiceManagementController;
use App\Http\Controllers\Admin\AddressManagementController;
use App\Http\Controllers\Admin\SecondaryAddressManagementController;
use App\Http\Controllers\AppointmentManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api){
    $api->get('/', function () {
        return 'Hello Fadergs Comunidade';
    });

    $api->group(['prefix'=> 'auth'], function ($api){
        $api->post('/operator/login', 'App\Http\Controllers\Auth\AuthController@operatorLogin');
        $api->post('/user/login', 'App\Http\Controllers\Auth\AuthController@userLogin');
        $api->post('/user/signup', 'App\Http\Controllers\UserController@store');

        
        $api->group(['middleware' => 'api.auth'], function ($api){
            $api->post('/token/refresh', 'App\Http\Controllers\Auth\AuthController@refresh');
            $api->post('/logout', 'App\Http\Controllers\Auth\AuthController@logout');
        });
    });

    $api->group(['prefix' => 'api/user'], function ($api) {
        $api->post('', 'App\Http\Controllers\UserController@create');
        $api->put('{id}', 'App\Http\Controllers\UserController@update')->where('id', '[0-9]+');
        $api->get('{id}', 'App\Http\Controllers\UserController@show')->where('id', '[0-9]+');
        $api->get('', 'App\Http\Controllers\UserController@index');
    });  

    $api->group(['middleware' => ['role:admin'], 'prefix' => 'admin'], 
        function ($api){
            $api->get('/home', 'App\Http\Controllers\Admin\AdminOperatorController@index');
            $api->group(['prefix' => 'restore'], function ($api){
                $api->post('operator', 'App\Http\Controllers\Admin\AdminOperatorController@restoreOperator');
                $api->post('service', 'App\Http\Controllers\Admin\AdminOperatorController@restoreService');
                
            });

            $api->resource('service', ServiceManagementController::class);
            $api->resource('addresses', AddressManagementController::class);
            $api->group(['prefix' => 'address'], function ($api){
                $api->resource('secondary', SecondaryAddressManagementController::class);
            });


            $api->group(['prefix' => 'accounts'], 
                function ($api){
                    $api->resource('admin', AdminAccountController::class);
                    $api->resource('manager', ManagerAccountController::class);
                    $api->resource('student', StudentAccountController::class);
            });
    });

    $api->group(['middleware' => ['role:admin|manager'], 'prefix' => 'address'],
        function ($api){
            $api->group(['prefix' => 'restore'], function ($api){
                $api->post('mainaddress', 'App\Http\Controllers\Admin\AdminOperatorController@restoreAddress');
                $api->post('secondaryaddress', 'App\Http\Controllers\Admin\AdminOperatorController@restoreSecondaryAddress');
            });
        });

    $api->group(['middleware' => 'api.auth'], function ($api){
        $api->resource('appointments', AppointmentManagementController::class);
    });

});
