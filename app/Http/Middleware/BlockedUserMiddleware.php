<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;


class BlockedUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,  $guard = null): Response
    {

        if ($request->method() != 'GET') {
            $user = Auth::user();
            //$res = $user->is_blocked ? true : false;
            if ($user->is_blocked)
            abort(403, __("messages.unauthorized"));

            return $next($request);
        }

        return $next($request);
       
    }
}
