<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelApprovePO;
use App\Http\Requests\PORequest;
use App\Models\Administration;
use App\Models\DetailPO;
use App\Models\HeaderPO;
use App\Models\HeaderRequestOrder;
use App\Services\CreateItemNumber;
use App\Services\PdfService;
use Carbon\Carbon;
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

    public function data(Request $request)
    {
        $data = HeaderPO::with('detail', 'vendor')
            ->when($request->look, function ($q) use ($request) {
                return $q->where('po_number', 'LIKE', "%{$request->look}%")
                    ->orWhere('po_date', 'LIKE', "%{$request->look}%");
            })
            ->get();

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

    public function cancleApprove($type, $ponumber)
    {
        DB::beginTransaction();
        try {
            $data = HeaderPO::where('po_number', $ponumber)->first();

            if ($type == 'operational') {
                $data->app_operational = 0;
            } else if ($type == 'finance') {
                $data->app_finance = 0;
            }

            $data->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Approve PO Berhasil Dibatalkan!'
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

    public function approve($type, $ponumber)
    {
        DB::beginTransaction();
        try {
            $data = HeaderPO::where('po_number', $ponumber)->first();

            if ($type == 'operational') {
                $data->app_operational = 1;
            } else if ($type == 'finance') {
                $data->app_finance = 1;
            }

            $data->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'PO Approved!'
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

    public function pdf($ponumber)
    {
        $data_po = HeaderPO::with('detail.product', 'vendor')->where('po_number', $ponumber)->first();
        $co = Administration::where('company_code', env('ID'))->first();
        // dd($co);
        $pdf = new PdfService();
        $pdf->AddPage();
        $pdf->setHeader();

        // Set font untuk konten
        $pdf->SetFont('Helvetica', 'B', 16);

        // Tambahkan teks konten
        $pdf->Cell(0, 10, 'PURCHASE ORDER', 0, 0, 'C');
        $pdf->Ln(8);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 10, $data_po->po_number, 0, 0, 'C');

        $pdf->SetFontSize(8);
        $pdf->Ln(15);

        $arr_po = array(
            array('PO DATE', ': ' . Carbon::parse($data_po->po_date)->format('d-m-Y'), 'Remarks', ': ' . $data_po->incoterms),
        );
        $dataHeader = array(
            array('SHIP TO', '', 'VENDOR DATA', ''),
        );
        $data = array(
            array('Name', ': ' . $co->company_name, 'Vendor', ': ' . $data_po->vendor_code),
            array('Phone', ': ' . $co->handphone, 'Name', ': ' . $data_po->vendor->nama_supp),
            array('Email', ': ' . $co->email, 'PIC', ': ' . $data_po->vendor->pic),
            array('Address', ': ' . $co->alamat, 'Phone', ': ' . $data_po->vendor->handphone),
            array('', '', 'Email', ': ' . $data_po->vendor->email),
            array('', '', 'Address', ': ' . $data_po->vendor->alamat),
        );
        $arr_header = array(
            array('Item Code', 'Item Name', 'Qty', 'Price / Unit', 'Tax', 'Disc', 'Total')
        );
        // dd($data_po->detail);
        $detailData = [];
        foreach ($data_po->detail as $detail) {
            $detailData[] = [
                $detail->item_code, // Kolom untuk Product Name
                $detail->product->nama_barang,      // Kolom untuk Quantity
                $detail->qty,      // Kolom untuk Quantity
                $pdf->formatRupiah($detail->product->harga),      // Kolom untuk Quantity
                $pdf->formatRupiah($detail->taxrp),      // Kolom untuk Quantity
                $pdf->formatRupiah($detail->discrp),      // Kolom untuk Quantity
                $pdf->formatRupiah($detail->total)      // Kolom untuk Quantity
            ];
        }

        $arr_calculate = array(
            array('', 'SUBTOTAL', $pdf->formatRupiah($data_po->subtotal)),
            array('', 'PPN', $pdf->formatRupiah($data_po->totalppn)),
            array('', 'DISKON', $pdf->formatRupiah($data_po->totaldisc)),
            array('', 'TOTAL', $pdf->formatRupiah($data_po->total)),
        );

        // Lebar kolom dinamis (misalnya)
        $widths = array(25, 65, 25, 65);
        $detailWidths = array(20, 30, 10, 30, 30, 30, 30);
        $calculateWidths = array(120, 20, 40);

        $detailAligns = [
            3 => 'R', // Kolom kedua rata kanan
            4 => 'R', // Kolom kedua rata kanan
            5 => 'R', // Kolom kedua rata kanan
            6 => 'R', // Kolom kedua rata kanan
        ];
        $calculateAligns = [
            2 => 'R' // Kolom kedua rata kanan
        ];

        // Buat tabel dengan lebar kolom dinamis
        $pdf->bodyTable($arr_po, $widths, 5, 0);
        $pdf->Ln(2);
        $pdf->bold();
        $pdf->bodyTable($dataHeader, $widths, 5, 0);
        $pdf->normal();
        $pdf->bodyTable($data, $widths, 5, 0);

        $pdf->Ln(8);
        $pdf->headerTable($arr_header, $detailWidths, 5);
        $pdf->bodyTable($detailData, $detailWidths, 5, 1, $detailAligns);

        $pdf->Ln(4);
        $pdf->bold();
        $pdf->bodyTable($arr_calculate, $calculateWidths, 5, 0, $calculateAligns);
        $pdf->normal();

        // $pdf->setFooter();
        $pdf->setDocumentTitle('Judul PDF Anda');

        return $pdf->outputPdf('document.pdf');
    }
}
