<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillRecap extends Model
{
    use HasFactory;
    protected $table = 'tbl_bill_recaps';

    protected $fillable = [
        'id_bill_recap ',
        'id_customer ',
        'load_date',
        'no_inv',
        'freight_type',
        'entry_date',
        'size',
        'unit_price',
        'amount',
        'payment_date',
        'payment_amount',
        'remaining_bill',
        'overdue_bill',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'tbl_customers', 'id_customer');
    }
}
