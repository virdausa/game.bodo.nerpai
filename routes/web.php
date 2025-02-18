<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

use App\Http\Controllers\CompanyController;

use App\Http\Controllers\Company\StoreController;

use App\Http\Middleware\CompanyMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lobby', function () {
    return view('lobby');
})->middleware(['auth', 'verified'])->name('lobby');

// App
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


// Company
Route::middleware(['auth', 
                CompanyMiddleware::class,
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Stores
    Route::resource('stores', StoreController::class);
    
    Route::post('/stores/switch/{stores}', [StoreController::class, 'switchStore'])->name('stores.switch');
    Route::get('/exit-store/{route}', [StoreController::class, 'exitStore'])->name('exit.store');
});


// require routes dari custom
foreach (glob(base_path('routes/custom/*.php')) as $file) {
    require $file;
}

require __DIR__ . '/auth.php';
