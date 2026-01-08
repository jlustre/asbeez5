<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\Address;
use App\Models\Country;
use App\Models\Timezone;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'admin@asbeez.com' => ['first_name' => 'Admin', 'last_name' => 'User', 'country_iso2' => 'US', 'tz' => 'America/New_York'],
            'seller@asbeez.com' => ['first_name' => 'Seller', 'last_name' => 'User', 'country_iso2' => 'PH', 'tz' => 'Asia/Manila'],
            'member@asbeez.com' => ['first_name' => 'Member', 'last_name' => 'User', 'country_iso2' => 'GB', 'tz' => 'Europe/London'],
        ];

        foreach ($map as $email => $data) {
            $user = User::where('email', $email)->first();
            if (! $user) {
                continue;
            }
            $countryId = optional(Country::where('iso2', $data['country_iso2'])->first())->id;
            $timezoneId = optional(Timezone::where('name', $data['tz'])->first())->id;

            Profile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'timezone_id' => $timezoneId,
                ]
            );

            // Create or update the user's default address
            if ($countryId) {
                $addrData = [
                    'label' => 'Default',
                    'type' => 'other',
                    'address_line1' => '123 Main St',
                    'address_line2' => null,
                    'city' => null,
                    'state' => null,
                    'postal_code' => null,
                    'country_id' => $countryId,
                    'is_default' => true,
                ];
                $existing = $user->defaultAddress()->first();
                if ($existing) {
                    $existing->update($addrData);
                } else {
                    $address = $user->addresses()->create($addrData);
                    $user->setDefaultAddress($address);
                }
            }
        }
    }
}
