<?php

namespace App\Imports;

use App\Jobs\ExcelChunkJob;
use App\Models\Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\BeforeImport;

class ExcelImport implements ToCollection, WithHeadingRow, WithChunkReading, WithEvents
{
    use RemembersRowNumber;

    public static $currentStartRow = 1;
    public static $totalRows;
    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    public function startRow(): int
    {
        return self::$currentStartRow;
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                self::$totalRows = $event->getReader()->getTotalRows()['Sheet1'] - 1;
                Log::debug("Total rows to process: " . self::$totalRows);
            }
        ];
    }

    public function collection(Collection $rows)
    {
        Redis::incr(Excel::JOBS_QUEUED_COUNT_KEY);
        Log::debug("Jobs queued count incremented. Current count: " . Redis::get(Excel::JOBS_QUEUED_COUNT_KEY));
        ExcelChunkJob::dispatch($rows, self::$currentStartRow);

        if ($this->isLastChunk($rows->count())) {
            Redis::set(Excel::JOBS_CREATION_COMPLETED_KEY, true);
            Log::debug("All jobs created. Total rows: " . self::$totalRows);
        }
        self::$currentStartRow = self::$currentStartRow + $this->chunkSize();
    }

    private function isLastChunk($chunkRowsCount): bool
    {
        $processingRows = Redis::incrby(Excel::QUEUED_ROWS_COUNT_KEY, $chunkRowsCount);

        Log::debug('Processing rows: ' . $processingRows . ', Total rows: ' . self::$totalRows);

        return $processingRows >= self::$totalRows;
    }
}
