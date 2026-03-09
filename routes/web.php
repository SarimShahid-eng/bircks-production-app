<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Supplier\MaterialController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Supplier\SupplierPaymentController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
    });
    Route::controller(SupplierController::class)->prefix('supplier')
        ->name('suppliers.')
        ->group(function () {
            Route::get('index', 'index')->name('index');
            Route::post('store', 'store')->name('store');
            Route::get('create', 'create')->name('create');
            Route::get('edit/{supplier}', 'edit')->name('edit');
        });
    Route::controller(SupplierPaymentController::class)->prefix('suppliersPayment')
        ->name('suppliersPayment.')
        ->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{supplierRecord}', 'edit')->name('edit');
        });
    Route::controller(MaterialController::class)->prefix('materials')
        ->name('materials.')
        ->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{material}', 'edit')->name('edit');
        });
    Route::controller(PurchaseController::class)->prefix('materials')
        ->name('materials.')
        ->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{material}', 'edit')->name('edit');
        });
    // Route::controller()
});

Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'authenticate')->name('login');
    Route::middleware('auth')->group(function () {
        Route::get('logout', 'logout')->name('logout');
    });
});
// Route::get('login',')
