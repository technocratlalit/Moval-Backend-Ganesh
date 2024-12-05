@php
    $labour_total_amt = isset($labour_category_tax_summery_details['labour']) ? array_column($labour_category_tax_summery_details['labour'], 'total') : [];
    $painting_total_amt = isset($labour_category_tax_summery_details['painting_labour']) ? array_column($labour_category_tax_summery_details['painting_labour'], 'total') : [];
    $imt_painting_total_amt = isset($labour_category_tax_summery_details['imt_painting_labour']) ? array_column($labour_category_tax_summery_details['imt_painting_labour'], 'total') : [];
@endphp

<br>
@if(!empty($labour_total_amt) && array_sum($labour_total_amt) > 0)
    <table width="100%" align="center" id="design">
        <tbody>
            <tr>
                <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;"> LABOUR TAX SUMMARY</td>
            </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px; width: 17%; border-left: 1px solid #000; font-weight: bold;">Sr. No.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 18%; font-weight: bold;">Tax %</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">GST Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">IGST Amt</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amount with GST</td>
            </tr>
            @if(isset($labour_category_tax_summery_details['labour']) && !empty($labour_category_tax_summery_details['labour']))
                @php
                    $indexCounter = 0;
                    $grandTotal = 0;
                    $grandTotalGstAmt = 0;
                    $grandTotalWithGstAmt = 0;
                @endphp
                @foreach($labour_category_tax_summery_details['labour'] as $gst_value => $item)
                    @php
                        $total = $item['total'] ?? 0;
                        $gst_amt = $item['gst_amt'] ?? 0;
                        $amt_with_gst = ($total + $gst_amt);

                        $grandTotal += $total;
                        $grandTotalGstAmt += $gst_amt;
                        $grandTotalWithGstAmt += $amt_with_gst;
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">{{ ++$indexCounter }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">{{ $gst_value }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($total) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($gst_amt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($gst_amt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($amt_with_gst) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotal) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($grandTotalGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($grandTotalGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalWithGstAmt)}}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif

@if(!empty($painting_total_amt) && array_sum($painting_total_amt) > 0)
    <table width="100%" align="center" id="design">
        <tbody>
            <tr>
                <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">PAINTING LABOUR TAX SUMMARY</td>
            </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; border-left: 1px solid #000; font-weight: bold;">Sr. No.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">Tax %</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 14%; font-weight: bold;">Dep. Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 13%; font-weight: bold;">GST Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 13%; font-weight: bold;">IGST Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Total Amt.</td>
            </tr>
            @if(isset($labour_category_tax_summery_details['painting_labour']) && !empty($labour_category_tax_summery_details['painting_labour']))
                @php
                    $indexCounter = 0;
                    $grandTotal = 0;
                    $grandTotalLess = 0;
                    $grandTotalAfterLess = 0;
                    $grandTotalGstAmt = 0;
                    $grandTotalWithGstAmt = 0;
                @endphp
                @foreach($labour_category_tax_summery_details['painting_labour'] as $gst_value => $item)
                    @php
                        $total = $item['total'] ?? 0;
                        $less = $item['less'] ?? 0;
                        $totalAfterDep = ($total - $less);
                        $calculatingGstAmt = ($totalAfterDep > 0 && $gst_value > 0) ? (($totalAfterDep * $gst_value) / 100) : 0;
                        $totalAfterAddingGSTOnTotal = ($totalAfterDep + $calculatingGstAmt);

                        $grandTotal += $total;
                        $grandTotalLess += $less;
                        $grandTotalAfterLess += $totalAfterDep;
                        $grandTotalGstAmt += $calculatingGstAmt;
                        $grandTotalWithGstAmt += $totalAfterAddingGSTOnTotal;
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">{{ ++$indexCounter }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">{{ $gst_value }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($total) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($less) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalAfterDep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($calculatingGstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($calculatingGstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalAfterAddingGSTOnTotal) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotal) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalLess) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalAfterLess) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($grandTotalGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($grandTotalGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalWithGstAmt)}}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif

@if(!empty($imt_painting_total_amt) && array_sum($imt_painting_total_amt) > 0)
    <table width="100%" align="center" id="design">
        <tbody>
            <tr>
                <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">IMT PAINTING LABOUR TAX SUMMARY</td>
            </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; border-left: 1px solid #000; font-weight: bold;">Sr. No.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 11%; font-weight: bold;">Tax %</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Total Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 17%; font-weight: bold;">Amt. After Dep.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 17%; font-weight: bold;">IMT Dep.Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 13%; font-weight: bold;">GST Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 13%; font-weight: bold;">IGST Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 19%; font-weight: bold;">Total Amt.</td>
            </tr>
            @if(isset($labour_category_tax_summery_details['imt_painting_labour']) && !empty($labour_category_tax_summery_details['imt_painting_labour']))
                @php
                    $indexCounter = 0;
                    $grandTotal = 0;
                    $grandTotalLess = 0;
                    $grandTotalAfterLess = 0;
                    $grandTotalImtLest = 0;
                    $grandTotalAfterImtLest = 0;
                    $grandTotalGstAmt = 0;
                    $grandTotalWithGstAmt = 0;
                @endphp
                @foreach($labour_category_tax_summery_details['imt_painting_labour'] as $gst_value => $item)
                    @php
                        $total = $item['total'] ?? 0;
                        $less = $item['less'] ?? 0;
                        $totalAfterDep = ($total - $less);
                        $imtDepAmt = $item['add_imt_less'] ?? 0;
                        $afterImtDepAmt = ($totalAfterDep - $imtDepAmt);
                        $calculatingGstAmt = ($afterImtDepAmt > 0 && $gst_value > 0) ? (($afterImtDepAmt * $gst_value) / 100) : 0;
                        $totalAfterAddingGSTOnTotal = ($afterImtDepAmt + $calculatingGstAmt);

                        $grandTotal += $total;
                        $grandTotalLess += $less;
                        $grandTotalAfterLess += $totalAfterDep;
                        $grandTotalImtLest += $imtDepAmt;
                        $grandTotalAfterImtLest += $afterImtDepAmt;
                        $grandTotalGstAmt += $calculatingGstAmt;
                        $grandTotalWithGstAmt += $totalAfterAddingGSTOnTotal;
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">{{ ++$indexCounter }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">{{ $gst_value }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($total) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($less) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalAfterDep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($imtDepAmt) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($afterImtDepAmt) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($calculatingGstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($calculatingGstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalAfterAddingGSTOnTotal) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotal) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalLess) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalAfterLess) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalImtLest) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalAfterImtLest) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($grandTotalGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($grandTotalGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalWithGstAmt)}}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif