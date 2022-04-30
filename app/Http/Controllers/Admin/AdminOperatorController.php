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

    
}
