<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Shipper;
use App\Models\Company;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::orderBy('name')->get();
        $shippers = Shipper::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        $shipperName = Shipper::pluck('name', 'id_shipper');
        $companyName = Company::pluck('name', 'id_company');
        return view('main.customer', compact('customers', 'shippers', 'shipperName', 'companies', 'companyName'));
    }

    public function store(Request $request) {
        $shipperIds = $request->id_shipper ?? [];
        
        $customer = Customer::updateOrCreate(
            ['name' => $request->customer],
            ['shipper_ids' => implode(',', $shipperIds)],
            ['id_company' => $request->id_company],
            ['is_detail_invoice' => $request->input_is_detail_invoice]
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
                $existingCustomer->id_company = $request->id_company;
                $existingCustomer->shipper_ids = $shipperIds;
                $existingCustomer->is_detail_invoice = $request->value_is_detail_invoice;
                $existingCustomer->save();
    
                return redirect()->back();
            }

        } else {
            $existingCustomer->id_company = $request->id_company;
            $existingCustomer->shipper_ids = $shipperIds;
            $existingCustomer->is_detail_invoice = $request->value_is_detail_invoice;
            $existingCustomer->save();
    
            return redirect()->back();
        }
    }
}
