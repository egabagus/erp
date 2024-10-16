<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class subModulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('tbl_sub_modul')->insert([
            [
                'kode' => '201',
                'kode_menu' => '200',
                'text' => 'Products',
                'url'  => 'master/barang',
                'icon'  => 'fas fa-bag-shopping',
            ],
            [
                'kode' => '202',
                'kode_menu' => '200',
                'text' => 'Vendor',
                'url'  => 'master/supplier',
                'icon'  => 'fas fa-user',
            ]
        ]);
    }
}
