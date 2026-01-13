<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class CashiersController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cashiers = Employee::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'email' => $e->email,
                'role' => $e->role,
            ]);

        return response()->json(['data' => $cashiers]);
    }

    public function switch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cashier_id' => ['required', 'integer', 'exists:employees,id'],
            'pin' => ['required', 'string']
        ]);

        /** @var Employee $emp */
        $emp = Employee::query()->findOrFail($validated['cashier_id']);
        // For demo purposes we store plain pin in pos_pin; in production hash and verify
        if (! Hash::check((string) $validated['pin'], (string) $emp->pos_pin)) {
            return response()->json(['message' => 'Invalid PIN'], 422);
        }

        Session::put('pos_cashier_id', $emp->id);
        Session::put('pos_cashier_name', $emp->name);

        return response()->json(['data' => [
            'id' => $emp->id,
            'name' => $emp->name,
        ]]);
    }

    public function session(Request $request): JsonResponse
    {
        $id = Session::get('pos_cashier_id');
        $name = Session::get('pos_cashier_name');
        return response()->json(['data' => $id ? ['id' => $id, 'name' => $name] : null]);
    }
}
