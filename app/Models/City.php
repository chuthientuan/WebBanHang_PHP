<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'tbl_city';
    protected $primaryKey = 'matp';
    protected $fillable = [
        'name_city',
        'type'
    ];
    public $timestamps = false;
}
