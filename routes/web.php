<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\shipmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ShipperController;
use App\Http\Controllers\ShipController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\BillRecapController;
use App\Http\Controllers\PricelistController;
use App\Http\Controllers\CasController;

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
Route::post('sea_shipment-update', [shipmentController::class, 'updateSeaShipment']);
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

// insurance
Route::get('/insurance', [InsuranceController::class, 'index']);
Route::post('insurance-update', [InsuranceController::class, 'update']);
Route::post('import-insurances', [InsuranceController::class, 'importInsurances']);

// pricelist
Route::get('/pricelist', [PricelistController::class, 'index']);
Route::post('pricelist-store', [PricelistController::class, 'store']);
Route::post('pricelist-update', [PricelistController::class, 'update']);
Route::post('import-pricelist', [PricelistController::class, 'importPricelist']);

// cas
Route::get('/cas', [CasController::class, 'index']);
Route::post('cas-store', [CasController::class, 'store']);
Route::post('cas-update', [CasController::class, 'update']);
Route::post('import-cas', [CasController::class, 'importCas']);