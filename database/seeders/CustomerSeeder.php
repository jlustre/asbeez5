<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::updateOrCreate(['email' => 'john@example.com'], [
            'name' => 'John Doe',
            'phone' => '555-1000',
            'loyalty_id' => 'L-1001',
        ]);
        Customer::updateOrCreate(['email' => 'jane@example.com'], [
            'name' => 'Jane Smith',
            'phone' => '555-2000',
            'loyalty_id' => 'L-1002',
        ]);
        Customer::updateOrCreate(['email' => 'alex@example.com'], [
            'name' => 'Alex Carter',
            'phone' => '555-3000',
            'loyalty_id' => 'L-1003',
        ]);
    }
}
