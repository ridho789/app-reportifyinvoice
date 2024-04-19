<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cas extends Model
{
    use HasFactory;
    protected $table = 'tbl_cas';

    protected $fillable = [
        'id_cas',
        'id_customer',
        'id_shipper',
        'lts',
        'desc',
        'charge',
    ];
}
