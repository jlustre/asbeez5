<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePermissionLevel
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('permission:6') ensures current user's permission_level >= 6
     */
    public function handle(Request $request, Closure $next, ?string $minLevel = null)
    {
        $required = is_numeric($minLevel) ? (int) $minLevel : 1;

        $principal = Auth::user();

        if (! $principal) {
            // Not authenticated
            abort(403, 'Authentication required');
        }

        $level = (int) ($principal->permission_level ?? 0);

        if ($level < $required) {
            abort(403, 'Insufficient permission level');
        }

        return $next($request);
    }
}
