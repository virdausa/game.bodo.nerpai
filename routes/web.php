<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InboundRequestController;
use App\Http\Controllers\LocationController;

use App\Http\Controllers\CustomerComplaintController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

route::resource("customers", CustomerController::class);
route::resource("purchases", PurchaseController::class);
route::resource("locations", LocationController::class);

route::resource("inbound_request", controller: InboundRequestController::class);


route::resource("products", controller: ProductController::class);

Route::resource('sales', SalesController::class);
Route::get('sales/{sale}/status/{status}', [SalesController::class, 'updateStatus'])->name('sales.updateStatus');
Route::put('sales/{sale}', [SalesController::class, 'update'])->name('sales.update');
Route::resource('customer_complaints', CustomerComplaintController::class);
Route::put('customer_complaints/{customer_complaint}/resolve', [CustomerComplaintController::class, 'resolve'])->name('customer_complaints.resolve');

Route::resource('sales', SalesController::class);

Route::resource('suppliers', SupplierController::class);
Route::resource('warehouses', WarehouseController::class);

Route::resource('inventory', InventoryController::class)->except(['show']);
Route::get('/inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
Route::get('/inventory/history', [InventoryController::class, 'history'])->name('inventory.history');

Route::get('sales/{sale}/status/{status}', [SalesController::class, 'updateStatus'])->name('sales.updateStatus');
Route::put('sales/{sale}', [SalesController::class, 'update'])->name('sales.update');
Route::resource('customer_complaints', CustomerComplaintController::class);
Route::put('customer_complaints/{customer_complaint}/resolve', [CustomerComplaintController::class, 'resolve'])->name('customer_complaints.resolve');

Route::resource('roles', RoleController::class);
Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
Route::get('/roles/data', [RoleController::class, 'getRolesData'])->name('roles.data');

Route::resource('permissions', PermissionController::class);


Route::resource('employees', EmployeeController::class);
require __DIR__ . '/auth.php';
