<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurance;
use App\Models\SeaShipment;
use App\Models\SeaShipmentLine;
use App\Imports\InsuranceImport;
use Maatwebsite\Excel\Facades\Excel;

class InsuranceController extends Controller
{
    public function index() {
        $insurances = Insurance::all();
        $logErrors = '';
        return view('main.insurance', compact('insurances', 'logErrors'));
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
