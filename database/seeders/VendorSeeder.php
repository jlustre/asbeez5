<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Country;
use App\Models\Timezone;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        // Primary seller
        $seller = User::where('email', 'seller@asbeez.com')->first();
        if (! $seller) {
            $admin = User::where('email', 'admin@asbeez.com')->first();
            $sponsorId = optional($admin)->id ?? 1;
            $seller = User::updateOrCreate(
                ['email' => 'seller@asbeez.com'],
                [
                    'username' => 'selleruser',
                    'sponsor_id' => $sponsorId,
                    'password' => 'password',
                    'email_verified_at' => now(),
                    'is_seller' => true,
                ]
            );
        }

        $country = Country::where('iso2', 'PH')->first();
        $timezone = Timezone::where('name', 'Asia/Manila')->first();

        Vendor::updateOrCreate(
            ['slug' => 'beezy-bazaar'],
            [
                'user_id' => $seller->id,
                'name' => 'Beezy Bazaar',
                'description' => 'Your friendly neighborhood marketplace vendor.',
                'email' => $seller->email,
                'phone' => '+63 912 345 6789',
                'address_line1' => '123 Bee Street',
                'city' => 'Makati',
                'state' => 'Metro Manila',
                'postal_code' => '1200',
                'country_id' => optional($country)->id,
                'timezone_id' => optional($timezone)->id,
                'logo_url' => null,
                'banner_url' => null,
                'commission_rate' => 10.00,
                'is_active' => true,
                'verification_status' => 'verified',
                'rating_avg' => 4.75,
                'rating_count' => 128,
                'meta' => [
                    'social' => [
                        'facebook' => 'https://facebook.com/beezybazaar',
                        'instagram' => 'https://instagram.com/beezybazaar',
                    ],
                ],
            ]
        );
    }
}
