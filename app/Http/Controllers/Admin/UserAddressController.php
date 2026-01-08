<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function store(Request $request, User $user)
    {
        $data = $request->validate([
            'label' => ['nullable','string','max:64'],
            'type' => ['nullable','string','max:32'],
            'address_line1' => ['required','string','max:255'],
            'address_line2' => ['nullable','string','max:255'],
            'city' => ['nullable','string','max:128'],
            'state' => ['nullable','string','max:128'],
            'postal_code' => ['nullable','string','max:32'],
            'country_id' => ['nullable','exists:countries,id'],
            'is_default' => ['nullable','boolean'],
        ]);

        $address = $user->addresses()->create([
            'label' => $data['label'] ?? 'Other',
            'type' => $data['type'] ?? 'other',
            'address_line1' => $data['address_line1'],
            'address_line2' => $data['address_line2'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country_id' => $data['country_id'] ?? null,
            'is_default' => (bool)($data['is_default'] ?? false),
        ]);

        if (!empty($data['is_default'])) {
            $user->setDefaultAddress($address);
        }

        return redirect()->route('admin.users.edit', $user)->with('status', 'Address added');
    }

    public function update(Request $request, User $user, Address $address)
    {
        abort_unless($address->user_id === $user->id, 404);

        $data = $request->validate([
            'label' => ['nullable','string','max:64'],
            'type' => ['nullable','string','max:32'],
            'address_line1' => ['required','string','max:255'],
            'address_line2' => ['nullable','string','max:255'],
            'city' => ['nullable','string','max:128'],
            'state' => ['nullable','string','max:128'],
            'postal_code' => ['nullable','string','max:32'],
            'country_id' => ['nullable','exists:countries,id'],
            'is_default' => ['nullable','boolean'],
        ]);

        $address->update([
            'label' => $data['label'] ?? $address->label,
            'type' => $data['type'] ?? $address->type,
            'address_line1' => $data['address_line1'],
            'address_line2' => $data['address_line2'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country_id' => $data['country_id'] ?? null,
            'is_default' => (bool)($data['is_default'] ?? false),
        ]);

        if (!empty($data['is_default'])) {
            $user->setDefaultAddress($address);
        }

        return redirect()->route('admin.users.edit', $user)->with('status', 'Address updated');
    }

    public function setDefault(User $user, Address $address)
    {
        abort_unless($address->user_id === $user->id, 404);
        $user->setDefaultAddress($address);
        return redirect()->route('admin.users.edit', $user)->with('status', 'Default address updated');
    }

    public function destroy(User $user, Address $address)
    {
        abort_unless($address->user_id === $user->id, 404);
        $wasDefault = (bool) $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $first = $user->addresses()->orderBy('id')->first();
            if ($first) {
                $user->setDefaultAddress($first);
            }
        }

        return redirect()->route('admin.users.edit', $user)->with('status', 'Address deleted');
    }
}
