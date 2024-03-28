<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeaShipment extends Model
{
    use HasFactory;
    protected $table = 'table_sea_shipment';

    protected $fillable = [
        'no_aju',
        'date',
        'origin',
        'etd',
        'eta',
        'id_shipper',
        'id_customer',
        'id_ship'
    ];
}
