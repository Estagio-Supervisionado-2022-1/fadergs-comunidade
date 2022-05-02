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
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Spatie\Permission\Traits\HasRoles;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Faker\Factory as Faker;
use App\Mail\OperatorAccountResetPassword;
use Canducci\ZipCode\Facades\ZipCode;
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

    public function restoreOperator (Request $request) {
        if ( $operator = Operator::onlyTrashed()
            ->where('email', $request->email)
            ->with('roles', 'Departament', 'permissions')
            ->get()) {
                throw new NotFoundHttpException('Operador não encontrado com o email = ' . $request->email);
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

    public function restoreService (Request $request){
        if (! $service = Service::onlyTrashed()
            ->where('name', $request->name)
            ->first()){
                throw new NotFoundHttpException('Serviço não encontrado com o nome = ' . $request->name);
        }

        if (! Departament::onlyTrashed()
            ->where('id', $service->departament_id)
            ->get()){
                throw new NotFoundHttpException('O serviço ao qual o departamento está associado, encontra-se desabilitado');
            }
        
        $service->restore();

        return response()->json(['message_success' => 'Serviço restaurado com sucesso']);
    }

    public function restoreAddress (Request $request){

        $zipCodeInfo = ZipCode::find($request->zipcode)->getObject();

        if (! $address = Address::onlyTrashed()
            ->where('zipcode', $zipCodeInfo->cep)
            ->first()){
                throw new NotFoundHttpException('Endereço não encontrado com o cep = ' . $request->zipcode);
        }
        
        $address->restore();

        return response()->json(['message_success' => 'Serviço restaurado com sucesso']);
    }
}
