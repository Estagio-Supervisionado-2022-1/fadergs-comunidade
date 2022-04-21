<?php

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

    $api->post('/operators/signup', 'App\Http\Controllers\OperatorController@store');
    $api->post('/operators/login', 'App\Http\Controllers\Auth\AuthController@login');

    $api->group(['middleware' => 'api', 'prefix' => 'auth'], function ($api){
        $api->post('/token/refresh', 'App\Http\Controllers\Auth\AuthController@refresh');
        $api->post('/logout', 'App\Http\Controllers\Auth\AuthController@logout');
    });

    $api->group(['middleware' => ['role:admin'], 'prefix' => 'admin'], 
        function ($api){
            $api->get('/home', 'App\Http\Controllers\Admin\AdminOperatorController@index');
    });

    $api->group(['prefix' => 'departament'], function ($api) {
        $api->post('', 'App\Http\Controllers\DepartamentController@create');
        $api->put('{id}', 'App\Http\Controllers\DepartamentController@update')->where('id', '[0-9]+');
        $api->get('{id}', 'App\Http\Controllers\DepartamentController@show')->where('id', '[0-9]+');
        $api->get('', 'App\Http\Controllers\DepartamentController@index');
        $api->delete('{id}', 'App\Http\Controllers\DepartamentController@destroy')->where('id', '[0-9]+');
    });

});
