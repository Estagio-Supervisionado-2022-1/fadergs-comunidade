<?php

namespace App\Http\Controllers\Admin;

use App\Classes\OperatorData;
use App\Http\Controllers\Controller;
use App\Mail\OperatorAccountCreation;
use App\Mail\OperatorAccountResetPassword;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\HttpException;



class ManagerAccountController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if (!auth('api')->check()){
            abort(400, 'usuario nao possui permissao');
        }
        $operatorData = new OperatorData();

        $validatorReturn = Validator::make(
            $request->all(), 
            $operatorData->getIndexRulesToValidate(), 
            $operatorData->getErrorMessagesToValidate()
        );



        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ], 400);
        }

        if ( $request->pagination) {
            $managers = $operatorData->getDataManagerOperator($request->pagination);
        }
        else {
            $managers = $operatorData->getDataManagerOperator(10);
        }

        return response()->json([
            'managers' => $managers
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        if (!auth('api')->check()){
            abort(400, 'usuario nao possui permissao');
        }
        $operatorData = new operatorData();

        $validatorReturn = Validator::make(
            $request->all(), 
            $operatorData->getStoreRulesToValidate(), 
            $operatorData->getErrorMessagesToValidate()
        );
        
        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ], 400);
        }

        try {
            $faker = Faker::create();
            $password = $faker->password(8,12);

            $manager = Operator::withTrashed()->create([
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => $password,
                'departament_id'    => $request->departament_id,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
            $loginData = [
                'admin_login' => $request->email,
                'admin_password' => $password
            ];
            Mail::to($request->email)
                ->send(new OperatorAccountCreation ($loginData));
    
            $manager->assignRole('manager');

        } catch (JWTException $e) {
            throw $e;
        }

        return response()->json(['message_success' => 'Coordenador criado com sucesso!'])
                            ->setStatusCode(201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        if (!auth('api')->check()){
            abort(400, 'usuario nao possui permissao');
        }
        $operatorData = new OperatorData();

        $manager = $operatorData->getDataManagerById($id);
            
        return response()->json($manager)->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if (!auth('api')->check()){
            abort(400, 'usuario nao possui permissao');
        }
        $operatorData = new OperatorData();

        $manager = $operatorData->getDataManagerById($id);

            if (!empty($request->name)){
                $validatorReturn = Validator::make($request->all(), [
                    'name'          => [
                        'required',
                        'string',
                        'min:3',
                        'max:50'
                    ]
                ], $operatorData->getErrorMessagesToValidate());
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()], 400);
                }

                $manager->updateOrCreate(['id' => $manager->id], [
                    'name' => $request->name,
                ]);

            }

            if (!empty($request->email)){
                $validatorReturn = Validator::make($request->all(), [
                    'email'         => [
                        'required',
                        'email',
                        'max:255',
                        'unique:operators,email'
                    ],
                ], $operatorData->getErrorMessagesToValidate());
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()], 400);
                }

                $manager->updateOrCreate(['id' => $manager->id], [
                    'email' => $request->email,
                ]);

            }


            if (isset($request->departament_id)){
                $validatorReturn = Validator::make($request->all(), [
                    'departament_id' => [
                        'required',
                        'integer'
                    ]
                ], $operatorData->getErrorMessagesToValidate());
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()], 400);
                }

                $manager->updateOrCreate(['id' => $manager->id], [
                    'departament_id' => $request->departament_id,
                ]);
            }

            if ($request->passwordReset){
                $validatorReturn = Validator::make($request->all(), $operatorData->getPasswordResetRulesToValidade(), $operatorData->getErrorMessagesToValidate());
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()], 400);
                }

                $faker = Faker::create();
                $password = $faker->password(8,12);

                $manager->updateOrCreate(['id' => $manager->id], [
                    'password' => $password,
                ]);

                $loginData = [
                    'admin_login' => $manager->email,
                    'admin_password' => $password
                ];

                Mail::to($manager->email)
                    ->send(new OperatorAccountResetPassword ($loginData));  
            }
            
            $response = [
                'message' => 'Coordenador atualizado com sucesso',
                'id' => $id
            ];

            return response()->json($response)->setStatusCode(200);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth('api')->check()){
            abort(400, 'usuario nao possui permissao');
        }
        if (! $manager = Operator::find($id)) {
            throw new NotFoundHttpException('Operador não encontrado com o id = ' . $id);
        }
        try {
            if ($manager->hasRole('manager')){
                $manager->delete();
                return response()->json(['message' => 'Coordenador desativado com sucesso']);
            }
            return response()->json(['message' => 'Coordenador não encontrado com o id = ' . $id]);
            
            
        } catch (HttpException $e) {
            throw $e;
        }
    }

}
