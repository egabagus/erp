<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\HeaderBapb;
use App\Models\HeaderPO;
use App\Models\HeaderRequestOrder;
use App\Models\HeaderTransaction;
use App\Models\Supplier;

class CreateItemNumber
{
    /**
     * Create a new class instance.
     */

    public static function generate($code)
    {
        $lastRecord = Barang::where('kode_barang', 'like', $code . '%')
            ->orderBy('kode_barang', 'desc')
            ->first();

        if ($lastRecord) {
            // Ambil nomor urut terakhir (misalnya 'AB000005')
            $lastNumber = (int) substr($lastRecord->kode_barang, strlen($code));
            // Tambah 1
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data, mulai dari 1
            $newNumber = 1;
        }

        // Format angka dengan leading zero (pad dengan 6 digit)
        $itemNumber = $code . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        return $itemNumber;
    }

    public static function generateVendorCode($code = 'VR')
    {
        $lastRecord = Supplier::orderBy('kode_supp', 'desc')
            ->first();

        if ($lastRecord) {
            // Ambil nomor urut terakhir (misalnya 'AB000005')
            $lastNumber = (int) substr($lastRecord->kode_barang, strlen($code));
            // Tambah 1
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data, mulai dari 1
            $newNumber = 1;
        }

        // Format angka dengan leading zero (pad dengan 6 digit)
        $itemNumber = $code . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        return $itemNumber;
    }

    public static function generateCustCode($code = 'CUS')
    {
        $lastRecord = Customer::orderBy('kode_cust', 'desc')
            ->first();

        if ($lastRecord) {
            // Ambil nomor urut terakhir (misalnya 'AB000005')
            $lastNumber = (int) substr($lastRecord->kode_cust, strlen($code));
            // Tambah 1
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data, mulai dari 1
            $newNumber = 1;
        }

        // Format angka dengan leading zero (pad dengan 6 digit)
        $itemNumber = $code . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        return $itemNumber;
    }

    public static function generateRequestNumber()
    {
        $lastRecord = HeaderRequestOrder::orderBy('req_number', 'desc')
            ->first();

        $code = 'REQ' . date('Y') . date('m'); // Prefix 'REQ' diikuti tahun saat ini

        if ($lastRecord) {
            // Ambil nomor urut terakhir setelah kode dan tahun (misalnya 'REQ2024000005')
            $lastNumber = (int) substr($lastRecord->req_number, strlen($code));
            // Tambah 1
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data, mulai dari 1
            $newNumber = 1;
        }

        // Format angka dengan leading zero (pad dengan 6 digit)
        $itemNumber = $code . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        return $itemNumber;
    }

    public static function generatePONumber()
    {
        $lastRecord = HeaderPO::orderBy('po_number', 'desc')
            ->first();

        $code = 'PO' . date('Y') . date('m'); // Prefix 'REQ' diikuti tahun saat ini

        if ($lastRecord) {
            // Ambil nomor urut terakhir setelah kode dan tahun (misalnya 'REQ2024000005')
            $lastNumber = (int) substr($lastRecord->po_number, strlen($code));
            // Tambah 1
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data, mulai dari 1
            $newNumber = 1;
        }

        // Format angka dengan leading zero (pad dengan 6 digit)
        $itemNumber = $code . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        return $itemNumber;
    }

    public static function generateTtbNumber()
    {
        $lastRecord = HeaderBapb::orderBy('bapb_no', 'desc')
            ->first();

        $code = 'TT' . date('Y') . date('m'); // Prefix 'REQ' diikuti tahun saat ini

        if ($lastRecord) {
            // Ambil nomor urut terakhir setelah kode dan tahun (misalnya 'REQ2024000005')
            $lastNumber = (int) substr($lastRecord->bapb_no, strlen($code));
            // Tambah 1
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data, mulai dari 1
            $newNumber = 1;
        }

        // Format angka dengan leading zero (pad dengan 6 digit)
        $itemNumber = $code . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        return $itemNumber;
    }

    public static function generateInvoiceNumber()
    {
        $lastRecord = HeaderTransaction::orderBy('no_transaksi', 'desc')
            ->first();

        $code = 'TT' . date('Y') . date('m'); // Prefix 'REQ' diikuti tahun saat ini

        if ($lastRecord) {
            // Ambil nomor urut terakhir setelah kode dan tahun (misalnya 'REQ2024000005')
            $lastNumber = (int) substr($lastRecord->no_transaksi, strlen($code));
            // Tambah 1
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data, mulai dari 1
            $newNumber = 1;
        }

        // Format angka dengan leading zero (pad dengan 6 digit)
        $itemNumber = $code . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        return $itemNumber;
    }
}
