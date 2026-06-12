<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsGuru
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'guru') {
            return $next($request);
        }

        abort(403);
    }
}