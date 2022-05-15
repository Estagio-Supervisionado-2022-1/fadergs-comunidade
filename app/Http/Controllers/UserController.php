<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(! $users =  User::all()){
            throw new NotFoundHttpException('Usuários não encontrados');
        }

        return response()->json(['users'=>$users], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
                'max:255',
                'unique:users,email'
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

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Crypt::encrypt($request->password),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return response()->json($user, 201);
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

        return response()->json($user, 200);
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