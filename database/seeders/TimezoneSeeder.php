<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Timezone;
use App\Models\Country;

class TimezoneSeeder extends Seeder
{
    public function run(): void
    {
        $timezones = [
            // United States
            ['name' => 'America/New_York', 'abbreviation' => 'EST', 'utc_offset' => '-05:00', 'offset_minutes' => -300, 'country_iso2' => 'US'],
            ['name' => 'America/Chicago',   'abbreviation' => 'CST', 'utc_offset' => '-06:00', 'offset_minutes' => -360, 'country_iso2' => 'US'],
            ['name' => 'America/Denver',    'abbreviation' => 'MST', 'utc_offset' => '-07:00', 'offset_minutes' => -420, 'country_iso2' => 'US'],
            ['name' => 'America/Los_Angeles','abbreviation' => 'PST', 'utc_offset' => '-08:00', 'offset_minutes' => -480, 'country_iso2' => 'US'],
            // Philippines
            ['name' => 'Asia/Manila',       'abbreviation' => 'PHT', 'utc_offset' => '+08:00', 'offset_minutes' => 480,  'country_iso2' => 'PH'],
            // United Kingdom
            ['name' => 'Europe/London',     'abbreviation' => 'GMT', 'utc_offset' => '+00:00', 'offset_minutes' => 0,    'country_iso2' => 'GB'],
        ];

        foreach ($timezones as $tz) {
            $country = Country::where('iso2', $tz['country_iso2'])->first();

            $timezone = Timezone::updateOrCreate(
                ['name' => $tz['name']],
                [
                    'abbreviation' => $tz['abbreviation'],
                    'utc_offset' => $tz['utc_offset'],
                    'offset_minutes' => $tz['offset_minutes'],
                ]
            );

            if ($country) {
                $timezone->countries()->syncWithoutDetaching([$country->id]);
            }
        }
    }
}
