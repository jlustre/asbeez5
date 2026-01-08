<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Timezone;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->query('q', '');
        $sort = (string) $request->query('sort', 'id');
        $dir = (string) $request->query('dir', 'desc');
        $perPage = (int) $request->query('per_page', 15);
        $countryId = $request->query('country_id');
        $isActive = $request->query('is_active'); // '1'|'0'|null

        $allowedSorts = ['id', 'name', 'email', 'is_active', 'created_at'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }
        if (!in_array($dir, ['asc', 'desc'], true)) {
            $dir = 'desc';
        }
        $perPage = max(5, min($perPage, 100));

        $vendors = Vendor::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%")
                          ->orWhere('slug', 'like', "%{$q}%");
                });
            })
            ->when(!empty($countryId), function ($query) use ($countryId) {
                $query->where('country_id', $countryId);
            })
            ->when($isActive !== null && $isActive !== '', function ($query) use ($isActive) {
                $query->where('is_active', (bool) $isActive);
            })
            ->with(['country'])
            ->orderBy($sort, $dir)
            ->paginate($perPage)
            ->withQueryString();

        $countries = Country::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.vendors.index', [
            'vendors' => $vendors,
            'countries' => $countries,
            'countryId' => $countryId,
            'q' => $q,
            'sort' => $sort,
            'dir' => $dir,
            'perPage' => $perPage,
            'isActive' => $isActive,
            'allowedSorts' => $allowedSorts,
        ]);
    }

    public function create()
    {
        $vendor = new Vendor();
        $countries = Country::query()->orderBy('name')->get(['id', 'name']);
        $timezones = Timezone::query()->orderBy('name')->get(['id', 'name']);
        return view('admin.vendors.create', compact('vendor', 'countries', 'timezones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:vendors,slug'],
            'description' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:32'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'timezone_id' => ['nullable', 'exists:timezones,id'],
            'logo_url' => ['nullable', 'string', 'max:255'],
            'banner_url' => ['nullable', 'string', 'max:255'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
            'verification_status' => ['nullable', 'string', 'max:50'],
        ]);

        $vendor = Vendor::create([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address_line1' => $data['address_line1'] ?? null,
            'address_line2' => $data['address_line2'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country_id' => $data['country_id'] ?? null,
            'timezone_id' => $data['timezone_id'] ?? null,
            'logo_url' => $data['logo_url'] ?? null,
            'banner_url' => $data['banner_url'] ?? null,
            'commission_rate' => $data['commission_rate'] ?? 10.00,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'verification_status' => $data['verification_status'] ?? 'pending',
        ]);

        return redirect()->route('admin.vendors.index')->with('status', 'Vendor created');
    }

    public function show(Vendor $vendor)
    {
        $vendor->load(['country', 'timezone', 'user']);
        return view('admin.vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        $countries = Country::query()->orderBy('name')->get(['id', 'name']);
        $timezones = Timezone::query()->orderBy('name')->get(['id', 'name']);
        $vendor->load(['country', 'timezone']);
        return view('admin.vendors.edit', compact('vendor', 'countries', 'timezones'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:vendors,slug,' . $vendor->id],
            'description' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:32'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'timezone_id' => ['nullable', 'exists:timezones,id'],
            'logo_url' => ['nullable', 'string', 'max:255'],
            'banner_url' => ['nullable', 'string', 'max:255'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
            'verification_status' => ['nullable', 'string', 'max:50'],
        ]);

        $vendor->update([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address_line1' => $data['address_line1'] ?? null,
            'address_line2' => $data['address_line2'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country_id' => $data['country_id'] ?? null,
            'timezone_id' => $data['timezone_id'] ?? null,
            'logo_url' => $data['logo_url'] ?? null,
            'banner_url' => $data['banner_url'] ?? null,
            'commission_rate' => $data['commission_rate'] ?? $vendor->commission_rate,
            'is_active' => (bool) ($data['is_active'] ?? $vendor->is_active),
            'verification_status' => $data['verification_status'] ?? $vendor->verification_status,
        ]);

        return redirect()->route('admin.vendors.index')->with('status', 'Vendor updated');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('admin.vendors.index')->with('status', 'Vendor deleted');
    }
}
