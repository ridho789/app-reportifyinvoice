<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pricelist;
use App\Models\Customer;
use App\Models\Shipper;
use App\Imports\PricelistImport;
use Maatwebsite\Excel\Facades\Excel;

class PricelistController extends Controller
{
    public function index() {
        $pricelists = Pricelist::all();
        $customer = Customer::pluck('name', 'id_customer');
        $shipper = Shipper::pluck('name', 'id_shipper');
        $logErrors = '';
        return view('main.pricelist', compact('pricelists', 'customer', 'shipper','logErrors'));
    }

    public function importPricelist(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        try {
            $file = $request->file('file');
            $import = new PricelistImport;
            Excel::import($import, $file);
            $logErrors = $import->getLogErrors();

            if ($logErrors) {
                return redirect('pricelist')->with('logErrors', $logErrors);

            } else {
                return redirect('pricelist');
            }

        } catch (\Exception $e) {
            $sqlErrors = $e->getMessage();

            if (!empty($sqlErrors)){
                $logErrors = $sqlErrors;
            }

            return redirect('pricelist')->with('logErrors', $logErrors);
        }
    }
}
