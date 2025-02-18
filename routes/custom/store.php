<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CompanyMiddleware;
use App\Http\Middleware\StoreMiddleware;

use App\Http\Controllers\StoreController;
use App\Http\Controllers\Store\StoreEmployeeController;
use App\Http\Controllers\StoreRoleController;
use App\Http\Controllers\StorePermissionController;

Route::middleware([
    'auth',
    CompanyMiddleware::class,
    StoreMiddleware::class,
])->group(function () {
    // Store
    Route::get('/dashboard-store', function () {
        return view('dashboard-store');
    })->name('dashboard-store');

    Route::resource('store_employees', StoreEmployeeController::class);
    
    Route::resource('store_roles', StoreRoleController::class);
    Route::resource('store_permissions', StorePermissionController::class);
});