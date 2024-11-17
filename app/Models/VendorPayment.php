<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorPayment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "tbl_vendor_payment";
    public $timestamps = true;
    protected $guarded = [];
}
