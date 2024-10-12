<?php

namespace App\Http\Controllers;

use App\Http\Requests\PORequest;
use App\Models\DetailPO;
use App\Models\HeaderPO;
use App\Models\HeaderRequestOrder;
use App\Services\CreateItemNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $header = new HeaderPO();
            $header->po_number       = CreateItemNumber::generatePONumber();
            $header->po_date         = $request->po_date;
            $header->purchaser       = $request->purchaser ?? '';
            $header->vendor_code     = $request->vendor;
            $header->total           = $request->total_all;
            $header->payment_terms   = $request->payment;
            $header->incoterms       = $request->remarks;
            $header->subtotal        = $request->subtotal_all;
            $header->totalppn        = $request->ppn_all;
            $header->totaldisc       = $request->disc_all;
            $header->save();

            $detail = self::detailStore($request, $header->po_number);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'PO Berhasil Dibuat!'
            ], 201);
        } catch (\Throwable $e) {
            // dd($e);
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function detailStore($request, $ponumber)
    {
        foreach ($request->input('code') as $key => $code) {
            $detail = new DetailPO();
            $detail->po_number     = $ponumber;
            $detail->item_code         = $code;
            $detail->qty           = $request->input('qty')[$key];
            $detail->discrp           = $request->input('disc')[$key];
            $detail->taxrp           = $request->input('ppnrp')[$key];
            $detail->total           = $request->input('total')[$key];
            $detail->save();
        }
    }
}
