<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricelist extends Model
{
    use HasFactory;
    protected $table = 'tbl_pricelists';

    protected $fillable = [
        'id_pricelist',
        'id_shipper',
        'id_customer',
        'origin',
        'price',
        'start_period',
        'end_period'
    ];
}
