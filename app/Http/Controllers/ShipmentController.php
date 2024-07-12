<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Shipper;
use App\Models\Ship;
use App\Models\Unit;
use App\Models\Desc;
use App\Models\Account;
use App\Models\Banker;
use App\Models\Origin;
use App\Models\State;
use App\Models\SeaShipment;
use App\Models\SeaShipmentLine;
use App\Imports\SeaShipmentImport;
use App\Models\Cas;
use App\Models\Insurance;
use App\Models\Pricelist;
use App\Models\SeaShipmentBill;
use App\Models\SeaShipmentAnotherBill;
use App\Models\BillRecap;
use App\Models\History;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PDF;
use DateTime;
use DateInterval;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;

class ShipmentController extends Controller
{
    public function index() {
        $seaShipment = SeaShipment::count();
        $logErrors = '';
        return view('shipment.list_shipments', compact('seaShipment', 'logErrors'));
    }

    // sea shipment
    public function createSeaShipment() {
        $seaShipment = '';
        $seaShipmentLines = '';
        $groupSeaShipmentLines = '';
        $customers = Customer::orderBy('name')->get();
        $shippers = Shipper::orderBy('name')->get();
        $ships = Ship::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $origins = Origin::orderBy('name')->get();
        $states = State::orderBy('name')->get();
        return view('shipment.sea_shipment.form_sea_shipment', compact('seaShipment', 'seaShipmentLines', 'customers', 'shippers', 'states', 
        'ships', 'units', 'origins', 'groupSeaShipmentLines'));
    }

    public function listSeaShipment() {
        $allSeaShipment = SeaShipment::orderBy('etd')->get();
        $customer = Customer::pluck('name', 'id_customer');
        $shipper = Shipper::pluck('name', 'id_shipper');
        $ship = Ship::pluck('name', 'id_ship');
        $origin = Origin::pluck('name', 'id_origin');
        return view('shipment.sea_shipment.list_sea_shipments', compact('allSeaShipment','customer', 'shipper', 'ship', 'origin'));
    }

    public function editSeaShipment($id) {
        // Encrypt-Decrypt ID
        $id = Crypt::decrypt($id);

        $seaShipment = SeaShipment::where('id_sea_shipment', $id)->first();
        $seaShipmentLines = SeaShipmentLine::where('id_sea_shipment', $seaShipment->id_sea_shipment)->get();
        $seaShipmentBill = SeaShipmentBill::where('id_sea_shipment', $seaShipment->id_sea_shipment)->orderBy('date')->get();
        $seaShipmentAnotherBill = SeaShipmentAnotherBill::where('id_sea_shipment', $seaShipment->id_sea_shipment)->orderBy('date')->get();
        $checkCbmDiff = false;

        $isWeight = false;
        $totalCbm1Overall = 0;
        $totalCbm2Overall = 0;
        $totalWeightOverall = 0;

        $groupSeaShipmentLines = $seaShipmentLines->groupBy(function ($item) {
            return $item->date;
        })->map(function ($group) use (&$checkCbmDiff, &$totalCbm1Overall, &$totalCbm2Overall, &$totalWeightOverall) {
            $totals = [
                'total_qty_pkgs' => $group->filter(function ($item) {
                    return is_numeric($item->qty_pkgs);
                })->sum('qty_pkgs'),
                'total_weight' => $group->filter(function ($item) {
                    return is_numeric($item->weight);
                })->sum('weight'),
                'total_cbm1' => $group->filter(function ($item) {
                    return is_numeric($item->tot_cbm_1);
                })->sum('tot_cbm_1'),
                'total_cbm2' => $group->filter(function ($item) {
                    return is_numeric($item->tot_cbm_2);
                })->sum('tot_cbm_2')
            ];

            $totalWeightOverall += $totals['total_weight'];
            $totalCbm1Overall += $totals['total_cbm1'];
            $totalCbm2Overall += $totals['total_cbm2'];
        
            $totals['cbm_difference'] = $totals['total_cbm1'] - $totals['total_cbm2'];

            if ($totals['cbm_difference'] != 0) {
                $checkCbmDiff = true;
            }
        
            return $totals;
        });

        // Check billing with weight or cbm
        $totalCbmOverall = $totalCbm2Overall != 0 ? $totalCbm2Overall : $totalCbm1Overall;
        if (($totalWeightOverall / 1000) > $totalCbmOverall) {
            $isWeight = true;

        }else {
            $isWeight = false;
        }

        $customers = Customer::orderBy('name')->get();
        $customer = Customer::where('id_customer', $seaShipment->id_customer)->first();
        $shippers = Shipper::orderBy('name')->get();
        $ships = Ship::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $descs = Desc::orderBy('name')->get();
        $origins = Origin::orderBy('name')->get();
        $originName = Origin::pluck('name', 'id_origin');
        $accounts = Account::orderBy('account_no')->get();
        $bankers = Banker::orderBy('name')->get();
        $states = State::orderBy('name')->get();
        return view('shipment.sea_shipment.form_sea_shipment', compact('seaShipment', 'seaShipmentLines', 'customers', 'customer', 'shippers', 'accounts', 'bankers', 'origins', 'originName', 
        'states', 'ships', 'units', 'descs', 'companies', 'groupSeaShipmentLines', 'checkCbmDiff', 'seaShipmentBill', 'seaShipmentAnotherBill', 'isWeight', 'totalWeightOverall', 'totalCbmOverall'));
    }

