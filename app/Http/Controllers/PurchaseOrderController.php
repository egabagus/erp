<?php

namespace App\Http\Controllers;

use App\Models\HeaderRequestOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index()
    {
        return view('purchasing.purchase-order.index');
    }

    public function add($req_number)
    {
        return view('purchasing.purchase-order.create', compact('req_number'));
    }
}
