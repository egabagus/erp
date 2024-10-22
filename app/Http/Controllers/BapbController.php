<?php

namespace App\Http\Controllers;

use App\Http\Requests\BapbRequest;
use App\Models\Barang;
use App\Models\DetailBapb;
use App\Models\HeaderBapb;
use App\Services\CreateItemNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BapbController extends Controller
{
    public function index()
    {
        return view('purchasing.bapb.index');
    }

    public function create()
    {
        $email = Auth::user()->email;
        return view('purchasing.bapb.create', compact('email'));
    }

    public function edit($po_number)
    {
        return view('purchasing.bapb.create', compact('po_number'));
    }

    public function data()
    {
        $data = HeaderBapb::with('detail', 'vendor')->get();

        return DataTables::of($data)
            ->make(true);
    }

    public function store(BapbRequest $request)
    {
        DB::beginTransaction();
        try {
            $ttb = new HeaderBapb();
            $ttb->bapb_no       = CreateItemNumber::generateTtbNumber();
            $ttb->po_number     = $request->po_number;
            $ttb->date          = $request->ttb_date;
            $ttb->vendor_code   = $request->vendor;
            $ttb->status        = 1;
            $ttb->total         = $request->total;
            $ttb->plat          = $request->plat;
            $ttb->inv_no        = $request->inv_no;
            $ttb->receive      = $request->receive;
            $ttb->save();

            $detail = self::storeDetail($request, $ttb->bapb_no);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'TTB Berhasil Dibuat!'
            ], 201);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeDetail($request, $number)
    {
        foreach ($request->input('code') as $key => $code) {
            $barang = Barang::where('kode_barang', $code)->first();

            $detail = new DetailBapb();
            $detail->bapb_no        = $number;
            $detail->kode_barang    = $code;
            $detail->stok_awal      = $barang->stock;
            $detail->stok_terima    = $request->input('qty')[$key];
            $detail->stok_akhir     = $barang->stock + $request->input('qty')[$key];
            $detail->status         = 1;
            $detail->save();

            $barang->stock          = $detail->stok_akhir;
            $barang->save();
        }
    }
}
