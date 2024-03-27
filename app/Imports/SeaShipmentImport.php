<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Customer;
use App\Models\Shipper;
use App\Models\Ship;

class SeaShipmentImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $rowNumber = 0;

        foreach ($collection as $row){
            if ($rowNumber < 3) {
                $headerColumn = $row->toArray();
                $rowNumber++;
                continue;
            }

            if ($rowNumber === 3) {
                // Shipper
                if ($row[3]) {
                    $checkShipper = Shipper::where('name', 'like', '%' . $row[3] . '%')->first();
                    if (empty($checkShipper)) {
                        $checkShipper = Shipper::create(['name'=> $row[3]]);
                    }
                }
    
                // Customer
                if ($row[2]) {
                    $checkCustomer = Customer::where('name', 'like', '%' . $row[2] . '%')->first();
                    if (empty($checkCustomer)) {
                        $checkCustomer = Customer::firstOrCreate(['name'=> $row[2], 'shipper_ids' => $checkShipper->id]);
                    }
                    
                    $checkShipperIds = $checkCustomer->shipper_ids;
                    if ($checkShipperIds && strpos($checkShipperIds, $checkShipper->id) === false) {
                        $checkShipperIds .= ",$checkShipper->id";
                        // Update shipper_ids in customer
                        Customer::where('id_customer', $checkCustomer->id_customer)->update(['shipper_ids' => $checkShipperIds]);
                    }
                }
                
                // Create shipment sea freight
                // $dataSeaShipment = [
                //     'no_aju' => $row[0],
                //     'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1]),
                //     'origin' => $row[5],
                //     'etd' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6]),
                //     'eta' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[7]),
                // ];
            }
        }
    }
}
