<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthorizationToolMiddleware
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
        Auth::guard('api')->check();
        $token = $request->has('Authorization') ?
            $request->get('Authorization') :
            $request->headers->get('Authorization');
        $request->headers->set('Authorization', $token);

        return $next($request);
    }
}
