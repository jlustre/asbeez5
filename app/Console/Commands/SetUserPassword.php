<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetUserPassword extends Command
{
    protected $signature = 'user:password {email} {password}';
    protected $description = 'Set a user\'s password (hashing applied via cast)';

    public function handle(): int
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        /** @var User|null $user */
        $user = User::where('email', $email)->first();
        if (! $user) {
            $this->error("User not found: {$email}");
            return self::FAILURE;
        }

        $user->password = $password; // Will be hashed by the model's casts
        $user->save();

        $this->info("Password updated for {$email}.");
        return self::SUCCESS;
    }
}
