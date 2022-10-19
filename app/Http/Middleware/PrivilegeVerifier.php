<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PrivilegeVerifier
{
    /**
     * Handle an incoming request.
     * User - Info - [committeeInfo] - Committee
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next , $RequiredCollection = null)
    {
        if(!Auth::check() || ( $RequiredCollection == null  ) && Auth::user()->info->committee->isEmpty() || ( $RequiredCollection != null ) && Auth::user->info->committee->union($RequiredCollection)->isEmpy())
            return redirect(route('home'));

        return $next($request);
    }
}
