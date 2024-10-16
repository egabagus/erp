<?php

use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RequestOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/production/request-order/pdf/{reqnumber}', [RequestOrderController::class, 'pdf']);
Route::get('/purchasing/purchase-order/print-pdf/{ponumber}', [PurchaseOrderController::class, 'pdf']);
