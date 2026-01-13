<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BranchUnit;
use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Http\Request;

class BranchUnitController extends Controller
{
    public function index()
    {
        $units = BranchUnit::with('branch')->orderBy('unit_number')->paginate(20);
        return view('admin.branch_units.index', compact('units'));
    }

    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        return view('admin.branch_units.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_id' => ['required', 'exists:branches,id'],
            'unit_number' => ['required', 'integer', 'min:1'],
            'code' => ['nullable', 'string', 'max:255', 'unique:branch_units,code'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);
        // enforce uniqueness per branch
        if (BranchUnit::where('branch_id', $data['branch_id'])->where('unit_number', $data['unit_number'])->exists()) {
            return back()->withErrors(['unit_number' => 'Unit number already exists for this branch.'])->withInput();
        }
        BranchUnit::create($data);
        return redirect()->route('admin.branch-units.index')->with('status', 'Branch unit created');
    }

    public function edit(BranchUnit $branch_unit)
    {
        $branches = Branch::orderBy('name')->get();
        return view('admin.branch_units.edit', compact('branch_unit', 'branches'));
    }

    public function update(Request $request, BranchUnit $branch_unit)
    {
        $data = $request->validate([
            'branch_id' => ['required', 'exists:branches,id'],
            'unit_number' => ['required', 'integer', 'min:1'],
            'code' => ['nullable', 'string', 'max:255', 'unique:branch_units,code,' . $branch_unit->id],
            'description' => ['nullable', 'string', 'max:255'],
        ]);
        if (BranchUnit::where('branch_id', $data['branch_id'])
            ->where('unit_number', $data['unit_number'])
            ->where('id', '!=', $branch_unit->id)
            ->exists()) {
            return back()->withErrors(['unit_number' => 'Unit number already exists for this branch.'])->withInput();
        }
        $branch_unit->update($data);
        return redirect()->route('admin.branch-units.index')->with('status', 'Branch unit updated');
    }

    public function destroy(BranchUnit $branch_unit)
    {
        $branch_unit->delete();
        return redirect()->route('admin.branch-units.index')->with('status', 'Branch unit deleted');
    }
}
