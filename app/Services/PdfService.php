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

        // dd(env('ID'));

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

    public function headerTable($data, $width, $height = 6, $border = 1)
    {
        // Atur warna latar belakang dan teks
        $this->SetFillColor(20, 72, 156); // Warna biru (RGB)
        $this->SetTextColor(255, 255, 255); // Warna putih

        $this->SetDrawColor(20, 72, 156); // Warna biru (RGB)
        // $this->SetDrawColor(2255, 255, 255); // Warna border
        foreach ($data as $row) {
            $maxHeight = 0; // Untuk mengatur tinggi baris berdasarkan MultiCell

            foreach ($row as $key => $value) {
                // Simpan posisi X dan Y awal
                $x = $this->GetX();
                $y = $this->GetY();

                // Cetak MultiCell dengan background warna biru
                $this->bold();
                $this->MultiCell($width[$key], $height, $value, $border, 'C', true);
                $this->normal();

                // Hitung ketinggian sel tertinggi di baris tersebut
                $maxHeight = max($maxHeight, $this->GetY() - $y);

                // Kembali ke posisi X awal, tetapi di baris yang sama
                $this->SetXY($x + $width[$key], $y);
            }

            // Pindah ke baris baru dengan tinggi maksimum
            $this->Ln($maxHeight);
        }

        // Kembalikan warna teks ke default untuk konten berikutnya
        $this->SetTextColor(0, 0, 0); // Warna hitam
    }


    public function bodyTable($data, $width, $height = 6, $border = 1, $alignColumns = [])
    {
        $this->SetDrawColor(190, 190, 190);
        foreach ($data as $row) {
            $maxHeight = 0; // Untuk mengatur tinggi baris berdasarkan MultiCell

            // Hitung ketinggian baris maksimum berdasarkan MultiCell
            foreach ($row as $key => $value) {
                // Simpan posisi X dan Y awal
                $x = $this->GetX();
                $y = $this->GetY();

                $align = $alignColumns[$key] ?? 'L'; // Mengasumsikan nilai selalu 'L', 'C', atau 'R'

                // Cetak MultiCell untuk membungkus teks
                $this->MultiCell($width[$key], $height, $value, $border, $align);

                // Hitung ketinggian sel tertinggi di baris tersebut
                $maxHeight = max($maxHeight, $this->GetY() - $y);

                // Kembali ke posisi X awal, tetapi di baris yang sama
                $this->SetXY($x + $width[$key], $y);
            }

            // Pindah ke baris baru dengan tinggi maksimum
            $this->Ln($maxHeight);
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
        // return $this->Cell(0, 5, number), 0, 0, 'R');
        return 'Rp ' . number_format($number, 0, ',', '.');
    }

    public function Footer()
    {
        $this->SetY(-15); // Pindahkan ke 15 mm dari bawah
        $this->SetFont('Helvetica', 'I', 8); // Font Italic
        $tanggal = date('d-m-Y H:i:s');
        $this->Cell(0, 10, 'Dicetak oleh System pada ' . $tanggal, 0, 0, 'C'); // Teks footer di tengah
    }
}
