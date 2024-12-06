<?php

use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\BapbController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerControoler;
use App\Http\Controllers\Master\ModulController;
use App\Http\Controllers\Master\PaymentMethodController;
use App\Http\Controllers\ProformaInvoiceController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RequestOrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/noaccess', function () {
    return view('role-permission.noaccess');
})->name('noaccess');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth']], function () {

    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [PermissionController::class, 'destroy']);

    Route::prefix('master')->group(function () {
        Route::controller(AdministrationController::class)->group(function () {
            Route::get('/administration', 'index');
            Route::get('/administration/data', 'data');
            Route::post('/administration/store', 'store');
        });

        Route::controller(UserController::class)->group(function () {
            Route::get('/users', 'index');
            Route::get('/users/data', 'data');
            Route::get('/users/data/{id}', 'show');
            Route::post('/users', 'store');
            Route::post('/users/update/{id}', 'update');
            Route::post('/users/delete/{id}', 'destroy');
            Route::post('/users/sign/{id}', 'uploadSignature');
        });

        Route::controller(BarangController::class)->group(function () {
            Route::get('/barang', 'index');
            Route::get('/barang/data', 'data');
            Route::post('/barang', 'store');
            Route::post('/barang/upload/{id}', 'upload');
            Route::post('/barang/update/{id}', 'update');
            Route::post('/barang/delete/{id}', 'destroy');
        });

        Route::controller(CategoryController::class)->group(function () {
            Route::get('/kategori/data', 'data');
            Route::get('/categories', 'categories');
            Route::post('/categories', 'store');
            Route::post('/categories/update/{id}', 'update');
            Route::post('/categories/delete/{id}', 'destroy');
        });

        Route::controller(SupplierController::class)->group(function () {
            Route::get('/supplier', 'index');
            Route::get('/supplier/data', 'data');
            Route::get('/supplier/data/show/{kodesupp}', 'show');
            Route::post('/supplier', 'store');
            Route::post('/supplier/update/{id}', 'update');
            Route::post('/supplier/delete/{id}', 'destroy');
            Route::get('/supplier/payment/{code}', 'showPayment');
            Route::post('/supplier/payment', 'storePayment');
        });

        Route::controller(CustomerControoler::class)->group(function () {
            Route::get('/customer', 'index');
            Route::get('/customer/data', 'data');
            Route::post('/customer', 'store');
            Route::post('/customer/update/{id}', 'update');
            Route::post('/customer/delete/{id}', 'destroy');
        });

        Route::prefix('roles')->group(function () {
            Route::resource('/', RoleController::class);
            Route::get('/data', [RoleController::class, 'data']);
            Route::get('/{roleId}/delete', [RoleController::class, 'destroy']);
            Route::get('/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole']);
            Route::put('/{roleId}/give-permissions', [RoleController::class, 'givePermissionToRole']);
        });

        Route::controller(PaymentMethodController::class)->group(function () {
            Route::get('/payment-method', 'index');
            Route::post('/payment-method', 'store');
            Route::get('/payment-method/data', 'data');
            Route::put('/payment-method/data/{id}', 'update');
            Route::delete('/payment-method/data/{id}', 'delete');
        });
    });

    Route::prefix('purchasing')->group(function () {
        Route::controller(PurchaseOrderController::class)->group(function () {
            Route::get('/purchase-order', 'index');
            Route::get('/purchase-order/create/{req_number}', 'add');
            Route::post('/purchase-order/store/', 'store');
            Route::get('/purchase-order/data', 'data');
            Route::post('/purchase-order/cancle-approve/{type}/{ponumber}', 'cancleApprove');
            Route::post('/purchase-order/approve/{type}/{ponumber}', 'approve');
        });

        Route::controller(BapbController::class)->group(function () {
            Route::get('/bapb', 'index');
            Route::get('/bapb/data', 'data');
            Route::post('/bapb', 'store');
            Route::get('/bapb/create', 'create');
            Route::get('/bapb/edit/{bapb_number}', 'edit');
            Route::get('/bapb/show/{bapb_number}', 'show');
            Route::put('/bapb/{bapb_number}', 'update');
            Route::delete('/bapb/{bapb_number}', 'destroy');
        });

        Route::prefix('master')->group(function () {
            Route::controller(BarangController::class)->group(function () {
                Route::get('/barang/{codeitem}', 'show');
            });
        });
    });

    Route::prefix('production')->group(function () {
        Route::controller(RequestOrderController::class)->group(function () {
            Route::get('/request-order', 'index');
            Route::get('/request-order/data', 'data');
            Route::get('/request-order/create', 'add');
            Route::get('/request-order/show/{req_number}', 'show');
            Route::post('/request-order/store', 'store');
            Route::post('/request-order/approve/{req_number}', 'approve');
            Route::post('/request-order/cancel-approve/{req_number}', 'cancelApprove');
        });
    });

    Route::prefix('marketing')->group(function () {
        Route::controller(TransactionHistoryController::class)->group(function () {
            Route::get('/history-transaction', 'index');
            Route::get('/history-transaction/data', 'data');
        });

        Route::controller(TransactionController::class)->group(function () {
            Route::get('/transaction', 'index');
            Route::post('/transaction', 'store');
        });

        Route::controller(ProformaInvoiceController::class)->group(function () {
            Route::get('/proforma-invoice', 'index');
        });
    });

    Route::prefix('master')->group(function () {
        Route::prefix('modul')->group(function () {
            Route::controller(ModulController::class)->group(function () {
                Route::get('/', 'index');
            });
        });
    });
});


require __DIR__ . '/auth.php';
include __DIR__ . '/pdf.php';
