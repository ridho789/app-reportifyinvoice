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
                <td class="bold" colspan="2">: <span class="space_content2">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $seaShipment->date)->format('d-M-y') }}</span></td>
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
            @endphp

            @foreach($groupSeaShipmentLines as $groupDate => $totals)
                @php
                    $date = substr($groupDate, 0, 10);
                    $lts = substr($groupDate, strrpos($groupDate, '-') + 1);

                    $qty = $totals['total_qty_loose'];
                    $amount = $totals['total_cbm2'] * $pricelist;
                    $totalQty += $qty;
                    $totalAmount += $amount;
                    $totalCbm += $totals['total_cbm2'];

                    $groupedMarkings = collect($totals['markings'])->groupBy(function ($marking) {
                        // Menentukan pola regex untuk ekstraksi prefix, separator, dan nomor
                        preg_match('/^(.*?)([-#\s\.\/])\s*(\d+)$/', $marking, $matches);
                        $prefix = $matches[1] ?? '';
                        $separator = $matches[2] ?? '';
                        $number = intval($matches[3] ?? 0);
                        return $prefix . $separator;
                    });

                    $mergedMarkings = $groupedMarkings->map(function ($group) {
                        $prefix = '';
                        $separator = '';
                        $suffixes = $group->map(function ($marking) use (&$prefix, &$separator) {
                            // Ekstraksi prefix dan separator dari marking pertama
                            if (empty($prefix) || empty($separator)) {
                                preg_match('/^(.*?)([-#\s\.\/])\s*\d+$/', $marking, $matches);
                                $prefix = $matches[1] ?? '';
                                $separator = $matches[2] ?? '';
                            }
                            return intval(substr($marking, strrpos($marking, $separator) + 1)); // Mengambil angka setelah separator
                        })->sort()->unique()->values()->toArray();

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
                    @if ($lts) 
                        <td width="30%" class="border_left_right text_center">{{ $customer->name }} {{ implode(', ', $mergedMarkings) }} = {{ $lts }}</td>
                    @else
                        <td width="30%" class="border_left_right text_center">{{ $customer->name }} {{ implode(', ', $mergedMarkings) }}</td>
                    @endif
                    <td width="12.5%" class="border_left_right text_center text_uppercase">{{ $qty }} PKG</td>
                    <td width="10%" class="border_left_right text_center text_uppercase">{{ $totals['total_cbm2'] }} M3</td>
                    <td width="15%" class="border_left_right text_center">{{ 'Rp ' . number_format($pricelist ?? 0, 0, ',', '.') }}</td>
                    <td width="20%" class="border_left_right text_center">{{ 'Rp ' . number_format($amount ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <!-- empty row -->
            @for ($i = 1; $i <= (17 - count($groupSeaShipmentLines)); $i++)
                <tr>
                    <td width="5%" class="border_left_right" style="height: 20px;"></td>
                    <td width="30%" class="border_left_right text_center"></td>
                    <td width="12.5%" class="border_left_right text_center text_uppercase"></td>
                    <td width="10%" class="border_left_right text_center text_uppercase"></td>
                    <td width="15%" class="border_left_right text_center"></td>
                    <td width="20%" class="border_left_right text_center"></td>
                </tr>
            @endfor

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
                        $hundreds_str .= $unit[floor($hundreds / 100)] . ' ratus ';
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
