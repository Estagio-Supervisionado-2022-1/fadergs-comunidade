<?php

use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\DepartamentController;
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


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api){



    // REFERENTE AO LOGIN
    $api->group(['prefix'=> 'auth'], function ($api){
        $api->post('/operator/login', 'App\Http\Controllers\Auth\AuthController@operatorLogin');
        $api->post('/user/login', 'App\Http\Controllers\Auth\AuthController@userLogin');
        $api->post('/user/signup', 'App\Http\Controllers\UserController@store'); 
        

    // SOMENTE LOGADO
        $api->group(['middleware' => 'api.auth'], function ($api){
            // SESSÃO
            $api->post('/token/refresh', 'App\Http\Controllers\Auth\AuthController@refresh');
            $api->post('/logout', 'App\Http\Controllers\Auth\AuthController@logout');

            // VISUALIZAR DADOS
            // PERMISSÕES ACESSÍVEIS A TODOS
            $api->group(['prefix' => 'view'], function($api){
                $api->get('services', 'App\Http\Controllers\Admin\ServiceManagementController@index');
                $api->get('service/{service}', 'App\Http\Controllers\Admin\ServiceManagementController@show');

                $api->get('addresses', 'App\Http\Controllers\Admin\SecondaryAddressManagementController@index');
                $api->get('addresses/{address}', 'App\Http\Controllers\Admin\SecondaryAddressManagementController@show');

                $api->get('departaments', 'App\Http\Controllers\DepartamentController@index');
                $api->get('departament/{departament}', 'App\Http\Controllers\DepartamentController@show');

                // DESDE QUE O USUÁRIO/OPERADOR ESTEJA ASSOCIADO OU O COORDENADOR SEJA RESPONSÁVEL PELO DEPARTAMENTO OU SEJA O ADMIN
                $api->get('appointment/{appointment}', 'App\Http\Controllers\AppointmentManagementController@show');

                // ADMIN
                $api->group(['middleware' => ['role:admin']], function ($api) {
                    $api->get('admin/home', 'App\Http\Controllers\Admin\AdminOperatorController@index');

                    $api->get('accounts/admin', 'App\Http\Controllers\Admin\AdminAccountController@index');
                    $api->get('accounts/admin/{admin}', 'App\Http\Controllers\Admin\AdminAccountController@show');
                    
                    $api->get('accounts/manager', 'App\Http\Controllers\Admin\ManagerAccountController@index');
                    $api->get('accounts/manager/{manager}', 'App\Http\Controllers\Admin\ManagerAccountController@show');

                    $api->get('main/addresses', 'App\Http\Controllers\Admin\AddressManagementController@index');
                    $api->get('main/addresses/{address}', 'App\Http\Controllers\Admin\AddressManagementController@show');
                    
                    $api->get('admin/appointments', 'App\Http\Controllers\AdminAppointmentController@index');
                    $api->get('admin/appointments/{appointment}', 'App\Http\Controllers\AdminAppointmentController@show');

                    
                });

                // MANAGER / ADMIN
                $api->group(['middleware' => ['role:admin|manager']], function ($api) {
                    $api->get('accounts/students', 'App\Http\Controllers\Admin\StudentAccountController@index');
                    $api->get('accounts/students/{student}', 'App\Http\Controllers\Admin\StudentAccountController@show');
                });

                // MANAGER
                $api->group(['middleware' => ['role:manager']], function ($api) {
                    $api->get('manager/appointments', 'App\Http\Controllers\ManagerAppointmentController@index');
                    $api->get('manager/appointments/{appointments}', 'App\Http\Controllers\ManagerAppointmentController@show');
            });
                
                // STUDENT
                $api->group(['middleware' => ['role:student']], function ($api) {
                    $api->get('student/appointments', 'App\Http\Controllers\StudentAppointmentController@index');
                    $api->get('student/appointments/{appointments}', 'App\Http\Controllers\StudentAppointmentController@show');
            });


                $api->group(['middleware' => ['role:user']], function ($api) {
                    $api->get('user/appointments', 'App\Http\Controllers\UserAppointmentController@index'); 
                });
            });


            // CRIAÇÃO DE DADOS
            $api->group(['prefix' => 'new'], function ($api){

                // ADMIN
                $api->group(['middleware' => ['role:admin']], function ($api) {
                    $api->post('account/admin', 'App\Http\Controllers\Admin\AdminAccountController@store');
                    $api->post('account/manager', 'App\Http\Controllers\Admin\ManagerAccountController@store');
                    $api->post('departament', 'App\Http\Controllers\DepartamentController@store');
                    $api->post('admin/appointment', 'App\Http\Controllers\AdminAppointmentController@store');

            });

                // MANAGER / ADMIN
                $api->group(['middleware' => ['role:admin|manager']], function ($api) {
                    $api->post('service', 'App\Http\Controllers\Admin\ServiceManagementController@store');
                    $api->post('account/student', 'App\Http\Controllers\Admin\StudentAccountController@store');
                    $api->post('main/address', 'App\Http\Controllers\Admin\AddressManagementController@store');
                    $api->post('address', 'App\Http\Controllers\Admin\SecondaryAddressManagementController@store');
                });

                // MANAGER
                $api->group(['middleware' => ['role:manager']], function ($api){
                    $api->post('manager/appointment', 'App\Http\Controllers\ManagerAppointmentController@store');
                });


            });
            
            
            
            // ATUALIZAÇÃO DE DADOS
            // PERMISSÃO ACESSÍVEL A TODOS
            $api->group(['prefix' => 'edit'], function($api){
                // ADMIN
                $api->group(['middleware' => ['role:admin']], function ($api) {
                    $api->put('account/admin/{admin}', 'App\Http\Controllers\Admin\AdminAccountController@update');
                    $api->put('account/manager/{manager}', 'App\Http\Controllers\Admin\ManagerAccountController@update');
                    $api->put('departament/{departament}', 'App\Http\Controllers\DepartamentController@update');
                    $api->put('admin/appointment/{appointment}', 'App\Http\Controllers\AdminAppointmentController@update');
                });

                // MANAGER / ADMIN
                $api->group(['middleware' => ['role:admin|manager']], function ($api) {
                    $api->put('service/{service}', 'App\Http\Controllers\Admin\ServiceManagementController@update');
                    $api->put('account/student/{student}', 'App\Http\Controllers\Admin\StudentAccountController@update');
                    $api->put('main/address/{address}', 'App\Http\Controllers\Admin\AddressManagementController@update');
                    $api->put('address/{secondary}', 'App\Http\Controllers\Admin\SecondaryAddressManagementController@update');
                });

                $api->group(['middleware' => ['role:manager']], function ($api){
                    $api->put('manager/appointment/{appointment}', 'App\Http\Controllers\ManagerAppointmentController@update');
                });

                //VISIVEL A TODOS
                // DESDE QUE O USUÁRIO/OPERADOR ESTEJA ASSOCIADO OU O COORDENADOR SEJA RESPONSÁVEL PELO DEPARTAMENTO OU SEJA O ADMIN
                


                // RECUPERACAO DE SENHA
                $api->group(['prefix' => 'recover'], function ($api){
                    $api->post('operators/email/recover', 'App\Http\Controllers\OperatorController@sendEmailResetPassword');
                    $api->post('operators/reset', 'App\Http\Controllers\OperatorController@resetPassword');

                    $api->group(['middleware' => ['role:admin']], function ($api) {
                        $api->post('operator', 'App\Http\Controllers\Admin\AdminOperatorController@restoreOperator');
                    });
                    // ADMIN / MANAGER 
                    $api->group(['middleware' => ['role:admin|manager']], function ($api) {
                        $api->post('service', 'App\Http\Controllers\Admin\AdminOperatorController@restoreService');
                        $api->post('main/address', 'App\Http\Controllers\Admin\AdminOperatorController@restoreAddress');
                        $api->post('address', 'App\Http\Controllers\Admin\AdminOperatorController@restoreSecondaryAddress');
                    });
                });

            });

            // DELETAR DADOS
            $api->group(['prefix' => 'delete'], function($api){
                // ADMIN
                $api->group(['middleware' => ['role:admin']], function ($api) {
                    $api->delete('account/admin/{admin}', 'App\Http\Controllers\Admin\AdminAccountController@destroy');
                    $api->delete('account/manager/{manager}', 'App\Http\Controllers\Admin\ManagerAccountController@destroy');
                    $api->delete('departament/{departament}', 'App\Http\Controllers\DepartamentController@destroy');
                    
                });
                // ADMIN / MANAGER
                $api->group(['middleware' => ['role:admin|manager']], function ($api) {
                    $api->delete('service/{service}', 'App\Http\Controllers\Admin\ServiceManagementController@destroy');
                    $api->delete('main/address/{address}', 'App\Http\Controllers\Admin\AddressManagementController@destroy');
                    $api->delete('address/{address}', 'App\Http\Controllers\Admin\SecondaryAddressManagementController@destroy');
                    $api->delete('account/student/{student}', 'App\Http\Controllers\Admin\StudentAccountController@destroy');
                    $api->delete('appointment/{appointment}', 'App\Http\Controllers\AdminAppointmentController@destroy');
                }); 


            });
       
        });
    });
});
