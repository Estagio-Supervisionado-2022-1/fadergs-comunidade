<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! $user = auth()->guard('api_users')->user()){
            return response()->json(['message_error' => 'Usuário não está logado'], 401);
        }

        $futureAppointmentsOfUser = Appointment::where('user_id', $user->id)
                                            ->whereDate('datetime', '>=', Carbon::now()
                                                                                    ->toDateString())
                                            ->get();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
            'cpf' => [
                'required',
                'string',
                'regex:/^[0-9]{11}/'
            ],
            'telphone' => [
                'string',
                'min:11'
            ],
            'password'      => [
                'required',
                'string',
                Password::min(8)
                            ->mixedCase()
                            ->numbers()
                            ->symbols()
                            ->uncompromised()
            ],
        ];

        $validatorReturn = Validator::make($request->all(), $rulesToValidate);
        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ]);
        }
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => $request->password,
            'cpf'               => $request->cpf,
            'telphone'          => $request->telphone,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $user->assignRole('user');
        
        try {
            $token = auth()->login($user);
        } catch (JWTException $e) {
            throw $e;
        }

        $user = auth()->guard('api_users')->user();
        $user->userRole = User::find($user->id)->getRoleNames()[0];

        
        return  response()->json([
            'user' => $user,
            'token' => $this->respondWithToken($token),
        ]);

    }

    private function respondWithToken($token){
        return response()->json([
            'access_token'      => $token,
            'token_type'        => 'bearer',
            'expires_in'        => auth()->factory()->getTTL() * 60,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if (empty($user)) {
            return abort(404);
        }

        return response()->json(['user'=>$user], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $rulesToValidate = [
            'name'          => [
                'required',
                'string',
                'min:3',
                'max:50'
            ],
            'email'         => [
                'required',
                'email',
                'max:255'
            ],
            'password'      => [
                'required',
                'string',
                Password::min(8)
                            ->mixedCase()
                            ->numbers()
                            ->symbols()
                            ->uncompromised()
            ]
        ];
        $validatorReturn = Validator::make($request->all(), $rulesToValidate);
        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ]);
        }

        $user = User::find($id);

        if (empty($user)) {
            return abort(404);
        }

        $user->update([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Crypt::encrypt($request->password),
            'updated_at' => now()
        ]);

        return response()->json(['user'=>$user], 200);
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