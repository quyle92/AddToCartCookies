<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {   

        // $this->middleware('auth:api', ['except' => ['login']]);
        //$this->middleware('custom.jwt', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['name', 'password']);

        if (! $token = auth('api')->claims(['project_name' => 'SMLC'])->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {   

        return response()->json(auth('api')->user());//(1)
    }

    public function payload()
    {
         $payload = JWTAuth::getPayload();
         return $payload->get('project_name');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'employee_id' => auth('api')->user()->employee_id,
            'employee_name' => auth('api')->user()->name,
            'is_finished_tutorial' => auth('api')->user()->is_finished_tutorial,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
/**
 * Note
 */
//(1) must define Accept:application/json at request header to receive json respons, else it will redirect usert to login page if unthenticated.