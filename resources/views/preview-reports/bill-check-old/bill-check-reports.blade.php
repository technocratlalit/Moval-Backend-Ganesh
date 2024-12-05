<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bill check format</title>

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        @font-face {
            font-family: 'verdana';
            font-weight: normal;
            font-style: normal;
            font-variant: normal;
            src: url("fonts/verdana.ttf") format('truetype');
        }

        th,
        td {
            font-family: verdana !important;
        }

        body {
            font-family: 'verdana', sans-serif;
        }

        .bill table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .bill th {
            border: 1px solid #000;
            padding: 0px 3px;
            font-size: 14px !important;
        }

        .bill td {
            border: 1px solid #000;
            padding: 0px 3px;
            font-size: 14px !important;
        }
    </style>
</head>

<body>
<div style=" margin: 0% 1% !important; width: 100%; font-family: 'Verdana' !important; font-size: 13px;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top: 20px; font-size: 13px; font-family: Verdana, sans-serif;">
        <tbody>
            <tr>
                <td align="center" valign="top">
                    @if ($letter_head_img)
                        <img src="{{ asset('public/storage/'.$letter_head_img) }}" style="height:auto;">
                    @else
                        <p>No letter head image available</p>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <div>
        <div style="border-top: 3px solid #000; margin-top: 10px;"></div>
    </div>

    <table width="100%" cellspacing="0" cellpadding="0" align="center">
        <tbody>
            <tr>
                <td align="center" valign="top" style="font-weight: bold; padding-top: 10px; padding-bottom: 10px; text-decoration: underline;">REPAIR LOSS ASSESSMENT BILL CHECK REPORT</td>
            </tr>
        </tbody>
    </table>
    <table width="100%" cellpadding="0" cellspacing="0" align="center">
        <tbody>
            <tr>
                <td align="left" valign="top" style="width: 15%; padding: 0px 3px; font-weight: bold;">Report No. :</td>
                <td align="left" valign="top" style="width: 15%; padding: 0px 3px;">{{ isset($policyDetails->inspection_reference_no) ? $policyDetails->inspection_reference_no : '' }}</td>
                <td align="left" valign="top" style="width: 50%; padding: 0px 3px;">&nbsp;</td>
                <td align="left" valign="top" style="width: 8%; padding: 0px 3px; font-weight: bold;">Date :</td>
                <td align="left" valign="top" style="width: 12%; padding: 0px 3px;">{{ isset($policyDetails->reportGeneratedOn) ? \Carbon\Carbon::parse($policyDetails->reportGeneratedOn)->format('d/m/Y') : '' }}</td>
            </tr>
        </tbody>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0" align="center">
        <tbody>
            <tr>
                <td align="center" valign="top" style="padding-top: 10px; padding-bottom: 10px; text-decoration: underline;">In respect of Vehicle Registration No.<strong>{{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}</strong>Accident Date:<strong>{{ !empty($policyDetails->date_time_accident) ? \Carbon\Carbon::parse($policyDetails->date_time_accident)->format('d/m/Y h:i A') : '' }}</strong></td>
            </tr>
        </tbody>
    </table>
    <table width="100%" cellpadding="0" cellspacing="0" align="center">
        <tbody>
            <tr>
                <td align="left" valign="top" style="width: 10%; padding: 0px 3px;">Insurer</td>
                <td align="left" valign="top" style="width: 5%; padding: 0px 3px;"> :</td>
                <td align="left" valign="top" style="width: 85%; padding: 0px 3px;"><strong>{{ isset($policyDetails->office_name) ? $policyDetails->office_name : '' }}</strong>, {{ isset($policyDetails->office_address) ? $policyDetails->office_address : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding: 0px 3px;">Insured</td>
                <td align="left" valign="top" style="padding: 0px 3px;"> :</td>
                <td align="left" valign="top" style="padding: 0px 3px;"><strong>{{ isset($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</strong>, {{ isset($policyDetails->insured_address) ? $policyDetails->insured_address : '' }}</td>
            </tr>
        </tbody>
    </table>

    <div>
        <div style="border-top: 1px solid #000; margin-top: 10px;"></div>
    </div>

    <table width="100%" cellpadding="0" cellspacing="0" align="center">
        <tbody>
            <tr>
                <td align="left" valign="top" style="width: 30%; padding: 0px 3px; font-weight: bold;">PARTS DETAIL</td>
                <td align="left" valign="top" style="width: 70%; padding: 0px 3px; font-weight: bold;"> (PARTS)</td>
            </tr>
        </tbody>
    </table>

    <div>
        <div style="border-top: 0px solid #000;"></div>
    </div>
    @php
        $SummerAssessmentAmounts = [];
        $IMTPaintingLabAssRemarksShow = false;
    @endphp
    @if(isset($lossAssessment[0]['alldetails']))

        @php
            $alldetails = json_decode($lossAssessment[0]['alldetails'], true);
            $subPartUniqueValue=[];
            $indexCounter = 0;
            $totalBilledAmt = 0;
            $totalAssessedAmt = 0;
            $totalAddGSTBilledAmt = 0;
            $totalAddGSTAssessedAmt = 0;
        @endphp

        <div class="bill">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
                <tbody>
                    <tr>
                        <td align="left" valign="top" style="width:6%; padding: 0px 3px; font-weight: bold;">Sr. No.</td>
                        <td align="left" valign="top" style="width: 18%; padding: 0px 3px; font-weight: bold;">Particulars</td>
                        <td align="left" valign="top" style="width: 6%; padding: 0px 3px; font-weight: bold;">GST %</td>
                        <td align="left" valign="top" colspan="3" style="width: 5%; padding: 0px 3px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="border: 0px;">
                                <tbody>
                                    <tr>
                                        <td align="center" colspan="3" valign="top" style="font-weight: bold; border: 0px;">Serial No.</td>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top" style="padding: 0px 3px; border: 0px; border-top: 2px solid #000;">Est.</td>
                                        <td align="center" valign="top" style="padding: 0px 3px; border: 0px; border-top: 2px solid #000;">SR</td>
                                        <td align="center" valign="top" style="padding: 0px 3px; border: 0px; border-top: 2px solid #000;">Bill</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td align="center" valign="top" style="width: 5%; padding: 0px 3px; font-weight: bold;">QE</td>
                        <td align="center" valign="top" style="width: 5%; padding: 0px 3px; font-weight: bold;">QA</td>
                        <td align="center" valign="top" style="width: 17%; padding: 0px 3px; font-weight: bold;">Billed Amt.</td>
                        <td align="center" valign="top" style="width: 17%; padding: 0px 3px; font-weight: bold;">Ass. Amt.</td>
                    </tr>
                    @if(is_array($alldetails))
                        @php
                            $GSTBilledPartPerAmt = 0;
                        @endphp
                        <!-- Main Part -->
                        @foreach($alldetails as $index => $detail)
                            @if(isset($detail['category']) && ($detail['category'] != null || $detail['category'] !="") && ($detail['ass_amt'] > 0 || !empty($detail['quantities'])))
                                @php
                                    $subPartUniqueValue[]= $detail['gst'];
                                    $indexCounter++;
                                    if(empty($detail['quantities'])) {
                                        $GSTBilledPartPerAmt += $detail['billed_part_amt'];
                                        if($detail['imt_23'] == "Yes") {
                                            if($lossAssessment[0]['MultipleGSTonBilled'] == 0) {
                                                $SummerAssessmentAmounts['Endorsement'][$lossAssessment[0]['GSTBilledPartPer']] = isset($SummerAssessmentAmounts['Endorsement'][$lossAssessment[0]['GSTBilledPartPer']]) ? ($SummerAssessmentAmounts['Endorsement'][$lossAssessment[0]['GSTBilledPartPer']] + $detail['billed_part_amt']) : $detail['billed_part_amt'];
                                            } else {
                                                $SummerAssessmentAmounts['Endorsement'][$detail['gst']] = isset($SummerAssessmentAmounts['Endorsement'][$detail['gst']]) ? ($SummerAssessmentAmounts['Endorsement'][$detail['gst']] + $detail['billed_part_amt']) : $detail['billed_part_amt'];
                                            }
                                        } else {
                                            if($lossAssessment[0]['MultipleGSTonBilled'] == 0) {
                                                $SummerAssessmentAmounts[$detail['category']][$lossAssessment[0]['GSTBilledPartPer']] = isset($SummerAssessmentAmounts[$detail['category']][$lossAssessment[0]['GSTBilledPartPer']]) ? ($SummerAssessmentAmounts[$detail['category']][$lossAssessment[0]['GSTBilledPartPer']] + $detail['billed_part_amt']) : $detail['billed_part_amt'];
                                            } else {
                                                $SummerAssessmentAmounts[$detail['category']][$detail['gst']] = isset($SummerAssessmentAmounts[$detail['category']][$detail['gst']]) ? ($SummerAssessmentAmounts[$detail['category']][$detail['gst']] + $detail['billed_part_amt']) : $detail['billed_part_amt'];
                                            }
                                        }
                                    }
                                    if($detail['imt_23'] == "Yes") {
                                        $IMTPaintingLabAssRemarksShow = true;
                                    }
                                @endphp
                                <tr>
                                    <td align="left" valign="top">{{ $indexCounter }}</td>
                                    <td align="left" valign="top">{!! ($detail['imt_23'] == "Yes") ? '<strong>*</strong>' : '' !!} {{ $detail['description'] }}</td>
                                    <td align="center" valign="top">{{ !empty($detail['gst']) ? $detail['gst'] : '0' }}</td>
                                    <td align="center" valign="top">{{ !empty($detail['e_sr_no']) ? $detail['e_sr_no'] : '0' }}</td>
                                    <td align="center" valign="top">{{ $indexCounter }}</td>
                                    <td align="center" valign="top">{{ !empty($detail['b_sr_no']) ? $detail['b_sr_no'] : '0' }}</td>
                                    <td align="center" valign="top">{{ !empty($detail['qe']) ? $detail['qe'] : '-' }}</td>
                                    <td align="center" valign="top">{{ !empty($detail['qa']) ? $detail['qa'] : '-' }}</td>
                                    <td align="right" valign="top">{!! (!empty($detail['billed_part_amt']) && $detail['billed_part_amt'] > 0) ? number_format_custom($detail['billed_part_amt']) : '0.00' !!}</td>
                                    <td align="right" valign="top">{!! (!empty($detail['ass_amt']) && $detail['ass_amt'] > 0) ? number_format_custom($detail['ass_amt']) : '0.00' !!}</td>
                                </tr>
                            @endif

                            <!-- Sub parts -->
                            @if (!empty($detail['quantities']))
                                @php
                                    $quantityIndex = 0;
                                @endphp
                                @foreach($detail['quantities'] as $quantity)
                                    @if(isset($quantity['category']) && ($quantity['category'] != null || $quantity['category'] !="") && ($quantity['ass_amt'] > 0))
                                        @php
                                            if($quantity['imt_23'] == "Yes") {
                                                $IMTPaintingLabAssRemarksShow = true;
                                            }
                                            $subPartUniqueValue[]= $quantity['gst'];
                                            $GSTBilledPartPerAmt += $quantity['billed_part_amt'];
                                            if($detail['imt_23'] == "Yes") {
                                                if($lossAssessment[0]['MultipleGSTonBilled'] == 0) {
                                                    $SummerAssessmentAmounts['Endorsement'][$lossAssessment[0]['GSTBilledPartPer']] = (isset($SummerAssessmentAmounts['Endorsement'][$lossAssessment[0]['GSTBilledPartPer']]) && !empty($SummerAssessmentAmounts['Endorsement'][$lossAssessment[0]['GSTBilledPartPer']])) ? ($SummerAssessmentAmounts['Endorsement'][$lossAssessment[0]['GSTBilledPartPer']] + $quantity['billed_part_amt']) : $quantity['billed_part_amt'];
                                                } else {
                                                    $SummerAssessmentAmounts['Endorsement'][$quantity['gst']] = (isset($SummerAssessmentAmounts['Endorsement'][$quantity['gst']]) && !empty($SummerAssessmentAmounts['Endorsement'][$quantity['gst']])) ? ($SummerAssessmentAmounts['Endorsement'][$quantity['gst']] + $quantity['billed_part_amt']) : $quantity['billed_part_amt'];
                                                }
                                            } else {
                                                if($lossAssessment[0]['MultipleGSTonBilled'] == 0) {
                                                    $SummerAssessmentAmounts[$quantity['category']][$lossAssessment[0]['GSTBilledPartPer']] = (isset($SummerAssessmentAmounts[$quantity['category']][$lossAssessment[0]['GSTBilledPartPer']]) && !empty($SummerAssessmentAmounts[$quantity['category']][$lossAssessment[0]['GSTBilledPartPer']])) ? ($SummerAssessmentAmounts[$quantity['category']][$lossAssessment[0]['GSTBilledPartPer']] + $quantity['billed_part_amt']) : $quantity['billed_part_amt'];
                                                } else {
                                                    $SummerAssessmentAmounts[$quantity['category']][$quantity['gst']] = (isset($SummerAssessmentAmounts[$quantity['category']][$quantity['gst']]) && !empty($SummerAssessmentAmounts[$quantity['category']][$quantity['gst']]))  ? ($SummerAssessmentAmounts[$quantity['category']][$quantity['gst']] + $quantity['billed_part_amt']) : $quantity['billed_part_amt'];
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td align="left" valign="top" style="font-size: 12px; font-style: italic; padding-left: 5px">{{ $indexCounter }}.{{ ++$quantityIndex }}</td>
                                            <td align="left" valign="top" style="font-size: 12px; font-style: italic; padding-left: 5px">{!! ($quantity['imt_23'] == "Yes") ? '<strong>*</strong>' : '' !!} {{ $quantity['description'] }}</td>
                                            <td align="center" valign="top" style="font-size: 12px; font-style: italic; padding-left: 5px">{{ !empty($quantity['gst']) ? $quantity['gst'] : '0' }}</td>
                                            <td align="center" valign="top" style="font-size: 12px; font-style: italic; padding-left: 5px">{{ !empty($quantity['e_sr_no']) ? $quantity['e_sr_no'] : '0' }}</td>
                                            <td align="center" valign="top" style="font-size: 12px; font-style: italic; padding-left: 5px">{{ $indexCounter }}.{{ $quantityIndex }}</td>
                                            <td align="center" valign="top" style="font-size: 12px; font-style: italic; padding-left: 5px">{{ !empty($quantity['b_sr_no']) ? $quantity['b_sr_no'] : '0' }}</td>
                                            <td align="center" valign="top" style="font-size: 12px; font-style: italic; padding-left: 5px">{{ !empty($quantity['qe']) ? $quantity['qe'] : '-' }}</td>
                                            <td align="center" valign="top" style="font-size: 12px; font-style: italic; padding-left: 5px">{{ !empty($quantity['qa']) ? $quantity['qa'] : '-' }}</td>
                                            <td align="right" valign="top" style="font-size: 12px; font-style: italic; padding-left: 5px">{!! (!empty($quantity['billed_part_amt']) && $quantity['billed_part_amt'] > 0) ? number_format_custom($quantity['billed_part_amt']) : '0.00' !!}</td>
                                            <td align="right" valign="top" style="font-size: 12px; font-style: italic; padding-left: 5px">{!! (!empty($quantity['ass_amt']) && $quantity['ass_amt'] > 0) ? number_format_custom($quantity['ass_amt']) : '0.00' !!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                @endif
                        @endforeach


                        <!-- GST Bifercation -->
                        @php
                            $uniqueGstRates = [];
                            $subUniqueGstRates = [];
                            $subUniqueGstRates = array_values(array_unique($subPartUniqueValue));
                            sort($subUniqueGstRates);
                            $grandTotalBilledAmt = 0;
                            $grandTotalAssessedAmt = 0;
                            $grandTotalGSTBilledAmt = 0;
                            $grandTotalGSTAssessedAmt = 0;
                        @endphp

                        @foreach($subUniqueGstRates as $value)
                            @unless(in_array($value, $uniqueGstRates))
                                @php
                                    $totalBilledAmt = 0;
                                    $totalAssessedAmt = 0;
                                    $totalGSTBilledAmt = 0;
                                    $totalGSTAssessedAmt = 0;
                                @endphp
                                @foreach($alldetails as $detail)

                                    @if($detail['gst'] == $value && empty($detail['quantities']))
                                        @php
                                            $totalBilledAmt += $detail['billed_part_amt'];
                                            $totalAssessedAmt += $detail['ass_amt'];
                                            $totalGSTBilledAmt += !empty($detail['billed_part_amt']) ? $detail['billed_part_amt'] : 0;
                                            $totalGSTAssessedAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                        @endphp
                                    @endif

                                    @if(isset($detail['quantities']) && !empty($detail['quantities']))
                                        @foreach($detail['quantities'] as $partQuantity)
                                            @if($partQuantity['gst'] == $value)
                                                @php
                                                    $totalBilledAmt += $partQuantity['billed_part_amt'];
                                                    $totalAssessedAmt += $partQuantity['ass_amt'];
                                                    $totalGSTBilledAmt += !empty($partQuantity['billed_part_amt']) ? $partQuantity['billed_part_amt'] : 0;
                                                    $totalGSTAssessedAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                <tr>
                                    @if(($lossAssessment[0]['MultipleGSTonBilled'] == 1 && $lossAssessment[0]['GSTBilledPartPer'] !=0) && ($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] !=0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=null)))
                                        <td align="left" valign="top" colspan="8">Total (Parts with GST {{ $value }}%)</td>
                                        <td align="right" valign="top">{{ number_format_custom($totalGSTBilledAmt, 2) }}</td>
                                        <td align="right" valign="top">{{ number_format_custom($totalGSTAssessedAmt, 2) }}</td>
                                    @elseif(($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] !=0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=null)))
                                        <td align="left" valign="top" colspan="8">Total (Parts with GST {{ $value }}%)</td>
                                        <td align="right" valign="top">{{ ($value==0) ? number_format_custom($GSTBilledPartPerAmt, 2) : '0.00' }}</td>
                                        <td align="right" valign="top">{{ number_format_custom($totalGSTAssessedAmt, 2) }}</td>
                                    @elseif(($lossAssessment[0]['MultipleGSTonBilled']==1 && $lossAssessment[0]['GSTBilledPartPer'] !=0))
                                        <td align="left" valign="top" colspan="8">Total (Parts with GST {{ $value }}%)</td>
                                        <td align="right" valign="top">{{ number_format_custom($totalGSTBilledAmt, 2) }}</td>
                                        <td align="right" valign="top">0.00</td>
                                    @elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] =null) && ($lossAssessment[0]['MultipleGSTonBilled']==1 && $lossAssessment[0]['GSTBilledPartPer'] !=0))
                                        <td align="left" valign="top" colspan="8">Total (Parts with GST {{ $value }}%)</td>
                                        <td align="right" valign="top">{{ number_format_custom($totalGSTBilledAmt, 2) }}</td>
                                        <td align="right" valign="top">0.00</td>
                                    @elseif(($lossAssessment[0]['MultipleGSTonBilled']==1 && $lossAssessment[0]['GSTBilledPartPer'] ==0) && ($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] !=0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=null)))
                                        <td align="left" valign="top" colspan="8">Total (Parts with GST {{ $value }}%)</td>
                                        <td align="right" valign="top">0.00</td>
                                        <td align="right" valign="top">{{ number_format_custom($totalGSTAssessedAmt, 2) }}</td>
                                    @elseif(($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] !=0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=null)) || ($lossAssessment[0]['MultipleGSTonBilled']==1 && $lossAssessment[0]['GSTBilledPartPer'] ==0))
                                        <td align="left" valign="top" colspan="8">Total (Parts with GST {{ $value }}%)</td>
                                        <td align="right" valign="top">0.00</td>
                                        <td align="right" valign="top">{{ number_format_custom($totalGSTAssessedAmt, 2) }}</td>
                                    @endif
                                </tr>
                                @php
                                    $grandTotalBilledAmt += $totalBilledAmt;
                                    $grandTotalAssessedAmt += $totalAssessedAmt;
                                    $grandTotalGSTBilledAmt += $totalGSTBilledAmt;
                                    $grandTotalGSTAssessedAmt += $totalGSTAssessedAmt;
                                @endphp
                            @endunless
                        @endforeach

                        <!-- END GST Bifercation -->
                        <tr>
                            <td align="left" valign="top" colspan="8" style="font-weight:bold;">Total</td>
                            <td align="right" valign="top" style="font-weight:bold;">{{ number_format_custom($grandTotalBilledAmt, 2) }}</td>
                            <td align="right" valign="top" style="font-weight:bold;">{{ number_format_custom($grandTotalAssessedAmt, 2) }}</td>
                        </tr>

                        <!-- Start Add GST Part -->
                        @php
                            $grandTotalGSTBilledAmt = 0;
                            $grandTotalGSTAssessedAmt = 0;
                            $totalAmtWithGSTBilled = 0;
                            $totalAmtWithGSTAssessed = 0;
                        @endphp
                        @foreach($subUniqueGstRates as $value)
                            @unless(in_array($value, $uniqueGstRates))
                                @php
                                    $totalAddGSTBilledAmt = 0;
                                    $totalAddGSTAssessedAmt = 0;
                                    $totalGSTBilledAmt = 0;
                                    $totalGSTAssessedAmt = 0;
                                @endphp
                                @foreach($alldetails as $detail)

                                    @if($detail['gst'] == $value && empty($detail['quantities']))
                                        @php
                                            $totalAddGSTBilledAmt += $detail['billed_part_amt'];
                                            $totalAddGSTAssessedAmt += $detail['ass_amt'];
                                            $totalGSTBilledAmt += !empty($detail['billed_part_amt']) ? $detail['billed_part_amt'] : 0;
                                            $totalGSTAssessedAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                        @endphp
                                    @endif

                                    @if(isset($detail['quantities']))
                                        @foreach($detail['quantities'] as $partQuantity)
                                            @if($partQuantity['gst'] == $value)
                                                @php
                                                    $totalAddGSTBilledAmt += $partQuantity['billed_part_amt'];
                                                    $totalAddGSTAssessedAmt += $partQuantity['ass_amt'];
                                                    $totalGSTBilledAmt += !empty($partQuantity['billed_part_amt']) ? $partQuantity['billed_part_amt'] : 0;
                                                    $totalGSTAssessedAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                <tr>
                                    @if(($lossAssessment[0]['MultipleGSTonBilled']==1 && $lossAssessment[0]['GSTBilledPartPer'] !=0) && ($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] !=0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=null)))
                                        <td align="left" valign="top" colspan="8">Add GST @ {{ $value }}%</td>
                                        <td align="right" valign="top">{{ number_format_custom(($totalGSTBilledAmt * $value / 100), 2) }}</td>
                                        <td align="right" valign="top">{{ number_format_custom(($totalGSTAssessedAmt * $value / 100), 2) }}</td>
                                    @elseif(($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] !=0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=null)))
                                        <td align="left" valign="top" colspan="8">Add GST @ {{ $value }}%</td>
                                        <td align="right" valign="top">0.00</td>
                                        <td align="right" valign="top">{{ number_format_custom(($totalGSTAssessedAmt * $value / 100), 2) }}</td>
                                    @elseif(($lossAssessment[0]['MultipleGSTonBilled']==1 && $lossAssessment[0]['GSTBilledPartPer'] !=0))
                                        <td align="left" valign="top" colspan="8">Add GST @ {{ $value }}%</td>
                                        <td align="right" valign="top">{{ number_format_custom(($totalGSTBilledAmt * $value / 100), 2) }}</td>
                                        <td align="right" valign="top">0.00</td>
                                    @elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] =null) && ($lossAssessment[0]['MultipleGSTonBilled']==1 && $lossAssessment[0]['GSTBilledPartPer'] !=0))
                                        <td align="left" valign="top" colspan="8">Add GST @ {{ $value }}%</td>
                                        <td align="right" valign="top">{{ number_format_custom(($totalGSTBilledAmt * $value / 100), 2) }}</td>
                                        <td align="right" valign="top">0.00</td>
                                    @elseif(($lossAssessment[0]['MultipleGSTonBilled']==1 && $lossAssessment[0]['GSTBilledPartPer'] ==0) && ($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] !=0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=null)))
                                        <td align="left" valign="top" colspan="8">Add GST @ {{ $value }}%</td>
                                        <td align="right" valign="top">0.00</td>
                                        <td align="right" valign="top">{{ number_format_custom(($totalGSTAssessedAmt * $value / 100), 2) }}</td>
                                    @elseif(($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] !=0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=null)) || ($lossAssessment[0]['MultipleGSTonBilled']==1 && $lossAssessment[0]['GSTBilledPartPer'] ==0))
                                        <td align="left" valign="top" colspan="8">Add GST @ {{ $value }}%</td>
                                        <td align="right" valign="top">0.00</td>
                                        <td align="right" valign="top">{{ number_format_custom(($totalGSTAssessedAmt * $value / 100), 2) }}</td>
                                    @endif
                                </tr>
                                @php
                                    $grandTotalGSTBilledAmt += $totalGSTBilledAmt;
                                    $grandTotalGSTAssessedAmt += $totalGSTAssessedAmt;
                                    $totalAmtWithGSTBilled += ($totalGSTBilledAmt * $value / 100);
                                    $totalAmtWithGSTAssessed += ($totalGSTAssessedAmt * $value / 100);
                                @endphp
                            @endunless
                        @endforeach

                        <!-- End the GST Part  -->
                        <tr>
                            <td align="left" valign="top" colspan="8" style="font-weight: bold;">Net Total</td>
                            <td align="right" valign="top" style="font-weight: bold;">{{ number_format_custom(($grandTotalGSTBilledAmt + $totalAmtWithGSTBilled), 2) }}</td>
                            <td align="right" valign="top" style="font-weight: bold;">{{ number_format_custom(($totalAmtWithGSTAssessed + $grandTotalGSTAssessedAmt), 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            @if(!empty($IMTPaintingLabAssRemarksShow))
                <table width="100%" border="0" align="left" style="font-size: 12px; font-weight: bold; padding-top: 3px;">
                    <tr>
                        <td>* The Star marks denotes against the IMT-21 allowed parts.</td>
                    </tr>
                </table>
            @endif
            <div style="margin-top: 20px;">
                <div style="border-top: 1px solid #000;"></div>
            </div>

            <!-- Bill Check Labour Report-->
            @include('preview-reports.bill-check.labour-report')
            <!-- End Bill check Labour Report -->

            @endif
            @php
                $netLibility = 0;
                if(isset($lossAssessment[0])){

                   $netLibility = ($lossAssessment[0]['totalass'] ?? 0) -
                                  ($lossAssessment[0]['ImposedClause'] ?? 0) -
                                  ($lossAssessment[0]['CompulsoryDeductable'] ?? 0) -
                                  ($lossAssessment[0]['less_voluntary_excess'] ?? 0) -
                                  ($lossAssessment[0]['SalvageAmt'] ?? 0) +
                                  ($lossAssessment[0]['TowingCharges'] ?? 0) +
                                  ($lossAssessment[0]['additional_towing'] ?? 0);

               }
               $totalAmountWords = convertNumberToWords(number_format_custom($netLibility));
            @endphp

            <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
                <tbody>
                    <tr>
                        <td align="left" valign="top" style="padding-top: 10px; font-weight: bold;">Net Liability</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="padding-top: 10px;">Based On Details Provided Above, The Lowest Liability Under The Subject Policy Of Insurance Works Out To
                            @if(isset($lossAssessment[0]))
                                {{
                                    number_format_custom(
                                        ($lossAssessment[0]['totalass'] ?? 0) -
                                        ($lossAssessment[0]['ImposedClause'] ?? 0) -
                                        ($lossAssessment[0]['CompulsoryDeductable'] ?? 0) -
                                        ($lossAssessment[0]['less_voluntary_excess'] ?? 0) -
                                        ($lossAssessment[0]['SalvageAmt'] ?? 0) +
                                        ($lossAssessment[0]['TowingCharges'] ?? 0) +
                                        ($lossAssessment[0]['additional_towing'] ?? 0),
                                    2)
                                }}
                            @endif
                            <span style="font-weight: bold;">(Rupees {{$totalAmountWords}} Only)</span>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="padding-top: 10px;font-weight: bold;" colspan="3">
                            @if(!empty($signature_img))
                                <img src="{{ asset('public/storage/'.$signature_img) }}" style="width:100px;">
                            @else
                                <p>
                                    <br/>
                                    <br/>
                                    <br/>
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="padding-top:5px;font-weight: bold;" colspan="3">{{ !empty($adminHeaderFooter->name) ? $adminHeaderFooter->name : '-' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="padding-top: 3px;" colspan="3">{{ !empty($adminHeaderFooter->designation) ? $adminHeaderFooter->designation : '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>