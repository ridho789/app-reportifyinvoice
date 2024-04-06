<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Insurance;
use App\Models\SeaShipmentLine;

class InsuranceImport implements ToCollection
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
            
            if ($row[0]) {
                $expanded_marking = [];
                
                if (strpos($row[0], '-') !== false) {
                    list($prefix, $endRange) = explode('-', $row[0]);
                    list($mainPrefix, $startRange) = explode('#', $prefix);
                    $main = $mainPrefix;
                    $startNum = intval($startRange);

                    for ($num = $startNum; $num <= $endRange; $num++) {
                        $expanded_marking[] = "$main#$num";
                    }

                } else {
                    $expanded_marking[] = $row[0];
                }

                foreach ($expanded_marking as $marking) {
                    $checkSeaShipmentLine = SeaShipmentLine::where('marking', $marking)->first();

                    if ($checkSeaShipmentLine) {
                        Insurance::insert([
                            'id_sea_shipment_line' => $checkSeaShipmentLine->id_sea_shipment_line,
                            'idr' => $row[1],
                        ]);
                    }
                }
            }

            // Next row
            $rowNumber++;
        }
    }

    public function getLogErrors()
    {
        return $this->logErrors;
    }
}
