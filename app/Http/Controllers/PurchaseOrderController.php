<?php

namespace App\Http\Controllers;

use App\Http\Requests\PORequest;
use App\Models\DetailPO;
use App\Models\HeaderPO;
use App\Models\HeaderRequestOrder;
use App\Services\CreateItemNumber;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index()
    {
        return view('purchasing.purchase-order.index');
    }

    public function add($req_number)
    {
        $email = Auth::user()->email;
        return view('purchasing.purchase-order.create', compact('req_number', 'email'));
    }

    public function data()
    {
        $data = HeaderPO::with('detail', 'vendor')->get();

        return DataTables::of($data)
            ->make(true);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $header = new HeaderPO();
            $header->po_number       = CreateItemNumber::generatePONumber();
            $header->po_date         = $request->po_date;
            $header->purchaser       = $request->purchaser ?? '';
            $header->vendor_code     = $request->vendor;
            $header->total           = $request->total_all;
            $header->payment_terms   = $request->payment;
            $header->incoterms       = $request->remarks;
            $header->subtotal        = $request->subtotal_all;
            $header->totalppn        = $request->ppn_all;
            $header->totaldisc       = $request->disc_all;
            $header->save();

            $detail = self::detailStore($request, $header->po_number);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'PO Berhasil Dibuat!'
            ], 201);
        } catch (\Throwable $e) {
            // dd($e);
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function detailStore($request, $ponumber)
    {
        foreach ($request->input('code') as $key => $code) {
            $detail = new DetailPO();
            $detail->po_number     = $ponumber;
            $detail->item_code         = $code;
            $detail->qty           = $request->input('qty')[$key];
            $detail->discrp           = $request->input('disc')[$key];
            $detail->taxrp           = $request->input('ppnrp')[$key];
            $detail->total           = $request->input('total')[$key];
            $detail->save();
        }
    }

    public function pdf($ponumber)
    {
        $data = HeaderPO::with('detail', 'vendor')->where('po_number', $ponumber)->first();
        $pdf = new PdfService();

        // dd($data);
        // Tambahkan halaman
        $pdf->AddPage();

        // Set header
        $pdf->setHeader();

        // Set font untuk konten
        $pdf->SetFont('Helvetica', 'B', 16);

        // Tambahkan teks konten
        $pdf->Cell(0, 10, 'PURCHASE ORDER', 0, 0, 'C');
        $pdf->Ln(8);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 10, $data->po_number, 0, 0, 'C');

        $pdf->SetFontSize(8);
        $pdf->Ln(15);

        $data = array(
            array('PO Date', ': ' . $data->po_date, 'Vendor', ': ' . $data->vendor_code),
        );

        // Lebar kolom dinamis (misalnya)
        $widths = array(20, 70, 20, 70); // Sesuaikan lebar kolom sesuai kebutuhan
        // Buat tabel dengan lebar kolom dinamis
        $pdf->bodyTable($data, $widths, 5, 0);

        $pdf->setDocumentTitle('Judul PDF Anda');

        return $pdf->outputPdf('document.pdf');
    }
}
