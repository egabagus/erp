<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;
    protected $table = "tbl_supplier";
    public $timestamps = true;
    protected $guarded = [];

    public function payment(): HasMany
    {
        return $this->hasMany(VendorPayment::class, 'vendor_code', 'kode_supp');
    }
}
