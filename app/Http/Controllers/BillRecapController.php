<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillRecapController extends Controller
{
    public function index() {
        return view('/bill_recap.list_bill_recaps');
    }
}
