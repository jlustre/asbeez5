<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm muted">Owner (User ID)</label>
        <input type="number" name="user_id" value="{{ old('user_id', $vendor->user_id) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div>
        <label class="block text-sm muted">Name</label>
        <input type="text" name="name" value="{{ old('name', $vendor->name) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div>
        <label class="block text-sm muted">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $vendor->slug) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm muted">Description</label>
        <textarea name="description" rows="3"
            class="w-full p-2 rounded bg-gray-800 text-white">{{ old('description', $vendor->description) }}</textarea>
    </div>
    <div>
        <label class="block text-sm muted">Email</label>
        <input type="email" name="email" value="{{ old('email', $vendor->email) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div>
        <label class="block text-sm muted">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $vendor->phone) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div>
        <label class="block text-sm muted">Address Line 1</label>
        <input type="text" name="address_line1" value="{{ old('address_line1', $vendor->address_line1) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div>
        <label class="block text-sm muted">Address Line 2</label>
        <input type="text" name="address_line2" value="{{ old('address_line2', $vendor->address_line2) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div>
        <label class="block text-sm muted">City</label>
        <input type="text" name="city" value="{{ old('city', $vendor->city) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div>
        <label class="block text-sm muted">State</label>
        <input type="text" name="state" value="{{ old('state', $vendor->state) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div>
        <label class="block text-sm muted">Postal Code</label>
        <input type="text" name="postal_code" value="{{ old('postal_code', $vendor->postal_code) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div>
        <label class="block text-sm muted">Country</label>
        <select name="country_id" class="w-full p-2 rounded bg-gray-800 text-white">
            <option value="">Select a country</option>
            @foreach($countries as $c)
            <option value="{{ $c->id }}" {{ (string) old('country_id', $vendor->country_id) === (string) $c->id ?
                'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm muted">Timezone</label>
        <select name="timezone_id" class="w-full p-2 rounded bg-gray-800 text-white">
            <option value="">Select a timezone</option>
            @foreach($timezones as $t)
            <option value="{{ $t->id }}" {{ (string) old('timezone_id', $vendor->timezone_id) === (string) $t->id ?
                'selected' : '' }}>{{ $t->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm muted">Logo URL</label>
        <input type="text" name="logo_url" value="{{ old('logo_url', $vendor->logo_url) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div>
        <label class="block text-sm muted">Banner URL</label>
        <input type="text" name="banner_url" value="{{ old('banner_url', $vendor->banner_url) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div>
        <label class="block text-sm muted">Commission Rate (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="commission_rate"
            value="{{ old('commission_rate', $vendor->commission_rate) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
    <div>
        <label class="block text-sm muted">Active</label>
        <select name="is_active" class="w-full p-2 rounded bg-gray-800 text-white">
            <option value="1" {{ old('is_active', (int) $vendor->is_active) === 1 ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ old('is_active', (int) $vendor->is_active) === 0 ? 'selected' : '' }}>No</option>
        </select>
    </div>
    <div>
        <label class="block text-sm muted">Verification Status</label>
        <input type="text" name="verification_status"
            value="{{ old('verification_status', $vendor->verification_status) }}"
            class="w-full p-2 rounded bg-gray-800 text-white">
    </div>
</div>