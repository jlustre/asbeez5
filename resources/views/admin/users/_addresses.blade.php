<div style="margin-top:24px;">
    <h2 style="margin:0 0 12px 0;">Addresses</h2>

    @if(session('status'))
    <div style="margin-bottom:12px; padding:10px; border-radius:8px; background:#1f2937; color:#e5e7eb;">
        {{ session('status') }}
    </div>
    @endif

    <div style="display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:12px;">
        <div>
            <h3 style="margin:0 0 8px 0;">Existing Addresses</h3>
            <div style="display:flex; flex-direction:column; gap:8px;">
                @forelse($user->addresses as $addr)
                <div
                    style="padding:10px; border:1px solid #1f2937; border-radius:8px; background:#0b1220; color:#e5e7eb;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <strong>{{ $addr->label ?? 'Address' }}</strong>
                            @if($addr->is_default)
                            <span class="muted" style="margin-left:8px;">(Default)</span>
                            @endif
                        </div>
                        <div style="display:flex; gap:8px;">
                            <details>
                                <summary class="btn" style="cursor:pointer;">Edit</summary>
                                <div style="margin-top:8px;">
                                    <form method="post"
                                        action="{{ route('admin.users.addresses.update', [$user, $addr]) }}">
                                        @csrf
                                        @method('PUT')
                                        <div
                                            style="display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:8px;">
                                            <div>
                                                <label>Label</label>
                                                <input type="text" name="label" value="{{ $addr->label }}"
                                                    style="width:100%; padding:6px; border-radius:6px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                                            </div>
                                            <div>
                                                <label>Type</label>
                                                <input type="text" name="type" value="{{ $addr->type }}"
                                                    style="width:100%; padding:6px; border-radius:6px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                                            </div>
                                            <div>
                                                <label>Address Line 1</label>
                                                <input type="text" name="address_line1"
                                                    value="{{ $addr->address_line1 }}" required
                                                    style="width:100%; padding:6px; border-radius:6px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                                            </div>
                                            <div>
                                                <label>Address Line 2</label>
                                                <input type="text" name="address_line2"
                                                    value="{{ $addr->address_line2 }}"
                                                    style="width:100%; padding:6px; border-radius:6px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                                            </div>
                                            <div>
                                                <label>City</label>
                                                <input type="text" name="city" value="{{ $addr->city }}"
                                                    style="width:100%; padding:6px; border-radius:6px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                                            </div>
                                            <div>
                                                <label>State</label>
                                                <input type="text" name="state" value="{{ $addr->state }}"
                                                    style="width:100%; padding:6px; border-radius:6px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                                            </div>
                                            <div>
                                                <label>Postal Code</label>
                                                <input type="text" name="postal_code" value="{{ $addr->postal_code }}"
                                                    style="width:100%; padding:6px; border-radius:6px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                                            </div>
                                            <div>
                                                <label>Country</label>
                                                <select name="country_id"
                                                    style="width:100%; padding:6px; border-radius:6px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                                                    <option value="">Select country</option>
                                                    @foreach(($countries ?? []) as $c)
                                                    <option value="{{ $c->id }}" {{ (string)$addr->country_id ===
                                                        (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                <input type="checkbox" name="is_default" value="1"
                                                    id="is_default_{{ $addr->id }}" {{ $addr->is_default ? 'checked' :
                                                '' }}>
                                                <label for="is_default_{{ $addr->id }}">Set as default</label>
                                            </div>
                                        </div>
                                        <div style="margin-top:8px; display:flex; gap:8px;">
                                            <button class="btn" type="submit">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </details>
                            @if(!$addr->is_default)
                            <form method="post"
                                action="{{ route('admin.users.addresses.set-default', [$user, $addr]) }}">
                                @csrf
                                <button class="btn" type="submit">Set Default</button>
                            </form>
                            @endif
                            <form method="post" action="{{ route('admin.users.addresses.destroy', [$user, $addr]) }}"
                                onsubmit="return confirm('Delete this address?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn" type="submit" style="background:#7f1d1d;">Delete</button>
                            </form>
                        </div>
                    </div>
                    <div style="margin-top:8px;">
                        <div>{{ $addr->address_line1 }} {{ $addr->address_line2 }}</div>
                        <div>{{ $addr->city }} {{ $addr->state }} {{ $addr->postal_code }}</div>
                        <div>{{ optional($addr->country)->name }}</div>
                        <div class="muted">Type: {{ $addr->type ?? 'other' }}</div>
                    </div>
                </div>
                @empty
                <div class="muted">No addresses yet.</div>
                @endforelse
            </div>
        </div>
        <div>
            <h3 style="margin:0 0 8px 0;">Add New Address</h3>
            <form method="post" action="{{ route('admin.users.addresses.store', $user) }}">
                @csrf
                <div style="display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:12px;">
                    <div>
                        <label>Label</label>
                        <input type="text" name="label" placeholder="Home/Office"
                            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                    </div>
                    <div>
                        <label>Type</label>
                        <input type="text" name="type" placeholder="billing/shipping/other"
                            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                    </div>
                    <div>
                        <label>Address Line 1</label>
                        <input type="text" name="address_line1" required
                            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                    </div>
                    <div>
                        <label>Address Line 2</label>
                        <input type="text" name="address_line2"
                            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                    </div>
                    <div>
                        <label>City</label>
                        <input type="text" name="city"
                            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                    </div>
                    <div>
                        <label>State</label>
                        <input type="text" name="state"
                            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                    </div>
                    <div>
                        <label>Postal Code</label>
                        <input type="text" name="postal_code"
                            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                    </div>
                    <div>
                        <label>Country</label>
                        <select name="country_id"
                            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
                            <option value="">Select country</option>
                            @foreach(($countries ?? []) as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <input type="checkbox" name="is_default" value="1" id="is_default">
                        <label for="is_default">Set as default</label>
                    </div>
                </div>
                <div style="margin-top:12px;">
                    <button class="btn" type="submit">Add Address</button>
                </div>
            </form>
        </div>
    </div>
</div>