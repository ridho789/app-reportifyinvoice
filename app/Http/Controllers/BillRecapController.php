<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\BillRecap;
use Illuminate\Support\Facades\Crypt;

class BillRecapController extends Controller
{
    public function index() {
        $customers = Customer::all();
        $bills = BillRecap::all();
        $customerName = Customer::pluck('name', 'id_customer');
        return view('/bill_recap.list_bill_recap', compact('customers', 'bills', 'customerName'));
    }

    public function create() {
        $customers = Customer::all();
        $bill = '';
        return view('/bill_recap.form_bill_recap', compact('customers', 'bill'));
    }

    public function store(Request $request) {
        BillRecap::insert([
            'id_customer' => $request->id_customer,
            'load_date' => $request->load_date,
            'no_inv' => $request->no_inv,
            'freight_type' => $request->freight,
            'entry_date' => $request->entry_date,
            'size' => $request->size,
            'unit_price' => $request->unit_price,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_amount' => $request->payment_amount,
            'remaining_bill' => $request->remaining_bill,
            'overdue_bill' => $request->overdue_bill,
        ]);

        return redirect('/list_bill_recap');
    }

    public function edit($id) {
        $id = Crypt::decrypt($id);
        $customers = Customer::all();
        $bill = BillRecap::where('id_bill_recap', $id)->first();
        return view('/bill_recap.form_bill_recap', compact('customers', 'bill'));
    }

    public function update(Request $request) {
        BillRecap::where('id_bill_recap', $request->id)->update([
            'id_customer' => $request->id_customer,
            'load_date' => $request->load_date,
            'no_inv' => $request->no_inv,
            'freight_type' => $request->freight,
            'entry_date' => $request->entry_date,
            'size' => $request->size,
            'unit_price' => $request->unit_price,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_amount' => $request->payment_amount,
            'remaining_bill' => $request->remaining_bill,
            'overdue_bill' => $request->overdue_bill,
        ]);

        return redirect('/list_bill_recap');
    }
}
