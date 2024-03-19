<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Shipper;

class ShipmentController extends Controller
{
    public function index() {
        return view('/shipment.list_shipments');
    }

    // sea shipment
    public function create() {
        $customers = Customer::orderBy('name')->get();
        $shippers = Shipper::all();
        return view('/shipment.sea_shipment.form_sea_shipment', compact('customers', 'shippers'));
    }
}
