<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Categori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function data()
    {
        $data = Categori::get();

        return DataTables::of($data)
            ->make(true);
    }

    public function categories(Request $request)
    {
        $data = Categori::when($request->search, function ($query) use ($request) {
            $params = $request->search;
            return $query->where('nama_kategori', 'LIKE', "%$params%");
        })->get();

        return DataTables::of($data)
            ->make(true);
    }

    public function store(CategoryRequest $request)
    {
        DB::beginTransaction();

        try {

            $kategori                     = new Categori();
            $kategori->kode_kategori      = $request->kode_kategori;
            $kategori->nama_kategori      = $request->nama_kategori;
            $kategori->deskripsi          = $request->deskripsi;
            $kategori->status             = $request->status;
            $kategori->save();

            DB::commit();

            return response()->json([
                'data' => $kategori
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $barang = Categori::find($id);

            $data = [
                'kode_kategori' => $request->kode_kategori,
                'nama_kategori' => $request->nama_kategori,
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
            $barang = Categori::findOrFail($id);
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
}
