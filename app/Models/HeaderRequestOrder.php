<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function po(): HasOne
    {
        return $this->hasOne(HeaderPO::class, 'req_number', 'req_number');
    }

    public function req(): BelongsTo
    {
        return $this->belongsTo(User::class, 'req_by', 'email');
    }

    public function approved(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approve_by', 'email');
    }
}
