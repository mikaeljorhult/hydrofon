<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Auth\AuthenticationException;

class Administrator
{
    /**
     * Handle an incoming request.
     *
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle(Request $request, Closure $next): Response
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
