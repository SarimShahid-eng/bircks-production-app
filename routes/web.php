<?php

use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerLedgerController;
use App\Http\Controllers\Customer\CustomerPaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductionBatchController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Supplier\SupplierLedgerController;
use App\Http\Controllers\Supplier\SupplierPaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
    });
    Route::prefix('supplier')
        ->name('suppliers.')
        ->group(function () {
            Route::controller(SupplierController::class)->group(function () {
                Route::get('index', 'index')->name('index');
                Route::post('store', 'store')->name('store');
                Route::get('create', 'create')->name('create');
                Route::get('edit/{supplier}', 'edit')->name('edit');
            });
            Route::controller(SupplierLedgerController::class)->group(function () {
                Route::get('ledger', 'ledger')->name('ledger');
            });
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
    Route::controller(PurchaseController::class)->prefix('purchases')
        ->name('purchases.')
        ->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{purchase}', 'edit')->name('edit');
        });
    Route::prefix('customer')
        ->name('customers.')
        ->group(function () {
            Route::controller(CustomerController::class)->group(function () {
                Route::get('index', 'index')->name('index');
                Route::post('store', 'store')->name('store');
                Route::get('create', 'create')->name('create');
                Route::get('edit/{customer}', 'edit')->name('edit');
            });
            Route::controller(CustomerLedgerController::class)->group(function () {
                Route::get('ledger', 'ledger')->name('ledger');
            });
        });
    Route::controller(CustomerPaymentController::class)->prefix('customerPayment')
        ->name('customersPayment.')
        ->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{customerRecord}', 'edit')->name('edit');
        });
    Route::controller(SaleController::class)->prefix('sales')
        ->name('sales.')
        ->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{sale}', 'edit')->name('edit');
        });
    Route::controller(ProductionBatchController::class)->prefix('production_batches')
        ->name('production_batches.')
        ->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{batch}', 'edit')->name('edit');
        });
    Route::get('/materials/{material}/rate', fn (\App\Models\Material $material) => response()->json(['avg_rate' => $material->avg_rate])
    )->name('materials.rate');
    // Route::controller()
});

Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'authenticate')->name('login');
    Route::middleware('auth')->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
});
// Route::get('login',')
