<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Barang extends Model
{
    use HasFactory;
    protected $table = "tbl_barang";
    public $timestamps = false;
    protected $guarded = [];

    public function category(): HasOne
    {
        return $this->hasOne(Categori::class, 'kode_kategori', 'kategori');
    }
}
