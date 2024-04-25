<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Shipper;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::orderBy('name')->get();
        $shippers = Shipper::orderBy('name')->get();
        $shipperName = Shipper::pluck('name', 'id_shipper');
        return view('main.customer', compact('customers', 'shippers', 'shipperName'));
    }

    public function store(Request $request) {
        $shipperIds = $request->id_shipper ?? [];
    
        $customer = Customer::updateOrCreate(
            ['name' => $request->customer],
            ['shipper_ids' => implode(',', $shipperIds)]
        );
    
        if ($customer->wasRecentlyCreated) {
            return redirect()->back();

        } else {
            return redirect()->back()->with([
                'error' => $request->customer . ' already exists in the system',
                'error_type' => 'duplicate-alert',
                'input' => $request->all(),
            ]);
        }
    }
    

    public function update(Request $request) {
        $existingCustomer = Customer::where('id_customer', $request->id)->firstOrFail();
        $currentCustomer = $existingCustomer->name;
    
        $shipperIds = null;
        if ($request->id_shipper) {
            $shipperIds = implode(',', $request->id_shipper);
        }
    
        if ($currentCustomer != $request->customer) {
            $checkCustomer = Customer::where('name', $request->customer)->exists();
    
            if ($checkCustomer) {
                return redirect()->back()->with([
                    'error' => $request->customer . ' already in the system',
                    'error_type' => 'duplicate-alert',
                    'input' => $request->all(),
                ]);

            } else {
                $existingCustomer->name = $request->customer;
                $existingCustomer->shipper_ids = $shipperIds;
                $existingCustomer->save();
    
                return redirect()->back();
            }

        } else {
            $existingCustomer->shipper_ids = $shipperIds;
            $existingCustomer->save();
    
            return redirect()->back();
        }
    }
}
