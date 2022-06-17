<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Mail\OperatorAccountCreation;
use App\Mail\OperatorAccountResetPassword;
use App\Models\Operator;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Classes\OperatorData;


class StudentAccountController extends Controller
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
        $operator = auth('api')->user();
        $operator->userRole = Operator::find($operator->id)->getRoleNames()[0];

        if ($operator->userRole == 'admin'){
            if ( $request->pagination) {
                $students = $operatorData->getDataStudentOperator($request->pagination, $request->departament);
            }
            else {
                $students = $operatorData->getDataStudentOperator(10, $request->departament);
            }
        }
        else if ($operator->userRole == 'manager') {
            if ( $request->pagination) {
                $students = $operatorData->getDataStudentOperatorLikeManager($request->pagination, $request->departament);
            }
            else {
                $students = $operatorData->getDataStudentOperatorLikeManager(10, $request->departament);
            }
        }        

        return response()->json([
            'students' => $students
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
        $operatorData = new OperatorData();

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

            $student = Operator::withTrashed()->create([
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
    
            $student->assignRole('student');

        } catch (JWTException $e) {
            throw $e;
        }

        return response()->json(['message_success' => 'Aluno criado com sucesso!'])
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

        $operator = auth()->guard('api')->user();
        $operator->userRole = Operator::find($operator->id)->getRoleNames()[0];

        if ($operator->userRole == 'student'){
            $student = $operatorData->getDataStudentById($operator->id);    
        }
        else {
            $student = $operatorData->getDataStudentById($id);    
        }
        
        return response()->json($student)->setStatusCode(200);
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

        $student = $operatorData->getDataStudentById($id);

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

                $student->updateOrCreate(['id' => $student->id], [
                    'name' => $request->name,
                ]);

            }

            if (!empty($request->email)){
                $validatorReturn = Validator::make($request->all(), [
                    'email'         => [
                        'required',
                        'email',
                        'max:255',
                    ],
                ], $operatorData->getErrorMessagesToValidate());
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()], 400);
                }

                $student->updateOrCreate(['id' => $student->id], [
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

                $student->updateOrCreate(['id' => $student->id], [
                    'departament_id' => $request->departament_id,
                ]);
            }

            if ($request->passwordReset){
                $validatorReturn = Validator::make($request->all(), [
                    'passwordReset'      => [
                        'required',
                        'accepted',
                    ],
                ], $operatorData->getErrorMessagesToValidate());
            

                if ($validatorReturn->fails()){
                    return response()->json(['errors' => $validatorReturn->errors()], 400);
                }

                $faker = Faker::create();
                $password = $faker->password(8,12);

                $student->updateOrCreate(['id' => $student->id], [
                    'password' => $password,
                ]);

                $loginData = [
                    'admin_login' => $student->email,
                    'admin_password' => $password
                ];

                Mail::to($student->email)
                    ->send(new OperatorAccountResetPassword ($loginData));  
            }
            
            $response = [
                'message' => 'Aluno atualizado com sucesso',
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
        if (! $student = Operator::find($id)) {
            throw new NotFoundHttpException('Operador não encontrado com o id = ' . $id);
        }
        try {
            if ($student->hasRole('student')){
                $student->delete();
                return response()->json(['message' => 'Aluno desativado com sucesso']);
            }
            return response()->json(['message' => 'Aluno não encontrado com o id = ' . $id]);
            
            
        } catch (HttpException $e) {
            throw $e;
        }
    }
}
