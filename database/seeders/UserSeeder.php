<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->sequence(fn($sequence) => [
                'password' => bcrypt('password'),
                'email' => 'admin@admin.com',
            ])
            ->create();
    }
}
