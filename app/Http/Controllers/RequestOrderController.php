<?php

namespace App\Http\Controllers;

use App\Models\DetailRequestOrder;
use App\Models\HeaderRequestOrder;
use App\Services\CreateItemNumber;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RequestOrderController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index()
    {
        return view('production.request-order.index');
    }

    public function data()
    {
        $data = HeaderRequestOrder::with('detail', 'po')->get();

        return DataTables::of($data)
            ->make(true);
    }

    public function add()
    {
        return view('production.request-order.create');
    }

    public function show($req_number)
    {
        try {
            $data = HeaderRequestOrder::with('detail.product')->where('req_number', $req_number)->first();

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

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $req = new HeaderRequestOrder();
            $req->req_number        = CreateItemNumber::generateRequestNumber();
            $req->req_by            = $request->req_by;
            $req->date              = $request->date;
            $req->due_date          = $request->due_date;
            $req->app_manager       = 0;
            $req->note              = $request->note;
            $req->save();

            // dd($request, $req->req_number);
            $detail = self::storeDetail($request, $req->req_number);

            DB::commit();

            return response()->json([
                'data' => $req
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    public function storeDetail($request, $req_number)
    {
        foreach ($request->input('item_code') as $key => $item_code) {
            $detail = new DetailRequestOrder();
            $detail->req_number     = $req_number;
            $detail->item_code      = $item_code;
            $detail->qty            = $request->input('qty')[$key];
            $detail->save();
        }
    }

    public function approve($req_number)
    {
        DB::beginTransaction();

        $email = Auth::user()->email;

        try {
            $req = HeaderRequestOrder::where('req_number', $req_number)->first();
            $req->app_manager = 1;
            $req->approve_by  = $email;
            $req->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $req
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'error' => $e
            ], 500);
        }
    }

    public function cancelApprove($req_number)
    {
        DB::beginTransaction();

        try {
            $req = HeaderRequestOrder::where('req_number', $req_number)->first();
            $req->app_manager = 0;
            $req->approve_by  = '';
            $req->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $req
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'error' => $e
            ], 500);
        }
    }

    public function pdf($req_number)
    {
        $dataRO = HeaderRequestOrder::with('detail.product', 'req', 'approved')->where('req_number', $req_number)->first();
        $pdf = new PdfService();

        // dd($dataRO);
        // Tambahkan halaman
        $pdf->AddPage();

        // Set header
        $pdf->setHeader();

        // Set font untuk konten
        $pdf->SetFont('Helvetica', 'B', 16);

        // Tambahkan teks konten
        $pdf->Cell(0, 10, 'PURCHASE REQUEST', 0, 0, 'C');
        $pdf->Ln(8);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 10, $dataRO->req_number, 0, 0, 'C');

        $pdf->SetFontSize(8);
        $pdf->Ln(15);

        $data = array(
            array('Req Date', ': ' . $dataRO->date, 'Due Date', ': ' . $dataRO->due_date),
            array('Req By', ': ' . $dataRO->req_by, '', ''),
            array('Note', ': ' . $dataRO->note, '', ''),
        );

        // Lebar kolom dinamis (misalnya)
        $widths = array(20, 70, 20, 70); // Sesuaikan lebar kolom sesuai kebutuhan
        $widthsTable = array(25, 45, 20, 45, 45); // Sesuaikan lebar kolom sesuai kebutuhan

        // Buat tabel dengan lebar kolom dinamis
        $pdf->bodyTable($data, $widths, 5, 0);

        $pdf->Ln(5);
        $pdf->bold();
        $header = array(
            array('Item Code', 'Item Name', 'Quantity', 'Price / Unit', 'Total')
        );

        $pdf->headerTable($header, $widthsTable);
        $pdf->normal();
        // dd($dataRO);
        $detailData = [];
        foreach ($dataRO->detail as $detail) {
            $detailData[] = [
                $detail->item_code, // Kolom untuk Product Name
                $detail->product->nama_barang,      // Kolom untuk Quantity
                $detail->qty,      // Kolom untuk Quantity
                $pdf->formatRupiah($detail->product->harga),      // Kolom untuk Quantity
                $pdf->formatRupiah($detail->product->harga * $detail->qty),      // Kolom untuk Quantity
            ];
        }

        // Memasukkan data detail ke dalam PDF
        $pdf->bodyTable($detailData, $widthsTable);

        $pdf->Ln(10);
        $pdf->Cell(45, 5, 'Request By,', 0, 0, 'C');
        $pdf->Cell(45, 5, 'Approved By,', 0, 0, 'C');
        $pdf->Cell(30, 5, $pdf->qrCode(env('APP_URL') . 'production/request-order/pdf/' . $req_number, 160, $pdf->GetY(), 25), 0, 1, 'C');
        // TTD
        $reqBy = $dataRO->req;
        if ($reqBy) {
            $reqSign = 'app/' . $reqBy->signature;
        } else {
            $reqSign = '';
        }

        $appBy = $dataRO->approved;
        if ($appBy) {
            $appSign = 'app/' . $appBy->signature;
        } else {
            $appSign = '';
        }
        $pdf->Cell(45, 16, $reqBy && $reqBy->signature ? $pdf->Image(storage_path($reqSign), $pdf->GetX() + 8, $pdf->GetY() + 2, 30) : '', 0, 0);
        $pdf->Cell(45, 16, $appBy && $appBy->signature ? $pdf->Image(storage_path($appSign), $pdf->GetX() + 8, $pdf->GetY() + 2, 30) : '', 0, 1);

        $pdf->Cell(45, 5, $reqBy ? '( ' . $reqBy->name . ' )' : '( Production Staff )', 0, 0, 'C');
        $pdf->Cell(45, 5, $appBy ? '( ' . $appBy->name . ' )' : '( Production Manager )', 0, 0, 'C');
        $pdf->Ln(4);
        $pdf->Cell(45, 5, 'Production Staff', 0, 0, 'C');
        $pdf->Cell(45, 5, 'Production Manager', 0, 0, 'C');


        $pdf->setDocumentTitle('Judul PDF Anda');

        return $pdf->outputPdf('document.pdf');
    }
}
