<?php

use App\Http\Controllers\RequestOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/production/request-order/pdf/{reqnumber}', [RequestOrderController::class, 'pdf']);
