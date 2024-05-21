<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Shipper;
use App\Models\Ship;
use App\Models\SeaShipment;
use App\Models\SeaShipmentLine;
use App\Imports\SeaShipmentImport;
use App\Models\Cas;
use App\Models\Insurance;
use App\Models\Pricelist;
use App\Models\SeaShipmentBill;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PDF;
use DateTime;
use DateInterval;
use Carbon\Carbon;

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
        return view('shipment.sea_shipment.form_sea_shipment', compact('seaShipment', 'seaShipmentLines', 'customers', 'shippers', 'ships', 'groupSeaShipmentLines'));
    }

    public function listSeaShipment() {
        $allSeaShipment = SeaShipment::orderBy('date')->get();
        $customer = Customer::pluck('name', 'id_customer');
        $shipper = Shipper::pluck('name', 'id_shipper');
        $ship = Ship::pluck('name', 'id_ship');
        return view('shipment.sea_shipment.list_sea_shipments', compact('allSeaShipment','customer', 'shipper', 'ship'));
    }

    public function editSeaShipment($id) {
        // Encrypt-Decrypt ID
        $id = Crypt::decrypt($id);

        $seaShipment = SeaShipment::where('id_sea_shipment', $id)->first();
        $seaShipmentLines = SeaShipmentLine::where('id_sea_shipment', $seaShipment->id_sea_shipment)->orderBy('date')->orderBy('marking')->get();
        $seaShipmentBill = SeaShipmentBill::where('id_sea_shipment', $seaShipment->id_sea_shipment)->orderBy('date')->get();
        $checkCbmDiff = false;

        $groupSeaShipmentLines = $seaShipmentLines->groupBy(function ($item) {
            return $item->date;
        })->map(function ($group) use (&$checkCbmDiff) {
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
        
            $totals['cbm_difference'] = $totals['total_cbm1'] - $totals['total_cbm2'];

            if ($totals['cbm_difference'] != 0) {
                $checkCbmDiff = true;
            }
        
            return $totals;
        });

        $customers = Customer::orderBy('name')->get();
        $customer = Customer::where('id_customer', $seaShipment->id_customer)->first();
        $shippers = Shipper::orderBy('name')->get();
        $ships = Ship::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        return view('shipment.sea_shipment.form_sea_shipment', compact('seaShipment', 'seaShipmentLines', 'customers', 'customer', 'shippers', 
        'ships', 'companies', 'groupSeaShipmentLines', 'checkCbmDiff', 'seaShipmentBill'));
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
            $SeaShipment->origin = strtoupper($request->origin);
            $SeaShipment->etd = $request->etd;
            $SeaShipment->eta = $request->eta;

            $SeaShipment->save();
        }

        foreach ($request->id_sea_shipment_line as $index => $idSeaShipmentLine) {
            $seaShipmentLine = SeaShipmentLine::find($idSeaShipmentLine);
            
            if ($seaShipmentLine) {
                $seaShipmentLine->update([
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
                    'desc' => strtoupper($request->desc[$index]),
                    'state' => $request->state[$index],
                ]);
            }
        }

        return redirect()->back();
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
        $seaShipment = SeaShipment::where('id_sea_shipment', $id_sea_shipment)->first();
        $seaShipmentLines = SeaShipmentLine::where('id_sea_shipment', $seaShipment->id_sea_shipment)->orderBy('date')->orderBy('marking')->get();
        $seaShipmentBill = SeaShipmentBill::where('id_sea_shipment', $seaShipment->id_sea_shipment)->orderBy('date')->get();

        $groupSeaShipmentLines = $seaShipmentLines->groupBy(function ($item) {
            return $item->date . '-' . $item->lts;
        })->map(function ($group) use ($seaShipment) {
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
                })->sum('tot_cbm_2')
            ];
        
            // set cas
            $cas = null;
            $lts = $group->first()->lts;

            $defaultCas = Cas::where('id_customer', null)->where('id_shipper', null)->where('lts', $lts)->where('start_period', null)
                ->where('end_period', null)->first();
        
            $shipperCas = Cas::where('id_customer', null)->where('id_shipper', $seaShipment->id_shipper)->where('lts', $lts)->where('start_period', null)
                ->where('end_period', null)->first();
        
            $customerCas = Cas::where('id_customer', $seaShipment->id_customer)->where('id_shipper', null)->where('lts', $lts)->where('start_period', null)
                ->where('end_period', null)->first();

            $customerShipperCas = Cas::where('id_customer', $seaShipment->id_customer)->where('id_shipper', $seaShipment->id_shipper)->where('lts', $lts)->where('start_period', null)
                ->where('end_period', null)->first();

            $periodCas = Cas::where('id_customer', $seaShipment->id_customer)->where('id_shipper', $seaShipment->id_shipper)->where('lts', $lts)
                ->where('start_period', '>=', $seaShipment->date)->where('end_period', null)->first();

            $allPeriodCas = Cas::where('id_customer', $seaShipment->id_customer)->where('id_shipper', $seaShipment->id_shipper)->where('lts', $lts)
                ->where('start_period', '<=', $seaShipment->date)->where('end_period', '>=', $seaShipment->date)->first();

            if ($defaultCas) {
                $cas = $defaultCas->charge;
            } 
            
            if ($shipperCas) {
                $cas = $shipperCas->charge;
            } 
            
            if ($customerCas) {
                $cas = $customerCas->charge;
            }

            if ($customerShipperCas) {
                $cas = $customerShipperCas->charge;
            }

            if ($periodCas) {
                $cas = $periodCas->charge;
            }

            if ($allPeriodCas) {
                $cas = $allPeriodCas->charge;
            }
        
            $totals['cas'] = $cas;
        
            // cbm difference
            $totals['cbm_difference'] = $totals['total_cbm1'] - $totals['total_cbm2'];
        
            $markings = $group->pluck('marking')->unique()->toArray();
            $totals['markings'] = $markings;
        
            return $totals;
        });

        function romanNumerals($number) {
            $roman = '';
            $romanDigit = array('','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII');
            if($number > 0 && $number <= 12) {
                $roman = $romanDigit[$number];
            }
            return $roman;
        }

        $invNumber = $request->inv_no;
        list($numberPart, $suffixPart) = explode('-', $invNumber);
        $formattedNumberPart = sprintf("%03d", $numberPart);
        $invNumber = $formattedNumberPart . '-' . $suffixPart;

        $month = ltrim(date("m", strtotime($seaShipment->date)), '0');
        $monthRoman = romanNumerals($month);
        $year = date("Y", strtotime($seaShipment->date));
        
        $customer = Customer::where('id_customer', $seaShipment->id_customer)->first();
        $shipper = Shipper::where('id_shipper', $seaShipment->id_shipper)->first();
        $company = Company::where('id_company', $request->id_company)->first();

        // update company if changed in customer
        if ($customer->id_company != $request->id_company) {
            Customer::where('id_customer', $seaShipment->id_customer)->update([
                'id_company' => $request->id_company
            ]);
        }

        // update bill diff in customer
        $bill_diff = null;
        if ($request->bill_diff) {

            $numericBillDiff = preg_replace("/[^0-9]/", "", explode(",", $request->bill_diff)[0]);
            if ($request->bill_diff[0] === '-') {
                $numericBillDiff *= -1;
            }

            $bill_diff = $numericBillDiff;

            Customer::where('id_customer', $seaShipment->id_customer)->update([
                'bill_diff' => $numericBillDiff
            ]);
        }

        // update invoice type
        $inv_type = null;
        if ($request->inv_type) {
            $inv_type = $request->inv_type;

            Customer::where('id_customer', $seaShipment->id_customer)->update([
                'inv_type' => $inv_type
            ]);
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

        // set pricelist
        $pricelist = null;

        $defaultPricelist = Pricelist::where('id_customer', null)->where('id_shipper', null)->where('origin', $seaShipment->origin)
        ->where('start_period', null)->where('end_period', null)->first();

        $shipperPricelist = Pricelist::where('id_customer', null)->where('id_shipper', $seaShipment->id_shipper)->where('origin', $seaShipment->origin)
        ->where('start_period', null)->where('end_period', null)->first();

        $customerPricelist = Pricelist::where('id_customer', $seaShipment->id_customer)->where('id_shipper', null)->where('origin', $seaShipment->origin)
        ->where('start_period', null)->where('end_period', null)->first();

        $customerShipperPricelist = Pricelist::where('id_customer', $seaShipment->id_customer)->where('id_shipper', $seaShipment->id_shipper)->where('origin', $seaShipment->origin)
        ->where('start_period', null)->where('end_period', null)->first();

        $periodPricelist = Pricelist::where('id_customer', $seaShipment->id_customer)->where('id_shipper', $seaShipment->id_shipper)->where('origin', $seaShipment->origin)
        ->where('start_period', '>=', $seaShipment->date)->where('end_period', null)->first();

        $allPeriodPricelist = Pricelist::where('id_customer', $seaShipment->id_customer)->where('id_shipper', $seaShipment->id_shipper)->where('origin', $seaShipment->origin)
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

        // data bill
        $dataBill = [
            'dateBL' => $request->dateBL,
            'codeShipment' => $request->codeShipment,
            'transport' => $request->transport,
            'bl' => $request->bl,
            'permit' => $request->permit,
            'insurance' => $request->insurance
        ];

        $resultBIll = [];

        foreach ($dataBill["dateBL"] as $index => $date) {
            $resultBIll[] = [
                "dateBL" => $dataBill["dateBL"][$index],
                "codeShipment" => $dataBill["codeShipment"][$index],
                "transport" => $dataBill["transport"][$index],
                "bl" => $dataBill["bl"][$index],
                "permit" => $dataBill["permit"][$index],
                "insurance" => $dataBill["insurance"][$index]
            ];

            $checkSeaShipmentBill = SeaShipmentBill::where('id_sea_shipment', $seaShipment->id_sea_shipment)->where('date', $date)->first();
            if ($checkSeaShipmentBill) {
                $checkSeaShipmentBill->code = $dataBill["codeShipment"][$index];
                $checkSeaShipmentBill->transport = preg_replace("/[^0-9]/", "", explode(",", $dataBill["transport"][$index])[0]);
                $checkSeaShipmentBill->bl = preg_replace("/[^0-9]/", "", explode(",", $dataBill["bl"][$index])[0]);
                $checkSeaShipmentBill->permit = preg_replace("/[^0-9]/", "", explode(",", $dataBill["permit"][$index])[0]);
                $checkSeaShipmentBill->insurance = preg_replace("/[^0-9]/", "", explode(",", $dataBill["insurance"][$index])[0]);
                $checkSeaShipmentBill->save();

            } else {
                SeaShipmentBill::create([
                    'id_sea_shipment' => $seaShipment->id_sea_shipment,
                    'date' => $date,
                    'code' => $dataBill["codeShipment"][$index],
                    'transport' => preg_replace("/[^0-9]/", "", explode(",", $dataBill["transport"][$index])[0]),
                    'bl' => preg_replace("/[^0-9]/", "", explode(",", $dataBill["bl"][$index])[0]),
                    'permit' => preg_replace("/[^0-9]/", "", explode(",", $dataBill["permit"][$index])[0]),
                    'insurance' => preg_replace("/[^0-9]/", "", explode(",", $dataBill["insurance"][$index])[0])
                ]);
            }
        }

        // update data in shipment
        $seaShipment->term = $request->term;
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
            'pricelist' => $pricelist,
            'term' => $request->term,
            'paymentDue' => $paymentDue,
            'banker' => $request->banker,
            'account_no' => $request->account_no,
            'imageContent' => $imageContent,
            'invNameGenerate' => $invNameGenerate,
            'titleInv' => $titleInv,
            'companyName' => $companyName,
            'bill_diff' => $bill_diff,
            'inv_type' => $inv_type,
            'dataBill' => $dataBill
        ])->setPaper('folio', 'potrait');

        return $pdf->download($customer->name . '-' . $shipper->name . '-' . $invNumber . '_' . $company->shorter . '_' . 'INV_' . $monthRoman . '_' . $year . '.pdf');
    }
}
