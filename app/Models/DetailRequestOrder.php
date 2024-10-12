<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailRequestOrder extends Model
{
    use HasFactory;
    protected $table = "tbl_dreq_order";
    public $timestamps = true;
    protected $guarded = [];

    public function product(): HasOne
    {
        return $this->hasOne(Barang::class, 'kode_barang', 'item_code');
    }
}
