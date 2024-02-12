<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index() {
        return view('/shipment.list_shipments');
    }

    public function create() {
        return view('/shipment.form_shipment');
    }
}
