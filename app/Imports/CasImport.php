<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Cas;

class CasImport implements ToCollection
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
                // Header column
                $headerColumn = $row->toArray();
                $rowNumber++;
                continue;
            }
        }

        $dataCas = [
            'lts' => strtoupper($row[0]),
            'charge' => $row[1],
            'desc' => $row[2],
        ];

        $exitingCas = Cas::where('lts', $row[0])->first();
        
        if (!$exitingCas) {
            Cas::create($dataCas);
        }
    }

    public function getLogErrors()
    {
        return $this->logErrors;
    }
}
