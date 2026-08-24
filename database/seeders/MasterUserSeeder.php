<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterUserSeeder extends Seeder
{
    /**
     * Run the database seeds for Master User account.
     */
    public function run(): void
    {
        // Master Account Utama
        User::updateOrCreate(
            ['email' => 'master@gmail.com'],
            [
                'name' => 'Master Administrator',
                'password' => Hash::make('reyhansyaputra'),
                'role' => 'master',
            ]
        );

        // Master Account Cadangan
        if (User::where('email', 'master@example.com')->doesntExist()) {
            User::create([
                'name' => 'Super Master Admin',
                'email' => 'master@example.com',
                'password' => Hash::make('password'),
                'role' => 'master',
            ]);
        }
    }
}
