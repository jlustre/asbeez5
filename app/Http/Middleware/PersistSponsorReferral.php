<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class PersistSponsorReferral
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $sp = $request->query('sp');

        if (! is_null($sp)) {
            $id = null;

            // Accept numeric IDs or 10-char hashed values
            if (is_numeric($sp)) {
                $id = (int) $sp;
            } elseif (is_string($sp) && strlen($sp) === 10) {
                $decoded = unhash_id($sp);
                if (is_int($decoded)) {
                    $id = $decoded;
                }
            }

            if (! is_null($id)) {
                $user = User::find($id);
                if ($user) {
                    $request->session()->put('sponsor_id', $user->id);
                    // Clear any previous invalid flag when sponsor is valid
                    $request->session()->forget('invalid_referral');
                } else {
                    // Invalid user id
                    $request->session()->forget('sponsor_id');
                    $request->session()->put('invalid_referral', true);
                }
            } else {
                // Could not decode sp
                $request->session()->forget('sponsor_id');
                $request->session()->put('invalid_referral', true);
            }
        }

        return $next($request);
    }
}
