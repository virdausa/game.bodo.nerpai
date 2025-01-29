<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix("supplier")->group(function () {
    Route::get("/", [SupplierController::class, "index"]);
    Route::get("/{supplier}", [SupplierController::class, "show"]);
    Route::post("/", [SupplierController::class, "store"]);
    Route::patch("/{supplier}", [SupplierController::class, "update"]);
    Route::delete("/{supplier}", [SupplierController::class, "destroy"]);
});
