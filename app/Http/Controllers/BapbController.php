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

    public function edit($bapb_number)
    {
        $data = HeaderBapb::with('vendor')->where('bapb_no', $bapb_number)->first();
        return view('purchasing.bapb.edit', compact('bapb_number', 'data'));
    }

    public function data()
    {
        $data = HeaderBapb::with('detail', 'vendor')->get();

        return DataTables::of($data)
            ->make(true);
    }

    public function show($bapb_number)
    {
        try {
            $data = HeaderBapb::with('detail.item')->where('bapb_no', $bapb_number)->first();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
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

    public function update(BapbRequest $request, $bapb_number)
    {
        DB::beginTransaction();
        try {
            $ttb                = HeaderBapb::where('bapb_no', $bapb_number)->first();
            $ttb->po_number     = $request->po_number;
            $ttb->date          = $request->ttb_date;
            $ttb->vendor_code   = $request->vendor_code;
            $ttb->status        = 1;
            $ttb->total         = $request->total;
            $ttb->plat          = $request->plat;
            $ttb->inv_no        = $request->inv_no;
            $ttb->receive       = $request->receive;
            $ttb->save();

            $detail = self::updateDetail($request, $ttb->bapb_no);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'TTB Berhasil Diubah!'
            ], 200);
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

    public function updateDetail($request, $number)
    {
        foreach ($request->input('item') as $key => $code) {
            $detailbapb             = DetailBapb::where('bapb_no', $number)->where('kode_barang', $code)->first();
            $barang = Barang::where('kode_barang', $code)->first();
            $barang->stock          = $detailbapb->stok_awal;
            $detailbapb->delete();

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

    public function destroy($bapb_number)
    {
        DB::beginTransaction();
        try {
            $ttb                = HeaderBapb::where('bapb_no', $bapb_number)->first();
            $ttb->delete();

            $detail = self::destroyDetail($bapb_number);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'TTB Berhasil Dihapus!'
            ], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyDetail($bapb_number)
    {
        $detail = DetailBapb::where('bapb_no', $bapb_number)->get();
        foreach ($detail as $key => $value) {
            $value->delete();
        }
    }
}
