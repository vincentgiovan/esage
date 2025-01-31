<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllowedRoles
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$allowed)
    {
        $user = Auth::user();
        
        // Ensure user is authenticated and their role is allowed
        if (!$user || !in_array($user->role->role_name, $allowed)) {
            abort(403);
        }

        return $next($request);
    }
}
