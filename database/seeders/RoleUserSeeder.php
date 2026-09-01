<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@proofofcontent.test',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Sales Representative',
                'email' => 'sales@proofofcontent.test',
                'password' => Hash::make('password'),
                'role' => 'sales',
            ],
            [
                'name' => 'Verifier / Backoffice',
                'email' => 'verifier@proofofcontent.test',
                'password' => Hash::make('password'),
                'role' => 'verifier',
            ]
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
