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

    $api->group(['prefix' => 'operators'], function ($api){
        $api->post('/signup', 'App\Http\Controllers\OperatorController@store');
        $api->post('/login', 'App\Http\Controllers\Auth\AuthController@login');
        
        $api->post('password/email/recover/', 'App\Http\Controllers\OperatorController@sendEmailResetPassword');
        $api->post('password/reset', 'App\Http\Controllers\OperatorController@resetPassword');
    });

    $api->group(['middleware' => 'api', 'prefix' => 'auth'], function ($api){
        $api->post('/token/refresh', 'App\Http\Controllers\Auth\AuthController@refresh');
        $api->post('/logout', 'App\Http\Controllers\Auth\AuthController@logout');
    });

    $api->group(['middleware' => ['role:admin'], 'prefix' => 'admin'], 
        function ($api){
            $api->get('/home', 'App\Http\Controllers\Admin\AdminOperatorController@index');
    });

    

});
