<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index() {
        return view('/invoice.list_invoices');
    }

    public function create() {
        return view('/invoice.form_invoice');
    }
}
