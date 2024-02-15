<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\shipmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ShipperController;
use App\Http\Controllers\BillRecapController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index']);

// shipment
Route::get('/list_shipments', [shipmentController::class, 'index']);
Route::get('/form_shipments', [shipmentController::class, 'create']);

// bill recaps
Route::get('/list_bill_recaps', [BillRecapController::class, 'index']);

// customer
Route::get('/customer', [CustomerController::class, 'index']);
Route::post('customer-store', [CustomerController::class, 'store']);
Route::post('customer-update', [CustomerController::class, 'update']);

// shipper
Route::get('/shipper', [ShipperController::class, 'index']);
Route::post('shipper-store', [ShipperController::class, 'store']);
Route::post('shipper-update', [ShipperController::class, 'update']);
