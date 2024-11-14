<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\DetailTransaction;
use App\Models\HeaderTransaction;
use App\Services\CreateItemNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        return view('marketing.transaction.index');
    }

    public function store(TransactionRequest $request)
    {
        DB::beginTransaction();
        // dd($request);
        try {
            $header = new HeaderTransaction();
            $header->no_transaksi       = CreateItemNumber::generateInvoiceNumber();
            $header->date         = $request->date;
            $header->customer_id       = $request->customer;
            $header->user_id     = $request->req_by;
            $header->status           = 1;
            $header->po_number   = $request->po_number;
            $header->note       = $request->note;
            $header->payment_note        = $request->payment_note;
            $header->subtotal        = $request->subtotal_all;
            $header->ppn_total        = $request->ppn_all;
            $header->disc_total       = $request->disc_all;
            $header->ship_date       = $request->ship_date;
            $header->freight_type       = $request->freight_type;
            $header->origin_country       = $request->origin_country;
            $header->port_embarkation       = $request->port_embarkation;
            $header->port_discharge       = $request->port_discharge;
            $header->freight_rp       = $request->freight_rp;
            $header->insurance_rp       = $request->insurance;
            $header->other       = $request->others;
            $header->total       = $request->total_all;
            $header->save();

            $detail = self::detailStore($request, $header->no_transaksi);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Invoice Berhasil Dibuat!'
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

    public function detailStore($request, $inv_no)
    {
        foreach ($request->input('code') as $key => $code) {
            $detail = new DetailTransaction();
            $detail->no_transaksi     = $inv_no;
            $detail->kode_barang         = $code;
            $detail->qty           = $request->input('qty')[$key];
            $detail->disc           = $request->input('disc')[$key];
            $detail->ppn           = $request->input('ppnrp')[$key];
            $detail->total_price           = $request->input('total')[$key];
            $detail->save();
        }
    }
}
