<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAuth
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
        if(!Auth::check()) {
            if($request->ajax()) {
                return response('Session Timeout, please login to continue.',401);
            }
            return redirect()->route('login');
        }
        $user = Auth::user();
        if(!isset($user->logged_on)) {
            Auth::logout();
            if($request->ajax()) {
                return response('Session Timeout',302);
            }
            return redirect()->route('login');
        }
        $user->logged_on = date('Y-m-d H:i:s');
        $user->save();
        return $next($request);
    }
}
