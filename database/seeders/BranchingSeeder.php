<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use App\Models\Branch;
use App\Models\BranchUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BranchingSeeder extends Seeder
{
    public function run(): void
    {
        $category = BusinessCategory::firstOrCreate(
            ['slug' => 'grocery'],
            ['name' => 'Grocery', 'description' => 'General grocery category']
        );

        $branch = Branch::firstOrCreate(
            ['code' => 'BR-001'],
            [
                'business_category_id' => $category->id,
                'name' => 'Downtown Branch',
                'description' => 'Primary store in city center',
                'phone' => '555-0101',
                'email' => 'downtown@example.com',
                'address_line1' => '123 Main St',
                'city' => 'Metropolis',
                'state' => 'CA',
                'postal_code' => '94016',
                'country' => 'US',
                'latitude' => 37.7749,
                'longitude' => -122.4194,
            ]
        );

        BranchUnit::firstOrCreate(
            ['branch_id' => $branch->id, 'unit_number' => 1],
            [
                'code' => 'BR-001-U1',
                'description' => 'Front Register Unit',
            ]
        );

        BranchUnit::firstOrCreate(
            ['branch_id' => $branch->id, 'unit_number' => 2],
            [
                'code' => 'BR-001-U2',
                'description' => 'Back Office Unit',
            ]
        );
    }
}
