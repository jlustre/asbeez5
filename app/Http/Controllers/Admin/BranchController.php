<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with('category')->orderBy('name')->paginate(20);
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        $categories = BusinessCategory::orderBy('name')->get();
        $employees = \App\Models\Employee::orderBy('name')->get();
        return view('admin.branches.create', compact('categories', 'employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'business_category_id' => ['required', 'exists:business_categories,id'],
            'code' => ['nullable', 'string', 'max:255', 'unique:branches,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'manager_employee_id' => ['nullable', 'exists:employees,id'],
            'assistant_manager_employee_id' => ['nullable', 'exists:employees,id'],
            'pricing_type' => ['nullable', 'string', 'max:255'],
            'opening_hours' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $data['is_active'] ?? true;
        // decode opening_hours if JSON provided
        if (!empty($data['opening_hours'])) {
            $decoded = json_decode($data['opening_hours'], true);
            $data['opening_hours'] = $decoded ?? null;
        }
        $branch = Branch::create($data);
        // ensure each branch has at least one unit
        \App\Models\BranchUnit::firstOrCreate(
            ['branch_id' => $branch->id, 'unit_number' => 1],
            ['code' => $branch->code ? $branch->code . '-U1' : null, 'description' => 'Default unit']
        );
        return redirect()->route('admin.branches.index')->with('status', 'Branch created');
    }

    public function edit(Branch $branch)
    {
        $categories = BusinessCategory::orderBy('name')->get();
        $employees = \App\Models\Employee::orderBy('name')->get();
        return view('admin.branches.edit', compact('branch', 'categories', 'employees'));
    }

    public function update(Request $request, Branch $branch)
    {
        $data = $request->validate([
            'business_category_id' => ['required', 'exists:business_categories,id'],
            'code' => ['nullable', 'string', 'max:255', 'unique:branches,code,' . $branch->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'manager_employee_id' => ['nullable', 'exists:employees,id'],
            'assistant_manager_employee_id' => ['nullable', 'exists:employees,id'],
            'pricing_type' => ['nullable', 'string', 'max:255'],
            'opening_hours' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $data['is_active'] ?? true;
        if (!empty($data['opening_hours'])) {
            $decoded = json_decode($data['opening_hours'], true);
            $data['opening_hours'] = $decoded ?? null;
        }
        $branch->update($data);
        return redirect()->route('admin.branches.index')->with('status', 'Branch updated');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('admin.branches.index')->with('status', 'Branch deleted');
    }
}
