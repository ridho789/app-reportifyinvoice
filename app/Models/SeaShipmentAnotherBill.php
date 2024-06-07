<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeaShipmentAnotherBill extends Model
{
    use HasFactory;
    protected $table = 'tbl_sea_shipment_another_bill';
    protected $primaryKey = 'id_sea_shipment_another_bill';

    protected $fillable = [
        'id_sea_shipment',
        'date',
        'desc',
        'charge',
    ];
}
