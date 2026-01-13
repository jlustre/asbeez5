<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('name')->paginate(15);
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['nullable','email','max:255','unique:employees,email'],
            'phone' => ['nullable','string','max:50'],
            'role' => ['required','string','max:50'],
            'pos_pin' => ['nullable','string','max:12'],
            'is_active' => ['nullable','boolean'],
            'hired_at' => ['nullable','date'],
        ]);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        if (!empty($data['pos_pin'])) { $data['pos_pin'] = Hash::make($data['pos_pin']); }
        Employee::create($data);
        return redirect()->route('admin.employees.index')->with('status','Employee created');
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['nullable','email','max:255','unique:employees,email,'.$employee->id],
            'phone' => ['nullable','string','max:50'],
            'role' => ['required','string','max:50'],
            'pos_pin' => ['nullable','string','max:12'],
            'is_active' => ['nullable','boolean'],
            'hired_at' => ['nullable','date'],
            'terminated_at' => ['nullable','date'],
        ]);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        if (array_key_exists('pos_pin', $data)) {
            if (!empty($data['pos_pin'])) { $data['pos_pin'] = Hash::make($data['pos_pin']); }
            else { unset($data['pos_pin']); }
        }
        $employee->update($data);
        return redirect()->route('admin.employees.index')->with('status','Employee updated');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('status','Employee deleted');
    }
}
