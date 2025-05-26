<?php

namespace Tests\Unit;

use App\Http\Services\ExcelService;
use Database\Seeders\ExcelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExcelServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            ExcelSeeder::class,
        ]);
    }
    public function test_grouped_by_date(): void
    {
        $excelService = $this->app->make(ExcelService::class);
        $groupedByDate = $excelService->getGroupedByDate();

        $this->assertNotEmpty($groupedByDate, 'Grouped data should not be empty');

        $keys = $groupedByDate->keys()->slice(0, 10);
        foreach ($keys as $key) {
            $this->assertTrue(
                \DateTime::createFromFormat('Y-m-d', $key) !== false,
                'The first item key should be a valid date in Y-m-d format'
            );
        }

    }
}
