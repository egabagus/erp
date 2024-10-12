<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\CreateItemNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerControoler extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index()
    {
        return view('master.client.index');
    }

    public function data()
    {
        $data = Customer::get();

        return DataTables::of($data)
            ->make(true);
    }

    public function store(CustomerRequest $request)
    {
        DB::beginTransaction();

        try {
            $cust                     = new Customer();
            $cust->kode_cust          = CreateItemNumber::generateCustCode();
            $cust->nama_cust          = $request->nama_cust;
            $cust->pic                = $request->pic;
            $cust->handphone          = $request->handphone;
            $cust->email              = $request->email;
            $cust->alamat             = $request->alamat;
            $cust->state              = $request->state;
            $cust->prov               = $request->prov;
            $cust->city               = $request->city;
            $cust->district           = $request->district;
            $cust->village            = $request->village;
            $cust->deskripsi          = $request->deskripsi;
            $cust->status             = $request->status;
            $cust->save();

            DB::commit();

            return response()->json([
                'data' => $cust
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    public function update(UpdateCustomerRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $cust = Customer::find($id);

            $cust->nama_cust          = $request->nama_cust;
            $cust->pic                = $request->pic;
            $cust->handphone          = $request->handphone;
            $cust->email              = $request->email;
            $cust->alamat             = $request->alamat;
            $cust->state              = $request->state;
            $cust->prov               = $request->prov;
            $cust->city               = $request->city;
            $cust->district           = $request->district;
            $cust->village            = $request->village;
            $cust->deskripsi          = $request->deskripsi;
            $cust->status             = $request->status;
            $cust->save();

            DB::commit();

            return response()->json([
                'data' => $cust
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
            $cust = Customer::findOrFail($id);
            $cust->delete();

            DB::commit();

            return response()->json([
                'data' => $cust
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }
}
