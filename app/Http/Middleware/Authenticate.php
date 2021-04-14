<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param $request
     * @return mixed
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('auth.redirect',
                ['provider' => 'prosanteconnect']);
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param $guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (session('authenticated')) {
            return $next($request);
        }

        return redirect()->route('auth.redirect',
            ['provider' => 'prosanteconnect']);
    }
}
