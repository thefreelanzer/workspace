<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/get-companies', [CompanyController::class, 'getCompanies'])->name('get_companies');
    Route::get('/get-employees', [EmployeeController::class, 'getEmployees'])->name('get_employees');
});

// Route to retrieve the authenticated user
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