    public function storeSeaShipment(Request $request) {
        $customer = Customer::where('id_customer', $request->id_customer)->first();
        $shipperIds = $customer->shipper_ids;
        $shipperIdsArray = explode(",", $shipperIds);

        if (!in_array($request->id_shipper, $shipperIdsArray)) {
            Customer::where('id_customer', $request->id_customer)->update([
                'shipper_ids' => $shipperIds . ',' . $request->id_shipper,
            ]);
        }

        $dataSeaShipment = [
            'no_aju' => strtoupper($request->no_aju),
            'date' => $request->date,
            'id_customer' => $request->id_customer,
            'id_shipper' => $request->id_shipper,
            'id_ship' => $request->id_ship,
            'id_origin' => $request->id_origin,
            'etd' => $request->etd,
            'eta' => $request->eta,
        ];

        $checkSeaShipment = SeaShipment::where('no_aju', strtoupper($request->no_aju))->where('id_customer', $request->id_customer)->where('id_shipper', $request->id_shipper)
        ->where('id_origin', $request->id_origin)->where('etd', $request->etd)->first();

        if (empty($checkSeaShipment)) {
            $createdSeaShipment = SeaShipment::create($dataSeaShipment);
            $seaShipmentId = $createdSeaShipment->id_sea_shipment;

            foreach ($request->bldate as $index => $bldate) {
                $dataSeaShipmentLine = [
                    'date' => $bldate,
                    'code' => strtoupper($request->code[$index]),
                    'marking' => strtoupper($request->marking[$index]),
                    'qty_pkgs' => $request->qty_pkgs[$index],
                    'qty_loose' => $request->qty_loose[$index],
                    'unit_qty_pkgs' => $request->unit_qty_pkgs[$index],
                    'unit_qty_loose' => $request->unit_qty_loose[$index],
                    'weight' => $request->weight[$index],
                    'dimension_p' => $request->p[$index],
                    'dimension_l' => $request->l[$index],
                    'dimension_t' => $request->t[$index],
                    'tot_cbm_1' => $request->cbm1[$index],
                    'tot_cbm_2' => $request->cbm2[$index],
                    'lts' => strtoupper($request->lts[$index]),
                    'qty' => $request->qty[$index],
                    'id_unit' => $request->id_unit[$index],
                    'desc' => strtoupper($request->desc[$index]),
                    'id_state' => $request->id_state[$index],
                    'id_sea_shipment' => $seaShipmentId,
                ];

                SeaShipmentLine::create($dataSeaShipmentLine);
            }

            $encryptedId = Crypt::encrypt($seaShipmentId);
            return redirect("/sea_shipment-edit/{$encryptedId}");

        } else {
            return redirect()->back()->with([
                'error' => 'Already exists in the system',
                'error_type' => 'duplicate-alert',
                'input' => $request->all(),
                'isValid' => false,
            ]);
        }
    }

    public function updateSeaShipment(Request $request) {
        $customer = Customer::where('id_customer', $request->id_customer)->first();
        $shipperIds = $customer->shipper_ids;
        $shipperIdsArray = explode(",", $shipperIds);

        if (!in_array($request->id_shipper, $shipperIdsArray)) {
            Customer::where('id_customer', $request->id_customer)->update([
                'shipper_ids' => $shipperIds . ',' . $request->id_shipper,
            ]);
        }

        $SeaShipment = SeaShipment::find($request->id_sea_shipment);

        if ($SeaShipment) {
            $SeaShipment->no_aju = strtoupper($request->no_aju);
            $SeaShipment->date = $request->date;
            $SeaShipment->id_customer = $request->id_customer;
            $SeaShipment->id_shipper = $request->id_shipper;
            $SeaShipment->id_ship = $request->id_ship;
            $SeaShipment->id_origin = $request->id_origin;
            $SeaShipment->etd = $request->etd;
            $SeaShipment->eta = $request->eta;
        }

        // File
        $request->validate([
            'file_shipment_status' => 'mimes:pdf|max:2048',
        ]);

        if ($request->file('file_shipment_status')) {
            $file = $request->file('file_shipment_status');
            $dateTime = new DateTime();
            $dateTime->modify('+7 hours');
            $currentDateTime = $dateTime->format('d_m_Y_H_i_s');
            $fileName = $currentDateTime . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('ShipmentStatus', $fileName, 'public');
        
            if ($SeaShipment) {
                $SeaShipment->file_shipment_status = $filePath;
            }
        }

        // Save
        $SeaShipment->save();
        
        foreach ($request->id_sea_shipment_line as $index => $idSeaShipmentLine) {
            $seaShipmentLine = SeaShipmentLine::find($idSeaShipmentLine);
        
            $data = [
                'date' => $request->bldate[$index],
                'code' => strtoupper($request->code[$index]),
                'marking' => strtoupper($request->marking[$index]),
                'qty_pkgs' => $request->qty_pkgs[$index],
                'qty_loose' => $request->qty_loose[$index],
                'unit_qty_pkgs' => $request->unit_qty_pkgs[$index],
                'unit_qty_loose' => $request->unit_qty_loose[$index],
                'weight' => $request->weight[$index],
                'dimension_p' => $request->p[$index],
                'dimension_l' => $request->l[$index],
                'dimension_t' => $request->t[$index],
                'tot_cbm_1' => $request->cbm1[$index],
                'tot_cbm_2' => $request->cbm2[$index],
                'lts' => strtoupper($request->lts[$index]),
                'qty' => $request->qty[$index],
                'id_unit' => $request->id_unit[$index],
                'desc' => strtoupper($request->desc[$index]),
                'id_state' => $request->id_state[$index],
                'id_sea_shipment' => $request->id_sea_shipment,
            ];
        
            if ($seaShipmentLine) {
                $seaShipmentLine->update($data);

            } else {
                // Buat data baru di SeaShipmentLine jika tidak ada
                SeaShipmentLine::create($data + ['id_sea_shipment_line' => $idSeaShipmentLine]);
            }
        }

        return redirect()->back();
    }

