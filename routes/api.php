<?php

use App\Http\Controllers\Admin\AdminAccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
        $api->post('/operator/login', 'App\Http\Controllers\Auth\AuthController@login');
        
        $api->group(['middleware' => 'api.auth', 'prefix' => 'operator'], function ($api){
            $api->post('/token/refresh', 'App\Http\Controllers\Auth\AuthController@refresh');
            $api->post('/logout', 'App\Http\Controllers\Auth\AuthController@logout');
        });
    });
    

   // CRIAR ROTA PARA EDITAR INFORMAÇÕES SEPARADAMENTE

    $api->group(['middleware' => ['role:admin'], 'prefix' => 'admin'], 
        function ($api){
            $api->get('/home', 'App\Http\Controllers\Admin\AdminOperatorController@index');
            $api->group(['middleware' => ['role:admin'], 'prefix' => 'accounts'], 
                function ($api){
                    $api->resource('admin', AdminAccountController::class);
            });
    });

    

});
