<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Shipper;
use App\Models\Ship;
use App\Imports\SeaShipmentImport;
use Maatwebsite\Excel\Facades\Excel;

class ShipmentController extends Controller
{
    public function index() {
        return view('/shipment.list_shipments');
    }

    // sea shipment
    public function createSeaShipment() {
        $customers = Customer::orderBy('name')->get();
        $shippers = Shipper::orderBy('name')->get();
        $ships = Ship::orderBy('name')->get();
        return view('/shipment.sea_shipment.form_sea_shipment', compact('customers', 'shippers', 'ships'));
    }

    public function importSeaShipment(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        try {
            $file = $request->file('file');
            $import = new SeaShipmentImport;

            Excel::import($import, $file);
            return redirect('/customer');

        } catch (\Exception $e) {

            return redirect('/list_shipments');
        }
    }
}
