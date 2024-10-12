<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeaderRequestOrder extends Model
{
    use HasFactory;
    protected $table = "tbl_hreq_order";
    public $timestamps = true;
    protected $guarded = [];

    public function detail(): HasMany
    {
        return $this->hasMany(DetailRequestOrder::class, 'req_number', 'req_number');
    }
}