    public function deleteSeaShipment($id) {
        SeaShipmentLine::where('id_sea_shipment', $id)->delete();
        SeaShipment::where('id_sea_shipment', $id)->delete();
        SeaShipmentBill::where('id_sea_shipment', $id)->delete();
        SeaShipmentAnotherBill::where('id_sea_shipment', $id)->delete();
        History::where('id_changed_data', $id)->delete();

        if (count(SeaShipment::all()) == 0) {
            return redirect('list_shipments');
            
        } else {
            return redirect()->back();
        }
    }

    public function importSeaShipment(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);
    
        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file);
            $sheetNames = $spreadsheet->getSheetNames();
    
            $import = new SeaShipmentImport($sheetNames);
            Excel::import($import, $file);
            $logErrors = $import->getLogErrors();
    
            if ($logErrors) {
                return redirect('list_shipments')->with('logErrors', $logErrors);
            } else {
                return redirect('list_sea_shipment');
            }
    
        } catch (\Exception $e) {
            $sqlErrors = $e->getMessage();
    
            if (!empty($sqlErrors)){
                $logErrors = $sqlErrors;
            }
    
            return redirect('list_shipments')->with('logErrors', $logErrors);
        }
    }

    public function printSeaShipment(Request $request) {
        $id_sea_shipment = $request->id;
        $seaShipment = SeaShipment::where('id_sea_shipment', $id_sea_shipment)->firstOrFail();
        $seaShipmentLines = SeaShipmentLine::where('id_sea_shipment', $seaShipment->id_sea_shipment)->orderBy('date')->orderBy('marking')->get();
        $seaShipmentBill = SeaShipmentBill::where('id_sea_shipment', $seaShipment->id_sea_shipment)->orderBy('date')->get();

        $customer = Customer::where('id_customer', $seaShipment->id_customer)->firstOrFail();
        $shipper = Shipper::where('id_shipper', $seaShipment->id_shipper)->first();
        $company = Company::where('id_company', $request->id_company)->first();
        $descsData = Desc::orderBy('name')->get();
        $account = $request->id_account ? Account::where('id_account', $request->id_account)->first() : null;
        $banker = $request->id_banker ? Banker::where('id_banker', $request->id_banker)->first() : null;
        $origin = Origin::where('id_origin', $seaShipment->id_origin)->first();

        // set pricelist
        $pricelist = 0;

        $defaultPricelist = Pricelist::where('id_customer', null)->where('id_shipper', null)->where('id_origin', $seaShipment->id_origin)
        ->where('start_period', null)->where('end_period', null)->first();

        $shipperPricelist = Pricelist::where('id_customer', null)->where('id_shipper', $seaShipment->id_shipper)->where('id_origin', $seaShipment->id_origin)
        ->where('start_period', null)->where('end_period', null)->first();

        $customerPricelist = Pricelist::where('id_customer', $seaShipment->id_customer)->where('id_shipper', null)->where('id_origin', $seaShipment->id_origin)
        ->where('start_period', null)->where('end_period', null)->first();

        $customerShipperPricelist = Pricelist::where('id_customer', $seaShipment->id_customer)->where('id_shipper', $seaShipment->id_shipper)->where('id_origin', $seaShipment->id_origin)
        ->where('start_period', null)->where('end_period', null)->first();

        $periodPricelist = Pricelist::where('id_customer', $seaShipment->id_customer)->where('id_shipper', $seaShipment->id_shipper)->where('id_origin', $seaShipment->id_origin)
        ->where('start_period', '>=', $seaShipment->date)->where('end_period', null)->first();

        $allPeriodPricelist = Pricelist::where('id_customer', $seaShipment->id_customer)->where('id_shipper', $seaShipment->id_shipper)->where('id_origin', $seaShipment->id_origin)
        ->where('start_period', '<=', $seaShipment->date)->where('end_period', '>=', $seaShipment->date)->first();

        if ($defaultPricelist) {
            $pricelist = $defaultPricelist->price;
        }

        if ($shipperPricelist) {
            $pricelist = $shipperPricelist->price;
        }

        if ($customerPricelist) {
            $pricelist = $customerPricelist->price;
        }

        if ($customerShipperPricelist) {
            $pricelist = $customerShipperPricelist->price;
        }

        if ($periodPricelist) {
            $pricelist = $periodPricelist->price;
        }

        if ($allPeriodPricelist) {
            $pricelist = $allPeriodPricelist->price;
        }

        $totalCbm1Overall = 0;
        $totalCbm2Overall = 0;
        $totalCasOverall = 0;
        $totalCbmDiffOverall = 0;
        $totalAmountOverall = 0;
        $totalWeightOverall = 0;
        $totalAmountWeightOverall = 0;
        $totalAmountCbmOverall = 0;
        $totalAmountUnit = 0;

        // Initial variabel
        $isWeight = $request->is_weight;
        $is_tonase = false;

        function calculateTotals($group, $customer, $seaShipment, &$totalCbm1Overall, &$totalCbm2Overall, &$totalWeightOverall, &$totalCasOverall, &$totalCbmDiffOverall, 
        &$totalAmountWeightOverall, &$totalAmountCbmOverall, &$totalAmountUnit, $pricelist, $isWeight) {
            $totals = [
                'total_qty_pkgs' => $group->filter(function ($item) {
                    return is_numeric($item->qty_pkgs);
                })->sum('qty_pkgs'),
                'total_qty_loose' => $group->filter(function ($item) {
                    return is_numeric($item->qty_loose);
                })->sum('qty_loose'),
                'total_weight' => $group->filter(function ($item) {
                    return is_numeric($item->weight);
                })->sum('weight'),
                'total_cbm1' => $group->filter(function ($item) {
                    return is_numeric($item->tot_cbm_1);
                })->sum('tot_cbm_1'),
                'total_cbm2' => $group->filter(function ($item) {
                    return is_numeric($item->tot_cbm_2);
                })->sum('tot_cbm_2'),
                'total_qty_unit' => $group->filter(function ($item) {
                    return is_numeric($item->qty);
                })->sum('qty'),
            ];

            $totalWeightOverall += $totals['total_weight'];
            $totalCbm1Overall += $totals['total_cbm1'];
            $totalCbm2Overall += $totals['total_cbm2'];

            // set cas
            $cas = null;
            $lts = $group->first()->lts;
            $idUnit = $group->first()->id_unit;

            // set unit
            $unit = null;
            if ($idUnit) {
                $unit = Unit::where('id_unit', $idUnit)->value('name');
            }

            $cas = Cas::where('id_customer', $seaShipment->id_customer)
                    ->where('id_shipper', $seaShipment->id_shipper)
                    ->where('lts', $lts)
                    ->where('id_origin', $seaShipment->id_origin)
                    ->where('id_unit', $idUnit)
                    ->where(function ($query) use ($seaShipment) {
                        $query->whereNull('start_period')
                            ->whereNull('end_period')
                            ->orWhere('start_period', '<=', $seaShipment->date)
                            ->where('end_period', '>=', $seaShipment->date)
                            ->orWhere('start_period', '>=', $seaShipment->date)
                            ->whereNull('end_period');
                    })->value('charge') ?? 
                Cas::where('id_customer', $seaShipment->id_customer)->whereNull('id_shipper')->where('lts', $lts)->where('id_origin', $seaShipment->id_origin)->where('id_unit', $idUnit)->value('charge') ??
                Cas::whereNull('id_customer')->where('id_shipper', $seaShipment->id_shipper)->where('lts', $lts)->where('id_origin', $seaShipment->id_origin)->where('id_unit', $idUnit)->value('charge') ??
                Cas::whereNull('id_customer')->whereNull('id_shipper')->where('lts', $lts)->where('id_origin', $seaShipment->id_origin)->where('id_unit', $idUnit)->value('charge');

            $totals['cas'] = $cas;
            $totalCasOverall += $totals['cas'];

            // amount
            $cbm = $totals['total_cbm2'] != 0 ? $totals['total_cbm2'] : $totals['total_cbm1'];
            $weight = $totals['total_weight'];
            
            if (in_array($lts, ['LP', 'LPI', 'LPM', 'LPM/LPI', 'LPI/LPM'])) {
                $qtyUnit = $totals['total_qty_unit'];
                $totalAmountUnit += $qtyUnit * $totals['cas'];
                
            } else {
                if ($isWeight || ($unit == 'T')) {
                    $totalAmountWeightOverall += $weight * ($pricelist + $totals['cas']);

                } else {
                    if ($lts) {
                        $totalAmountCbmOverall += $cbm * ($pricelist + $totals['cas']);
                    }
                }
            }

            // $totals['markings'] = $group->pluck('marking')->unique()->toArray();

            // initialize markings with empty arrays
            $totals['markings'] = [];

            // process each item to calculate total[marking] and item_qty if lts is LP, LPI, or LPM
            $group->each(function ($item) use (&$totals) {
                $marking = $item->marking;
                if (!isset($totals['markings'][$marking])) {
                    $totals['markings'][$marking] = 0;
                }

                // Add item_qty if lts is LP, LPI, LPM, LPM/LPI, LPI/LPM
                if (in_array($item->lts, ['LP', 'LPI', 'LPM', 'LPM/LPI', 'LPI/LPM'])) {
                    $totals['markings'][$marking] += $item->qty;
                }
            });

            return $totals;
        }

        // Initial grouping
        if (in_array($origin->name, ['SIN-BTH', 'SIN-JKT'])) {

            // reset total value
            $totalCbm1Overall = 0;
            $totalCbm2Overall = 0;
            $totalCasOverall = 0;
            $totalCbmDiffOverall = 0;
            $totalAmountOverall = 0;
            $totalWeightOverall = 0;
            $totalAmountWeightOverall = 0;
            $totalAmountCbmOverall = 0;
            $totalAmountUnit = 0;

            // Initial variabel
            $is_tonase = false;

            $groupSeaShipmentLines = $seaShipmentLines->groupBy(function ($item) use (&$is_tonase) {
                // unit
                $unit = Unit::where('id_unit', $item->id_unit)->value('name');

                // Changed to active weight if unit = tonase
                if ($unit == 'T') {
                    $is_tonase = true;
                }

                $unitPart = $unit ? $unit . '-' : '';
                return $item->date . '-' . $unitPart . $item->lts;
                
            })->map(function ($group) use ($customer, $seaShipment, &$totalCbm1Overall, &$totalCbm2Overall, &$totalWeightOverall, &$totalCasOverall, &$totalCbmDiffOverall, 
                &$totalAmountWeightOverall, &$totalAmountCbmOverall, &$totalAmountUnit, $pricelist, $isWeight) {
                return calculateTotals($group, $customer, $seaShipment, $totalCbm1Overall, $totalCbm2Overall, $totalWeightOverall, $totalCasOverall, $totalCbmDiffOverall, 
                $totalAmountWeightOverall, $totalAmountCbmOverall, $totalAmountUnit, $pricelist, $isWeight);
            });
        }

        // Conditional re-grouping
        if (($request->inv_type && $request->inv_type == 'separate') || in_array($origin->name, ['BTH-SIN', 'BTH-JKT'])) {

            // reset total value
            $totalCbm1Overall = 0;
            $totalCbm2Overall = 0;
            $totalCasOverall = 0;
            $totalCbmDiffOverall = 0;
            $totalAmountOverall = 0;
            $totalWeightOverall = 0;
            $totalAmountWeightOverall = 0;
            $totalAmountCbmOverall = 0;
            $totalAmountUnit = 0;

            // Initial variabel
            $is_tonase = false;

            $groupSeaShipmentLines = $seaShipmentLines->groupBy(function ($item) use (&$is_tonase) {
                // unit
                $unit = Unit::where('id_unit', $item->id_unit)->value('name');

                // Changed to active weight if unit = tonase
                if ($unit == 'T') {
                    $is_tonase = true;
                }

                $unitPart = $unit ? $unit . '-' : '';
                return $item->date . '-' . $unitPart . $item->marking . '-' . $item->lts;

            })->map(function ($group) use ($customer, $seaShipment, &$totalCbm1Overall, &$totalCbm2Overall, &$totalWeightOverall, &$totalCasOverall, &$totalCbmDiffOverall, 
                &$totalAmountWeightOverall, &$totalAmountCbmOverall, &$totalAmountUnit, $pricelist, $isWeight) {
                return calculateTotals($group, $customer, $seaShipment, $totalCbm1Overall, $totalCbm2Overall, $totalWeightOverall, $totalCasOverall, $totalCbmDiffOverall, 
                $totalAmountWeightOverall, $totalAmountCbmOverall, $totalAmountUnit, $pricelist, $isWeight);
            });
        }

        // Group seaShipmentLines by Date and calculate total_cbm1, total_cbm2, and cbm difference
        $groupedSeaShipmentLinesDate = $seaShipmentLines->groupBy(function($item) {
            return $item->date;
        })->map(function($group) use (&$totalCbmDiffOverall) {
            $total_cbm1 = $group->sum('tot_cbm_1');
            $total_cbm2 = $group->sum('tot_cbm_2');

            // Calculate cbm difference and accumulate to $totalCbmDiffOverall
            $cbmDiff = $total_cbm1 - $total_cbm2;
            $totalCbmDiffOverall += $cbmDiff;

            return [
                'total_cbm1' => $total_cbm1,
                'total_cbm2' => $total_cbm2,
                'cbm_difference' => $cbmDiff,
                'items' => $group
            ];
        });

        // Apply isWeight condition to totalAmountOverall calculation
        if ($isWeight) {
            $totalAmountOverall = $totalAmountWeightOverall;
            
        } else {
            if ($is_tonase) {
                $totalAmountOverall = $totalAmountCbmOverall + $totalAmountWeightOverall;

            } else {
                $totalAmountOverall = $totalAmountCbmOverall;
            }
        }

        function romanNumerals($number) {
            $roman = '';
            $romanDigit = array('','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII');
            if($number > 0 && $number <= 12) {
                $roman = $romanDigit[$number];
            }
            return $roman;
        }

        $invNumber = $request->inv_no;
        if (strpos($invNumber, '-') !== false) {
            list($numberPart, $suffixPart) = explode('-', $invNumber);
            $formattedNumberPart = sprintf("%03d", $numberPart);
            $invNumber = $formattedNumberPart . '-' . $suffixPart;

        } else {
            $invNumber = sprintf("%03d", $invNumber);
        }

        $month = ltrim(date("m", strtotime($seaShipment->date)), '0');
        $monthRoman = romanNumerals($month);
        $year = date("Y", strtotime($seaShipment->date));

        // update company if changed in customer
        if ($customer->id_company != $request->id_company) {
            $customer->id_company = $request->id_company;
            $customer->save();
        }

        // update banker if changed in customer
        if ($customer->id_banker != $request->id_banker) {
            $customer->id_banker = $request->id_banker;
            $customer->save();
        }

        // update account if changed in customer
        if ($customer->id_account != $request->id_account) {
            $customer->id_account = $request->id_account;
            $customer->save();
        }

        // update bill diff in customer
        $bill_diff = null;
        if ($request->bill_diff) {

            $numericBillDiff = preg_replace("/[^0-9]/", "", explode(",", $request->bill_diff)[0]);
            if ($request->bill_diff[0] === '-') {
                $numericBillDiff *= -1;
            }

            $bill_diff = $numericBillDiff;
            $customer->bill_diff = $numericBillDiff;
            $customer->save();
        }

        // update invoice type
        $inv_type = null;
        if ($request->inv_type) {
            $inv_type = $request->inv_type;
            $customer->inv_type = $inv_type;
            $customer->save();
        }

        // format invoice
        $invNameGenerate = $invNumber . '/' . $company->shorter . '/' . 'INV/' . $monthRoman . '/' . $year;
        $titleInv = $customer->name . '-' . $shipper->name . '-' . $invNumber . '/' . $company->shorter . '/' . 'INV/' . $monthRoman . '/' . $year;

        // payment due
        $shipmentDate = new DateTime($seaShipment->etd);
        $termInterval = new DateInterval('P' . $request->term . 'D');
        $shipmentDate->add($termInterval);

        $paymentDue = $shipmentDate->format('Y-m-d');

        if ($company->shorter == 'KPN') {
            $imagePath = public_path('asset/assets/img/KOP/KPN.png');
            $imageContent = file_get_contents($imagePath);
            $companyName = 'PT KARYA PUTRA NATUNA';
        }

        if ($company->shorter == 'BMM') {
            $imagePath = public_path('asset/assets/img/KOP/BMM.png');
            $imageContent = file_get_contents($imagePath);
            $companyName = 'PT BEVI MARGI MULYA';
        }

        if ($company->shorter == 'BMA') {
            $imagePath = public_path('asset/assets/img/KOP/BMA.png');
            $imageContent = file_get_contents($imagePath);
            $companyName = 'PT BIEMAN MAKMUR ABADI';
        }

        if ($company->shorter == 'SMK') {
            $imagePath = public_path('asset/assets/img/KOP/SMK.jpg');
            $imageContent = file_get_contents($imagePath);
            $companyName = 'PT SURYA MAKMUR KREASI';
        }

        if ($company->shorter == 'SMD') {
            $imagePath = public_path('asset/assets/img/KOP/SMD.png');
            $imageContent = file_get_contents($imagePath);
            $companyName = 'SEMADI';
        }

        if ($company->shorter == 'SNM') {
            $imagePath = public_path('asset/assets/img/KOP/SNM.jpg');
            $imageContent = file_get_contents($imagePath);
            $companyName = 'PT SETIA NEGARA MAJU';
        }

        $dataBill = null;
        $totalTransportOverall = 0;
        $totalBlOverall = 0;
        $totalPermitOverall = 0;
        $totalInsuranceOverall = 0;
        $totalanotherBillOverall = 0;

        if (in_array($origin->name, ['SIN-BTH', 'SIN-JKT'])) {
            $dataBill = [
                'dateBL' => $request->dateBL,
                'codeShipment' => $request->codeShipment,
                'transport' => $request->transport,
                'bl' => $request->bl,
                'permit' => $request->permit,
                'insurance' => $request->insurance
            ];

            if ($dataBill) {
                foreach ($dataBill["dateBL"] as $index => $date) {
                    $transportValue = (int) preg_replace("/[^0-9]/", "", explode(",", $dataBill["transport"][$index])[0]);
                    $blValue = (int) preg_replace("/[^0-9]/", "", explode(",", $dataBill["bl"][$index])[0]);
                    $permitValue = (int) preg_replace("/[^0-9]/", "", explode(",", $dataBill["permit"][$index])[0]);
                    $insuranceValue = (int) preg_replace("/[^0-9]/", "", explode(",", $dataBill["insurance"][$index])[0]);

                    $totalTransportOverall += $transportValue;
                    $totalBlOverall += $blValue;
                    $totalPermitOverall += $permitValue;
                    $totalInsuranceOverall += $insuranceValue;

                    try {
                        $checkSeaShipmentBill = SeaShipmentBill::where('id_sea_shipment', $seaShipment->id_sea_shipment)->where('date', $date)->firstOrFail();
                    
                        $checkSeaShipmentBill->code = $dataBill["codeShipment"][$index];
                        $checkSeaShipmentBill->transport = $transportValue;
                        $checkSeaShipmentBill->bl = $blValue;
                        $checkSeaShipmentBill->permit = $permitValue;
                        $checkSeaShipmentBill->insurance = $insuranceValue;
                        $checkSeaShipmentBill->save();

                    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                        SeaShipmentBill::create([
                            'id_sea_shipment' => $seaShipment->id_sea_shipment,
                            'date' => $date,
                            'code' => $dataBill["codeShipment"][$index],
                            'transport' => $transportValue,
                            'bl' => $blValue,
                            'permit' => $permitValue,
                            'insurance' => $insuranceValue
                        ]);
                    }
                }
            }
        }

        // Another bill
        $dataAnotherBill = [
            'id' => $request->idAnotherBill,
            'date' => $request->dateAnotherBL,
            'desc' => $request->id_desc,
            'charge' => $request->anotherBill,
            'note' => $request->anotherBillNote
        ];

        if ($dataAnotherBill) {
            $ids = is_array($dataAnotherBill["id"]) ? $dataAnotherBill["id"] : [$dataAnotherBill["id"]];
            $dates = is_array($dataAnotherBill["date"]) ? $dataAnotherBill["date"] : [$dataAnotherBill["date"]];
            $descs = is_array($dataAnotherBill["desc"]) ? $dataAnotherBill["desc"] : [$dataAnotherBill["desc"]];
            $charges = is_array($dataAnotherBill["charge"]) ? $dataAnotherBill["charge"] : [$dataAnotherBill["charge"]];
            $notes = is_array($dataAnotherBill["note"]) ? $dataAnotherBill["note"] : [$dataAnotherBill["note"]];
            $dateCount = count($dates);

            for ($indexDate = 0; $indexDate < $dateCount; $indexDate++) {
                $id = isset($ids[$indexDate]) ? $ids[$indexDate] : null;
                $date = isset($dates[$indexDate]) ? $dates[$indexDate] : null;
                $desc = isset($descs[$indexDate]) ? $descs[$indexDate] : null;
                $charge = isset($charges[$indexDate]) ? $charges[$indexDate] : null;
                $note = isset($notes[$indexDate]) ? $notes[$indexDate] : null;
                $anotherBillValue = $charge ? preg_replace("/[^0-9]/", "", $charge) : null;

                $totalanotherBillOverall += $anotherBillValue;
    
                if ($id) {
                    $checkSeaShipmentAnotherBill = SeaShipmentAnotherBill::where('id_sea_shipment_other_bill', $id)->firstOrFail();

                    // Skip processing if both desc and charge are null or 0
                    if (is_null($desc) && ($anotherBillValue == 0 || is_null($anotherBillValue))) {
                        if ($checkSeaShipmentAnotherBill) {
                            $checkSeaShipmentAnotherBill->delete();
                        }
                        continue;
                    }
    
                    if ($checkSeaShipmentAnotherBill) {
                        $checkSeaShipmentAnotherBill->id_desc = $desc;
                        $checkSeaShipmentAnotherBill->charge = $anotherBillValue;
                        $checkSeaShipmentAnotherBill->note = $note;
                        $checkSeaShipmentAnotherBill->save();
                    }
                    
                } else {

                    if ($desc && ($anotherBillValue != 0 || $anotherBillValue)) {
                        SeaShipmentAnotherBill::create([
                            'id_sea_shipment' => $seaShipment->id_sea_shipment,
                            'date' => $date,
                            'id_desc' => $desc,
                            'charge' => $anotherBillValue,
                            'note' => $note
                        ]);
                    }

                }
            }
        }

        // update data in shipment
        $seaShipment->no_inv = $request->inv_no;
        $seaShipment->term = $request->term;
        $seaShipment->is_weight = $request->is_weight;
        $seaShipment->save();

        if ($request->is_print) {
            $seaShipment->is_printed = true;
            $seaShipment->printcount += 1;
            $seaShipment->printdate = Carbon::now()->addHours(7);
            $seaShipment->save();

            $pdf = PDF::loadView('pdf.generate_invoice', [
                'customer' => $customer,
                'shipper' => $shipper,
                'seaShipment' => $seaShipment,
                'seaShipmentLines' => $seaShipmentLines,
                'seaShipmentBill' => $seaShipmentBill,
                'groupSeaShipmentLines' => $groupSeaShipmentLines,
                'groupedSeaShipmentLinesDate' => $groupedSeaShipmentLinesDate,
                'pricelist' => $pricelist,
                'term' => $request->term,
                'is_weight' => $isWeight,
                'paymentDue' => $paymentDue,
                'banker' => $banker,
                'account_no' => $account,
                'origin' => $origin,
                'imageContent' => $imageContent,
                'invNameGenerate' => $invNameGenerate,
                'titleInv' => $titleInv,
                'companyName' => $companyName,
                'bill_diff' => $bill_diff,
                'inv_type' => $inv_type,
                'dataBill' => $dataBill,
                'dataAnotherBill' => $dataAnotherBill,
                'descsData' => $descsData
            ])->setPaper('folio', 'portrait');

            // after print create data to bill recap
            $checkBillRecap = BillRecap::where('id_sea_shipment', $seaShipment->id_sea_shipment)->first();

            // size
            if (in_array($origin->name, ['SIN-BTH', 'SIN-JKT'])) {
                if ($isWeight) {
                    if (($totalWeightOverall / 1000) > ($totalCbm2Overall != 0 ? $totalCbm2Overall : $totalCbm1Overall)) {
                        $size = ($totalWeightOverall / 1000) . ' T';

                    } else {
                        $size = $totalWeightOverall . ' KG';
                    }

                } else {
                    $size = ($totalCbm2Overall != 0 ? $totalCbm2Overall : $totalCbm1Overall) + $totalCbmDiffOverall . ' M3';
                }

            } else {
                if ($isWeight) {
                    if (($totalWeightOverall / 1000) > ($totalCbm2Overall != 0 ? $totalCbm2Overall : $totalCbm1Overall)) {
                        $size = ($totalWeightOverall / 1000) . ' T';

                    } else {
                        $size = $totalWeightOverall . ' KG';
                    }

                } else {
                    $size = ($totalCbm2Overall != 0 ? $totalCbm2Overall : $totalCbm1Overall) . ' M3';
                }
            }

            $amountOther = $totalBlOverall + $totalPermitOverall + $totalTransportOverall + $totalInsuranceOverall + $totalanotherBillOverall;
            // Calculate with pricelist
            $amountDiff = $totalCbmDiffOverall * $pricelist;

            if ($isWeight) {
                $amountDiff = 0;
            }

            $allTotalAmount = $totalAmountOverall + $amountOther + $amountDiff + $totalAmountUnit;

            if (!$checkBillRecap) {
                BillRecap::create([
                    'id_sea_shipment' => $seaShipment->id_sea_shipment,
                    'inv_no' => $invNameGenerate,
                    'freight_type' => 'SEA FREIGHT',
                    'size' => $size,
                    'unit_price' => $pricelist,
                    'amount' => $allTotalAmount,
                ]);

            } else {
                $checkBillRecap->inv_no = $invNameGenerate;
                $checkBillRecap->size = $size;
                $checkBillRecap->unit_price = $pricelist;
                $checkBillRecap->amount = $allTotalAmount;
                $checkBillRecap->save();
            }
            
            $output = $pdf->output();
            // Simpan PDF invoice ke file sementara
            $tempInvoicePath = storage_path('app/temp_invoice.pdf');
            file_put_contents($tempInvoicePath, $output);

            if ($seaShipment->file_shipment_status) {
                $uploadedFile = storage_path('app/public/' . $seaShipment->file_shipment_status);

                // Inisialisasi mPDF
                $mpdf = new Mpdf();
                
                // Tambahkan halaman dari file PDF yang dihasilkan oleh DomPDF
                $pageCount1 = $mpdf->SetSourceFile($tempInvoicePath);
                for ($pageNo = 1; $pageNo <= $pageCount1; $pageNo++) {
                    $tplId = $mpdf->ImportPage($pageNo);
                    $mpdf->AddPage();
                    $mpdf->UseTemplate($tplId);
                }

                // Tambahkan halaman dari file PDF yang diunggah
                $pageCount2 = $mpdf->SetSourceFile($uploadedFile);
                for ($pageNo = 1; $pageNo <= $pageCount2; $pageNo++) {
                    $tplId = $mpdf->ImportPage($pageNo);
                    $mpdf->AddPage();
                    $mpdf->UseTemplate($tplId);
                }

                // Output merge PDF
                return response()->streamDownload(function () use ($mpdf, $customer, $shipper, $invNumber, $company, $monthRoman, $year) {
                    echo $mpdf->Output('', 'S');
                }, $customer->name . '-' . $shipper->name . '-' . $invNumber . '_' . $company->shorter . '_' . 'INV_' . $monthRoman . '_' . $year . '.pdf');

            } else {
                return $pdf->download($customer->name . '-' . $shipper->name . '-' . $invNumber . '_' . $company->shorter . '_' . 'INV_' . $monthRoman . '_' . $year . '.pdf');
            }
        }

        if ($request->is_update) {
            return redirect()->back();
        }
    }
}
