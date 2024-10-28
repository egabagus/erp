<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeaderProforma extends Model
{
    use HasFactory;
    protected $table = "tbl_hproforma_inv";
    public $timestamps = true;
    protected $guarded = [];

    public function detail(): HasMany
    {
        return $this->hasMany(DetailProforma::class, 'proforma_no', 'proforma_no');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'kode_cust');
    }
}
