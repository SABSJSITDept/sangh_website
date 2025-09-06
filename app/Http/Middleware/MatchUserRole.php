<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MatchUserRole
{
    /**
     * Handle an incoming request.
     *
     * Usage in routes:
     *   ->middleware('matchRole:sahitya,super_admin')
     *
     * This method accepts either:
     *  - a single comma-separated string ("role1,role2")
     *  - or multiple parameters (role1, role2)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = session('user');

        // no user in session => redirect to login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Access denied');
        }

        // If roles were passed as a single comma-separated string, split them
        if (count($roles) === 1 && is_string($roles[0]) && strpos($roles[0], ',') !== false) {
            $roles = array_map('trim', explode(',', $roles[0]));
        } else {
            // trim every provided role param
            $roles = array_map(function ($r) {
                return is_string($r) ? trim($r) : $r;
            }, $roles);
        }

        // If no roles supplied accidentally, deny
        if (empty($roles)) {
            return redirect()->route('login')->with('error', 'Access denied');
        }

        // Check if user's role matches any of the allowed roles
        // Use strict comparison if you expect exact match
        if (!in_array($user->role, $roles, true)) {
            return redirect()->route('login')->with('error', 'Access denied');
        }

        return $next($request);
    }
}
