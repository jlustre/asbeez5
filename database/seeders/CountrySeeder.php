<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            [
                'name' => 'United States',
                'iso2' => 'US',
                'iso3' => 'USA',
                'phone_code' => '1',
                'currency_code' => 'USD',
                'currency_name' => 'US Dollar',
                'region' => 'Americas',
                'subregion' => 'Northern America',
            ],
            [
                'name' => 'Canada',
                'iso2' => 'CA',
                'iso3' => 'CAN',
                'phone_code' => '1',
                'currency_code' => 'CAD',
                'currency_name' => 'Canadian Dollar',
                'region' => 'Americas',
                'subregion' => 'Northern America',
            ],
            [
                'name' => 'Mexico',
                'iso2' => 'MX',
                'iso3' => 'MEX',
                'phone_code' => '52',
                'currency_code' => 'MXN',
                'currency_name' => 'Mexican Peso',
                'region' => 'Americas',
                'subregion' => 'Northern America',
            ],
            [
                'name' => 'Philippines',
                'iso2' => 'PH',
                'iso3' => 'PHL',
                'phone_code' => '63',
                'currency_code' => 'PHP',
                'currency_name' => 'Philippine Peso',
                'region' => 'Asia',
                'subregion' => 'South-Eastern Asia',
            ],
            [
                'name' => 'United Kingdom',
                'iso2' => 'GB',
                'iso3' => 'GBR',
                'phone_code' => '44',
                'currency_code' => 'GBP',
                'currency_name' => 'Pound Sterling',
                'region' => 'Europe',
                'subregion' => 'Northern Europe',
            ],
        ];

        foreach ($countries as $c) {
            Country::updateOrCreate(
                ['iso2' => $c['iso2']],
                $c
            );
        }
    }
}
