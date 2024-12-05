@php
    $category_part_metal_total = isset($part_tax_cate_summary_details['metal']) ? array_column($part_tax_cate_summary_details['metal'], 'amount') : [];
    $category_part_imt_metal_total = isset($part_tax_cate_summary_details['imt_metal']) ? array_column($part_tax_cate_summary_details['imt_metal'], 'amount') : [];
    $category_part_glass_total = isset($part_tax_cate_summary_details['glass']) ? array_column($part_tax_cate_summary_details['glass'], 'amount') : [];
    $category_part_rubber_plast_total = isset($part_tax_cate_summary_details['rubber_plast']) ? array_column($part_tax_cate_summary_details['rubber_plast'], 'amount') : [];
    $category_part_imt_rubber_plast_total = isset($part_tax_cate_summary_details['imt_rubber_plast']) ? array_column($part_tax_cate_summary_details['imt_rubber_plast'], 'amount') : [];
    $category_part_fiber_total = isset($part_tax_cate_summary_details['fiber']) ? array_column($part_tax_cate_summary_details['fiber'], 'amount') : [];
    $category_part_recondition_total = isset($part_tax_cate_summary_details['recondition']) ? array_column($part_tax_cate_summary_details['recondition'], 'amount') : [];
@endphp
<br>
@if(!empty($category_part_metal_total) && array_sum($category_part_metal_total) > 0)
    <table width="100%" align="center" id="design">
        <tbody>
            <tr>
                <td align="left" valign="top" style="font-weight: bold; border-right:none; border-bottom:none; padding: 3px 0px;"><span style="text-decoration:underline;">PARTS TAX SUMMARY</span></td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;">Metal</td>
            </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">GST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">IGST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
            </tr>
            @if(isset($part_tax_cate_summary_details['metal']) && !empty($part_tax_cate_summary_details['metal']))
                @php
                    $index = 0;
                    $grandTotal = 0;
                    $grandDep = 0;
                    $grandAfterDep = 0;
                    $grandGstAmt = 0;
                    $grandTotalWithGstAmt = 0;
                @endphp
                @foreach($part_tax_cate_summary_details['metal'] as $gst => $item)
                    @php
                        $amount = $item['amount'] ?? 0;
                        $dep = $item['dep'] ?? 0;
                        $afterDep = ($amount - $dep);
                        $gstAmt = (($afterDep * $gst) / 100);
                        $totalWithGst = ($afterDep + $gstAmt);

                        $grandTotal += $amount;
                        $grandDep += $dep;
                        $grandAfterDep += $afterDep;
                        $grandGstAmt += $gstAmt;
                        $grandTotalWithGstAmt += $totalWithGst;
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{ ++$index }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px; ">{{ $gst }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($amount) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($dep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($afterDep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($gstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($gstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalWithGst) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotal)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandAfterDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalWithGstAmt)}}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif

@if(!empty($category_part_glass_total) && array_sum($category_part_glass_total) > 0)
    <table width="100%" align="center" id="design">
        <tbody>
            <tr>
                <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold; padding-top:5px;">Glass</td>
            </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">GST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">IGST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
            </tr>
            @if(isset($part_tax_cate_summary_details['glass']) && !empty($part_tax_cate_summary_details['glass']))
                @php
                    $index = 0;
                    $grandTotal = 0;
                    $grandDep = 0;
                    $grandAfterDep = 0;
                    $grandGstAmt = 0;
                    $grandTotalWithGstAmt = 0;
                @endphp
                @foreach($part_tax_cate_summary_details['glass'] as $gst => $item)
                    @php
                        $amount = $item['amount'] ?? 0;
                        $dep = $item['dep'] ?? 0;
                        $afterDep = ($amount - $dep);
                        $gstAmt = (($afterDep * $gst) / 100);
                        $totalWithGst = ($afterDep + $gstAmt);

                        $grandTotal += $amount;
                        $grandDep += $dep;
                        $grandAfterDep += $afterDep;
                        $grandGstAmt += $gstAmt;
                        $grandTotalWithGstAmt += $totalWithGst;
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{ ++$index }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px; ">{{ $gst }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($amount) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($dep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($afterDep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($gstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($gstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalWithGst) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotal)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandAfterDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalWithGstAmt)}}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif

