<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class VerifyJWTToken
{
    public function __construct()
    {   
        Config::set('auth.defaults.guard', 'api');
        
    }
    
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

            if( ! $user )
            {
                throw new UserNotDefinedException();
            }

        }catch (JWTException  $e) {
            
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->responseMessage('token_expired', Response::HTTP_UNAUTHORIZED);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['error' => 'token_invalid'], Response::HTTP_UNAUTHORIZED);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->json(['error' => 'token_blacklisted'], Response::HTTP_UNAUTHORIZED);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\UserNotDefinedException) {
                return response()->json(['error' => 'User not found.'], Response::HTTP_UNAUTHORIZED);
            } else{
                return response()->json(['error'=>'Token is required!'], Response::HTTP_UNAUTHORIZED);
            }
        }
        return $next($request);
    }
}
