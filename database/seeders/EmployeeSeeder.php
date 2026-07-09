<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            ['name' => 'Manager 01', 'email' => 'nks.manager01@gmail.com', 'role' => 'manager', 'base_salary' => 15000000],
            ['name' => 'Manager 02', 'email' => 'nks.manager02@gmail.com', 'role' => 'manager', 'base_salary' => 15000000],
            ['name' => 'Chef 01', 'email' => 'nks.chef01@gmail.com', 'role' => 'chef', 'base_salary' => 12000000],
            ['name' => 'Chef 02', 'email' => 'nks.chef02@gmail.com', 'role' => 'chef', 'base_salary' => 12000000],
            ['name' => 'Waiter 01', 'email' => 'nks.waiter01@gmail.com', 'role' => 'waiter', 'base_salary' => 8000000],
        ];

        foreach ($employees as $employee) {
            \App\Models\User::updateOrCreate(
                ['email' => $employee['email']],
                [
                    'name' => $employee['name'],
                    'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                    'role' => $employee['role'],
                    'base_salary' => $employee['base_salary'],
                    'is_admin' => false,
                ]
            );
        }
    }
}
