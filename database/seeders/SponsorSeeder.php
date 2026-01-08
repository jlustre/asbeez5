<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sponsor user if one doesn't already exist
        $user = User::where('email', 'sponsor@example.com')->first();

        if (! $user) {
            $user = new User();
            $user->username = 'sponsor';
            $user->email = 'sponsor@example.com';
            $user->password = Hash::make('password');
            $user->email_verified_at = now();
            // Optional flags if your schema supports them
            if (property_exists($user, 'is_seller')) {
                $user->is_seller = true;
            }
            $user->remember_token = Str::random(10);
            $user->save();
        }

        // Output the hashed referral code for convenience
        if (function_exists('hash_id')) {
            $code = hash_id($user->id);
            $this->command?->info("Sponsor created: ID={$user->id}, username={$user->username}, code={$code}");
            $this->command?->info("Test URL: http://127.0.0.1:8000/register?sp={$code}");
        } else {
            $this->command?->warn('hash_id() not available. Ensure app/Support/helpers.php is autoloaded.');
        }
    }
}
