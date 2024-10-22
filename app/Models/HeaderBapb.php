<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeaderBapb extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "tbl_hbapb";
    public $timestamps = true;
    protected $guarded = [];

    public function detail(): HasMany
    {
        return $this->hasMany(DetailBapb::class, 'bapb_no', 'bapb_no');
    }

    public function vendor()
    {
        return $this->belongsTo(Supplier::class, 'vendor_code', 'kode_supp');
    }
}
