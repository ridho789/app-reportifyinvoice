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
        $customers = Customer::orderBy('name')->get();
        $shippers = Shipper::orderBy('name')->get();
        $logErrors = '';
        return view('main.cas', compact('cas', 'customer', 'shipper', 'customers', 'shippers', 'logErrors'));
    }

    public function store(Request $request) {
        $numericNewCharge = preg_replace("/[^0-9]/", "", explode(",", $request->new_charge)[0]);
        if ($request->new_charge[0] === '-') {
            $numericNewCharge *= -1;
        }

        $dataNewCas = [
            'id_customer' => $request->id_customer,
            'id_shipper' => $request->id_shipper,
            'lts' => $request->new_lts,
            'charge' => $numericNewCharge,
            'desc' => $request->new_desc,
            'start_period' => $request->new_start_period,
            'end_period' => $request->new_end_period
        ];

        $exitingCas = Cas::where('id_customer', $request->id_customer)->where('id_shipper', $request->id_shipper)->where('lts', $request->new_lts)->where('charge', $numericNewCharge)
            ->where('desc', $request->new_desc)->where('start_period', $request->new_start_period)->where('end_period', $request->new_end_period)->first();

        $dataCustomer = Customer::where('id_customer', $request->id_customer)->first();
        $dataShipper = Shipper::where('id_shipper', $request->id_shipper)->first();

        if ($dataCustomer) {
            $customerName = $dataCustomer->name;

        } else {
            $customerName = 'null';
        }

        if ($dataShipper) {
            $shipperName = $dataShipper->name;

        } else {
            $shipperName = 'null';
        }

        if ($request->new_start_period) {
            $startPeriod = $request->new_start_period;

        } else {
            $startPeriod = 'null';
        }

        if ($request->new_end_period) {
            $endPeriod = $request->new_end_period;

        } else {
            $endPeriod = 'null';
        }

        if ($exitingCas) {
            $logErrors = 'Customer: ' . $customerName . ' - ' . 'Shipper: ' . $shipperName . ' - ' . 'LTS: ' . $request->new_lts . ' - ' . 'Charge: ' . 
            $request->new_charge . ' - ' . 'Desc: ' . $request->new_desc . ' - ' . 'Start Period: ' . $startPeriod . ' - ' . 'End Period: ' . $endPeriod . ', already in the system';
            
            return redirect('cas')->with('logErrors', $logErrors);

        } else {
            Cas::create($dataNewCas);
            return redirect('cas');
        }
    }

    public function update(Request $request) {
        $numericCharge = preg_replace("/[^0-9]/", "", explode(",", $request->charge)[0]);
        if ($request->charge[0] === '-') {
            $numericCharge *= -1;
        }

        $dataCas = [
            'id_customer' => $request->id_customer,
            'id_shipper' => $request->id_shipper,
            'lts' => $request->lts,
            'charge' => $numericCharge,
            'desc' => $request->desc,
            'start_period' => $request->start_period,
            'end_period' => $request->end_period
        ];

        Cas::where('id_cas', $request->id)->update($dataCas);

        return redirect('cas');
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
