<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurance;
use App\Models\SeaShipmentLine;
use App\Imports\InsuranceImport;
use Maatwebsite\Excel\Facades\Excel;

class InsuranceController extends Controller
{
    public function index() {
        $insurances = Insurance::all();
        $seaShipmentLine = SeaShipmentLine::pluck('marking', 'id_sea_shipment_line');
        $logErrors = '';
        return view('main.insurance', compact('insurances', 'seaShipmentLine', 'logErrors'));
    }

    public function update(Request $request) {
        $numericCharge = preg_replace("/[^0-9]/", "", explode(",", $request->charge)[0]);
        if ($request->charge[0] === '-') {
            $numericCharge *= -1;
        }

        Insurance::where('id_insurance', $request->id)->update([
            'idr' => $numericCharge
        ]);

        return redirect()->back();
    }

    public function importInsurances(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        try {
            $file = $request->file('file');
            $import = new InsuranceImport;
            Excel::import($import, $file);
            $logErrors = $import->getLogErrors();

            if ($logErrors) {
                return redirect('insurance')->with('logErrors', $logErrors);

            } else {
                return redirect('insurance');
            }

        } catch (\Exception $e) {
            $sqlErrors = $e->getMessage();

            if (!empty($sqlErrors)){
                $logErrors = $sqlErrors;
            }

            return redirect('insurance')->with('logErrors', $logErrors);
        }
    }
}
