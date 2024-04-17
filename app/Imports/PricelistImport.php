<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Customer;
use App\Models\Shipper;
use App\Models\Pricelist;

class PricelistImport implements ToCollection
{
    /**
    * @param Collection $collection
    */

    private $logErrors = [];

    public function collection(Collection $collection)
    {
        $rowNumber = 0;
        foreach ($collection as $row) {
            if ($rowNumber < 1) {
                // Header shipment column
                $headerColumn = $row->toArray();
                $rowNumber++;
                continue;
            }

            // Shipper
            if ($row[1]) {
                $checkShipper = Shipper::where('name', 'like', '%' . $row[1] . '%')->first();
                if (empty($checkShipper)) {
                    $checkShipper = Shipper::create(['name' => strtoupper($row[1])]);
                    $IdShipper = $checkShipper->id;
                } else {
                    $IdShipper = $checkShipper->id_shipper;
                }
            }

            // Customer
            if ($row[0]) {
                $checkCustomer = Customer::where('name', 'like', '%' . $row[0] . '%')->first();
                if (empty($checkCustomer)) {
                    $checkCustomer = Customer::create(['name' => strtoupper($row[0]), 'shipper_ids' => $IdShipper]);
                    $IdCustomer = $checkCustomer->id;
                } else {
                    $IdCustomer = $checkCustomer->id_customer;
                }

                $checkShipperIds = $checkCustomer->shipper_ids;
                if ($checkShipperIds && strpos($checkShipperIds, $IdShipper) === false) {
                    $checkShipperIds .= ",$IdShipper";
                    // Update shipper_ids in customer
                    Customer::where('id_customer', $IdCustomer)->update(['shipper_ids' => $checkShipperIds]);
                }
            }

            $dataPricelist = [
                'id_customer' => $IdCustomer,
                'id_shipper' => $IdShipper,
                'origin' => strtoupper($row[2]),
                'price' => $row[3],
            ];

            $exitingPricelist = Pricelist::where('id_customer', $IdCustomer)->where('id_shipper', $IdShipper)->where('origin', strtolower($row[2]))->where('price', $row[3])->first();
            if ($exitingPricelist) {
                continue;

            } else {
                // Create pricelist
                Pricelist::create($dataPricelist);
            }
        }
    }

    public function getLogErrors()
    {
        return $this->logErrors;
    }
}
