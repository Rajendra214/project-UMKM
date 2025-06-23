<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create Super Admin
        Admin::create([
            'name' => 'Super Administrator',
            'email' => 'admin@umkmprediction.com',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create Regular Admin
        Admin::create([
            'name' => 'Admin UMKM',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        echo "✅ Admin accounts created successfully!\n";
        echo "📧 Super Admin: admin@umkmprediction.com | Password: admin123\n";
        echo "📧 Regular Admin: admin@example.com | Password: password\n";
    }
}
