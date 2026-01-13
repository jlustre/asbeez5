<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\BusinessCategory;
use App\Models\Branch;
use App\Models\BranchUnit;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure a demo Business Category exists
        $category = BusinessCategory::updateOrCreate(
            ['slug' => 'grocery'],
            [
                'name' => 'Grocery',
                'description' => 'Grocery retail category',
                'is_active' => true,
                'extra' => [],
            ]
        );

        // Create a demo Branch
        $branch = Branch::updateOrCreate(
            ['code' => 'GR-001'],
            [
                'business_category_id' => $category->id,
                'name' => 'AsBeez Main Store',
                'description' => 'Primary grocery retail branch for demos',
                'phone' => '555-0100',
                'email' => 'main-store@asbeez.com',
                'address_line1' => '123 Market Street',
                'address_line2' => null,
                'city' => 'Beeville',
                'state' => 'CA',
                'postal_code' => '90001',
                'country' => 'US',
                'latitude' => 34.0522,
                'longitude' => -118.2437,
                'is_active' => true,
                'pricing_type' => 'retail',
                'opening_hours' => [
                    'mon-fri' => ['09:00','18:00'],
                    'sat' => ['10:00','16:00'],
                    'sun' => null,
                ],
                'extra' => [],
            ]
        );

        // Create minimal Branch Units (1 and 2)
        BranchUnit::updateOrCreate(
            ['branch_id' => $branch->id, 'unit_number' => 1],
            [
                'code' => 'REG-01',
                'description' => 'Front register',
            ]
        );
        BranchUnit::updateOrCreate(
            ['branch_id' => $branch->id, 'unit_number' => 2],
            [
                'code' => 'REG-02',
                'description' => 'Side register',
            ]
        );
    }
}
