<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use JWTAuth;

class AuthController extends BaseApiController
{
    public const REDIRECT_AFTER_LOGIN = '/admin/chat/';
    public const REDIRECT_AFTER_LOGOUT = '/login';
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        Config::set('auth.defaults.guard', 'api');
        $this->middleware('custom.jwt', ['except' => ['login']]);
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
        JWTAuth::setToken($token);//(2)

        return $this->respondWithToken($token, $user);
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
        $token = \JWTAuth::getToken();

        \JWTAuth::invalidate($token);
        auth('api')->logout();

        return redirect(  self::REDIRECT_AFTER_LOGOUT );
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
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
/**
 * Note
 */
//(1) must define Accept:application/json at request header to receive json respons, else it will redirect usert to login page if unthenticated.
//(2)if you want to get token from JWTAuth::getToken()->get(), you have to set it first by calling JWTAuth::setToken($token), JWTAuth::getToken()->get() will return null.
//Ref: https://stackoverflow.com/questions/44766047/jwtgettoken-doesnt-get-the-proper-token
//***From my experience, when retrieving user, prevent using JWTAuth::toUser(JWTAuth::getToken()) as it will retrieve user from default guard (which is often "web" which use "User" as UserProvider as set in config\auth.php). It will cause problem if you use want e.i "users" to be UserProvider for User Authentication, but "admin" to be UserProvider for Admin Authentication (as in the case of game.swagsoft.com). Proof: Ref: https://laracasts.com/discuss/channels/laravel/jwtauth-not-working-as-expected (jensaronsson)
//Solution for this:
//UserController:
// // public function __construct()
//     {
//         Config::set('auth.defaults.guard', 'user');
//     }
//AdminController:
//// // public function __construct()
//     {
//         Config::set('auth.defaults.guard', 'admin');
//     }
//=> so JWTAuth::toUser(JWTAuth::getToken()) will use correct UserProvider to retrieve user.
