<?php

namespace App\Services\Pdf;

use App\Models\Administration;
use App\Models\HeaderBapb;
use App\Models\HeaderTransaction;
use App\Services\PdfService;
use Carbon\Carbon;

class ReceiptOfGoods
{
    public static function generate($receipt_num)
    {
        $data = HeaderBapb::with('detail.item', 'vendor')->where('bapb_no', $receipt_num)->first();

        $pdf = new PdfService();
        $pdf->AddPage();
        $pdf->setHeader();

        // Set font untuk konten
        $pdf->SetFont('Helvetica', 'B', 16);

        // Tambahkan teks konten
        $pdf->Ln(1);
        $pdf->Cell(0, 10, 'TANDA TERIMA BARANG', 0, 0, 'C');
        $pdf->Ln(8);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 10, $data->bapb_no, 0, 0, 'C');

        $pdf->SetFontSize(8);
        $pdf->Ln(15);

        $dataHeader = array(
            array('VENDOR', '', 'DETAILS', ''),
        );
        $dataDetail = array(
            array('Code', ': ' . $data->vendor->kode_supp, 'PO Number', ': ' . $data->po_number),
            array('Name', ': ' . $data->vendor->nama_supp, 'Date', ': ' . Carbon::parse($data->date)->format('d-m-Y')),
            array('PIC', ': ' . $data->vendor->pic, 'Plat Number', ': ' . $data->plat ?? '-'),
            array('Phone', ': ' . $data->vendor->handphone, 'Invoice Number', ': ' . $data->inv_no),
            array('Address', ': ' . $data->vendor->alamat, 'Receiver', ': ' . $data->receive),
        );

        // Array of Widths
        $widths = array(30, 60, 30, 60);

        $pdf->bold();
        $pdf->bodyTable($dataHeader, $widths, 5, 0);
        $pdf->normal();
        $pdf->bodyTable($dataDetail, $widths, 5, 0);

        $pdf->setDocumentTitle('Judul PDF Anda');
        return $pdf->outputPdf('TTB-' . $receipt_num . '.pdf');
    }
}
