<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'tbl_province';
    protected $primaryKey = 'maqh';
    protected $fillable = [
        'name_province',
        'type',
        'matp'
    ];
    public $timestamps = false;
}
