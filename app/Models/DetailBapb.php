<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailBapb extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "tbl_dbapb";
    public $timestamps = true;
    protected $guarded = [];

    public function item(): HasOne
    {
        return $this->hasOne(Barang::class, 'kode_barang', 'kode_barang');
    }
}
