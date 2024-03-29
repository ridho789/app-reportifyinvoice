<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Shipper;
use App\Models\Ship;
use App\Models\SeaShipment;
use App\Models\SeaShipmentLine;
use App\Imports\SeaShipmentImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;

class ShipmentController extends Controller
{
    public function index() {
        $seaShipment = SeaShipment::count();
        return view('/shipment.list_shipments', compact('seaShipment'));
    }

    // sea shipment
    public function createSeaShipment() {
        $customers = Customer::orderBy('name')->get();
        $shippers = Shipper::orderBy('name')->get();
        $ships = Ship::orderBy('name')->get();
        return view('/shipment.sea_shipment.form_sea_shipment', compact('customers', 'shippers', 'ships'));
    }

    public function listSeaShipment() {
        $allSeaShipment = SeaShipment::all();
        $customer = Customer::pluck('name', 'id_customer');
        $shipper = Shipper::pluck('name', 'id_shipper');
        $ship = Ship::pluck('name', 'id_ship');
        return view('/shipment.sea_shipment.list_sea_shipments', compact('allSeaShipment','customer', 'shipper', 'ship'));
    }

    public function editSeaShipment($id) {
        // Encrypt-Decrypt ID
        $id = Crypt::decrypt($id);

        $seaShipment = SeaShipment::where('id_sea_shipment', $id)->first();
        $seaShipmentLines = SeaShipmentLine::where('id_sea_shipment', $seaShipment->id_sea_shipment)->get();
    }

    public function importSeaShipment(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        $file = $request->file('file');
        $import = new SeaShipmentImport;

        Excel::import($import, $file);
        return redirect('/customer');

        // try {
        //     $file = $request->file('file');
        //     $import = new SeaShipmentImport;

        //     Excel::import($import, $file);
        //     return redirect('/customer');

        // } catch (\Exception $e) {
        //     $sqlErrors = $e->getMessage();

        //     if (!empty($sqlErrors)){
        //         $logErrors = $sqlErrors;
        //     }

        //     return redirect('/list_shipments');
        // }
    }
}
