<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;

class BackfillDefaultAddressesSeeder extends Seeder
{
    public function run(): void
    {
        User::query()
            ->select(['id'])
            ->chunkById(200, function ($users) {
                foreach ($users as $user) {
                    // If a default address already exists, skip
                    if ($user->defaultAddress()->exists()) {
                        continue;
                    }

                    // If user has any address, set the first one as default
                    $first = $user->addresses()->orderBy('id')->first();
                    if ($first) {
                        $user->setDefaultAddress($first);
                        continue;
                    }

                    // Otherwise, create a placeholder default address
                    $address = $user->addresses()->create([
                        'label' => 'Default',
                        'type' => 'other',
                        'address_line1' => 'N/A',
                        'address_line2' => null,
                        'city' => null,
                        'state' => null,
                        'postal_code' => null,
                        'country_id' => null,
                        'is_default' => true,
                    ]);
                    $user->setDefaultAddress($address);
                }
            });
    }
}
