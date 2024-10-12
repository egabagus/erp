<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
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
}
