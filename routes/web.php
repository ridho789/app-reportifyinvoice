<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\shipmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ShipperController;
use App\Http\Controllers\ShipController;
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

// sea shipment
Route::get('/form_sea_shipment', [shipmentController::class, 'createSeaShipment']);
Route::get('/list_sea_shipment', [shipmentController::class, 'listSeaShipment']);
Route::get('sea_shipment-edit/{id_sea_shipment_line}', [shipmentController::class, 'editSeaShipment']);
Route::post('import-sea-shipment', [shipmentController::class, 'importSeaShipment']);

// bill recaps
Route::get('/list_bill_recap', [BillRecapController::class, 'index']);
Route::get('/form_bill_recap', [BillRecapController::class, 'create']);
Route::post('bill_recap-store', [BillRecapController::class, 'store']);
Route::get('bill_recap-edit/{id_bill_recap}', [BillRecapController::class, 'edit']);
Route::post('bill_recap-update', [BillRecapController::class, 'update']);

// customer
Route::get('/customer', [CustomerController::class, 'index']);
Route::post('customer-store', [CustomerController::class, 'store']);
Route::post('customer-update', [CustomerController::class, 'update']);

// shipper
Route::get('/shipper', [ShipperController::class, 'index']);
Route::post('shipper-store', [ShipperController::class, 'store']);
Route::post('shipper-update', [ShipperController::class, 'update']);

// ship
Route::get('/ship', [ShipController::class, 'index']);
Route::post('ship-store', [ShipController::class, 'store']);
Route::post('ship-update', [ShipController::class, 'update']);