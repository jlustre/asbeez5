<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommissionRate;
use App\Models\Vendor;
use App\Models\VendorCommissionRate;

class CommissionRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'type' => 'percent',
                'tier' => 'Starter',
                'description' => 'Default commission for new vendors',
                'rate' => 10.00,
                'min_orders' => null,
                'max_orders' => 99,
                'min_order_value' => null,
                'max_order_value' => null,
                'min_revenue' => null,
                'qualifications' => [
                    'kpi' => [ 'orders' => '<100' ],
                ],
                'starts_at' => null,
                'ends_at' => null,
                'is_active' => true,
            ],
            [
                'type' => 'percent',
                'tier' => 'Standard',
                'description' => 'Lower rate once you are established',
                'rate' => 8.00,
                'min_orders' => 100,
                'max_orders' => 499,
                'min_order_value' => null,
                'max_order_value' => null,
                'min_revenue' => null,
                'qualifications' => [
                    'kpi' => [ 'orders_range' => '100-499' ],
                ],
                'starts_at' => null,
                'ends_at' => null,
                'is_active' => true,
            ],
            [
                'type' => 'percent',
                'tier' => 'Premium',
                'description' => 'Best rate for high volume vendors',
                'rate' => 5.00,
                'min_orders' => 500,
                'max_orders' => null,
                'min_order_value' => null,
                'max_order_value' => null,
                'min_revenue' => null,
                'qualifications' => [
                    'kpi' => [ 'orders' => '>=500' ],
                ],
                'starts_at' => null,
                'ends_at' => null,
                'is_active' => true,
            ],
            [
                'type' => 'fixed',
                'tier' => 'Fixed Lite',
                'description' => 'Flat fee for low-margin items',
                'rate' => 5.00,
                'min_orders' => null,
                'max_orders' => null,
                'min_order_value' => null,
                'max_order_value' => null,
                'min_revenue' => null,
                'qualifications' => [
                    'categories' => ['low-margin'],
                ],
                'starts_at' => null,
                'ends_at' => null,
                'is_active' => true,
            ],
            [
                'type' => 'fixed',
                'tier' => 'Fixed Enterprise',
                'description' => 'Flat fee for enterprise contracts',
                'rate' => 2.50,
                'min_orders' => null,
                'max_orders' => null,
                'min_order_value' => null,
                'max_order_value' => null,
                'min_revenue' => 100000.00,
                'qualifications' => [
                    'revenue' => '>=100000',
                ],
                'starts_at' => null,
                'ends_at' => null,
                'is_active' => true,
            ],
        ];

        foreach ($tiers as $t) {
            CommissionRate::updateOrCreate(
                [
                    'type' => $t['type'],
                    'tier' => $t['tier'],
                ],
                $t
            );
        }

        // Optional: Assign a preferential rate to Beezy Bazaar if present
        $vendor = Vendor::query()->where('slug', 'beezy-bazaar')->first();
        if ($vendor) {
            $preferred = CommissionRate::updateOrCreate(
                [
                    'type' => 'percent',
                    'tier' => 'Vendor Preferred',
                ],
                [
                    'description' => 'Preferred rate for Beezy Bazaar',
                    'rate' => 4.00,
                    'min_orders' => null,
                    'max_orders' => null,
                    'min_order_value' => null,
                    'max_order_value' => null,
                    'min_revenue' => null,
                    'qualifications' => [ 'vendor_slug' => 'beezy-bazaar' ],
                    'starts_at' => null,
                    'ends_at' => null,
                    'is_active' => true,
                ]
            );

            VendorCommissionRate::updateOrCreate(
                [
                    'vendor_id' => $vendor->id,
                    'commission_rate_id' => $preferred->id,
                    'starts_at' => $preferred->starts_at,
                ],
                [
                    'is_active' => true,
                    'priority' => 10,
                    'notes' => 'Auto-assigned preferred rate in seeder',
                ]
            );
        }
    }
}