@if(!empty($category_part_rubber_plast_total) && array_sum($category_part_rubber_plast_total) > 0)
    <table width="100%" align="center" id="design">
        <tbody>
            <tr>
                <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">Rubber/Plastic</td>
            </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">GST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">IGST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
            </tr>
            @if(isset($part_tax_cate_summary_details['rubber_plast']) && !empty($part_tax_cate_summary_details['rubber_plast']))
                @php
                    $index = 0;
                    $grandTotal = 0;
                    $grandDep = 0;
                    $grandAfterDep = 0;
                    $grandGstAmt = 0;
                    $grandTotalWithGstAmt = 0;
                @endphp
                @foreach($part_tax_cate_summary_details['rubber_plast'] as $gst => $item)
                    @php
                        $amount = $item['amount'] ?? 0;
                        $dep = $item['dep'] ?? 0;
                        $afterDep = ($amount - $dep);
                        $gstAmt = (($afterDep * $gst) / 100);
                        $totalWithGst = ($afterDep + $gstAmt);

                        $grandTotal += $amount;
                        $grandDep += $dep;
                        $grandAfterDep += $afterDep;
                        $grandGstAmt += $gstAmt;
                        $grandTotalWithGstAmt += $totalWithGst;
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{ ++$index }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px; ">{{ $gst }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($amount) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($dep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($afterDep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($gstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($gstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalWithGst) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotal)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandAfterDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalWithGstAmt)}}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif

@if(!empty($category_part_fiber_total) && array_sum($category_part_fiber_total) > 0)
    <table width="100%" align="center" id="design">
        <tbody>
            <tr>
                <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">Fibre</td>
            </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">GST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">IGST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
            </tr>
            @if(isset($part_tax_cate_summary_details['fiber']) && !empty($part_tax_cate_summary_details['fiber']))
                @php
                    $index = 0;
                    $grandTotal = 0;
                    $grandDep = 0;
                    $grandAfterDep = 0;
                    $grandGstAmt = 0;
                    $grandTotalWithGstAmt = 0;
                @endphp
                @foreach($part_tax_cate_summary_details['fiber'] as $gst => $item)
                    @php
                        $amount = $item['amount'] ?? 0;
                        $dep = $item['dep'] ?? 0;
                        $afterDep = ($amount - $dep);
                        $gstAmt = (($afterDep * $gst) / 100);
                        $totalWithGst = ($afterDep + $gstAmt);

                        $grandTotal += $amount;
                        $grandDep += $dep;
                        $grandAfterDep += $afterDep;
                        $grandGstAmt += $gstAmt;
                        $grandTotalWithGstAmt += $totalWithGst;
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{ ++$index }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px; ">{{ $gst }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($amount) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($dep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($afterDep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($gstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($gstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalWithGst) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotal)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandAfterDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalWithGstAmt)}}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif

@if(!empty($category_part_recondition_total) && array_sum($category_part_recondition_total) > 0)
    <table width="100%" align="center" id="design">
        <tbody>
            <tr>
                <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">Recondition</td>
            </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">GST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">IGST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
            </tr>
            @if(isset($part_tax_cate_summary_details['recondition']) && !empty($part_tax_cate_summary_details['recondition']))
                @php
                    $index = 0;
                    $grandTotal = 0;
                    $grandDep = 0;
                    $grandAfterDep = 0;
                    $grandGstAmt = 0;
                    $grandTotalWithGstAmt = 0;
                @endphp
                @foreach($part_tax_cate_summary_details['recondition'] as $gst => $item)
                    @php
                        $amount = $item['amount'] ?? 0;
                        $dep = $item['dep'] ?? 0;
                        $afterDep = ($amount - $dep);
                        $gstAmt = (($afterDep * $gst) / 100);
                        $totalWithGst = ($afterDep + $gstAmt);

                        $grandTotal += $amount;
                        $grandDep += $dep;
                        $grandAfterDep += $afterDep;
                        $grandGstAmt += $gstAmt;
                        $grandTotalWithGstAmt += $totalWithGst;
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{ ++$index }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px; ">{{ $gst }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($amount) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($dep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($afterDep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($gstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($gstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalWithGst) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotal)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandAfterDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalWithGstAmt)}}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif

