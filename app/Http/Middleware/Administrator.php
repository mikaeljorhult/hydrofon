<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class Administrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        // User is not logged in.
        if ($request->user() === null) {
            throw new AuthenticationException('Unauthenticated.', [], route('login'));
        }

        // User is not an administrator.
        if (! $request->user()->isAdmin()) {
            abort(403);
        }

        return $next($request);
    }
}
