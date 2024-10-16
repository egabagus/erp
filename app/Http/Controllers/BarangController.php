<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangRequest;
use App\Http\Requests\PhotoRequest;
use App\Http\Requests\UpdateBarangRequest;
use App\Models\Barang;
use App\Services\CreateItemNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index()
    {
        return view('master.barang.index');
    }

    public function data()
    {
        $data = Barang::with('category')->get();

        return DataTables::of($data)
            ->make(true);
    }

    public function show($codeitem)
    {
        try {
            $data = Barang::where('kode_barang', $codeitem)->first();
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(BarangRequest $request)
    {
        DB::beginTransaction();

        try {

            $barang                     = new Barang();
            $barang->kode_barang        = CreateItemNumber::generate($request->kategori);
            $barang->nama_barang        = $request->nama_barang;
            $barang->harga              = $request->harga;
            $barang->kategori           = $request->kategori;
            $barang->satuan             = $request->satuan;
            $barang->deskripsi          = $request->deskripsi;
            $barang->status             = $request->status;
            $barang->save();

            DB::commit();

            return response()->json([
                'data' => $barang
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    public function update(UpdateBarangRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $barang = Barang::find($id);

            $data = [
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'harga' => $request->harga,
                'kategori' => $request->kategori,
                'satuan' => $request->satuan,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
            ];

            $barang->update($data);

            DB::commit();

            return response()->json([
                'data' => $barang
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
            $barang = Barang::findOrFail($id);
            $barang->delete();

            DB::commit();

            return response()->json([
                'data' => $barang
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    public function upload(PhotoRequest $request, $id)
    {
        DB::beginTransaction();

        try {

            $file = $request->file('foto');

            $extension = $file->getClientOriginalExtension();
            $fileName = Str::random(40) . '.' . $extension;

            $path = $file->storeAs('item-picture', $fileName, 'public');
            $barang = Barang::find($id);

            $barang->foto = $fileName;
            $barang->save();

            DB::commit();

            return response()->json([
                'data' => $barang
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }
}
