<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "tbl_dtransaksi";
    public $timestamps = true;
    protected $guarded = [];
}
