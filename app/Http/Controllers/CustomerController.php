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

        if ($existingCustomer) {
            return redirect()->back()->with([
                'error' => $request->customer . ' already in the system',
                'error_type' => 'duplicate-alert',
                'input' => $request->all(),
            ]);

        } else {

            $customer = $request->customer;
            $id_shipper = $request->id_shipper;
            Customer::insert(['name'=> $customer, 'id_shipper' => $id_shipper]);
            
            return redirect()->back();
        }
    }

    public function update(Request $request) {
        $existingCustomer = Customer::where('id_customer', $request->id)->first();
        $currentCustomer = $existingCustomer->name;

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
                    'id_shipper' => $request->id_shipper
                ]);
        
                return redirect()->back();
            }

        } else {
            customer::where('id_customer', $request->id)->update([
                'id_shipper' => $request->id_shipper
            ]);
    
            return redirect()->back();
        }
    } 
}
