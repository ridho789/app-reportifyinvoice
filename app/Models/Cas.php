<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\HistoryCasObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([HistoryCasObserver::class])]
class Cas extends Model
{
    use HasFactory;
    protected $table = 'tbl_cas';
    protected $primaryKey = 'id_cas';

    protected $fillable = [
        'id_cas',
        'id_customer',
        'id_shipper',
        'lts',
        'desc',
        'charge',
        'start_period',
        'end_period'
    ];
}