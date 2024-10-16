<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ModulController extends Controller
{
    public function index()
    {
        return view('master.modul.index');
    }

    public function data()
    {
        $data = Modul::get();

        return DataTables::of($data)
            ->make(true);
    }
}
