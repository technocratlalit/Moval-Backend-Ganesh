@if(isset($lossAssessment[0]['alldetails']))
    @php
        $allLabourCharges = json_decode($lossAssessment[0]['alldetails'], true);
        $indexCounter = 0;
        $gstUniqueValue =[];
        $totalLabBilledAmt = 0;
        $totalLabAssessmentAmt = 0;
        $totalLabBilledPaintingAmt = 0;
        $gstUniqueValueAmount =[];
    @endphp
    @if(is_array($allLabourCharges))
        <div>
            <div style="padding: 0px 3px; font-weight: bold;">LABOUR</div>
        </div>

        <div>
            <div style="border-top: 1px solid #000;"></div>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center"
               style="font-size: 16px; font-family: Verdana, sans-serif;">
            <tbody>
            <tr>
                <td align="left" valign="top" style="width: 10%; padding: 0px 3px; font-weight: bold; font-size: 15px;">S. No</td>
                <td align="left" valign="top" style="width: 45%; padding: 0px 3px; font-weight: bold; font-size: 15px;">Description of Labour</td>
                <td align="left" valign="top" style="width: 8%; font-weight: bold; font-size: 15px; text-align: left;">GST %</td>
                <td align="left" valign="top" style="width: 17%; padding: 0px 3px; font-weight: bold; font-size: 15px;">Bill Serial No.</td>
                <td align="center" valign="top" style="width: 10%; padding: 0px 3px; font-weight: bold; font-size: 15px;">Billed Amt.</td>
                <td align="center" valign="top" style="width: 10%; padding: 0px 3px; font-weight: bold; font-size: 15px;">Ass. Amt.</td>
            </tr>
            @foreach($allLabourCharges as $index => $detail)
                @if(($detail['billed_lab_amt'] > 0 || $detail['ass_lab'] > 0 || $detail['billed_paint_amt'] > 0) && ($detail['ass_lab'] > 0 || !empty($detail['quantities'])))
                    @php
                        $indexCounter++;
                        $gstUniqueValue[]= $detail['gst'];
                        if(empty($detail['quantities'])){
                            $totalLabBilledAmt += $detail['billed_lab_amt'];
                            $totalLabAssessmentAmt += $detail['ass_lab'];
                            $totalLabBilledPaintingAmt += $detail['payable_amt'];
                            if($lossAssessment[0]['MultipleGSTonLab']==1) {
                                if(isset($gstUniqueValueAmount[$detail['gst']]['billed_lab_amt'])) {
                                    $gstUniqueValueAmount[$detail['gst']]['ass_lab'] += $detail['billed_lab_amt'];
                                    $gstUniqueValueAmount[$detail['gst']]['billed_lab_amt'] += $detail['ass_lab'];
                                } else {
                                    $gstUniqueValueAmount[$detail['gst']]['billed_lab_amt'] = $detail['billed_lab_amt'];
                                    $gstUniqueValueAmount[$detail['gst']]['ass_lab'] = $detail['ass_lab'];
                                }
                            } else{
                                if(isset($gstUniqueValueAmount[0]['billed_lab_amt'])) {
                                    $gstUniqueValueAmount[0]['ass_lab'] += $detail['billed_lab_amt'];
                                    $gstUniqueValueAmount[0]['billed_lab_amt'] += $detail['ass_lab'];
                                } else {
                                    $gstUniqueValueAmount[0]['billed_lab_amt'] = $detail['billed_lab_amt'];
                                    $gstUniqueValueAmount[0]['ass_lab'] = $detail['ass_lab'];
                                }
                            }
                            if($lossAssessment[0]['MultipleGSTonLab'] == 1) {
                                $SummerAssessmentAmounts['billed_paint_amt'][$detail['gst']] = isset($SummerAssessmentAmounts['billed_paint_amt'][$detail['gst']]) ? ($SummerAssessmentAmounts['billed_paint_amt'][$detail['gst']] + $detail['billed_paint_amt']) : $detail['billed_paint_amt'];
                            } else {
                                $SummerAssessmentAmounts['billed_paint_amt'][$lossAssessment[0]['GSTLabourPer']] = isset($SummerAssessmentAmounts['billed_paint_amt'][$lossAssessment[0]['GSTLabourPer']]) ? ($SummerAssessmentAmounts['billed_paint_amt'][$lossAssessment[0]['GSTLabourPer']] + $detail['billed_paint_amt']) : $detail['billed_paint_amt'];
                            }
                        }
                    @endphp
                    <tr>
                        <td align="center" valign="top" style=" font-size: 13px; padding: 0px 3px; border-left: 1px solid #000;">{{ $indexCounter }}</td>
                        <td align="left" valign="top" style=" font-size: 13px; padding: 0px 3px;">{{ $detail['description'] }}</td>
                        @if($lossAssessment[0]['MultipleGSTonLab']==1)
                            <td align="left" valign="top" style="font-size: 13px; font-style: italic; text-align: left; padding-left: 5px;">{{ !empty($detail['gst']) ? $detail['gst'] : '0' }}</td>
                        @else
                            <td align="left" valign="top" style="font-size: 13px; font-style: italic; text-align: left; padding-left: 5px;">{!! (!empty($lossAssessment[0]['GSTLabourPer']) && $lossAssessment[0]['GSTLabourPer'] > 0) ? $lossAssessment[0]['GSTLabourPer'] : '-' !!}</td>
                        @endif
                        <td align="right" valign="top" style="font-size: 13px; padding: 0px 3px;">{{ !empty($detail['b_sr_no']) ? $detail['b_sr_no'] : ' ' }}</td>
                        <td align="right" valign="top" style="ont-size: 13px; padding: 0px 3px;">{{ (!empty($detail['billed_lab_amt']) && $detail['billed_lab_amt'] > 0) ? number_format_custom($detail['billed_lab_amt']) : '0.00' }}</td>
                        <td align="right" valign="top" style="font-size: 13px; padding: 0px 3px;">{{ (!empty($detail['ass_lab']) && $detail['ass_lab'] > 0) ? number_format_custom($detail['ass_lab']) : '0.00' }}</td>
                    </tr>
                @endif
                @if(!empty($detail['quantities']))
                    @php
                        $quantityIndex = 0;
                    @endphp
                    @foreach($detail['quantities'] as $quantity)
                        @if((isset($detail['billed_lab_amt']) && ($quantity['billed_lab_amt'] != 0 || $quantity['ass_lab'] != 0) || $quantity['billed_paint_amt'] != 0) && ($quantity['ass_lab'] > 0))
                            @php
                                $gstUniqueValue[]= $quantity['gst'];
                                $totalLabBilledAmt += $quantity['billed_lab_amt'];
                                $totalLabAssessmentAmt += $quantity['ass_lab'];
                                $totalLabBilledPaintingAmt += $quantity['payable_amt'];
                                if($lossAssessment[0]['MultipleGSTonLab']==1) {
                                    if(isset($gstUniqueValueAmount[$quantity['gst']]['billed_lab_amt'])) {
                                        $gstUniqueValueAmount[$quantity['gst']]['ass_lab'] += $quantity['billed_lab_amt'];
                                        $gstUniqueValueAmount[$quantity['gst']]['billed_lab_amt'] += $quantity['ass_lab'];
                                    } else {
                                        $gstUniqueValueAmount[$quantity['gst']]['billed_lab_amt'] = $quantity['billed_lab_amt'];
                                        $gstUniqueValueAmount[$quantity['gst']]['ass_lab'] = $quantity['ass_lab'];
                                    }
                                } else{
                                    if(isset($gstUniqueValueAmount[0]['billed_lab_amt'])) {
                                        $gstUniqueValueAmount[0]['ass_lab'] += $quantity['billed_lab_amt'];
                                        $gstUniqueValueAmount[0]['billed_lab_amt'] += $quantity['ass_lab'];
                                    } else {
                                        $gstUniqueValueAmount[0]['billed_lab_amt'] = $quantity['billed_lab_amt'];
                                        $gstUniqueValueAmount[0]['ass_lab'] = $quantity['ass_lab'];
                                    }
                                }
                                if($lossAssessment[0]['MultipleGSTonLab'] == 1) {
                                    $SummerAssessmentAmounts['billed_paint_amt'][$quantity['gst']] = isset($SummerAssessmentAmounts['billed_paint_amt'][$quantity['gst']]) ? ($SummerAssessmentAmounts['billed_paint_amt'][$quantity['gst']] + $quantity['billed_paint_amt']) : $quantity['billed_paint_amt'];
                                } else {
                                    $SummerAssessmentAmounts['billed_paint_amt'][$lossAssessment[0]['GSTLabourPer']] = isset($SummerAssessmentAmounts['billed_paint_amt'][$lossAssessment[0]['GSTLabourPer']]) ? ($SummerAssessmentAmounts['billed_paint_amt'][$lossAssessment[0]['GSTLabourPer']] + $quantity['billed_paint_amt']) : $quantity['billed_paint_amt'];
                                }
                            @endphp
                            <tr>
                                <td align="center" valign="top" style=" font-size: 13px; padding: 0px 3px; font-style: italic; padding-left: 15px; border-left: 1px solid #000;">{{ $indexCounter }}.{{ ++$quantityIndex }}</td>
                                <td align="left" valign="top" style=" font-size: 13px; padding: 0px 3px; font-style: italic;">{{ $quantity['description'] }}</td>
                                @if($lossAssessment[0]['MultipleGSTonLab']==1)
                                    <td align="left" valign="top" style="font-size: 13px; font-style: italic; text-align: left; padding-left: 5px;">{{ !empty($quantity['gst']) ? $quantity['gst'] : '0' }}</td>
                                @else
                                    <td align="left" valign="top" style="font-size: 13px; font-style: italic; text-align: left; padding-left: 5px;">{!! (!empty($lossAssessment[0]['GSTLabourPer']) && $lossAssessment[0]['GSTLabourPer'] > 0) ? $lossAssessment[0]['GSTLabourPer'] : '-' !!}</td>
                                @endif
                                <td align="right" valign="top" style="font-size: 13px; padding: 0px 3px; font-style: italic;">{{ !empty($quantity['b_sr_no']) ? $quantity['b_sr_no'] : ' ' }}</td>
                                <td align="right" valign="top" style="font-size: 13px; padding: 0px 3px; font-style: italic;">{{ (!empty($quantity['billed_lab_amt']) && $quantity['billed_lab_amt'] > 0) ? number_format_custom($quantity['billed_lab_amt']) : '0.00' }}</td>
                                <td align="right" valign="top" style="font-size: 13px; padding: 0px 3px; font-style: italic;">{{ (!empty($quantity['ass_lab']) && $quantity['ass_lab'] > 0) ? number_format_custom($quantity['ass_lab']) : '0.00' }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach
            <!-- GST Bifercation -->
            @php
                $subUniqueGstRates = array_values(array_unique($gstUniqueValue));
                sort($subUniqueGstRates);
                $grand_total_billed_lab_amt = 0;
                $grand_total_ass_amt = 0;
            @endphp

            @foreach($subUniqueGstRates as $value)
                @if(isset($gstUniqueValueAmount[$value]))
                    <tr>
                        <td align="left" valign="top" colspan="4">Total (Labour with GST {{ $value }}%)</td>
                        <td align="right" valign="top">{{ number_format_custom($gstUniqueValueAmount[$value]['billed_lab_amt'], 2) }}</td>
                        <td align="right" valign="top">{{ number_format_custom($gstUniqueValueAmount[$value]['ass_lab'], 2) }}</td>
                    </tr>
                @endif
            @endforeach

            <tr>
                <td align="left" valign="top" colspan="4" style=" font-size: 13px; font-weight: bold;">Total Labour Charges</td>
                <td align="right" valign="top" style=" font-size: 13px; font-weight: bold;">{{ number_format_custom($totalLabBilledAmt, 2) }}</td>
                <td align="right" valign="top" style=" font-size: 13px; font-weight: bold;">{{ number_format_custom($totalLabAssessmentAmt, 2) }}</td>
            </tr>

            <!-- Add GST on Labour -->
            @php
                $subUniqueGst = array_values(array_unique($gstUniqueValue));
                sort($subUniqueGst);
                $subTotalAsslabAmt =0;
                $subTotalbilledLabAmt =0;
                $subTotalBilledPaintingLabAmt =0;
            @endphp

            @if($lossAssessment[0]['MultipleGSTonLab']==1)

                @foreach($subUniqueGst as $value)
                    @unless(in_array($value, $uniqueGstRates))
                        @php
                            $totalBilledlabAmt = 0;
                            $totalAsslabAmt = 0;
                            $totalBilledPaintingLabAmt = 0;
                            $uniqueGstRates[] = $value;
                        @endphp

                        @foreach($allLabourCharges as $detail)
                            @if($detail['gst'] == $value)
                                @if($detail['billed_lab_amt'] != 0 || $detail['ass_lab'] != 0)
                                    @php
                                        if(empty($detail['quantities'])){
                                            $totalBilledlabAmt += !empty($detail['billed_lab_amt']) ? $detail['billed_lab_amt'] : 0;
                                            $totalAsslabAmt += !empty($detail['ass_lab']) ? $detail['ass_lab'] : 0;
                                            $totalBilledPaintingLabAmt += !empty($detail['payable_amt']) ? $detail['payable_amt'] : 0;
                                        }
                                    @endphp
                                @endif
                            @endif

                            @if(isset($detail['quantities']))

                                @foreach($detail['quantities'] as $partQuantity)
                                    @if($partQuantity['gst'] == $value)

                                        @if($partQuantity['billed_lab_amt'] != 0 || $partQuantity['ass_lab'] != 0)
                                            @php
                                                $totalBilledlabAmt += !empty($partQuantity['billed_lab_amt']) ? $partQuantity['billed_lab_amt'] : 0;
                                                $totalAsslabAmt += !empty($partQuantity['ass_lab']) ? $partQuantity['ass_lab'] : 0;
                                                $totalBilledPaintingLabAmt += !empty($partQuantity['payable_amt']) ? $partQuantity['payable_amt'] : 0;

                                            @endphp
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        <tr>
                            <td align="left" valign="top" colspan="4" style="font-size: 13px;">Add GST {{ $value }}%
                            </td>
                            <td align="right" valign="top"
                                style="font-size: 14px;">{{ $value != 0 ? number_format_custom(($totalBilledlabAmt * $value / 100), 2) : '0.00' }}</td>
                            <td align="right" valign="top"
                                style="font-size: 14px;">{{ $value != 0 ? number_format_custom(($totalAsslabAmt * $value / 100), 2) : '0.00' }}</td>
                        </tr>

                        @php
                            $subTotalAsslabAmt += ($totalAsslabAmt *  $value / 100);
                            $subTotalbilledLabAmt += floatval($totalBilledlabAmt *  $value / 100);
                            $subTotalBilledPaintingLabAmt += floatval($totalBilledPaintingLabAmt *  $value / 100);
                        @endphp

                    @endunless
                @endforeach

            @else
                @if($lossAssessment[0]['GstonAssessedLab']=="N")
                    <tr>
                        <td align="left" valign="top" colspan="4" style="padding: 0px 3px; font-size: 13px;">Add GST 0
                            %
                        </td>
                        <td align="right" valign="top" style="padding: 0px 3px; font-size: 13px;">0.00</td>
                        <td align="right" valign="top" style="padding: 0px 3px; font-size: 13px;">0.00</td>
                    </tr>
                    @php
                        $subTotalAsslabAmt += 0;
                        $subTotalbilledLabAmt += 0;
                        $subTotalBilledPaintingLabAmt += 0;
                    @endphp
                @else
                    <tr>
                        <td align="left" valign="top" colspan="4" style="padding: 0px 3px; font-size: 13px;">Add
                            GST {{ $lossAssessment[0]['GSTLabourPer'] }} %
                        </td>
                        <td align="right" valign="top"
                            style="padding: 0px 3px; font-size: 13px;">{{ $lossAssessment[0]['GSTLabourPer'] != 0 ? number_format_custom(($totalLabBilledAmt * $lossAssessment[0]['GSTLabourPer'] / 100), 2) : '0.00' }}</td>
                        <td align="right" valign="top"
                            style="padding: 0px 3px; font-size: 13px;">{{ $lossAssessment[0]['GSTLabourPer'] != 0 ? number_format_custom(($totalLabAssessmentAmt * $lossAssessment[0]['GSTLabourPer'] / 100), 2) : '0.00' }}</td>
                    </tr>
                    @php
                        $subTotalAsslabAmt += ($totalLabAssessmentAmt *  $lossAssessment[0]['GSTLabourPer'] / 100);
                        $subTotalbilledLabAmt += floatval($totalLabBilledAmt *  $lossAssessment[0]['GSTLabourPer'] / 100);
                        $subTotalBilledPaintingLabAmt += floatval($totalLabBilledPaintingAmt *  $lossAssessment[0]['GSTLabourPer'] / 100);
                    @endphp

                @endif
            @endif
            <!-- End GST on Labour -->

            @php
                $totalPaintingLabIMTAmt = ($lossAssessment[0]['totalPaintingIMT'] - $lossAssessment[0]['depAmtPaintingIMT'] + $lossAssessment[0]['gstPaintingIMT']);
                $totalAssessedLabAmt = ($lossAssessment[0]['totalPainting'] - $lossAssessment[0]['depAmtPainting'] + $lossAssessment[0]['gstPainting']);
                $PaintingLabourwithTaxes = 0;
                if(isset($SummerAssessmentAmounts['billed_paint_amt']) && !empty($SummerAssessmentAmounts['billed_paint_amt'])) {
                    foreach ($SummerAssessmentAmounts['billed_paint_amt'] as $percent => $amt) {
                      $PaintingLabourwithTaxes += ($amt + number_format_custom(($amt * $percent / 100), 2));
                    }
                }
            @endphp

            <tr>
                <td align="left" valign="top" colspan="4" style="font-weight: bold; font-size: 13px;">Total labour
                    charges with Taxes
                </td>
                <td align="right" valign="top"
                    style="font-weight: bold; font-size: 13px;">{{ number_format_custom(($totalLabBilledAmt + $subTotalbilledLabAmt), 2) }}</td>
                <td align="right" valign="top"
                    style="font-weight: bold; font-size: 13px;">{{ number_format_custom(($totalLabAssessmentAmt + $subTotalAsslabAmt), 2) }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" colspan="5" style="font-weight: bold; font-size: 13px;">Total labour
                    charges Endorsement Items with Taxes
                </td>
                <td align="right" valign="top"
                    style="font-weight: bold; font-size: 13px;">{{ number_format_custom(($lossAssessment[0]['totalPaintingIMT'] - $lossAssessment[0]['depAmtPaintingIMT'] + $lossAssessment[0]['gstPaintingIMT']), 2) }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" colspan="4" style="font-weight: bold; font-size: 14px;">Painting Labour with Taxes</td>
                <td align="right" valign="top" style="font-weight: bold; font-size: 13px;">{{ number_format_custom($PaintingLabourwithTaxes, 2) }}</td>
                <td align="right" valign="top" style="font-weight: bold; font-size: 13px;">{{ number_format_custom(($lossAssessment[0]['totalPainting'] - $lossAssessment[0]['depAmtPainting'] + $lossAssessment[0]['gstPainting']), 2) }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" colspan="4" style="font-weight: bold; font-size: 14px;">Net Labour Amount</td>
                <td align="right" valign="top" style="font-weight: bold; font-size: 13px;">{{ number_format_custom(($totalLabBilledAmt + $subTotalbilledLabAmt + $PaintingLabourwithTaxes), 2) }}</td>
                <td align="right" valign="top" style="font-weight: bold; font-size: 13px;">{{ number_format_custom(($totalLabAssessmentAmt + $subTotalAsslabAmt + $totalPaintingLabIMTAmt + $totalAssessedLabAmt), 2) }}</td>
            </tr>
            </tbody>
        </table>
    @endif

    <!-- Assessment Summary Report-->
    @include('preview-reports.bill-check.assessment-summary-report')
    <!-- End Ass. Summary Report -->
@endif
