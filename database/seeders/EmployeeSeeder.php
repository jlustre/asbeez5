<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'code' => 'C001',
                'email' => 'cashier1@asbeez.com',
                'name' => 'Cashier One',
                'role' => 'cashier',
                'permission_level' => 1,
                'pin' => '1111',
                'is_active' => true,
            ],
            [
                'code' => 'C002',
                'email' => 'cashier2@asbeez.com',
                'name' => 'Cashier Two',
                'role' => 'cashier',
                'permission_level' => 1,
                'pin' => '2222',
                'is_active' => true,
            ],
            [
                'code' => 'SUP001',
                'email' => 'supervisor@asbeez.com',
                'name' => 'Store Supervisor',
                'role' => 'supervisor',
                'permission_level' => 2,
                'pin' => '3333',
                'is_active' => true,
            ],
            [
                'code' => 'ASM001',
                'email' => 'asstmgr@asbeez.com',
                'name' => 'Assistant Manager',
                'role' => 'assistant_manager',
                'permission_level' => 3,
                'pin' => '4444',
                'is_active' => true,
            ],
            [
                'code' => 'MGR001',
                'email' => 'manager@asbeez.com',
                'name' => 'Store Manager',
                'role' => 'manager',
                'permission_level' => 4,
                'pin' => '5555',
                'is_active' => true,
            ],
            [
                'code' => 'RM001',
                'email' => 'regmanager@asbeez.com',
                'name' => 'Regional Manager',
                'role' => 'regional_manager',
                'permission_level' => 5,
                'pin' => '6666',
                'is_active' => true,
            ],
        ];

        // Try to assign seeded branch BR-001 if present
        $branchId = optional(\App\Models\Branch::where('code', 'BR-001')->first())->id;

        foreach ($employees as $e) {
            Employee::updateOrCreate(
                ['email' => $e['email']],
                [
                    'user_id' => null,
                    'branch_id' => $branchId,
                    'employee_code' => $e['code'],
                    'name' => $e['name'],
                    'phone' => null,
                    'role' => $e['role'],
                    'permission_level' => $e['permission_level'],
                    'pos_pin' => Hash::make($e['pin']),
                    'is_active' => $e['is_active'],
                    'hired_at' => now(),
                ]
            );
        }
    }
}
