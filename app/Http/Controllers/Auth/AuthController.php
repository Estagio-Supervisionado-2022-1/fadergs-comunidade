<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request){
        $rulesToValidate = [
            'email'         => [
                'required',
                'email',
                'max:255',
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

        $credentials = $request->only('email', 'password');

        try {
            if(!$token = auth()->attempt($credentials)){
                throw new UnauthorizedHttpException('Usuário ou senha inválidos');
            }
            
        } catch (JWTException $e) {
            throw $e;
        }
        $operator = auth()->user();
        $operator->userRole = Operator::find($operator->id)->getRoleNames()[0];

        return response()->json([
            'user'=> $operator,
            'token' => $this->respondWithToken($token),
        ]);
        
    }

    public function refresh (){
        try {
            if(!$token = auth()->getToken()){
                throw new NotFoundHttpException('Token não existe');
            }

            return $this->respondWithToken(auth()->refresh($token));

        } catch (JWTException $e) {
            throw $e;
        }
    }

    public function logout (){
        try {
            auth()->logout();
        } catch (JWTException $e) {
            throw $e;
        }
        
        return response()->json(['message' => 'Usuário deslogado com sucesso']);
    }


    private function respondWithToken($token){
        return response()->json([
            'access_token'      => $token,
            'token_type'        => 'bearer',
            'expires_in'        => auth()->factory()->getTTL() * 60,
        ]);
    }

    
}
