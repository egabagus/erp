<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use App\Models\VendorPayment;
use App\Services\CreateItemNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index()
    {
        return view('master.supplier.index');
    }

    public function data()
    {
        $data = Supplier::get();

        return DataTables::of($data)
            ->make(true);
    }

    public function show($kodesupp)
    {
        try {
            $data = Supplier::where('kode_supp', $kodesupp)->first();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    public function store(SupplierRequest $request)
    {
        DB::beginTransaction();

        try {

            $supplier                     = new Supplier();
            $supplier->kode_supp          = CreateItemNumber::generateVendorCode();
            $supplier->nama_supp          = $request->nama_supp;
            $supplier->pic                = $request->pic;
            $supplier->handphone          = $request->handphone;
            $supplier->email              = $request->email;
            $supplier->alamat             = $request->alamat;
            $supplier->deskripsi          = $request->deskripsi;
            $supplier->status             = $request->status;
            $supplier->save();

            DB::commit();

            return response()->json([
                'data' => $supplier
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    public function update(UpdateSupplierRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $supplier = Supplier::find($id);

            $supplier->nama_supp          = $request->nama_supp;
            $supplier->pic                = $request->pic;
            $supplier->handphone          = $request->handphone;
            $supplier->email              = $request->email;
            $supplier->alamat             = $request->alamat;
            $supplier->deskripsi          = $request->deskripsi;
            $supplier->status             = $request->status;
            $supplier->save();

            DB::commit();

            return response()->json([
                'data' => $supplier
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();

            DB::commit();

            return response()->json([
                'data' => $supplier
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    public function showPayment($code)
    {
        try {
            $data = VendorPayment::where('vendor_code', $code)->get();

            return response()->json([
                'data' => $data
            ], 201);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storePayment(Request $request)
    {
        DB::beginTransaction();

        try {
            $existing = VendorPayment::where('vendor_code', $request->kode_vendor)->delete();

            foreach ($request->input('bank') as $key => $bank) {
                $payment = new VendorPayment();
                $payment->vendor_code   = $request->kode_vendor;
                $payment->name          = $bank;
                $payment->desc          = $request->input('rekening')[$key];
                $payment->save();
            }

            DB::commit();

            return response()->json([
                'data' => $payment
            ], 201);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
