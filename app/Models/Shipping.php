<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'tbl_shipping';
    protected $primaryKey = 'shipping_id';
    protected $fillable = [
        'shipping_name',
        'shipping_address',
        'shipping_phone',
        'shipping_email',
        'shipping_note'
    ];
    public $timestamps = false;
    
    public function order()
    {
        return $this->hasOne(Order::class, 'shipping_id', 'shipping_id');
    }
}
