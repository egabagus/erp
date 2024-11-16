<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailPO extends Model
{
    use HasFactory;
    protected $table = "tbl_detail_po";
    public $timestamps = true;
    protected $guarded = [];

    public function product(): HasOne
    {
        return $this->hasOne(Barang::class, 'kode_barang', 'item_code');
    }
}
