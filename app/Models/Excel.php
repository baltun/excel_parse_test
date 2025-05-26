<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excel extends Model
{
    use HasFactory;

    public const JOBS_QUEUED_COUNT_KEY = 'excel_import_jobs_queued_count';
    public const JOBS_EXECUTING_COUNT_KEY = 'excel_import_jobs_executing_count';
    public const JOBS_CREATION_COMPLETED_KEY = 'excel_import_jobs_creation_completed';

    public const QUEUED_ROWS_COUNT_KEY = 'excel_import_queued_rows_count'; // это значение увеличивается на 1000 с каждым запущенным на импорт чанком
    public const PROCESSED_ROWS_COUNT_KEY = 'excel_import_processed_rows_count'; // это значение увеличивается на 1 с каждой обработанной в джобе строкой

    public const IMPORT_ERRORS_KEY = 'excel_import_errors';


    protected $table = 'excel';



    public $fillable = [
        'id',
        'name',
        'date',
    ];
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = false;
}
