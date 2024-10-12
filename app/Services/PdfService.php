<?php

namespace App\Services;

use App\Models\Administration;
use FPDF;

class PdfService extends FPDF
{
    protected $title;

    public function setHeader()
    {
        $profile = Administration::where('company_code', env('ID'))->first();

        // dd($profile);

        $this->SetFont('Helvetica', 'B', 12);

        // Set posisi untuk header
        // $this->SetY(5);
        $this->SetMargins(15, 15, 15);

        $logo = storage_path('app/public/company-logo/' . $profile->logo);

        $this->Cell(35, 0, $this->Image($logo, 15, $this->GetX() + 3, 25));
        $this->Cell(60, 8, $profile->company_name, 0, 0);
        $this->Ln();
        $this->SetFont('Helvetica', '', 8);
        $this->Cell(30, 0, '');
        $this->Cell(60, 4, $profile->alamat, 0, 0);
        $this->Ln();
        $this->Cell(30, 0, '');
        $this->Cell(60, 4, 'Telp/WA : ' . $profile->handphone . ' / ' . $profile->whatsapp, 0, 0);
        $this->Ln();
        $this->Cell(30, 0, '');
        $this->Cell(60, 4, 'Fax : ' . $profile->fax . '  |  Email : ' . $profile->email, 0, 0);
        $this->Ln();
        $this->Cell(30, 0, '');
        $this->Cell(60, 4, $profile->website, 0, 0);
        // Judul kop surat

        // Garis bawah header
        $this->Ln(1);
        $this->SetDrawColor(0, 0, 0); // Warna hitam (default)
        $this->SetLineWidth(0.5); // Atur ketebalan garis
        $this->Line($this->getX(), $this->getY() + 8, 195, $this->getY() + 8);
        $this->Ln(15);
    }

    public function setDocumentTitle($title)
    {
        $this->title = $title;
    }

    // Output PDF dengan nama file
    public function outputPdf($fileName)
    {
        return response($this->Output('S', $fileName), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }

    public function bodyTable($data, $width, $height = 6, $border = 1)
    {
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                $this->Cell($width[$key], $height, $value, $border, 0, 'L');
            }
            $this->Ln(); // Pindah ke baris baru setelah satu baris selesai
        }
    }

    public function bold(): void
    {
        $this->SetFont("", "B");
    }

    public function normal(): void
    {
        $this->SetFont("", "");
    }

    function formatRupiah($number)
    {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }
}
