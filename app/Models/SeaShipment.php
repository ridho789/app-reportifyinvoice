<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\HistorySeaShipmentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([HistorySeaShipmentObserver::class])]
class SeaShipment extends Model
{
    use HasFactory;
    protected $table = 'tbl_sea_shipment';
    protected $primaryKey = 'id_sea_shipment';

    protected $fillable = [
        'no_aju',
        'no_inv',
        'date',
        'id_origin',
        'etd',
        'eta',
        'id_shipper',
        'id_customer',
        'id_ship',
        'is_weight',
        'term',
        'file_shipment_status',
        'value_key',
        'is_printed',
        'printcount',
        'printdate',
        'id_history'
    ];
}
