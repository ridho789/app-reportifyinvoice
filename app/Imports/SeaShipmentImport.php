<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Customer;
use App\Models\Shipper;
use App\Models\Ship;
use App\Models\SeaShipment;
use App\Models\SeaShipmentLine;

class SeaShipmentImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Var row
        $rowNumber = 0;

        foreach ($collection as $row){
            if ($rowNumber < 3) {
                // Header shipment column
                $headerColumn = $row->toArray();
                $rowNumber++;
                continue;
            }

            // Shipment
            if ($rowNumber === 3) {
                // Shipper
                if ($row[3]) {
                    $checkShipper = Shipper::where('name', 'like', '%' . $row[3] . '%')->first();
                    if (empty($checkShipper)) {
                        $checkShipper = Shipper::create(['name'=> $row[3]]);
                        $IdShipper = $checkShipper->id;

                    } else {
                        $IdShipper = $checkShipper->id_shipper;
                    }
                }
    
                // Customer
                if ($row[2]) {
                    $checkCustomer = Customer::where('name', 'like', '%' . $row[2] . '%')->first();
                    if (empty($checkCustomer)) {
                        $checkCustomer = Customer::create(['name'=> $row[2], 'shipper_ids' => $IdShipper]);
                    }
                    
                    $checkShipperIds = $checkCustomer->shipper_ids;
                    if ($checkShipperIds && strpos($checkShipperIds, $IdShipper) === false) {
                        $checkShipperIds .= ",$IdShipper";
                        // Update shipper_ids in customer
                        Customer::where('id_customer', $checkCustomer->id_customer)->update(['shipper_ids' => $checkShipperIds]);
                    }
                }

                // Ship
                if ($row[4]) {
                    $checkShip = Ship::where('name', 'like', '%' . $row[4] . '%')->first();
                    if (empty($checkShip)) {
                        $checkShip = Ship::create(['name'=> $row[4]]);
                        $IdShip = $checkShip->id;

                    } else {
                        $IdShip = $checkShip->id_ship;
                    }
                }
                
                $dataSeaShipment = [
                    'no_aju' => $row[0],
                    'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1]),
                    'origin' => $row[5],
                    'etd' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6]),
                    'eta' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[7]),
                    'id_shipper' => $IdShipper,
                    'id_customer' => $checkCustomer->id_customer,
                    'id_ship' => $IdShip,
                ];
                
                // Create shipment sea freight
                $seaShipment = SeaShipment::create($dataSeaShipment);
            }

            // Shipment line
            if ($rowNumber > 8 && !empty($row[0])) {
                $tot_cbm = ($row[8] * $row[9] * $row[10]) / 1000;

                $dataShipmentLine = [
                    'id_sea_shipment' => $seaShipment->id,
                    'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[0]),
                    'code' => $row[1],
                    'marking' => $row[2],
                    'qty_pkgs' => $row[3],
                    'unit_qty_pkgs' => $row[4],
                    'qty_loose' => $row[5],
                    'unit_qty_loose' => $row[6],
                    'weight' => $row[7],
                    'dimension_p' => $row[8],
                    'dimension_l' => $row[9],
                    'dimension_t' => $row[10],
                    'tot_cbm_1' => $tot_cbm,
                    'tot_cbm_2' => $row[12],
                    'lts' => $row[13],
                    'desc' => $row[14],
                    'state' => $row[15],
                ];

                // Create shipment sea freight line
                SeaShipmentLine::create($dataShipmentLine);
            }

            // Next row
            $rowNumber++;
        }
    }
}
