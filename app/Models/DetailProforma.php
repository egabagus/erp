<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProforma extends Model
{
    use HasFactory;
    protected $table = "tbl_dproforma_inv";
    public $timestamps = true;
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'item_code', 'kode_barang');
    }
}
