<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\HistoryCustomerObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([HistoryCustomerObserver::class])]
class Customer extends Model
{
    use HasFactory;
    protected $table = 'tbl_customers';
    protected $primaryKey = 'id_customer';

    protected $fillable = [
        'name',
        'shipper_ids',
        'id_company'
    ];
}
