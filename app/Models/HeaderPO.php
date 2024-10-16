<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeaderPO extends Model
{
    use HasFactory;
    protected $table = "tbl_header_po";
    public $timestamps = true;
    protected $guarded = [];

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPO::class, 'po_number', 'po_number');
    }

    public function vendor()
    {
        return $this->belongsTo(Supplier::class, 'vendor_code', 'kode_supp');
    }
}
