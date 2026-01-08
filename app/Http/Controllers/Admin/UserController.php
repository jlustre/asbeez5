<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Timezone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->query('q', '');
        $role = (string) $request->query('role', ''); // admin|seller|member|''
        $sort = (string) $request->query('sort', 'id');
        $dir = (string) $request->query('dir', 'desc');
        $perPage = (int) $request->query('per_page', 15);
        $countryId = $request->query('country_id');

        $allowedSorts = ['id', 'username', 'email', 'is_admin', 'is_seller', 'created_at'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }
        if (!in_array($dir, ['asc', 'desc'], true)) {
            $dir = 'desc';
        }
        $perPage = max(5, min($perPage, 100));

        $users = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('username', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when(!empty($countryId), function ($query) use ($countryId) {
                $query->whereHas('addresses', function ($a) use ($countryId) {
                    $a->where('country_id', $countryId)->where('is_default', true);
                });
            })
            ->when($role === 'admin', fn($q) => $q->where('is_admin', true))
            ->when($role === 'seller', fn($q) => $q->where('is_seller', true))
            ->when($role === 'member', fn($q) => $q->where(function($qq){
                $qq->where('is_admin', false)->where('is_seller', false);
            }))
            ->with(['defaultAddress'])
            ->orderBy($sort, $dir)
            ->paginate($perPage)
            ->withQueryString();

        $countries = Country::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.users.index', [
            'users' => $users,
            'countries' => $countries,
            'countryId' => $countryId,
            'q' => $q,
            'role' => $role,
            'sort' => $sort,
            'dir' => $dir,
            'perPage' => $perPage,
            'allowedSorts' => $allowedSorts,
        ]);
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        $user = new User();
        $countries = Country::query()->orderBy('name')->get(['id','name']);
        $timezones = Timezone::query()->orderBy('name')->get(['id','name']);
        $sponsors = User::query()->orderBy('username')->get(['id','username']);
        return view('admin.users.create', compact('user','countries','timezones','sponsors'));
    }

    public function edit(User $user)
    {
        $countries = Country::query()->orderBy('name')->get(['id','name']);
        $timezones = Timezone::query()->orderBy('name')->get(['id','name']);
        $user->load(['profile.timezone','defaultAddress','addresses.country']);
        $sponsors = User::query()->orderBy('username')->get(['id','username']);
        return view('admin.users.edit', compact('user','countries','timezones','sponsors'));
    }

    public function store(Request $request)
    {
        // Sanitize username: lowercase, alphanumeric only, max 25 chars
        $sanitized = Str::lower(preg_replace('/[^a-z0-9]/', '', (string) $request->input('username')));
        $request->merge(['username' => substr($sanitized, 0, 25)]);

        $data = $request->validate([
            'username' => ['required', 'string', 'max:25', 'regex:/^[a-z0-9]+$/', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'sponsor_id' => ['required','exists:users,id'],
            'is_admin' => ['sometimes', 'boolean'],
            'is_seller' => ['sometimes', 'boolean'],
            'is_online' => ['sometimes', 'boolean'],
            'last_login_at' => ['nullable', 'date'],
            'last_login_ip' => ['nullable', 'string', 'max:64'],
        ]);

        $user = new User();
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->sponsor_id = $data['sponsor_id'];
        $user->password = Hash::make($data['password']);
        $user->is_admin = (bool) ($data['is_admin'] ?? false);
        $user->is_seller = (bool) ($data['is_seller'] ?? false);
        $user->is_online = (bool) ($data['is_online'] ?? false);
        $user->last_login_at = $data['last_login_at'] ?? null;
        $user->last_login_ip = $data['last_login_ip'] ?? null;
        $user->save();

        // Profile data (create/update after user is saved)
        $profileData = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'phone' => $request->input('phone'),
            'timezone_id' => $request->input('timezone_id'),
            'avatar_url' => $request->input('avatar_url'),
            'birthdate' => $request->input('birthdate'),
        ];
        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

        // Addresses (normalize): create default address if provided
        $addr = [
            'label' => $request->input('label') ?: 'Default',
            'type' => $request->input('type') ?: 'other',
            'address_line1' => $request->input('address_line1'),
            'address_line2' => $request->input('address_line2'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'postal_code' => $request->input('postal_code'),
            'country_id' => $request->input('country_id'),
            'is_default' => true,
        ];
        if (!empty($addr['address_line1'])) {
            $address = $user->addresses()->create($addr);
            $user->setDefaultAddress($address);
        }

        return redirect()->route('admin.users.index')->with('status', 'User created');
    }

    public function update(Request $request, User $user)
    {
        // Sanitize username: lowercase, alphanumeric only, max 25 chars
        $sanitized = Str::lower(preg_replace('/[^a-z0-9]/', '', (string) $request->input('username')));
        $request->merge(['username' => substr($sanitized, 0, 25)]);

        $data = $request->validate([
            'username' => ['required', 'string', 'max:25', 'regex:/^[a-z0-9]+$/', 'unique:users,username,' . $user->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'sponsor_id' => ['required','exists:users,id'],
            'password' => ['nullable', 'string', 'min:8'],
            'is_admin' => ['sometimes', 'boolean'],
            'is_seller' => ['sometimes', 'boolean'],
            'is_online' => ['sometimes', 'boolean'],
            'last_login_at' => ['nullable', 'date'],
            'last_login_ip' => ['nullable', 'string', 'max:64'],
        ]);

        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->sponsor_id = $data['sponsor_id'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->is_admin = (bool) ($data['is_admin'] ?? false);
        $user->is_seller = (bool) ($data['is_seller'] ?? false);
        $user->is_online = (bool) ($data['is_online'] ?? false);
        $user->last_login_at = $data['last_login_at'] ?? $user->last_login_at;
        $user->last_login_ip = $data['last_login_ip'] ?? $user->last_login_ip;
        $user->save();

        // Profile data
        $profileData = [
            'first_name' => $request->input('first_name', $user->profile->first_name ?? null),
            'last_name' => $request->input('last_name', $user->profile->last_name ?? null),
            'phone' => $request->input('phone', $user->profile->phone ?? null),
            'timezone_id' => $request->input('timezone_id', $user->profile->timezone_id ?? null),
            'avatar_url' => $request->input('avatar_url', $user->profile->avatar_url ?? null),
            'birthdate' => $request->input('birthdate', $user->profile->birthdate ?? null),
        ];
        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

        // Addresses (normalize): update or create default address
        $addr = [
            'label' => $request->input('label', $user->defaultAddress->label ?? 'Default'),
            'type' => $request->input('type', $user->defaultAddress->type ?? 'other'),
            'address_line1' => $request->input('address_line1', $user->defaultAddress->address_line1 ?? null),
            'address_line2' => $request->input('address_line2', $user->defaultAddress->address_line2 ?? null),
            'city' => $request->input('city', $user->defaultAddress->city ?? null),
            'state' => $request->input('state', $user->defaultAddress->state ?? null),
            'postal_code' => $request->input('postal_code', $user->defaultAddress->postal_code ?? null),
            'country_id' => $request->input('country_id', $user->defaultAddress->country_id ?? null),
            'is_default' => true,
        ];
        if (!empty($addr['address_line1'])) {
            $address = $user->defaultAddress()->first();
            if ($address) {
                $address->update($addr);
            } else {
                $address = $user->addresses()->create($addr);
            }
            $user->setDefaultAddress($address);
        }

        return redirect()->route('admin.users.index')->with('status', 'User updated');
    }

    public function destroy(User $user)
    {
        // prevent self-deletion
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('status', 'User deleted');
    }
}
