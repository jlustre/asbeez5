<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\TimezoneSeeder;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\VendorSeeder;
use Database\Seeders\CommissionRateSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Seed countries and timezones first
        $this->call([
            CountrySeeder::class,
            TimezoneSeeder::class,
            UserRoleSeeder::class,
            ProfileSeeder::class,
            VendorSeeder::class,
            CommissionRateSeeder::class,
        ]);
    }
}
