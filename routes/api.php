<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => '/v1'], function () {
    Route::post('/excel_upload', [\App\Http\Controllers\ExcelController::class, 'upload'])->middleware('auth.basic')->name('excel.upload');
});

