<?php

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
        $api->post('/operator/signup', 'App\Http\Controllers\OperatorController@store');
        $api->post('/operator/login', 'App\Http\Controllers\Auth\AuthController@login');
        
        $api->group(['middleware' => 'api.auth', 'prefix' => 'operator'], function ($api){
            $api->post('/token/refresh', 'App\Http\Controllers\Auth\AuthController@refresh');
            $api->post('/logout', 'App\Http\Controllers\Auth\AuthController@logout');
        });
    });

    // Route::resource('users', UserController::class);
    $api->group(['prefix' => 'api/user'], function ($api) {
        $api->post('', 'App\Http\Controllers\UserController@create');
        $api->put('{id}', 'App\Http\Controllers\UserController@update')->where('id', '[0-9]+');
        $api->get('{id}', 'App\Http\Controllers\UserController@show')->where('id', '[0-9]+');
        $api->get('', 'App\Http\Controllers\UserController@index');
    });    

    $api->group(['middleware' => ['role:admin'], 'prefix' => 'admin'], 
        function ($api){
            $api->get('/home', 'App\Http\Controllers\Admin\AdminOperatorController@index');
    });
});
