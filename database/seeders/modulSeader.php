<?php

namespace Database\Seeders;

use App\Models\Modul;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class modulSeader extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('tbl_modul')->insert([
            [
                'kode' => '100',
                'text' => 'Dashboard',
                'url'  => '',
                'icon'  => 'fas fa-house',
            ],
            [
                'kode' => '200',
                'text' => 'Master',
                'url'  => '',
                'icon'  => 'fas fa-server',
            ],
            [
                'kode' => '300',
                'text' => 'Purchasing',
                'url'  => '',
                'icon'  => 'fas fa-credit-card',
            ],
            [
                'kode' => '400',
                'text' => 'Production',
                'url'  => '',
                'icon'  => 'fas fa-industry',
            ],
            [
                'kode' => '500',
                'text' => 'Marketing',
                'url'  => '',
                'icon'  => 'fas fa-chart-simple',
            ],
            [
                'kode' => '600',
                'text' => 'Transaction',
                'url'  => '',
                'icon'  => 'fas fa-file',
            ],
            [
                'kode' => '700',
                'text' => 'Finance',
                'url'  => '',
                'icon'  => 'fas fa-file-invoice-dollar',
            ],
        ]);
    }
}
