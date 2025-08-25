<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1 user utama untuk login
        $adminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'is_admin' => true,
        ]);

        // Assign super_admin role to admin user
        $superAdminRole = Role::firstOrCreate(attributes: ['name' => 'super_admin']);
        $adminUser->assignRole($superAdminRole);

        // 99 user lainnya
        User::factory()->count(99)->create();
    }
}

