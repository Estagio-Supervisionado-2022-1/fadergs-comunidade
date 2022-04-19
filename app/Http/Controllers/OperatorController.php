<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Exceptions\JWTException;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'password'      => [
                'required',
                'string',
                Password::min(8)
                            ->mixedCase()
                            ->numbers()
                            ->symbols()
                            ->uncompromised()
            ],
            'departament_id' => [
                'required',
                'integer'
            ]
        ];

        $validatorReturn = Validator::make($request->all(), $rulesToValidate);
        if ($validatorReturn->fails()){
            return response()->json([
                'validation errors' => $validatorReturn->errors()
            ]);
        }
        $operator = Operator::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => $request->password,
            'departament_id'    => $request->departament_id,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
        try {
            $token = auth()->login($operator);
        } catch (JWTException $e) {
            throw $e;
        }

        
        return $this->respondWithToken($token);

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
