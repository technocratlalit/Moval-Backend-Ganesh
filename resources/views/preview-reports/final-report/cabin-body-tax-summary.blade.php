<br>
{{-- Cabin Body Tax Summary --}}
<table style="border:1px solid black" width="100%" align="center">
    <tbody>
        <tr>
            <td align="left" valign="top"
                style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">
                CABIN & LOAD BODY TAX SUMMARY</td>
        </tr>
    </tbody>
</table>
<table style="border:1px solid black" width="100%" align="center">
    <tbody>
        <tr>
            <td align="left" valign="top"
                style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">
                CABIN MATERIAL</td>
        </tr>
    </tbody>
</table>
@if ($cabinRecords)
    <table style="border:1px solid black" width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
            <tr>
                <td align="center" valign="top"
                    style="padding: 0px 3px; width: 10%; border-left: 1px solid #000; font-weight: bold;">Sr. No.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">Tax %
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total
                    Amt.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 14%; font-weight: bold;">Dep. Amt.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt.
                    After
                    Dep.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 13%; font-weight: bold;">GST Amt.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 13%; font-weight: bold;">IGST Amt.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Total
                    Amt.
                </td>
            </tr>
            @php
                $index = 0;
                $grandTotalAmt = 0;
                $grandTotalDep = 0;
                $grandTotalAftDep = 0;
                $grandTotalGstAmt = 0;
                $grandTotalWithGstAmt = 0;
            @endphp
            @foreach ($cabinDetailsCalculation[1]['parts_details_total'] as $gst => $details)
                @if (isset($details['assessed']))
                    @php
                        $assessed = collect($details['assessed']);

                        $total = $assessed->reduce(function ($carry, $item) {
                            return $carry + ($item['total'] ?? 0);
                        });
                        $dep = collect($details['assessed'])->reduce(function ($carry, $item) {
                            return $carry + ($item['dep'] ?? 0);
                        });
                        $afterDep = collect($details['assessed'])->reduce(function ($carry, $item) {
                            return $carry + ($item['amt_after_dep'] ?? 0);
                        });
                        $gstValue = collect($details['assessed'])->reduce(function ($carry, $item) {
                            return $carry + ($item['gst_amount'] ?? 0);
                        });
                        $amtAfterGst = collect($details['assessed'])->reduce(function ($carry, $item) {
                            return $carry + ($item['amt_after_gst'] ?? 0);
                        });
                        $grandTotalAmt += $total;
                        $grandTotalDep += $dep;
                        $grandTotalAftDep += $afterDep;
                        $grandTotalGstAmt += $gstValue;
                        $grandTotalWithGstAmt += $amtAfterGst;
                    @endphp
                    @if ($total > 0)
                        <tr>
                            <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">
                                {{ ++$index }}</td>
                            <td align="center" valign="top" style="padding: 0px 3px; ">{{ $gst }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">
                                {{ number_format_custom($total) }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">
                                {{ number_format_custom($dep) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">
                                {{ number_format_custom($afterDep) }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">
                                {{ $gstValue }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">0</td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">
                                {{ $amtAfterGst }}
                            </td>


                        </tr>
                    @endif
                @endif
            @endforeach
            <tr>
                <td align="right" style="font-weight:bold;padding:0 3px"colspan="2">Grand Total</td>
                <td align="right" style="font-weight:bold;padding:0 3px">{{ $grandTotalAmt }}</td>
                <td align="right" style="font-weight:bold;padding:0 3px">{{ $grandTotalDep }}</td>
                <td align="right" style="font-weight:bold;padding:0 3px">{{ $grandTotalAftDep }}</td>
                <td align="right" style="font-weight:bold;padding:0 3px">{{ $grandTotalGstAmt }}</td>
                <td align="right" style="font-weight:bold;padding:0 3px">
                    0
                </td>
                <td align="right" style="font-weight:bold;padding:0 3px">{{ $grandTotalWithGstAmt }}</td>
            </tr>
        </tbody>
    </table>
@endif
{{-- Cabin Labour Tax Summary --}}
@if (isset($cabinDetailsCalculation[1]['labour_total']['assessed']) &&
        $cabinDetailsCalculation[1]['labour_total']['assessed'] > 0)
    <table style="border:1px solid black"width="100%" align="center">
        <tbody>
            <tr>
                <td align="left" valign="top"
                    style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">
                    CABIN LABOUR</td>
            </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;border:1px solid black">
        <tbody>
            <tr>
                <td align="center" valign="top"
                    style="padding: 0px 3px; width: 10%; border-left: 1px solid #000; font-weight: bold;">Sr. No.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">Tax %
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total
                    Amt.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 14%; font-weight: bold;">Dep. Amt.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt.
                    After
                    Dep.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 13%; font-weight: bold;">GST
                    Amt.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 13%; font-weight: bold;">IGST
                    Amt.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Total
                    Amt.
                </td>
            </tr>
            @php
                $index = 0;
                $grandTotalAmt = 0;
                $grandTotalDep = 0;
                $grandTotalAftDep = 0;
                $grandTotalGstAmt = 0;
                $grandTotalWithGstAmt = 0;
            @endphp
            @foreach ($cabinDetailsCalculation[1]['labour_details_total'] as $gst => $details)
                @if (isset($details['assessed']))
                    @php

                        $assessed = Collect($details['assessed']);
                        $total = $assessed->get('total');
                        $grandTotalAmt += $total;
                        $dep = 0;
                        $grandTotalDep += $dep;
                        $afterDep = $total;
                        $grandTotalAftDep += $afterDep;
                        $gstValue = $assessed->get('gst_amount');
                        $grandTotalGstAmt += $gstValue;
                        $amtAfterGst = $assessed->get('amt_after_gst');
                        $subtotal = $amtAfterGst;
                        $grandTotalWithGstAmt += $subtotal;
                    @endphp
                    @if ($amtAfterGst > 0)
                        <tr>
                            <td align="center" valign="top"
                                style="padding: 0px 3px;  border-left: 1px solid #000;">
                                {{ ++$index }}</td>
                            <td align="center" valign="top" style="padding: 0px 3px; ">{{ $gst }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">
                                {{ number_format_custom($total) }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">
                                {{ number_format_custom($dep) }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">
                                {{ number_format_custom($afterDep) }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">
                                {{ $gstValue }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">
                                0
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">
                                {{ number_format_custom($amtAfterGst) }}</td>
                        </tr>
                    @endif
                @endif
            @endforeach
            <tr>
                <td align="right" style="font-weight:bold;padding:0 3px" colspan="2">Grand Total</td>
                <td align="right" style="font-weight:bold;padding:0 3px">{{ $grandTotalAmt }}</td>
                <td align="right" style="font-weight:bold;padding:0 3px">{{ $grandTotalDep }}</td>
                <td align="right" style="font-weight:bold;padding:0 3px">{{ $grandTotalAftDep }}</td>
                <td align="right" style="font-weight:bold;padding:0 3px">{{ $grandTotalGstAmt }}</td>
                <td align="right" style="font-weight:bold;padding:0 3px">
                    0
                </td>
                <td align="right" style="font-weight:bold;padding:0 3px">{{ $grandTotalWithGstAmt }}</td>
            </tr>
        </tbody>
    </table>
@endif
