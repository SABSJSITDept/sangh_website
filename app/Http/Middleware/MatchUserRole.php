<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MatchUserRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = session('user');

        if (!$user || $user->role !== $role) {
            return redirect()->route('login')->with('error', 'Access denied');
        }

        return $next($request);
    }
}
