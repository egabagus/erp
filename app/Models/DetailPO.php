<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetailPO extends Model
{
    use HasFactory;
    protected $table = "tbl_detail_po";
    public $timestamps = true;
    protected $guarded = [];
}
