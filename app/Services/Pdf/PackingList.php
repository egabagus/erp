<?php

namespace App\Services\Pdf;

use App\Models\Administration;
use App\Models\HeaderTransaction;
use App\Services\PdfService;
use Carbon\Carbon;

class PackingList
{
    public static function generate($inv_num)
    {
        $data = HeaderTransaction::with('detail.product', 'customer', 'user')->where('no_transaksi', $inv_num)->first();

        // dd($data->freight_type_str);
        $pdf = new PdfService();
        $pdf->AddPage();
        $pdf->setHeader();

        // Set font untuk konten
        $pdf->SetFont('Helvetica', 'B', 16);

        // Tambahkan teks konten
        $pdf->Ln(1);
        $pdf->Cell(0, 10, 'PACKING LIST', 0, 0, 'C');
        $pdf->Ln(8);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 10, $data->no_transaksi, 0, 0, 'C');

        $pdf->SetFontSize(8);
        $pdf->Ln(15);

        // Array of data
        $dataHeader = array(
            array('SHIP TO', '', 'SHIPPING DETAILS', ''),
        );
        $dataDetail = array(
            array('Code', ': ' . $data->customer->kode_cust, 'Freight Type', ': ' . $data->freight_type),
            array('Name', ': ' . $data->customer->nama_cust, 'Origin Country', ': ' . $data->origin_country),
            array('Email', ': ' . $data->customer->email, 'Port of Embarkation', ': ' . $data->port_embarkation),
            array('Phone', ': ' . $data->customer->handphone, 'Port of Discharge', ': ' . $data->port_discharge),
            array('Address', ': ' . $data->customer->alamat, 'Ship Date', ': ' . Carbon::parse($data->ship_date)->format('d-m-Y')),
        );
        $arr_header = array(
            array('Item Code', 'Item Name', 'Qty', 'Price / Unit', 'Tax', 'Disc', 'Total')
        );
        $arr_detail = [];
        foreach ($data->detail as $detail) {
            $arr_detail[] = [
                $detail->kode_barang, // Kolom untuk Product Name
                $detail->product->nama_barang,      // Kolom untuk Quantity
                $detail->qty,      // Kolom untuk Quantity
                $pdf->formatRupiah($detail->product->harga),      // Kolom untuk Quantity
                $pdf->formatRupiah($detail->ppn),      // Kolom untuk Quantity
                $pdf->formatRupiah($detail->disc),      // Kolom untuk Quantity
                $pdf->formatRupiah($detail->total_price)      // Kolom untuk Quantity
            ];
        }
        $arr_calculate = array(
            array('', 'SUBTOTAL', $pdf->formatRupiah($data->subtotal)),
            array('', 'PPN', $pdf->formatRupiah($data->ppntotal)),
            array('', 'DISKON', $pdf->formatRupiah($data->disctotal)),
            array('', 'FREIGHT COST', $pdf->formatRupiah($data->freight_rp)),
            array('', 'INSURANCE', $pdf->formatRupiah($data->insurance_rp)),
            array('', 'OTHER', $pdf->formatRupiah($data->other)),
            array('', 'TOTAL', $pdf->formatRupiah($data->total)),
        );

        // Array of Widths
        $widths = array(30, 60, 30, 60);
        $detailWidths = array(20, 30, 10, 30, 30, 30, 30);
        $calculateWidths = array(110, 40, 30);

        // Array of Aligns
        $detailAligns = [
            3 => 'R',
            4 => 'R',
            5 => 'R',
            6 => 'R',
        ];
        $calculateAligns = [
            2 => 'R'
        ];

        $pdf->bold();
        $pdf->bodyTable($dataHeader, $widths, 5, 0);
        $pdf->normal();
        $pdf->bodyTable($dataDetail, $widths, 5, 0);

        $pdf->Ln(8);
        $pdf->headerTable($arr_header, $detailWidths, 5);
        $pdf->bodyTable($arr_detail, $detailWidths, 5, 1, $detailAligns);

        $pdf->Ln(4);
        $pdf->bold();
        $pdf->bodyTable($arr_calculate, $calculateWidths, 5, 0, $calculateAligns);

        $pdf->Ln(8);
        $pdf->Cell(45, 5, 'Best Regards,', 0, 0, 'C');
        $pdf->Cell(45, 5, 'Best Regards,', 0, 0, 'C');
        $pdf->Cell(30, 5, $pdf->qrCode(env('APP_URL') . 'invoices/proforma-invoice/' . $inv_num, 120, $pdf->GetY() + 5, 20), 0, 1, 'C');
        $pdf->Cell(30, 5, '', 0, 1, 'C');
        $pdf->Ln(1);

        $user = $data->user;
        if ($user) {
            $userSign = 'app/' . $user->signature;
        } else {
            $userSign = '';
        }

        $pdf->normal();
        $pdf->Cell(45, 16, $pdf->Image(storage_path($userSign), $pdf->GetX() + 5, $pdf->GetY(), 30), 0, 0);
        $pdf->Ln(15);

        $pdf->Cell(45, 5, $user ? '( ' . $user->name . ' )' : '( General Manager )', 0, 0, 'C');
        $pdf->Cell(45, 5, $data->customer ? '( ' . $data->customer->nama_cust . ' )' : '(                     )', 0, 0, 'C');
        $pdf->Ln(4);
        $pdf->Cell(45, 5, 'General Manager', 0, 0, 'C');
        $pdf->Cell(45, 5, 'Buyer / Customer', 0, 0, 'C');


        $pdf->setDocumentTitle('Judul PDF Anda');
        return $pdf->outputPdf('INVOICE-' . $inv_num . '.pdf');
    }
}
