<?php

namespace App\Http\Controllers\Admin;

use App\Classes\OperatorData;
use App\Http\Controllers\Controller;
use App\Mail\OperatorAccountCreation;
use App\Mail\OperatorAccountResetPassword;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AdminAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $rulesToValidate = [
            'pagination' => [
                'integer',
                Rule::in([10, 25, 50, 100])
            ]
        ];

        $validatorReturn = Validator::make($request->all(), $rulesToValidate);

        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ]);
        }

        $operatorData = new OperatorData();

        if ( $request->pagination) {
            $administrators = $operatorData->getDataAdminOperator($request->pagination);
        }
        else {
            $administrators = $operatorData->getDataAdminOperator(10);
        }

        return response()->json([
            'administrators' => $administrators
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $rulesToValidate= [
            'name'          => [
                'required',
                'string',
                'min:3',
                'max:50'
            ],
            'email'         => [
                'required',
                'email',
                'max:255',
                'unique:operators,email'
            ],
            'departament_id' => [
                'required',
                'integer'
            ]
        ];
        $messagesToReturn = [
            'required' => 'O campo é obrigatório',
            'name.string' => 'O campo precisa ser uma string',
            'min' => 'O campo precisa conter no mínimo 3 carateres',
            'max' => 'O campo excedeu 50 caracteres',
            'email' => 'O campo precisa ser um e-mail válido',
            'email.max' => 'O campo excedeu 255 caracteres',
            'email.unique' => 'O e-mail já está cadastrado, reset a senha ou reative o usuário',
            'integer' => 'O campo precisa ser um número inteiro'
        ];

        $validatorReturn = Validator::make($request->all(), $rulesToValidate, $messagesToReturn);
        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ]);
        }

        try {
            $faker = Faker::create();
            $password = $faker->password(8,12);

            $administrator = Operator::withTrashed()->firstOrCreate([
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
    
            $administrator->assignRole('admin');

        } catch (JWTException $e) {
            throw $e;
        }

        return response()->json(['message_success' => 'Administrador criado com sucesso!'])
                            ->setStatusCode(201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){

        $operatorData = new OperatorData();

        $administrator = $operatorData->getDataAdminById($id);

        return response()->json($administrator)->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $operatorData = new OperatorData();

        $administrator = $operatorData->getDataAdminById($id);        

            if (!empty($request->name)){
                $validatorReturn = Validator::make($request->all(), [
                    'name'          => [
                        'required',
                        'string',
                        'min:3',
                        'max:50'
                    ]
                ]);
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()]);
                }

                $administrator->updateOrCreate(['id' => $administrator->id], [
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
                ]);
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()]);
                }

                $administrator->updateOrCreate(['id' => $administrator->id], [
                    'email' => $request->email,
                ]);

            }


            if (isset($request->departament_id)){
                $validatorReturn = Validator::make($request->all(), [
                    'departament_id' => [
                        'required',
                        'integer'
                    ]
                ]);
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()]);
                }

                $administrator->updateOrCreate(['id' => $administrator->id], [
                    'departament_id' => $request->departament_id,
                ]);
            }

            if ($request->passwordReset){
                $validatorReturn = Validator::make($request->all(), [
                    'passwordReset'      => [
                        'required',
                        'accepted',
                    ],
                ]);
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()]);
                }

                $faker = Faker::create();
                $password = $faker->password(8,12);

                $administrator->updateOrCreate(['id' => $administrator->id], [
                    'password' => $password,
                ]);

                $loginData = [
                    'admin_login' => $administrator->email,
                    'admin_password' => $password
                ];

                Mail::to($administrator->email)
                    ->send(new OperatorAccountResetPassword ($loginData));  
            }
            
            $response = [
                'message' => 'Administrador atualizado com sucesso',
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
        if (! $administrator = Operator::find($id)) {
            throw new NotFoundHttpException('Operador não encontrado com o id = ' . $id);
        }
        try {
            if ($administrator->hasRole('admin')){
                $administrator->delete();
                return response()->json(['message' => 'Administrador desativado com sucesso']);
            }

            return response()->json(['message' => 'Administrador não encontrado com o id = ' . $id]);
            
        } catch (HttpException $e) {
            throw $e;
        }

        

        
    }
}
