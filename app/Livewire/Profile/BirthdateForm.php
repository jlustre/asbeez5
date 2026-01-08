<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Component;

class BirthdateForm extends Component
{
    public bool $editing = false;

    #[Validate('nullable|date')]
    public ?string $birthdate = null; // Y-m-d

    public function mount(): void
    {
        $profile = auth()->user()->profile;
        $this->birthdate = $profile?->birthdate
            ? Carbon::parse($profile->birthdate)->format('Y-m-d')
            : null;
    }

    public function edit(): void
    {
        $this->editing = true;
    }

    public function cancel(): void
    {
        $this->editing = false;
        $this->mount();
    }

    public function save(): void
    {
        $this->validate();
        $profile = auth()->user()->profile;
        $profile->forceFill([
            'birthdate' => $this->birthdate ?: null,
        ])->save();

        $this->editing = false;
        $this->dispatch('notify', message: 'Birthday updated');
    }

    public function render()
    {
        $formatted = $this->birthdate
            ? Carbon::parse($this->birthdate)->format('d/m/Y')
            : '—';

        return view('livewire.profile.birthdate-form', [
            'formatted' => $formatted,
        ]);
    }
}
