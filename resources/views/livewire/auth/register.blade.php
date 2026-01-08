<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')"
            :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            @php($spId = session('sponsor_id', request('sp')))
            @php($spUser = $spId ? \App\Models\User::find($spId) : null)
            @php($invalidReferral = session('invalid_referral') === true || (request()->has('sp') && !$spUser))
            <!-- Username -->
            @if($invalidReferral)
            <div class="text-sm text-red-600">{{ __('Invalid referral link. Please ask your sponsor for the correct
                link.') }}</div>
            @endif
            <flux:input name="username" :label="__('Username')" :value="old('username')" type="text" required autofocus
                autocomplete="username" :placeholder="__('Username')" />

            <!-- Sponsor -->
            @if($spUser)
            <flux:input :label="__('Sponsor')" type="text" :value="$spUser->username" disabled />
            <input type="hidden" name="sponsor_id" value="{{ $spUser->id }}">
            @else
            <flux:input name="sponsor_id" :label="__('Sponsor')" :value="old('sponsor_id')" type="number" required
                autocomplete="off" :placeholder="__('Enter Sponsor')" />
            @endif

            <!-- Email Address -->
            <flux:input name="email" :label="__('Email address')" :value="old('email')" type="email" required
                autocomplete="email" placeholder="email@example.com" />

            <!-- Password -->
            <flux:input name="password" :label="__('Password')" type="password" required autocomplete="new-password"
                :placeholder="__('Password')" viewable />

            <!-- Confirm Password -->
            <flux:input name="password_confirmation" :label="__('Confirm password')" type="password" required
                autocomplete="new-password" :placeholder="__('Confirm password')" viewable />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button"
                    :disabled="$invalidReferral">
                    {{ $invalidReferral ? __('Invalid referral link') : __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>