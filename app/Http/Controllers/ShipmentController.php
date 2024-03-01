<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index() {
        return view('/shipment.list_shipments');
    }

    // sea shipment
    public function create() {
        return view('/shipment.sea_shipment.form_sea_shipment');
    }
}
