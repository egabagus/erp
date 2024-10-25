<?php

namespace App\Http\Controllers;

use App\Models\HeaderTransaction;
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
        $data = HeaderTransaction::get();
        return DataTables::of($data)
            ->make(true);
    }
}
