<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProformaInvoiceController extends Controller
{
    public function index()
    {
        return view('marketing.proforma.index');
    }
}
