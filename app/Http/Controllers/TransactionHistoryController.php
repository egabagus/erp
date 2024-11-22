<?php

namespace App\Http\Controllers;

use App\Models\HeaderTransaction;
use App\Services\Invoices\Proforma;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionHistoryController extends Controller
{
    public function index()
    {
        return view('marketing.history.index');
    }

    public static function data()
    {
        $data = HeaderTransaction::with('customer')->get();
        return DataTables::of($data)
            ->make(true);
    }

    public function proforma($inv_num)
    {
        return Proforma::generate($inv_num);
    }
}
