@php
    $part_tax_total = isset($part_tax_summary_details['parts']) ? array_column($part_tax_summary_details['parts'], 'amount') : [];
    $part_tax_imt_total = isset($part_tax_summary_details['imt_parts']) ? array_column($part_tax_summary_details['imt_parts'], 'amount') : [];
@endphp
<br>
@if(!empty($part_tax_total) && array_sum($part_tax_total) > 0)
    <table width="100%" align="center" id="design">
        <tbody>
        <tr>
            <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold; padding-top:10px;"><span style="text-decoration:underline;">PARTS TAX SUMMARY </span></td>
        </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Total Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amt. After Dep.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">GST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">IGST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
            </tr>
            @if(isset($part_tax_summary_details['parts']) && !empty($part_tax_summary_details['parts']))
                @php
                    $index = 0;
                    $grandTotalAmt = 0;
                    $grandTotalDep = 0;
                    $grandTotalAftDep = 0;
                    $grandTotalGstAmt = 0;
                    $grandTotalWithGstAmt = 0;
                @endphp
                @foreach($part_tax_summary_details['parts'] as $gst => $tax)
                    @php
                        $amount = $tax['amount'] ?? 0;
                        $dep = $tax['dep'] ?? 0;
                        $afterDep = ($amount - $dep);
                        $gstValue = (($afterDep * $gst) / 100);
                        $grandTotalAmt += $amount;
                        $grandTotalDep += $dep;
                        $grandTotalAftDep += $afterDep;
                        $grandTotalGstAmt += $gstValue;
                        $grandTotalWithGstAmt += ($gstValue + $afterDep);
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{ ++$index }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px; ">{{ $gst }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($amount)}}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($dep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($afterDep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($gstValue) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($gstValue) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($gstValue + $afterDep) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalAmt)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandTotalAftDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($grandTotalGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($grandTotalGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalWithGstAmt)}}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif

@if(!empty($part_tax_imt_total) && array_sum($part_tax_imt_total) > 0)
    <table width="100%" align="center" id="design">
        <tbody>
        <tr><td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">IMT PARTS</td></tr>
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
            @if(isset($part_tax_summary_details['imt_parts']) && !empty($part_tax_summary_details['imt_parts']))
                @php
                    $index = 0;
                    $grandTotalAmt = 0;
                    $grandTotalDep = 0;
                    $grandTotalAftDep = 0;
                    $grandTotalImt = 0;
                    $grandTotalAfterImt = 0;
                    $grandTotalGstAmt = 0;
                    $grandTotalWithGstAmt = 0;
                @endphp
                @foreach($part_tax_summary_details['imt_parts'] as $gst => $tax)
                    @php
                        $amount = $tax['amount'] ?? 0;
                        $dep = $tax['dep'] ?? 0;
                        $afterDep = ($amount - $dep);
                        $imt_dep = $tax['imt_dep'] ?? 0;
                        $after_imt_dep = ($afterDep - $imt_dep);
                        $gstValue = (($after_imt_dep * $gst) / 100);
                        $grandTotalAmt += $amount;
                        $grandTotalDep += $dep;
                        $grandTotalAftDep += $afterDep;
                        $grandTotalImt += $imt_dep;
                        $grandTotalAfterImt += $after_imt_dep;
                        $grandTotalGstAmt += $gstValue;
                        $grandTotalWithGstAmt += ($gstValue + $after_imt_dep);
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{ ++$index }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px; ">{{ $gst }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($amount) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($dep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($afterDep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($imt_dep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($after_imt_dep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($gstValue) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($gstValue) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($gstValue + $after_imt_dep) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalAmt)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandTotalAftDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandTotalImt)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandTotalAfterImt)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($grandTotalGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($grandTotalGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalWithGstAmt)}}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif