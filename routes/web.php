<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

use App\Http\Controllers\CompanyController;

use App\Http\Controllers\CompanyUserController;
use App\Http\Controllers\CompanyRoleController;
use App\Http\Controllers\CompanyPermissionController;

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InboundRequestController;
use App\Http\Controllers\OutboundRequestController;

use App\Http\Controllers\LocationController;

use App\Http\Controllers\CustomerComplaintController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\EmployeeController;

use App\Http\Middleware\LoginCompanyAccess;
use App\Http\Middleware\SwitchCompanyDatabase;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('ensure.logged.in')->group(function () {


});

Route::get('/lobby', function () {
    return view('lobby');
})->middleware(['auth', 'verified'])->name('lobby');


Route::middleware(['auth'])->group(function () { 
    Route::resource('users', UserController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('companies', CompanyController::class);
    Route::post('/companies/switch/{company}', [CompanyController::class, 'switchCompany'])->name('companies.switch');
    Route::get('/exit-company/{route}', [CompanyController::class, 'exitCompany'])->name('exit.company');
    Route::post('/companies/acceptInvite/{id}', [CompanyController::class, 'acceptInvite'])->name('companies.acceptInvite');
    Route::post('/companies/rejectInvite/{id}', [CompanyController::class, 'rejectInvite'])->name('companies.rejectInvite');

    Route::resource('roles', RoleController::class);
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/data', [RoleController::class, 'getRolesData'])->name('roles.data');

    Route::resource('permissions', PermissionController::class);
});

Route::middleware(['auth', 
                SwitchCompanyDatabase::class, 
                LoginCompanyAccess::class,
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::resource('company_users', CompanyUserController::class);
    Route::delete('/company_users/cancelInvite/{id}', [CompanyUserController::class, 'cancelInvite'])->name('company_users.cancelInvite');

    Route::resource('company_roles', CompanyRoleController::class);
    Route::get('/company_roles', [CompanyRoleController::class, 'index'])->name('company_roles.index');
    Route::get('/company_roles/data', [CompanyRoleController::class, 'getRolesData'])->name('company_roles.data');

    Route::resource('company_permissions', CompanyPermissionController::class);

    Route::resource('employees', EmployeeController::class);

    route::resource("customers", CustomerController::class);
    route::resource("purchases", PurchaseController::class);
    route::resource("locations", LocationController::class);

    route::resource("inbound_requests", controller: InboundRequestController::class);
    route::resource("outbound_requests", controller: OutboundRequestController::class);


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
});

require __DIR__ . '/auth.php';
