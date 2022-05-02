<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ServiceData;
use App\Http\Controllers\Controller;
use App\Models\Departament;
use App\Models\Service;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class ServiceManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        $serviceData = new ServiceData();

        $validatorReturn = Validator::make(
            $request->all(), 
            $serviceData->getIndexRulesToValidate(), 
            $serviceData->getErrorMessagesToValidate()
        );

        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ]);
        }

        if ( $request->pagination) {
            $services = $serviceData->getServiceData ($request->pagination);
        }
        else {
            $services = $serviceData->getServiceData(10);
        }

        return response()->json([
            'services' => $services
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $serviceData = new ServiceData();

        $validatorReturn = Validator::make(
            $request->all(), 
            $serviceData->getStoreRulesToValidate(), 
            $serviceData->getErrorMessagesToValidate()
        );


        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ]);
        }

        try {

            if (! $departament = Departament::find($request->departament_id)){
                throw new NotFoundHttpException('Departamento não encontrado com o id = ' . $request->departament_id);
            }

            $service = Service::withTrashed()->firstOrCreate([
                'name'              => $request->name,
                'departament_id'    => $departament->id,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

        } catch (JWTException $e) {
            throw $e;
        }

        return response()->json(['message_success' => 'Departamento criado com sucesso!'])
                            ->setStatusCode(201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        if (! $service = Service::where('id', $id)->with('departaments')->first()) {
            throw new NotFoundHttpException('Serviço não encontrado com o id = ' . $id);
        }

        return response()->json($service)->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $serviceData = new ServiceData();

        if (! $service = Service::where('id', $id)->with('departaments')->first()) {
            throw new NotFoundHttpException('Serviço não encontrado com o id = ' . $id);
        }
            if (!empty($request->name)){
                $validatorReturn = Validator::make($request->all(), [
                    'name'          => [
                        'required',
                        'string',
                        'min:3',
                        'max:50'
                    ]
                ], $serviceData->getErrorMessagesToValidate());
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()]);
                }

                $service->updateOrCreate(['id' => $service->id], [
                    'name' => $request->name,
                ]);

            }

            if (!empty($request->departament_id)){
                $validatorReturn = Validator::make($request->all(), [
                    'departament_id' => [
                        'required',
                        'integer'
                    ]
                ], $serviceData->getErrorMessagesToValidate());
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()]);
                }

                

                if (! $departament = Departament::find($request->departament_id)){
                    throw new NotFoundHttpException('Departamento não encontrado com o id = ' . $request->departament_id);
                }
                
                $service->updateOrCreate(['id' => $id], [
                    'departament_id' => $departament->id,
                ]);
            
            
            $response = [
                'message' => 'Serviço atualizado com sucesso',
                'id' => $id
            ];

            return response()->json($response)->setStatusCode(200);
        }

        return response()->json(['message_fail' => 'Não foi possível criar o serviço, entre em contato com o administrador']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! $service = Service::find($id)) {
            throw new NotFoundHttpException('Serviço não encontrado com o id = ' . $id);
        }
        try {
                $service->delete();
                return response()->json(['message' => 'Serviço desativado com sucesso']);
                        
        } catch (HttpException $e) {
            throw $e;
        }
    }
}
