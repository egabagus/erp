<?php

namespace App\Http\Controllers;

use App\Models\HeaderTransaction;
use App\Services\Invoices\Invoice;
use App\Services\Invoices\Proforma;
use App\Services\Pdf\PackingList;
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

    public function invoice($inv_num)
    {
        return Invoice::generate($inv_num);
    }

    public function packing_list($inv_num)
    {
        return PackingList::generate($inv_num);
    }
}
