<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Guest;
use App\User;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }

        // for guest chat (broadcasting)
        // $id = DB::table('guests')->insertGetId(
        //     ['guest_name' => 'guest']
        // );
        // $guest = Guest::find( $id );
        // $guest->guest_name = 'guest_' . $id;
        // $guest->save();
        $guest = Guest::find(2);
        Auth::login( $guest );
        

        return $next($request);
    }
}
