<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeaderTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "tbl_htransaksi";
    public $timestamps = true;
    protected $guarded = [];

    public function detail(): HasMany
    {
        return $this->hasMany(DetailTransaction::class, 'no_transaksi', 'no_transaksi');
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'kode_cust', 'customer_id');
    }
}
