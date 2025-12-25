<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
{
    if (auth()->check() && auth()->user()->role === 'user') {
        return redirect()->route('home')
            ->with('warning', 'Logout dulu sebelum login admin.');
    }

    return $next($request);
}
}
