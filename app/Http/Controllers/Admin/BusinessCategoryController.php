<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusinessCategoryController extends Controller
{
    public function index()
    {
        $categories = BusinessCategory::orderBy('name')->paginate(20);
        return view('admin.business_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.business_categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:business_categories,slug'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $data['is_active'] = $data['is_active'] ?? true;
        BusinessCategory::create($data);
        return redirect()->route('admin.business-categories.index')->with('status', 'Category created');
    }

    public function edit(BusinessCategory $business_category)
    {
        return view('admin.business_categories.edit', ['category' => $business_category]);
    }

    public function update(Request $request, BusinessCategory $business_category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:business_categories,slug,' . $business_category->id],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $data['is_active'] = $data['is_active'] ?? true;
        $business_category->update($data);
        return redirect()->route('admin.business-categories.index')->with('status', 'Category updated');
    }

    public function destroy(BusinessCategory $business_category)
    {
        $business_category->delete();
        return redirect()->route('admin.business-categories.index')->with('status', 'Category deleted');
    }
}
