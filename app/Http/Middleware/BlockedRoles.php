<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockedRoles
{
    public function handle(Request $request, Closure $next, ...$blocked)
    {
        $user = Auth::user();
        
        // Ensure user is authenticated and their role is allowed
        if (!$user || in_array($user->role->role_name, $blocked)) {
            abort(403);
        }

        return $next($request);
    }
}
