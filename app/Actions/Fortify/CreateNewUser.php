<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Str;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Sanitize username before validating
        $input['username'] = substr(Str::lower(preg_replace('/[^a-z0-9]/', '', (string) ($input['username'] ?? ''))), 0, 25);

        // Persisted sponsor from session takes precedence if present
        $sessionSponsor = request()->session()->get('sponsor_id');
        if ($sessionSponsor) {
            $input['sponsor_id'] = $sessionSponsor;
        }

        Validator::make($input, [
            'username' => ['required', 'string', 'max:25', 'regex:/^[a-z0-9]+$/', Rule::unique(User::class, 'username')],
            'sponsor_id' => ['required','exists:users,id'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'username' => $input['username'],
            'sponsor_id' => $input['sponsor_id'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);
    }
}
