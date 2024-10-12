<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdministrationRequest;
use App\Models\Administration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdministrationController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index()
    {
        return view('master.administration.index');
    }

    public function data()
    {
        try {
            $data = Administration::where('company_code', env('ID'))->first();

            return response()->json([
                'success' => true,
                'data' => $data,
                'code' => 200
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'code' => 500,
                'error' => $e
            ], 500);
        }
    }

    public function store(AdministrationRequest $request)
    {
        DB::beginTransaction();

        try {

            $adm                     = Administration::where('company_code', env('ID'))->first();
            $adm->company_name       = $request->company_name;
            $adm->brand_name         = $request->brand_name;
            $adm->alamat             = $request->alamat;
            $adm->handphone          = $request->handphone;
            $adm->email              = $request->email;
            $adm->fax                = $request->fax;
            $adm->whatsapp           = $request->whatsapp;
            $adm->website            = $request->website;

            $file = $request->file('logo');

            $extension = $file->getClientOriginalExtension();
            $fileName = Str::random(40) . '.' . $extension;

            $file->storeAs('company-logo', $fileName, 'public');
            $adm->logo = $fileName;

            $adm->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $adm,
                'code' => 200
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }
}
