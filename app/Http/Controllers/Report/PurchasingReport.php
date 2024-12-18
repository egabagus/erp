<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;

class PurchasingReport extends Controller
{
    public function po()
    {
        return view('report.purchase_order.index');
    }
}
