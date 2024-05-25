<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ $titleInv }}</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12.5px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            th {
                font-weight: bold;
                border: 1px solid #000;
            }
            td {
                padding: 5px;
            }

            .space_content {
                padding-left: 30px;
                font-weight: bold;
            }
            .space_content_main {
                padding-left: 75px;
                font-weight: bold;
            }
            .space_content2 {
                padding-left: 20px;
            }

            /* font */
            .bold {
                font-weight: bold;
            }
            .text_center {
                text-align: center;
            }
            .text_uppercase {
                text-transform: uppercase;
            }

            /* border */
            .border_left_right {
                border-left: 1px solid #000;
                border-right: 1px solid #000;
            }
            .no_top_border {
                border-left: 1px solid #000;
                border-right: 1px solid #000;
                border-bottom: 1px solid #000;
            }
        </style>
    </head>
    <body>
        <div style="margin-top:-40px;">
            <img src="data:image/jpeg;base64,{{ base64_encode($imageContent) }}" style="width: 650px; margin-left:40px;">
        </div>

        <table style="border: 1px solid #000;">
            <tr style="border: 1px solid #000;">
                <td colspan="6" style="text-align: center; padding-top: 0; padding-bottom: 0; font-weight:bold; font-size:22.5px;">INVOICE</td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td class="space_content" colspan="2">To :</td>
                <td style="padding-left: 70px;" class="bold" colspan="2">Inv. No.</td>
                <td class="bold" colspan="2">: <span class="space_content2">{{ $invNameGenerate }}</span></td>
            </tr>
            <tr>
                <td class="space_content" colspan="2"></td>
                <td style="padding-left: 70px;" class="bold" colspan="2">Date</td>
                <td class="bold" colspan="2">: <span class="space_content2">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $seaShipment->etd)->format('d-M-y') }}</span></td>
            </tr>
            <tr>
                <td class="space_content_main" colspan="2">{{ $customer->name }}</td>
                <td style="padding-left: 70px;" class="bold" colspan="2">Term</td>
                <td class="bold" colspan="2">: <span class="space_content2">{{ $term }} Days</span></td>
            </tr>
            <tr>
                <td class="space_content_main" colspan="2">{{ $shipper->name }}</td>
                <td style="padding-left: 70px;" class="bold" colspan="2">Payment Due</td>
                <td class="bold" colspan="2">: <span class="space_content2">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $paymentDue)->format('d-M-y') }}</span></td>
            </tr>
            <tr>
                <td class="space_content" colspan="2"></td>
                <td style="padding-left: 70px;" class="bold" colspan="2">Freight Type</td>
                <td class="bold" colspan="2">: <span class="space_content2">SEA FREIGHT</span></td>
            </tr>
            <tr>
                <td class="space_content" colspan="2"></td>
                <td style="padding-left: 70px;" class="bold" colspan="2">Banker</td>
                <td class="bold" colspan="2">: <span class="space_content2">{{ $banker }}</span></td>
            </tr>
            <tr>
                <td class="space_content" colspan="2"></td>
                <td style="padding-left: 70px;" class="bold" colspan="2">Account No.</td>
                <td class="bold" colspan="2">: <span class="space_content2">{{ $account_no }}</span></td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th colspan="2">Quantity</th>
                <th>Unit Price</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td class="border_left_right"></td>
                <td class="border_left_right text_center">Biaya Pengiriman {{ $seaShipment->origin }}</td>
                <td class="border_left_right"></td>
                <td class="border_left_right"></td>
                <td class="border_left_right"></td>
                <td class="border_left_right"></td>
            </tr>

            @php
                $amount = 0;
                $totalQty = 0;
                $totalAmount = 0;
                $totalCbm = 0;
                $checkLoopDate = null;
                $totalRow = count($groupSeaShipmentLines);
                $entryRow = 0;

                // Bill
                if ($dataBill) {
                    $resultBill = [];
                    foreach ($dataBill["dateBL"] as $index => $date) {
                        $resultBill[] = [
                            "dateBL" => $dataBill["dateBL"][$index] ?? null,
                            "codeShipment" => $dataBill["codeShipment"][$index] ?? null,
                            "transport" => isset($dataBill["transport"][$index]) ? preg_replace("/[^0-9]/", "", explode(",", $dataBill["transport"][$index])[0]) : null,
                            "bl" => isset($dataBill["bl"][$index]) ? preg_replace("/[^0-9]/", "", explode(",", $dataBill["bl"][$index])[0]) : null,
                            "permit" => isset($dataBill["permit"][$index]) ? preg_replace("/[^0-9]/", "", explode(",", $dataBill["permit"][$index])[0]) : null,
                            "insurance" => isset($dataBill["insurance"][$index]) ? preg_replace("/[^0-9]/", "", explode(",", $dataBill["insurance"][$index])[0]) : null
                        ];
                    }
                }
                $billIndex = 0;
            @endphp
            @if (in_array($seaShipment->origin, ['SIN-BTH', 'SIN-JKT']))
                @if ($inv_type == 'separate')
                    @php
                    $totalRow = count($seaShipmentLines);
                    @endphp
                    @foreach($seaShipmentLines as $index => $line)
                        @php
                            $qty = $line->qty_loose;
                            $cbm = $line->tot_cbm_2;
                            $unit_price = $pricelist;
                            $cas_value = null;
                            $entryRow++;

                            foreach ($groupSeaShipmentLines as $groupDate => $totals) {
                                if (in_array($line->marking, $totals['markings'])) {
                                    $cas_value = $totals['cas'];
                                    break;
                                }
                            }

                            if ($cas_value) {
                                $unit_price = $pricelist + intval($cas_value);
                            }

                            // Data tagihan lainnya
                            $bl = null;
                            $permit = null;
                            $transport = null;
                            $insurance = null;

                            if ($checkLoopDate != $line->date) {
                                if (isset($resultBill[$billIndex])) {
                                    $code = $resultBill[$billIndex]['codeShipment'];
                                    $bl = $resultBill[$billIndex]['bl'];
                                    $permit = $resultBill[$billIndex]['permit'];
                                    $transport = $resultBill[$billIndex]['transport'];
                                    $insurance = $resultBill[$billIndex]['insurance'];
                                }
                                $billIndex++;
                            }

                            $totalRow += ($bl ? 1 : 0) + ($permit ? 1 : 0) + ($transport ? 1 : 0) + ($insurance ? 1 : 0);
                            $entryRow += ($bl ? 1 : 0) + ($permit ? 1 : 0) + ($transport ? 1 : 0) + ($insurance ? 1 : 0);

                            $checkLoopDate = $line->date;

                            $amount = $cbm * $unit_price;
                            $totalQty += $qty;
                            $totalCbm += $cbm;
                            $totalAmount += $amount;
                        @endphp
                        <tr>
                            <td width="5%" class="border_left_right"></td>
                            <td width="30%" class="border_left_right text_center">
                                @if (!$cas_value)
                                    {{ $code ? $code : '' }} {{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('M-y') }}
                                @else
                                    {{ $code ? $code : '' }} {{ $line->marking }} = {{ $line->lts }} {{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('M-y') }}
                                @endif
                            </td>
                            <td width="12.5%" class="border_left_right text_center text_uppercase">{{ $qty }} PKG</td>
                            <td width="10%" class="border_left_right text_center text_uppercase">{{ $cbm }} M3</td>
                            <td width="15%" class="border_left_right text_center">
                                {{ 'Rp ' . number_format($unit_price ?? 0, 0, ',', '.') }}
                                @if ($cas_value)
                                    <br> <span style="font-size: smaller;">( +{{ number_format($cas_value ?? 0, 0, ',', '.') }} )</span>
                                @endif
                            </td>
                            <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($amount ?? 0, 0, ',', '.') }}</td>
                        </tr>
                            
                        <!-- bl -->
                        @if ($bl)
                            <tr>
                                <td width="5%" class="border_left_right"></td>
                                <td width="30%" class="border_left_right text_center">BL</td>
                                <td width="12.5%" class="border_left_right text_center text_uppercase"></td>
                                <td width="10%" class="border_left_right text_center text_uppercase"></td>
                                <td width="15%" class="border_left_right text_center"></td>
                                <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($bl ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        
                        <!-- permit -->
                        @if ($permit)
                            <tr>
                                <td width="5%" class="border_left_right"></td>
                                <td width="30%" class="border_left_right text_center">PERMIT</td>
                                <td width="12.5%" class="border_left_right text_center text_uppercase"></td>
                                <td width="10%" class="border_left_right text_center text_uppercase"></td>
                                <td width="15%" class="border_left_right text_center"></td>
                                <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($permit ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @endif

                        <!-- transport -->
                        @if ($transport)
                            <tr>
                                <td width="5%" class="border_left_right"></td>
                                <td width="30%" class="border_left_right text_center">TRANSPORT</td>
                                <td width="12.5%" class="border_left_right text_center text_uppercase"></td>
                                <td width="10%" class="border_left_right text_center text_uppercase"></td>
                                <td width="15%" class="border_left_right text_center"></td>
                                <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($transport ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @endif

                        <!-- insurance -->
                        @if ($insurance)
                            <tr>
                                <td width="5%" class="border_left_right"></td>
                                <td width="30%" class="border_left_right text_center">INSURANCE</td>
                                <td width="12.5%" class="border_left_right text_center text_uppercase"></td>
                                <td width="10%" class="border_left_right text_center text_uppercase"></td>
                                <td width="15%" class="border_left_right text_center"></td>
                                <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($insurance ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @endif

                        @php
                            if ($entryRow > 15) {
                                $entryRow = 0;
                                echo '</table>';
                                echo '<div style="page-break-after: always;"></div>';
                                echo '<table style="border: 1px solid #000;">';
                                echo '<tr>
                                        <th>Item</th>
                                        <th>Description</th>
                                        <th colspan="2">Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Amount</th>
                                    </tr>';
                            }
                        @endphp
                    @endforeach

                @else
                    @foreach($groupSeaShipmentLines as $groupDate => $totals)
                        @php
                            $date = substr($groupDate, 0, 10);
                            $lts = substr($groupDate, strrpos($groupDate, '-') + 1);

                            $qty = $totals['total_qty_loose'];
                            $unit_price = $pricelist;
                            $unitPriceCbmDiff = 0;
                            $amountCbmDiff = 0;
                            $entryRow++;
                            
                            // Jika ada cas
                            if ($totals['cas']) {
                                $unit_price = $pricelist + intval($totals['cas']);
                            }

                            // Jika ada selisih
                            if ($totals['cbm_difference']) {
                                $unitPriceCbmDiff = $bill_diff;
                                $amountCbmDiff = $totals['cbm_difference'] * $unitPriceCbmDiff;
                                $entryRow++;
                            }

                            // Data tagihan lainnya
                            $bl = null;
                            $permit = null;
                            $transport = null;
                            $insurance = null;

                            if ($checkLoopDate != $date) {
                                if (isset($resultBill[$billIndex])) {
                                    $code = $resultBill[$billIndex]['codeShipment'];
                                    $bl = $resultBill[$billIndex]['bl'];
                                    $permit = $resultBill[$billIndex]['permit'];
                                    $transport = $resultBill[$billIndex]['transport'];
                                    $insurance = $resultBill[$billIndex]['insurance'];
                                }
                                $billIndex++;
                            }

                            $totalRow += ($bl ? 1 : 0) + ($permit ? 1 : 0) + ($transport ? 1 : 0) + ($insurance ? 1 : 0);
                            $entryRow += ($bl ? 1 : 0) + ($permit ? 1 : 0) + ($transport ? 1 : 0) + ($insurance ? 1 : 0);

                            $checkLoopDate = $date;
                            
                            $amount = $totals['total_cbm2'] * $unit_price;
                            $totalQty += $qty;
                            $totalAmount += $amount + $amountCbmDiff + (intval($bl) + intval($permit) + intval($transport) + intval($insurance));
                            $totalCbm += $totals['total_cbm2'] + $totals['cbm_difference'];

                            $groupedMarkings = collect($totals['markings'])->groupBy(function ($marking) {
                                // Menentukan pola regex untuk ekstraksi prefix dan nomor
                                preg_match('/^(.*?)([-#\s\.\/]?)\s*(\d*)$/', $marking, $matches);
                                $prefix = $matches[1] ?? '';
                                $separator = $matches[2] ?? '';
                                $number = $matches[3] ?? '';
                                return $prefix . $separator;
                            });

                            $mergedMarkings = $groupedMarkings->map(function ($group) {
                                $prefix = '';
                                $separator = '';
                                $suffixes = $group->map(function ($marking) use (&$prefix, &$separator) {
                                    // Ekstraksi prefix dan separator dari marking pertama
                                    preg_match('/^(.*?)([-#\s\.\/]?)\s*(\d*)$/', $marking, $matches);
                                    if (empty($prefix)) {
                                        $prefix = $matches[1] ?? '';
                                        $separator = $matches[2] ?? '';
                                    }
                                    return $matches[3] !== '' ? intval($matches[3]) : null; // Mengambil angka dari grup ketiga hasil regex, atau null jika tidak ada
                                })->filter()->sort()->unique()->values()->toArray();

                                if (empty($suffixes)) {
                                    return $prefix;
                                }

                                $merged = [];
                                $currentRange = [];
                                $lastSuffix = null; // Initialize with null
                                foreach ($suffixes as $suffix) {
                                    if ($lastSuffix !== null && $suffix - $lastSuffix !== 1) {
                                        $merged[] = count($currentRange) > 1 ? $prefix . $separator . $currentRange[0] . '-' . $lastSuffix : $prefix . $separator . $lastSuffix;
                                        $currentRange = [$suffix];
                                    } else {
                                        $currentRange[] = $suffix;
                                    }
                                    $lastSuffix = $suffix;
                                }
                                $merged[] = count($currentRange) > 1 ? $prefix . $separator . $currentRange[0] . '-' . $lastSuffix : $prefix . $separator . $lastSuffix;
                                return implode(', ', $merged);
                            })->values()->toArray();
                        @endphp
                        <tr>
                            <td width="5%" class="border_left_right"></td>
                            <td width="30%" class="border_left_right text_center">
                                @if (!$totals['cas'])
                                    {{ $code ? $code : '' }} {{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('M-y') }}
                                @else
                                    {{ $customer->name }} {{ implode(', ', $mergedMarkings) }} = {{ $lts }}
                                @endif
                            </td>
                            <td width="12.5%" class="border_left_right text_center text_uppercase">{{ $qty }} PKG</td>
                            <td width="10%" class="border_left_right text_center text_uppercase">{{ $totals['total_cbm2'] }} M3</td>
                            <td width="15%" class="border_left_right text_center">
                                {{ 'Rp ' . number_format($unit_price ?? 0, 0, ',', '.') }}
                                @if ($totals['cas'])
                                    <br> <span style="font-size: smaller;">( +{{ number_format($totals['cas'] ?? 0, 0, ',', '.') }} )</span>
                                @endif
                            </td>
                            <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($amount ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        
                        <!-- bl -->
                        @if ($bl)
                            <tr>
                                <td width="5%" class="border_left_right"></td>
                                <td width="30%" class="border_left_right text_center">BL</td>
                                <td width="12.5%" class="border_left_right text_center text_uppercase"></td>
                                <td width="10%" class="border_left_right text_center text_uppercase"></td>
                                <td width="15%" class="border_left_right text_center"></td>
                                <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($bl ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        
                        <!-- permit -->
                        @if ($permit)
                            <tr>
                                <td width="5%" class="border_left_right"></td>
                                <td width="30%" class="border_left_right text_center">PERMIT</td>
                                <td width="12.5%" class="border_left_right text_center text_uppercase"></td>
                                <td width="10%" class="border_left_right text_center text_uppercase"></td>
                                <td width="15%" class="border_left_right text_center"></td>
                                <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($permit ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @endif

                        <!-- transport -->
                        @if ($transport)
                            <tr>
                                <td width="5%" class="border_left_right"></td>
                                <td width="30%" class="border_left_right text_center">TRANSPORT</td>
                                <td width="12.5%" class="border_left_right text_center text_uppercase"></td>
                                <td width="10%" class="border_left_right text_center text_uppercase"></td>
                                <td width="15%" class="border_left_right text_center"></td>
                                <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($transport ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @endif

                        <!-- insurance -->
                        @if ($insurance)
                            <tr>
                                <td width="5%" class="border_left_right"></td>
                                <td width="30%" class="border_left_right text_center">INSURANCE</td>
                                <td width="12.5%" class="border_left_right text_center text_uppercase"></td>
                                <td width="10%" class="border_left_right text_center text_uppercase"></td>
                                <td width="15%" class="border_left_right text_center"></td>
                                <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($insurance ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        
                        <!-- selisih -->
                        @if ($totals['cbm_difference'])
                            <tr>
                                <td width="5%" class="border_left_right"></td>
                                <td width="30%" class="border_left_right text_center">Selisih SIN BTH ({{ $totals['total_cbm1'] }} - {{ $totals['total_cbm2'] }} M3)</td>
                                <td width="12.5%" class="border_left_right text_center text_uppercase"></td>
                                <td width="10%" class="border_left_right text_center text_uppercase">{{ $totals['cbm_difference'] }} M3</td>
                                <td width="15%" class="border_left_right text_center">{{ 'Rp ' . number_format($unitPriceCbmDiff ?? 0, 0, ',', '.') }}</td>
                                <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($amountCbmDiff ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @endif

                        @php
                            if ($entryRow > 15) {
                                $entryRow = 0;
                                echo '</table>';
                                echo '<div style="page-break-after: always;"></div>';
                                echo '<table style="border: 1px solid #000;">';
                                echo '<tr>
                                        <th>Item</th>
                                        <th>Description</th>
                                        <th colspan="2">Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Amount</th>
                                    </tr>';
                            }
                        @endphp
                    @endforeach
                @endif

                <!-- empty row -->
                @for ($i = 1; $i <= (17 - $entryRow); $i++)
                    <tr>
                        <td width="5%" class="border_left_right" style="height: 20px;"></td>
                        <td width="30%" class="border_left_right text_center"></td>
                        <td width="12.5%" class="border_left_right text_center text_uppercase"></td>
                        <td width="10%" class="border_left_right text_center text_uppercase"></td>
                        <td width="15%" class="border_left_right text_center"></td>
                        <td width="20%" class="border_left_right text_center"></td>
                    </tr>
                @endfor

            @else
                @foreach($seaShipmentLines as $line)
                @php
                    $qty = $line->qty_pkgs;
                    $cbm = $line->tot_cbm_1;
                    $unit_price = $pricelist;
                    $amount = $cbm * $unit_price;

                    $totalQty += $qty;
                    $totalAmount += $amount;
                    $totalCbm += $cbm;
                    $entryRow++;
                @endphp
                    <tr>
                        <td width="5%" class="border_left_right"></td>
                        <td width="30%" class="border_left_right text_center">BATAM {{ $line->marking }} {{ \Carbon\Carbon::createFromFormat('Y-m-d', $line->date)->format('M-y') }}</td>
                        <td width="12.5%" class="border_left_right text_center text_uppercase">{{ $qty }} PKG</td>
                        <td width="10%" class="border_left_right text_center text_uppercase">{{ $cbm }} M3</td>
                        <td width="15%" class="border_left_right text_center">{{ 'Rp ' . number_format($unit_price ?? 0, 0, ',', '.') }}</td>
                        <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($amount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        if ($entryRow > 15) {
                            $entryRow = 0;
                            echo '</table>';
                            echo '<div style="page-break-after: always;"></div>';
                            echo '<table style="border: 1px solid #000;">';
                            echo '<tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th colspan="2">Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Amount</th>
                                </tr>';
                        }
                    @endphp
                @endforeach

                <!-- empty row -->
                @for ($i = 1; $i <= (17 - $entryRow); $i++)
                    <tr>
                        <td width="5%" class="border_left_right" style="height: 20px;"></td>
                        <td width="30%" class="border_left_right text_center"></td>
                        <td width="12.5%" class="border_left_right text_center text_uppercase"></td>
                        <td width="10%" class="border_left_right text_center text_uppercase"></td>
                        <td width="15%" class="border_left_right text_center"></td>
                        <td width="20%" class="border_left_right text_center"></td>
                    </tr>
                @endfor
            @endif

            <tr>
                <td width="5%" class="border_left_right"></td>
                <td width="30%" class="border_left_right text_center">Total</td>
                <td width="12.5%" class="border_left_right text_center text_uppercase">{{ $totalQty }}</td>
                <td width="10%" class="border_left_right text_center text_uppercase">{{ $totalCbm }} M3</td>
                <td width="15%" class="border_left_right text_center"></td>
                <td width="20%" class="border_left_right text_center"></td>
            </tr>

        </table>
        <table style="margin-top: -1px;">
            <tr>
                <td colspan="4"></td>
                <td width="20%" style="font-size: 14px;" class="text_center">Total Rp. / S$.</td>
                <td width="21.6%" class="text_center no_top_border bold" id="total">{{ 'Rp ' . number_format($totalAmount ?? 0, 0, ',', '.') }}</td>
            </tr>
        </table>

        @php
        function spelledout($number) {
            $unit = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
            $dozen = ['sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas', 'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'];
            $tens = ['', 'sepuluh', 'dua puluh', 'tiga puluh', 'empat puluh', 'lima puluh', 'enam puluh', 'tujuh puluh', 'delapan puluh', 'sembilan puluh'];
            $thousands = ['', 'ribu', 'juta', 'miliar', 'triliun'];
        
            if ($number == 0) {
                return 'nol';
            }
        
            $result = '';
            $i = 0;
            while ($number > 0) {
                $hundreds = $number % 1000;
                $number = floor($number / 1000);
        
                if ($hundreds != 0) {
                    $hundreds_str = '';
                    if ($hundreds >= 100) {
                        if (floor($hundreds / 100) == 1) {
                            $hundreds_str .= 'seratus ';

                        } else {
                            $hundreds_str .= $unit[floor($hundreds / 100)] . ' ratus ';
                        }

                        $hundreds %= 100;
                    }
        
                    if ($hundreds >= 20) {
                        $hundreds_str .= $tens[floor($hundreds / 10)] . ' ';
                        $hundreds %= 10;

                    } elseif ($hundreds >= 10) {
                        $hundreds_str .= $dozen[$hundreds - 10] . ' ';
                        $hundreds = 0;
                    }
        
                    if ($hundreds > 0) {
                        $hundreds_str .= $unit[$hundreds] . ' ';
                    }
        
                    $result = $hundreds_str . $thousands[$i] . ' ' . $result;
                }
        
                $i++;
            }
        
            return trim($result);
        }
        @endphp

        <div style="margin-top: 10px; margin-bottom:5px;"><span>Say of, </span></div>

        @php
        function splitTextIntoLines($text, $maxWidth) {
            $words = explode(" ", $text);
            $lines = [];
            $currentLine = "";

            foreach ($words as $word) {
                $currentWidth = estimateTextWidth($currentLine . $word . " ");
                if ($currentWidth <= $maxWidth) {
                    $currentLine .= $word . " ";
                } else {
                    $lines[] = trim($currentLine);
                    $currentLine = $word . " ";
                }
            }

            if (!empty($currentLine)) {
                $lines[] = trim($currentLine);
            }

            return $lines;
        }

        function estimateTextWidth($text) {
            // Anggap setiap karakter memiliki lebar yang sama
            $charWidth = 8; // Ukuran karakter dalam piksel (disesuaikan sesuai kebutuhan)
            return strlen($text) * $charWidth;
        }

        $text = spelledout($totalAmount);
        $maxWidth = 550;
        $lines = splitTextIntoLines($text, $maxWidth);

        foreach ($lines as $index => $line) {
            if ($index === count($lines) - 1) {
                echo "<div style='text-align:left; border-bottom: 1px solid #000; width:425px; margin-top:2px; font-weight: bold; display: inline-block;'>$line rupiah</div>
                <div style='text-align:center; width:200px; display: inline-block; margin-left:75px;'>
                    <span><b>$companyName</b></span><br>
                    <span style='margin-top: 5px;'>Prepared by,</span>
                </div>";

            } else {
                echo "<div style='border-bottom: 1px solid #000; width:425px; margin-top:2px; font-weight: bold;'>$line</div>";
            }
        }
        @endphp

        <div style="text-align:left; margin-top: 20px; border-bottom: 1px solid #000; width:425px;"><span>Received by, </span></div>
        <div style="text-align:center; width:200px; border-bottom: 1px solid #000; margin-left:505px; margin-top:20px;"></div>
    </body>
</html>
