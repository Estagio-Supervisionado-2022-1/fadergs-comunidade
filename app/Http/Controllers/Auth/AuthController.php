<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function operatorLogin(Request $request){
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
        $operator = auth()->guard('api')->user();
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

    public function userLogin(Request $request){
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
            if(!$token = auth()->guard('api_users')->attempt($credentials)){
                throw new UnauthorizedHttpException('Usuário ou senha inválidos');
            }
            
        } catch (JWTException $e) {
            throw $e;
        }
        $user = auth()->guard('api_users')->user();
        $user->userRole = User::find($user->id)->getRoleNames()[0];

        return response()->json([
            'user'=> $user,
            'token' => $this->respondWithToken($token),
        ]);
    }
}
