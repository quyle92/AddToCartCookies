<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        }catch (JWTException  $e) {

            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['error' => 'token_expired'], 400);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['error' => 'token_invalid'], 400);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->json(['error' => 'token_blacklisted'], 400);
            }else{
                return response()->json(['error'=>'Token is required']);
            }
        }
        return $next($request);
    }
}

/*
Note
 */
//if you want to refresh token upon its expiration, do this:
//$new_token = JWTAuth::refresh($token);
// JWTAuth::setToken($new_token);// must setToken() here, else when calling JWTAuth::getToken() it will get the old one.
// $response = $next($request);
// $response->header('Authorization','Bearer '.$new_token);
//
//Ref: https://stackoverflow.com/questions/44766047/jwtgettoken-doesnt-get-the-proper-token
