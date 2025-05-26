<?php

namespace Database\Seeders;

use App\Models\Excel;
use Illuminate\Database\Seeder;

class ExcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Excel::factory()
            ->count(10)
            ->create();
    }
}
