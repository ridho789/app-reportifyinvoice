<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Cas;
use App\Models\Shipper;
use App\Models\Customer;

class CasImport implements ToCollection
{
    /**
    * @param Collection $collection
    */

    private $logErrors = [];

    public function collection(Collection $collection)
    {
        $rowNumber = 0;
        $currentRow = 0;
        foreach ($collection as $row) {
            $currentRow++;
            if ($rowNumber < 1) {
                // Header column
                $headerColumn = $row->toArray();
                $rowNumber++;
                continue;
            }

            // Shipper
            $IdShipper = null;
            if ($row[1]) {
                $checkShipper = Shipper::where('name', 'like', '%' . $row[1] . '%')->first();
                if (empty($checkShipper)) {
                    $checkShipper = Shipper::create(['name' => strtoupper($row[1])]);
                }
                
                // IdShipper
                $IdShipper = $checkShipper->id_shipper;
            }

            // Customer
            $IdCustomer = null;
            if ($row[0]) {
                $checkCustomer = Customer::where('name', 'like', '%' . $row[0] . '%')->first();
                if (empty($checkCustomer)) {
                    $checkCustomer = Customer::create(['name' => strtoupper($row[0]), 'shipper_ids' => $IdShipper]);
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

            $startPeriod = null;
            $endPeriod = null;
    
            if ($row[5]) {
                $startPeriod = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5]);
            }
    
            if ($row[6]) {
                $endPeriod = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6]);
            }
            
            $dataCas = [
                'id_customer' => $IdCustomer,
                'id_shipper' => $IdShipper,
                'lts' => strtoupper($row[2]),
                'charge' => $row[3],
                'desc' => $row[4],
                'start_period' => $startPeriod,
                'end_period' => $endPeriod
            ];
    
            $exitingCas = Cas::where('id_customer', $IdCustomer)->where('id_shipper', $IdShipper)->where('lts', $row[2])->where('charge', $row[3])->where('desc', $row[4])
            ->where('start_period', $startPeriod)->where('end_period', $endPeriod)->first();
            
            if ($exitingCas) {
                $errorMessage = 'Error importing data: The data in the row ' . $currentRow . ' already exists in the system ';
                $this->logErrors[] = $errorMessage;
    
            } else {
                Cas::create($dataCas);
            }
        }
    }

    public function getLogErrors()
    {
        return $this->logErrors;
    }
}
