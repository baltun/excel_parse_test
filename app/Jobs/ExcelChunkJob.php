<?php

namespace App\Jobs;

use App\Models\Excel;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class ExcelChunkJob implements ShouldQueue
{
    use Queueable;

    private $currentRow;

    /**
     * Create a new job instance.
     */
    public function __construct(public Collection $rows, public int $currentStartRow)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Redis::incr(Excel::JOBS_EXECUTING_COUNT_KEY);
        Log::debug("Executing jobs incremented. Current count: " . Redis::get(Excel::JOBS_EXECUTING_COUNT_KEY));
        $this->currentRow = $this->currentStartRow;
        foreach ($this->rows as $row) {
            $this->currentRow++;
            if ($this->validateRow($row) && !$this->isRowIdExists($row['id'])) {
                $excel = new Excel();
                $excel->id = $row['id'];
                $excel->name = $row['name'];
                $excel->date = Carbon::createFromFormat('d.m.Y', $row['date'])->format('Y-m-d');
                $excel->save();
            }
            Redis::incr(Excel::PROCESSED_ROWS_COUNT_KEY);
            Log::debug("Processed row: {$this->currentRow}, ID: {$row['id']}, Name: {$row['name']}, Date: {$row['date']}");
        }
        Redis::decr(Excel::JOBS_QUEUED_COUNT_KEY);
        Log::debug("Jobs queued count decremented. Current count: " . Redis::get(Excel::JOBS_QUEUED_COUNT_KEY));
        Redis::decr(Excel::JOBS_EXECUTING_COUNT_KEY);
        Log::debug("Jobs running count decremented. Current count: " . Redis::get(Excel::JOBS_QUEUED_COUNT_KEY));

        if (Redis::get(Excel::JOBS_CREATION_COMPLETED_KEY) && Redis::get(Excel::JOBS_EXECUTING_COUNT_KEY) == 0) {
            $this->finalizeImport();
        }
    }

    private function validateRow(mixed $row): bool
    {

        $validator = Validator::make($row->toArray(), [
            'id' => 'required|integer|min:1|max:' . PHP_INT_MAX,
            'name' => ['required', 'regex:/^[a-zA-Z ]+$/'],
            'date' => ['required', 'date_format:d.m.Y'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = "{$this->currentRow} - " . implode(', ', $errors);

            // Store errors in Redis
            Redis::rpush(Excel::IMPORT_ERRORS_KEY, $errorMessage);

            return false;
        } else {
            return true;
        }
    }

    private function finalizeImport()
    {
        Log::debug("Finalizing import. All jobs completed.");
        $errorKey = Excel::IMPORT_ERRORS_KEY;
        $errors = Redis::lrange($errorKey, 0, -1);

        if (!empty($errors)) {
            $filePath = storage_path('result.txt');
            file_put_contents($filePath, implode(PHP_EOL, $errors), FILE_APPEND);
        }

        Redis::del(
            Excel::JOBS_QUEUED_COUNT_KEY,
            Excel::JOBS_CREATION_COMPLETED_KEY,
            Excel::QUEUED_ROWS_COUNT_KEY,
            Excel::PROCESSED_ROWS_COUNT_KEY,
            Excel::IMPORT_ERRORS_KEY
        );
    }

    private function isRowIdExists(int $rowId)
    {
        return Excel::where('id', $rowId)->exists();
    }
}
