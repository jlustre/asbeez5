<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request)
    {
        $user = $request->user();

        $target = route('dashboard');
        if ($user && ($user->is_admin ?? false)) {
            $target = route('admin.dashboard');
        } elseif ($user && ($user->is_seller ?? false)) {
            $target = route('seller.dashboard');
        }

        // Respect intended if present, else go to role-based target
        return redirect()->intended($target);
    }
}
