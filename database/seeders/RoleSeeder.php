<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator with full access'
            ],
            [
                'name' => 'librarian',
                'description' => 'Librarian with book management access'
            ],
            [
                'name' => 'member',
                'description' => 'Regular member with borrowing access'
            ]
        ];

        foreach ($roles as $role) {
            \DB::table('roles')->insert($role);
        }
    }
}
