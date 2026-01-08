<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@asbeez.com'],
            [
                'username' => 'administrator',
                'sponsor_id' => 1,
                'password' => 'password', // hashed via cast
                'email_verified_at' => now(),
                'is_admin' => true,
                'is_seller' => false,
            ]
        );
        if (function_exists('hash_id') && empty($admin->hashed_id)) {
            $admin->forceFill(['hashed_id' => hash_id($admin->id)])->saveQuietly();
        }

        // Seller user
        $seller = User::updateOrCreate(
            ['email' => 'seller@asbeez.com'],
            [
                'username' => 'selleruser',
                'sponsor_id' => null,
                'password' => 'password',
                'email_verified_at' => now(),
                'is_admin' => false,
                'is_seller' => true,
            ]
        );
        if (function_exists('hash_id') && empty($seller->hashed_id)) {
            $seller->forceFill(['hashed_id' => hash_id($seller->id)])->saveQuietly();
        }

        // Member user
        $member = User::updateOrCreate(
            ['email' => 'member@asbeez.com'],
            [
                'username' => 'memberuser',
                'sponsor_id' => null,
                'password' => 'password',
                'email_verified_at' => now(),
                'is_admin' => false,
                'is_seller' => false,
            ]
        );
        // Set sponsor relationships after creation to satisfy FK constraints
        $seller->sponsor_id = $admin->id;
        $seller->saveQuietly();
        $member->sponsor_id = $seller->id;
        $member->saveQuietly();
        if (function_exists('hash_id') && empty($member->hashed_id)) {
            $member->forceFill(['hashed_id' => hash_id($member->id)])->saveQuietly();
        }
    }
}
