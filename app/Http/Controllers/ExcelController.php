<?php

namespace App\Http\Controllers;

use App\Http\Services\ExcelService;
use App\Imports\ExcelImport;
use App\Models\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel as LaravelExcel;

class ExcelController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }

        Redis::set(Excel::JOBS_QUEUED_COUNT_KEY, 0);
        Redis::set(Excel::JOBS_EXECUTING_COUNT_KEY, 0);
        Redis::set(Excel::JOBS_CREATION_COMPLETED_KEY, false);
        Redis::set(Excel::QUEUED_ROWS_COUNT_KEY, 0);
        Redis::set(Excel::PROCESSED_ROWS_COUNT_KEY, 0);
        Redis::del(Excel::IMPORT_ERRORS_KEY);

        Excel::truncate();

        LaravelExcel::import(new ExcelImport(), $file);


        return response()->json(['message' => 'File uploaded successfully and import is started']);
    }

    public function groupedByDate(ExcelService $excelService)
    {
        $rows = $excelService->getGroupedByDate();

        return response()->json($rows);
    }
}
