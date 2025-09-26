<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'tbl_payment';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'payment_method',
        'payment_status'
    ];
    public $timestamps = false;
}
