<?php

namespace Hydrofon\Http\Middleware;

use Closure;

class Impersonate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('impersonate')) {
            auth()->onceUsingId($request->session()->get('impersonate'));
        }

        return $next($request);
    }
}
