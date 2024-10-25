<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeaderTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "tbl_htransaksi";
    public $timestamps = true;
    protected $guarded = [];
}
