<?php

namespace App\Http\Services;

use App\Models\Excel;

class ExcelService
{
    public function getGroupedByDate()
    {
        $rows = Excel::all()->groupBy('date')->map(function ($group) {
            return $group->toArray();
        });

        return $rows;
    }
}
