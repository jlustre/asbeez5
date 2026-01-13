<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PosAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->has('pos_employee_id')) {
            return redirect()->route('pos');
        }
        return $next($request);
    }
}
