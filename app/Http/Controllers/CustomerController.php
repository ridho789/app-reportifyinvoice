<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Shipper;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::orderBy('name')->get();
        $shippers = Shipper::all();
        $shipperName = Shipper::pluck('name', 'id_shipper');
        return view('main.customer', compact('customers', 'shippers', 'shipperName'));
    }

    public function store(Request $request) {
        $existingCustomer = Customer::where('name', $request->customer)->first();

        $shipperIds = null;
        if ($request->id_shipper) {
            $shipperIds = implode(',', $request->id_shipper);
        }

        if ($existingCustomer) {
            return redirect()->back()->with([
                'error' => $request->customer . ' already in the system',
                'error_type' => 'duplicate-alert',
                'input' => $request->all(),
            ]);

        } else {

            $customer = $request->customer;
            $shipper_ids = $shipperIds;
            Customer::insert(['name'=> $customer, 'shipper_ids' => $shipper_ids]);
            
            return redirect()->back();
        }
    }

    public function update(Request $request) {
        $existingCustomer = Customer::where('id_customer', $request->id)->first();
        $currentCustomer = $existingCustomer->name;

        $shipperIds = null;
        if ($request->id_shipper) {
            $shipperIds = implode(',', $request->id_shipper);
        }

        if ($currentCustomer != $request->customer) {
            $checkCustomer = Customer::where('name', $request->customer)->first();

            if ($checkCustomer) {
                return redirect()->back()->with([
                    'error' => $request->customer . ' already in the system',
                    'error_type' => 'duplicate-alert',
                    'input' => $request->all(),
                ]);

            } else {
                customer::where('id_customer', $request->id)->update([
                    'name' => $request->customer,
                    'shipper_ids' => $shipperIds
                ]);
        
                return redirect()->back();
            }

        } else {
            customer::where('id_customer', $request->id)->update([
                'shipper_ids' => $shipperIds
            ]);
    
            return redirect()->back();
        }
    } 
}
