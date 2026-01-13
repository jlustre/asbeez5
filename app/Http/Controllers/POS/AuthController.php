<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required','string','max:32'],
            'pin' => ['required','string','size:4']
        ]);

        // Basic rate limiting per employee_code + IP
        $key = sprintf('pos-login:%s:%s', strtolower($validated['code']), $request->ip());
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['success' => false, 'message' => 'Too many attempts'], 429);
        }

        $employee = Employee::where('employee_code', $validated['code'])
            ->where('is_active', true)
            ->first();

        $ok = $employee && Hash::check($validated['pin'], (string) $employee->pos_pin);
        if (! $ok) {
            \Illuminate\Support\Facades\RateLimiter::hit($key, 60);
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        \Illuminate\Support\Facades\RateLimiter::clear($key);

        // Establish POS session
        $request->session()->put('pos_employee_id', $employee->id);
        $request->session()->put('pos_employee_name', $employee->name);
        $request->session()->put('pos_employee_role', $employee->role);
        $request->session()->put('pos_permission_level', (int) ($employee->permission_level ?? 1));
        if (!empty($employee->branch_id)) {
            $request->session()->put('pos_branch_id', (int) $employee->branch_id);
        }

        return response()->json(['success' => true]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'pos_employee_id',
            'pos_employee_name',
            'pos_employee_role',
            'pos_permission_level',
        ]);
        return redirect()->route('pos');
    }
}
