<?php

namespace App\Http\Controllers\Admin;

use App\Classes\AddressData;
use App\Classes\DepartamentData;
use App\Classes\OperatorData;
use App\Classes\ServiceData;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Departament;
use App\Models\Operator;
use App\Models\Service;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Spatie\Permission\Traits\HasRoles;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Faker\Factory as Faker;
use App\Mail\OperatorAccountResetPassword;
use Illuminate\Support\Facades\Mail;

class AdminOperatorController extends Controller
{
    use HasRoles;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
        if(! Operator::all() && Address::all() && Service::all() && Departament::all()){
            throw new NotFoundHttpException('Não existem dados nos registros, por favor, contate o adminsitrador');
        }

        $operatorData = new OperatorData();
        $addressData = new AddressData();
        $serviceData = new ServiceData();
        $departamentData = new DepartamentData();
        
        $administrators = $operatorData->getCountAdminOperator();
        $managers = $operatorData->getCountManagerOperator();
        $students = $operatorData->getCountStudentOperator();

        $addressess = $addressData->getCountAddresses();

        $services = $serviceData->getCountServices();

        $departaments = $departamentData->getCountDepartaments();


        return response()->json([
            'administrators' => $administrators,
            'managers' => $managers,
            'students' => $students,
            'addressess' => $addressess,
            'services' => $services,
            'departaments' => $departaments
        ]);
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function restoreOperator ($email) {
        if (! $operator = Operator::onlyTrashed()->where('email', $email)->with('roles', 'Departament', 'permissions')) {
            throw new NotFoundHttpException('Operador não encontrado com o email = ' . $email);
        }

        try {
            $operator->restore();

            $faker = Faker::create();
            $password = $faker->password(8,12);

            $operator->updateOrCreate(['id' => $operator->id], [
                'password' => $password,
            ]);

            $loginData = [
                'admin_login' => $operator->email,
                'admin_password' => $password
            ];

            Mail::to($operator->email)
                ->send(new OperatorAccountResetPassword ($loginData));  
        
            return response()->json(['message_success' => 'Operador reativado com sucesso, uma nova senha foi enviada ao e-mail']);
        } catch (HttpException $e) {
            throw $e;
        }
    }
}
