<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'tbl_payment';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'payment_method'
    ];
    public $timestamps = false;
    
    public function order()
    {
        return $this->hasOne(Order::class, 'payment_id', 'payment_id');
    }
}
