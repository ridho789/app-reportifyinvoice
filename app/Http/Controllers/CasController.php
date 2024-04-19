<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cas;
use App\Models\Customer;
use App\Models\Shipper;
use App\Imports\CasImport;
use Maatwebsite\Excel\Facades\Excel;

class CasController extends Controller
{
    public function index() {
        $cas = Cas::all();
        $customer = Customer::pluck('name', 'id_customer');
        $shipper = Shipper::pluck('name', 'id_shipper');
        $logErrors = '';
        return view('main.cas', compact('cas', 'customer', 'shipper', 'logErrors'));
    }

    public function update(Request $request) {
        $numericCharge = preg_replace("/[^0-9]/", "", explode(",", $request->charge)[0]);
        if ($request->charge[0] === '-') {
            $numericCharge *= -1;
        }

        Cas::where('id_cas', $request->id)->update([
            'desc' => $request->desc,
            'charge' => $numericCharge
        ]);

        return redirect()->back();
    }

    public function importCas(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        try {
            $file = $request->file('file');
            $import = new CasImport;
            Excel::import($import, $file);
            $logErrors = $import->getLogErrors();

            if ($logErrors) {
                return redirect('cas')->with('logErrors', $logErrors);

            } else {
                return redirect('cas');
            }

        } catch (\Exception $e) {
            $sqlErrors = $e->getMessage();

            if (!empty($sqlErrors)){
                $logErrors = $sqlErrors;
            }

            return redirect('cas')->with('logErrors', $logErrors);
        }
    }
}
