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
use PhpOffice\PhpSpreadsheet\IOFactory;

class ShipmentController extends Controller
{
    public function index() {
        $seaShipment = SeaShipment::count();
        $logErrors = '';
        return view('shipment.list_shipments', compact('seaShipment', 'logErrors'));
    }

    // sea shipment
    public function createSeaShipment() {
        $seaShipment = '';
        $seaShipmentLines = '';
        $groupSeaShipmentLines = '';
        $customers = Customer::orderBy('name')->get();
        $shippers = Shipper::orderBy('name')->get();
        $ships = Ship::orderBy('name')->get();
        return view('shipment.sea_shipment.form_sea_shipment', compact('seaShipment', 'seaShipmentLines', 'customers', 'shippers', 'ships', 'groupSeaShipmentLines'));
    }

    public function listSeaShipment() {
        $allSeaShipment = SeaShipment::all();
        $customer = Customer::pluck('name', 'id_customer');
        $shipper = Shipper::pluck('name', 'id_shipper');
        $ship = Ship::pluck('name', 'id_ship');
        return view('shipment.sea_shipment.list_sea_shipments', compact('allSeaShipment','customer', 'shipper', 'ship'));
    }

    public function editSeaShipment($id) {
        // Encrypt-Decrypt ID
        $id = Crypt::decrypt($id);

        $seaShipment = SeaShipment::where('id_sea_shipment', $id)->first();
        $seaShipmentLines = SeaShipmentLine::where('id_sea_shipment', $seaShipment->id_sea_shipment)->get();

        $groupSeaShipmentLines = $seaShipmentLines->groupBy(function ($item) {
            return $item->date;
        })->map(function ($group) {
            return [
                'total_qty_pkgs' => $group->filter(function ($item) {
                    return is_numeric($item->qty_pkgs);
                })->sum('qty_pkgs'),
                'total_weight' => $group->filter(function ($item) {
                    return is_numeric($item->weight);
                })->sum('weight'),
                'total_cbm1' => $group->filter(function ($item) {
                    return is_numeric($item->tot_cbm_1);
                })->sum('tot_cbm_1'),
                'total_cbm2' => $group->filter(function ($item) {
                    return is_numeric($item->tot_cbm_2);
                })->sum('tot_cbm_2')
            ];
        });

        $customers = Customer::orderBy('name')->get();
        $shippers = Shipper::orderBy('name')->get();
        $ships = Ship::orderBy('name')->get();
        return view('shipment.sea_shipment.form_sea_shipment', compact('seaShipment', 'seaShipmentLines', 'customers', 'shippers', 'ships', 'groupSeaShipmentLines'));
    }

    public function updateSeaShipment(Request $request) {
        SeaShipment::where('id_sea_shipment', $request->id_sea_shipment)->update([
            'no_aju' => $request->no_aju,
            'date' => $request->date,
            'id_customer' => $request->id_customer,
            'id_shipper' => $request->id_shipper,
            'id_ship' => $request->id_ship,
            'origin' => $request->origin,
            'etd' => $request->etd,
            'eta' => $request->eta,
        ]);

        foreach ($request->id_sea_shipment_line as $index => $idSeaShipmentLine) {
            SeaShipmentLine::where('id_sea_shipment_line', $idSeaShipmentLine)->update([
                'date' => $request->bldate[$index],
                'code' => $request->code[$index],
                'marking' => $request->marking[$index],
                'qty_pkgs' => $request->qty_pkgs[$index],
                'qty_loose' => $request->qty_loose[$index],
                'unit_qty_pkgs' => $request->unit_qty_pkgs[$index],
                'unit_qty_loose' => $request->unit_qty_loose[$index],
                'weight' => $request->weight[$index],
                'dimension_p' => $request->p[$index],
                'dimension_l' => $request->l[$index],
                'dimension_t' => $request->t[$index],
                'tot_cbm_1' => $request->cbm1[$index],
                'tot_cbm_2' => $request->cbm2[$index],
                'lts' => $request->lts[$index],
                'desc' => $request->desc[$index],
                'state' => $request->state[$index],
            ]);
        }

        return redirect()->back();
    }

    public function importSeaShipment(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file);
            $sheetNames = $spreadsheet->getSheetNames();

            $import = new SeaShipmentImport($sheetNames);
            Excel::import($import, $file);
            $logErrors = $import->getLogErrors();

            if ($logErrors) {
                return redirect('list_shipments')->with('logErrors', $logErrors);

            } else {
                return redirect('list_sea_shipment');
            }

        } catch (\Exception $e) {
            $sqlErrors = $e->getMessage();

            if (!empty($sqlErrors)){
                $logErrors = $sqlErrors;
            }

            return redirect('list_shipments')->with('logErrors', $logErrors);
        }
    }
}
