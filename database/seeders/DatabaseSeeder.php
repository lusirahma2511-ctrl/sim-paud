<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if admin already exists
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            User::create([
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@paud.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'Aktif',
            ]);
            $this->command->info('Admin account created successfully!');
            $this->command->info('Username: admin');
            $this->command->info('Password: admin123');
        } else {
            $this->command->info('Admin account already exists!');
            $this->command->info('Username: admin');
        }
    }
}
