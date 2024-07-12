<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Customer;
use App\Models\Shipper;
use App\Models\Ship;
use App\Models\Origin;
use App\Models\SeaShipment;
use App\Models\SeaShipmentLine;

class SeaShipmentImport implements WithMultipleSheets
{
    private $logErrors = [];
    private $sheetNames = [];

    public function __construct(array $sheetNames)
    {
        $this->sheetNames = $sheetNames;
    }

    public function getSheetNames()
    {
        return $this->sheetNames;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->sheetNames as $sheetName) {
            $sheets[$sheetName] = new SeaShipmentSheetImport($sheetName, $this->logErrors);
        }

        return $sheets;
    }

    public function getLogErrors()
    {
        return $this->logErrors;
    }
}

class SeaShipmentSheetImport implements ToCollection
{
    private $sheetName;
    private $logErrors;

    public function __construct($sheetName, &$logErrors)
    {
        $this->sheetName = $sheetName;
        $this->logErrors = &$logErrors;
    }

    public function collection(Collection $collection)
    {
        $rowNumber = 0;
        $currentRow = 0;

        try {
            foreach ($collection as $row) {
                $currentRow++;
                if ($rowNumber < 3) {
                    // Header column
                    $headerColumn = $row->toArray();
                    $rowNumber++;
                    continue;
                }

                // Skip row[3] 

                // Shipment
                if ($rowNumber === 3) {
                    // Shipper
                    if ($row[4]) {
                        $checkShipper = Shipper::where('name', 'like', '%' . $row[4] . '%')->first();
                        if (!$checkShipper) {
                            $checkShipper = Shipper::create(['name' => strtoupper($row[4])]);
                        }

                        // IdShipper
                        $IdShipper = $checkShipper->id_shipper;
                    }

                    // Customer
                    if ($row[2]) {
                        $checkCustomer = Customer::where('name', 'like', '%' . $row[2] . '%')->first();
                        if (!$checkCustomer) {
                            $checkCustomer = Customer::create(['name' => strtoupper($row[2]), 'shipper_ids' => $IdShipper]);
                        }

                        // IdCustomer
                        $IdCustomer = $checkCustomer->id_customer;

                        $checkShipperIds = $checkCustomer->shipper_ids;
                        if ($checkShipperIds && strpos($checkShipperIds, $IdShipper) === false) {
                            $checkShipperIds .= ",$IdShipper";

                            // Update shipper_ids in customer
                            Customer::where('id_customer', $IdCustomer)->update(['shipper_ids' => $checkShipperIds]);
                        }
                    }

                    // Ship
                    $IdShip = null;
                    if ($row[5]) {
                        $checkShip = Ship::where('name', 'like', '%' . $row[5] . '%')->first();
                        if (!$checkShip) {
                            $checkShip = Ship::create(['name' => strtoupper($row[5])]);
                        }

                        // IdShip
                        $IdShip = $checkShip->id_ship;
                    }

                    // Origin
                    $IdOrigin = null;
                    if ($row[6]) {
                        $checkOrigin = Origin::where('name', 'like', '%' . $row[6] . '%')->first();
                        if (!$checkOrigin) {
                            $checkOrigin = Origin::create(['name' => strtoupper($row[6])]);
                        }

                        // IdOrigin
                        $IdOrigin = $checkOrigin->id_origin;
                    }

                    $valueKey = $row[0] . \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1])->format('Y-m-d') . $IdShipper . $IdCustomer . $IdOrigin . 
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[7])->format('Y-m-d');

                    $dataSeaShipment = [
                        'no_aju' => strtoupper($row[0]),
                        'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1]),
                        'id_origin' => $IdOrigin,
                        'etd' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[7]),
                        'eta' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[8]),
                        'id_shipper' => $IdShipper,
                        'id_customer' => $IdCustomer,
                        'id_ship' => $IdShip,
                        'value_key' => $valueKey
                    ];

                    // Create shipment sea freight
                    $seaShipment = SeaShipment::create($dataSeaShipment);
                }

                // Shipment line
                if ($rowNumber > 8 && !empty($row[0])) {
                    $tot_cbm1 = null;
                    $tot_cbm2 = null;

                    if ($row[4]) {
                        $tot_cbm1 = number_format((($row[9] * $row[10] * $row[11]) / 1000000) * $row[4], 3);
                    }

                    if ($row[6]) {
                        $tot_cbm2 = number_format((($row[9] * $row[10] * $row[11]) / 1000000) * $row[6], 3);
                    }

                    $dataShipmentLine = [
                        'id_sea_shipment' => $seaShipment->id_sea_shipment,
                        'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[0]),
                        'code' => strtoupper($row[1]),
                        'marking' => strtoupper($row[2]),
                        'qty_pkgs' => $row[4],
                        'unit_qty_pkgs' => $row[5],
                        'qty_loose' => $row[6],
                        'unit_qty_loose' => $row[7],
                        'weight' => $row[8],
                        'dimension_p' => $row[9],
                        'dimension_l' => $row[10],
                        'dimension_t' => $row[11],
                        'tot_cbm_1' => $tot_cbm1,
                        'tot_cbm_2' => $tot_cbm2,
                        'lts' => strtoupper($row[14]),
                        'desc' => strtoupper($row[15]),
                        // 'state' => $row[15],
                    ];

                    // Create shipment sea freight line
                    SeaShipmentLine::create($dataShipmentLine);
                }

                // Next row
                $rowNumber++;
            }
        } catch (\Exception $e) {
            $this->logErrors[] = "Error in sheet '{$this->sheetName}' at row {$currentRow}: " . $e->getMessage();
        }
    }
}