@if(!empty($category_part_imt_metal_total) && array_sum($category_part_imt_metal_total) > 0)
    <table width="100%" align="center" id="design">
        <tbody>
        <tr><td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">IMT Metal</td></tr>
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
        @if(isset($part_tax_cate_summary_details['imt_metal']) && !empty($part_tax_cate_summary_details['imt_metal']))
            @php
                $index = 0;
                $grandTotal = 0;
                $grandDep = 0;
                $grandAfterDep = 0;
                $grandAddDep = 0;
                $grandAddAfterDep = 0;
                $grandGstAmt = 0;
                $grandTotalWithGstAmt = 0;
            @endphp
            @foreach($part_tax_cate_summary_details['imt_metal'] as $gst => $item)
                @php
                    $amount = $item['amount'] ?? 0;
                    $dep = $item['dep'] ?? 0;
                    $addDep = $item['add_dep'] ?? 0;
                    $afterDep = ($amount - $dep);
                    $addAfterDep = ($afterDep - $addDep);
                    $gstAmt = (($addAfterDep * $gst) / 100);
                    $totalWithGst = ($addAfterDep + $gstAmt);

                    $grandTotal += $amount;
                    $grandDep += $dep;
                    $grandAfterDep += $afterDep;
                    $grandAddDep += $addDep;
                    $grandAddAfterDep += $addAfterDep;
                    $grandGstAmt += $gstAmt;
                    $grandTotalWithGstAmt += $totalWithGst;
                @endphp
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{ ++$index }}</td>
                    <td align="center" valign="top" style="padding: 0px 3px; ">{{ $gst }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($amount) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($dep) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($afterDep) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($addDep) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($addAfterDep) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($gstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($gstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalWithGst) }}</td>
                </tr>
            @endforeach
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotal)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandDep)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandAfterDep)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandAddDep)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandAddAfterDep)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalWithGstAmt)}}</td>
            </tr>
        @endif
        </tbody>
    </table>
@endif

@if(!empty($category_part_imt_rubber_plast_total) && array_sum($category_part_imt_rubber_plast_total) > 0)
    <table width="100%" align="center" id="design">
        <tbody>
            <tr><td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">IMT Rubber</td></tr>
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
            @if(isset($part_tax_cate_summary_details['imt_rubber_plast']) && !empty($part_tax_cate_summary_details['imt_rubber_plast']))
                @php
                    $index = 0;
                    $grandTotal = 0;
                    $grandDep = 0;
                    $grandAfterDep = 0;
                    $grandAddDep = 0;
                    $grandAddAfterDep = 0;
                    $grandGstAmt = 0;
                    $grandTotalWithGstAmt = 0;
                @endphp
                @foreach($part_tax_cate_summary_details['imt_rubber_plast'] as $gst => $item)
                    @php
                        $amount = $item['amount'] ?? 0;
                        $dep = $item['dep'] ?? 0;
                        $addDep = $item['add_dep'] ?? 0;
                        $afterDep = ($amount - $dep);
                        $addAfterDep = ($afterDep - $addDep);
                        $gstAmt = (($addAfterDep * $gst) / 100);
                        $totalWithGst = ($addAfterDep + $gstAmt);

                        $grandTotal += $amount;
                        $grandDep += $dep;
                        $grandAfterDep += $afterDep;
                        $grandAddDep += $addDep;
                        $grandAddAfterDep += $addAfterDep;
                        $grandGstAmt += $gstAmt;
                        $grandTotalWithGstAmt += $totalWithGst;
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{ ++$index }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px; ">{{ $gst }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($amount) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($dep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($afterDep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($addDep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($addAfterDep) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($gstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($gstAmt) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalWithGst) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotal)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandAfterDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandAddDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandAddAfterDep)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==0) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['IGSTonPartsAndLab'] ==1) ? number_format_custom($grandGstAmt) : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandTotalWithGstAmt)}}</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif