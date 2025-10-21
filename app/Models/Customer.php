<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'tbl_customer';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_password',
        'customer_phone'
    ];
    public $timestamps = false;
    
    public function order()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
