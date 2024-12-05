<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bill check format</title>

    <style>
        @font-face {
            font-family: 'verdana';
            font-weight: normal;
            font-style: normal;
            font-variant: normal;
            src: url("fonts/verdana.ttf") format('truetype');
        }

        th, td {
            font-family: verdana !important;
        }

        body {
            font-family: 'verdana', sans-serif;
        }

        .bill table {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .bill thead {
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .bill tbody {
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .bill th {
            padding: 0px 3px;
            font-size: 14px !important;
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .bill td {
            padding: 0px 3px;
            font-size: 14px !important;
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .page-break {
            page-break-before: always;
        }

        .footer-container img {
            page-break-before: always;
        }
        @page{
            margin-header: 5mm;
        }
    </style>
</head>

<body>
<div style="border-bottom: 3px solid #000; text-align:center;">
    <div style="width: 100%;">
        @if ($letter_head_img)
            <img src="{{ asset('public/storage/'.$letter_head_img) }}" style="height:auto;">
        @else
            <p>No letter head image available</p>
        @endif
    </div>
</div>
<div style="width: 100%; font-family: 'Verdana' !important; font-size: 13px;">
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

    <div style="margin-top: 0.5rem; margin-bottom: 1rem;">
        <div style="border-top: 1px solid #000;"></div>
    </div>

    @php
        $alldetails = !empty($lossAssessment[0]['alldetails']) ? json_decode($lossAssessment[0]['alldetails'], true) : [];
        $getUniquePartsAndLabourGST = getUniquePartsAndLabourGST($alldetails);

        $uniqueGstValue = $getUniquePartsAndLabourGST['uniqueGstValue'];
        $uniqueBilledGstValue = $getUniquePartsAndLabourGST['uniqueBilledGstValue'];
        $uniqueLabourGstValue = $getUniquePartsAndLabourGST['uniqueLabourGstValue'];
        $uniqueBilledLabourGstValue = $getUniquePartsAndLabourGST['uniqueBilledLabourGstValue'];
        sort($uniqueGstValue);
        sort($uniqueBilledGstValue);
        $IMTPaintingLabAssRemarksShow = false;

        //Parts Gst Apply Condition Calculation
        $getPartsGstCondition = getPartsGstCondition($lossAssessment[0], $uniqueGstValue);
        $multipleEstGSTonParts = $getPartsGstCondition['MultipleEstPartsGst'];
        $nonMultipleEstGSTonParts = $getPartsGstCondition['nonMultipleEstPartsGst'];
        $multipleAssGSTonParts = $getPartsGstCondition['MultipleAssPartsGst'];
        $nonMultipleAssGSTonParts = $getPartsGstCondition['nonMultipleAssPartsGst'];
        $uniqueGstValue = array_unique(array_merge($multipleEstGSTonParts, $nonMultipleEstGSTonParts, $multipleAssGSTonParts, $nonMultipleAssGSTonParts));
        sort($uniqueGstValue);

        //Billed Gst Apply Condition Calculation
        $getBilledGstCondition = getBilledGstCondition($lossAssessment[0], $uniqueBilledGstValue);
        $MultipleGSTonBilled = $getBilledGstCondition['MultipleGSTonBilled'];
        $noneMultipleGSTonBilled = $getBilledGstCondition['noneMultipleGSTonBilled'];
        $uniqueBilledGstValue = array_unique(array_merge($MultipleGSTonBilled, $noneMultipleGSTonBilled));
        sort($uniqueBilledGstValue);

        $partsAndBilledCommonGstValues = array_unique(array_merge($uniqueGstValue, $uniqueBilledGstValue));
        sort($partsAndBilledCommonGstValues);

        //Billed Labour Gst Apply Condition Calculation
        $getBilledLabourGstCondition = getBilledLabourGstCondition($lossAssessment[0], $uniqueBilledLabourGstValue);
        $MultipleGSTonBilledLabour = $getBilledLabourGstCondition['MultipleGSTonBilledLabour'];
        $noneMultipleGSTonBilledLabour = $getBilledLabourGstCondition['noneMultipleGSTonBilledLabour'];
        $uniqueBilledLabourGstValue = array_unique(array_merge($MultipleGSTonBilledLabour, $noneMultipleGSTonBilledLabour));
        sort($uniqueBilledLabourGstValue);

        $totalPartsMetalAmt = 0;
        $totalPartsRubberPlastAmt = 0;
        $totalPartsGlassAmt = 0;
        $totalPartsFiberAmt = 0;
        $totalCostOfEndorsementPartsAmt = 0;
        $totalCostOfReconditionParts = 0;
        $TotalCostOfPartsAmt = 0;

    @endphp

    @if(!empty($alldetails) && is_array($alldetails))
        @php
            $indexCounter = 0;
            $rightColSpan = 9;
            $gstIndexWiseAmt = [];
            $partSupplementaryTitle = null;
        @endphp
        <div class="bill">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
                <tbody>
                    <tr>
                        <th colspan="3" style="text-align: left; padding-left: 5px; width: 30%; font-weight: bold;">PARTS DETAIL</th>
                        <th colspan="7" style="text-align: left; padding-left: 5px; width: 70%; font-weight: bold;">(PARTS)</th>
                    </tr>

                    <tr>
                        <th align="left" valign="top" rowspan="2" style="width: 6%;">S.No</th>
                        <th align="left" valign="top" rowspan="2" style="width: 18%;">Particulars</th>
                        <th align="center" valign="top" rowspan="2" style="width: 6%;">{!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} %</th>
                        <th align="center" valign="top" colspan="3" style="width: 26%;">Serial No</th>
                        <th align="center" valign="top" rowspan="2" style="width: 5%;">QE</th>
                        <th align="center" valign="top" rowspan="2" style="width: 5%;">QA</th>
                        <th align="right" valign="top" rowspan="2" style="width: 17%;">Billed Amt.</th>
                        <th align="right" valign="top" rowspan="2" style="width: 17%;">Ass. Amt.</th>
                    </tr>
                    <tr>
                        <th>Est.</th>
                        <th align="left">SR</th>
                        <th>Bill</th>
                    </tr>
                    @foreach($alldetails as $detail)
                        @if(!empty($detail['category']))
                            @if($detail['category'] == 'Supplementary' && !empty($detail['description']))
                                @php
                                    $partSupplementaryTitle = $detail['description'];
                                    if(empty($detail['quantities'])) {
                                        continue;
                                    }
                                    $partSupplementaryTitle = null;
                                @endphp
                            @endif
                            @if($detail['ass_amt'] > 0)
                                @if(!empty($partSupplementaryTitle))
                                    <tr>
                                        <td colspan="10" align="left" valign="middle" style="padding: 3px 5px 3px 5px; font-weight: bold;">{{ $partSupplementaryTitle }}</td>
                                    </tr>
                                    @php
                                        $partSupplementaryTitle = null;
                                    @endphp
                                @endif
                                @php
                                    if(empty($IMTPaintingLabAssRemarksShow) && $detail['imt_23'] == "Yes") {
                                        $IMTPaintingLabAssRemarksShow = true;
                                    }
                                    $detailGst = !empty($detail['gst']) ? intval($detail['gst']) : '0';
                                @endphp
                                <tr>
                                    <td align="left" valign="top">{{ ++$indexCounter }}</td>
                                    <td align="left" valign="top">{!! ($detail['imt_23'] == "Yes") ? '<strong>*</strong>' : '' !!} {{ $detail['description'] }}</td>
                                    <td align="center" valign="top">{{ !empty($multipleAssGSTonParts) ? $detailGst : array_sum($nonMultipleAssGSTonParts) }}</td>
                                    <td align="center" valign="top">{{ !empty($detail['e_sr_no']) ? $detail['e_sr_no'] : '0' }}</td>
                                    <td align="left" valign="top">{{ $indexCounter }}</td>
                                    <td align="center" valign="top">{{ !empty($detail['b_sr_no']) ? $detail['b_sr_no'] : '0' }}</td>
                                    <td align="center" valign="top">{{ !empty($detail['qe']) ? $detail['qe'] : '-' }}</td>
                                    <td align="center" valign="top">{{ !empty($detail['qa']) ? $detail['qa'] : '-' }}</td>
                                    <td align="right" valign="top">{!! (!empty($detail['billed_part_amt']) && $detail['billed_part_amt'] > 0) ? number_format_custom($detail['billed_part_amt']) : '-' !!}</td>
                                    <td align="right" valign="top">{!! (!empty($detail['ass_amt']) && $detail['ass_amt'] > 0) ? number_format_custom($detail['ass_amt']) : '-' !!}</td>
                                </tr>
                            @endif
                        @endif
                        @if(!empty($detail['quantities']))
                            @php
                                $quantityIndex = 0;
                            @endphp
                            @foreach($detail['quantities'] as $quantity)
                                @if(!empty($quantity['category']) && $quantity['ass_amt'] > 0)
                                    @php
                                        if(empty($IMTPaintingLabAssRemarksShow) && $quantity['imt_23'] == "Yes") {
                                            $IMTPaintingLabAssRemarksShow = true;
                                        }
                                        $quantityGst = !empty($quantity['gst']) ? intval($quantity['gst']) : '0';
                                    @endphp
                                    <tr>
                                        <td align="left" valign="top" style="font-size: 12px; font-style: italic;">{{ $indexCounter }}.{{ ++$quantityIndex }}</td>
                                        <td align="left" valign="top" style="font-size: 12px; font-style: italic;">{!! ($quantity['imt_23'] == "Yes") ? '<strong>*</strong>' : '' !!} {{ $quantity['description'] }}</td>
                                        <td align="center" valign="top" style="font-size: 12px; font-style: italic;">{{ !empty($multipleAssGSTonParts) ? $quantityGst : array_sum($nonMultipleAssGSTonParts) }}</td>
                                        <td align="center" valign="top" style="font-size: 12px; font-style: italic;">{{ !empty($quantity['e_sr_no']) ? $quantity['e_sr_no'] : '0' }}</td>
                                        <td align="left" valign="top" style="font-size: 12px; font-style: italic;">{{ $indexCounter }}.{{ $quantityIndex }}</td>
                                        <td align="center" valign="top" style="font-size: 12px; font-style: italic;">{{ !empty($quantity['b_sr_no']) ? $quantity['b_sr_no'] : '0' }}</td>
                                        <td align="center" valign="top" style="font-size: 12px; font-style: italic;">{{ !empty($quantity['qe']) ? $quantity['qe'] : '-' }}</td>
                                        <td align="center" valign="top" style="font-size: 12px; font-style: italic;">{{ !empty($quantity['qa']) ? $quantity['qa'] : '-' }}</td>
                                        <td align="right" valign="top" style="font-size: 12px; font-style: italic;">{!! (!empty($quantity['billed_part_amt']) && $quantity['billed_part_amt'] > 0) ? number_format_custom($quantity['billed_part_amt']) : '-' !!}</td>
                                        <td align="right" valign="top" style="font-size: 12px; font-style: italic;">{!! (!empty($quantity['ass_amt']) && $quantity['ass_amt'] > 0) ? number_format_custom($quantity['ass_amt']) : '-' !!}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if(!empty($partsAndBilledCommonGstValues))
                        @php
                            $subTotalGSTABilledAmt = 0;
                            $subTotalGSTAssessedAmt = 0;
                        @endphp
                        @foreach($partsAndBilledCommonGstValues as $rate)
                            @php
                                $totalAssessedAmt = 0;
                                $totalBilledPartAmt = 0;

                                $totalSummeryIMTMetalGstAmt = 0;
                                $totalSummeryMetalGstAmt = 0;
                                $totalSummeryIMTRubberGstAmt = 0;
                                $totalSummeryRubberGstAmt = 0;
                                $totalSummeryGlassGstAmt = 0;
                                $totalSummeryFibreGstAmt = 0;
                                $totalSummeryReconditionGstAmt = 0;
                            @endphp
                            @foreach ($alldetails as $detail)
                                @php
                                    if(!empty($detail['category']) && empty($detail['quantities']) && $detail['ass_amt'] > 0) {
                                        $detailGst = !empty($detail['gst']) ? intval($detail['gst']) : 0;
                                        $assessedAmt = 0;
                                        $billedPartAmt = 0;

                                        $summeryIMTMetalGstAmt = 0;
                                        $summeryMetalGstAmt = 0;
                                        $summeryIMTRubberGstAmt = 0;
                                        $summeryRubberGstAmt = 0;
                                        $summeryGlassGstAmt = 0;
                                        $summeryFibreGstAmt = 0;
                                        $summeryReconditionGstAmt = 0;

                                        $billedPartAmt = ($detail['billed_part_amt'] > 0) ? $detail['billed_part_amt'] : 0;
                                        $assessedAmt = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;

                                        switch ($detail['category']) {
                                            case 'Metal':
                                                if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                                    $summeryIMTMetalGstAmt = $billedPartAmt;
                                                } else {
                                                    $summeryMetalGstAmt = $billedPartAmt;
                                                }
                                                break;
                                            case 'Rubber':
                                                if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                                    $summeryIMTRubberGstAmt = $billedPartAmt;
                                                } else {
                                                    $summeryRubberGstAmt = $billedPartAmt;
                                                }
                                                break;
                                            case 'Glass':
                                                $summeryGlassGstAmt = $billedPartAmt;
                                                break;
                                            case 'Fibre':
                                                $summeryFibreGstAmt = $billedPartAmt;
                                                break;
                                            case 'Recondition':
                                                $summeryReconditionGstAmt = $billedPartAmt;
                                                break;
                                            default:break;
                                        }

                                        if(!empty($multipleAssGSTonParts) && isset($multipleAssGSTonParts[$rate]) && $detailGst==$rate) {
                                            $totalAssessedAmt += $assessedAmt;
                                        } elseif(!empty($nonMultipleAssGSTonParts) && isset($nonMultipleAssGSTonParts[$rate])) {
                                            $totalAssessedAmt += $assessedAmt;
                                        }

                                        if(!empty($MultipleGSTonBilled) && isset($MultipleGSTonBilled[$rate]) && $detailGst == $rate) {
                                            $totalBilledPartAmt += $billedPartAmt;

                                            $totalSummeryIMTMetalGstAmt += $summeryIMTMetalGstAmt;
                                            $totalSummeryMetalGstAmt += $summeryMetalGstAmt;
                                            $totalSummeryIMTRubberGstAmt += $summeryIMTRubberGstAmt;
                                            $totalSummeryRubberGstAmt += $summeryRubberGstAmt;
                                            $totalSummeryGlassGstAmt += $summeryGlassGstAmt;
                                            $totalSummeryFibreGstAmt += $summeryFibreGstAmt;
                                            $totalSummeryReconditionGstAmt += $summeryReconditionGstAmt;

                                        } elseif(!empty($noneMultipleGSTonBilled) && isset($noneMultipleGSTonBilled[$rate])) {
                                            $totalBilledPartAmt += $billedPartAmt;

                                            $totalSummeryIMTMetalGstAmt += $summeryIMTMetalGstAmt;
                                            $totalSummeryMetalGstAmt += $summeryMetalGstAmt;
                                            $totalSummeryIMTRubberGstAmt += $summeryIMTRubberGstAmt;
                                            $totalSummeryRubberGstAmt += $summeryRubberGstAmt;
                                            $totalSummeryGlassGstAmt += $summeryGlassGstAmt;
                                            $totalSummeryFibreGstAmt += $summeryFibreGstAmt;
                                            $totalSummeryReconditionGstAmt += $summeryReconditionGstAmt;
                                        }
                                    }

                                    if(!empty($detail['quantities'])) {
                                        foreach ($detail['quantities'] as $quantity) {
                                            if(!empty($quantity['category']) && $quantity['ass_amt'] > 0) {
                                                $quantityGst = !empty($quantity['gst']) ? intval($quantity['gst']) : 0;
                                                $quantityAssessedAmt = (!empty($quantity['category']) && $quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                $quantityBilledPartAmt = ($quantity['billed_part_amt'] > 0) ? $quantity['billed_part_amt'] : 0;

                                                $summeryQuantitiesIMTMetalGstAmt = 0;
                                                $summeryQuantitiesMetalGstAmt = 0;
                                                $summeryQuantitiesIMTRubberGstAmt = 0;
                                                $summeryQuantitiesRubberGstAmt = 0;
                                                $summeryQuantitiesGlassGstAmt = 0;
                                                $summeryQuantitiesFibreGstAmt = 0;
                                                $summeryQuantitiesReconditionGstAmt = 0;

                                                switch ($quantity['category']) {
                                                    case 'Metal':
                                                        if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                                            $summeryQuantitiesIMTMetalGstAmt = $quantityBilledPartAmt;
                                                        } else {
                                                            $summeryQuantitiesMetalGstAmt = $quantityBilledPartAmt;
                                                        }
                                                        break;
                                                    case 'Rubber':
                                                        if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                                            $summeryQuantitiesIMTRubberGstAmt = $quantityBilledPartAmt;
                                                        } else {
                                                            $summeryQuantitiesRubberGstAmt = $quantityBilledPartAmt;
                                                        }
                                                        break;
                                                    case 'Glass':
                                                        $summeryQuantitiesGlassGstAmt = $quantityBilledPartAmt;
                                                        break;
                                                    case 'Fibre':
                                                        $summeryQuantitiesFibreGstAmt = $quantityBilledPartAmt;
                                                        break;
                                                    case 'Recondition':
                                                        $summeryQuantitiesReconditionGstAmt = $quantityBilledPartAmt;
                                                        break;
                                                    default:break;
                                                }

                                                if(!empty($multipleAssGSTonParts) && isset($multipleAssGSTonParts[$rate]) && $quantityGst==$rate) {
                                                    $totalAssessedAmt += $quantityAssessedAmt;
                                                } elseif(!empty($nonMultipleAssGSTonParts) && isset($nonMultipleAssGSTonParts[$rate])) {
                                                    $totalAssessedAmt += $quantityAssessedAmt;
                                                }

                                                if(!empty($MultipleGSTonBilled) && isset($MultipleGSTonBilled[$rate]) && $quantityGst == $rate) {
                                                    $totalBilledPartAmt += $quantityBilledPartAmt;

                                                    $totalSummeryIMTMetalGstAmt += $summeryQuantitiesIMTMetalGstAmt;
                                                    $totalSummeryMetalGstAmt += $summeryQuantitiesMetalGstAmt;
                                                    $totalSummeryIMTRubberGstAmt += $summeryQuantitiesIMTRubberGstAmt;
                                                    $totalSummeryRubberGstAmt += $summeryQuantitiesRubberGstAmt;
                                                    $totalSummeryGlassGstAmt += $summeryQuantitiesGlassGstAmt;
                                                    $totalSummeryFibreGstAmt += $summeryQuantitiesFibreGstAmt;
                                                    $totalSummeryReconditionGstAmt += $summeryQuantitiesReconditionGstAmt;

                                                } elseif(!empty($noneMultipleGSTonBilled) && isset($noneMultipleGSTonBilled[$rate])) {
                                                    $totalBilledPartAmt += $quantityBilledPartAmt;

                                                    $totalSummeryIMTMetalGstAmt += $summeryQuantitiesIMTMetalGstAmt;
                                                    $totalSummeryMetalGstAmt += $summeryQuantitiesMetalGstAmt;
                                                    $totalSummeryIMTRubberGstAmt += $summeryQuantitiesIMTRubberGstAmt;
                                                    $totalSummeryRubberGstAmt += $summeryQuantitiesRubberGstAmt;
                                                    $totalSummeryGlassGstAmt += $summeryQuantitiesGlassGstAmt;
                                                    $totalSummeryFibreGstAmt += $summeryQuantitiesFibreGstAmt;
                                                    $totalSummeryReconditionGstAmt += $summeryQuantitiesReconditionGstAmt;
                                                }
                                            }
                                        }
                                    }
                                @endphp
                            @endforeach
                            @php
                                $gstIndexWiseAmt[$rate]['total_billed_amt'] = $totalBilledPartAmt;
                                $gstIndexWiseAmt[$rate]['total_ass_amt'] = $totalAssessedAmt;
                                $subTotalGSTABilledAmt += $totalBilledPartAmt;
                                $subTotalGSTAssessedAmt += $totalAssessedAmt;
                                //Gst Calculation  For parts
                                $totalSummeryIMTMetalGst = ($totalSummeryIMTMetalGstAmt > 0) ? (($totalSummeryIMTMetalGstAmt * $rate) / 100) : 0;
                                $totalSummeryMetalGst = ($totalSummeryMetalGstAmt > 0) ? (($totalSummeryMetalGstAmt * $rate) / 100) : 0;
                                $totalSummeryIMTRubberGst = ($totalSummeryIMTRubberGstAmt > 0) ? (($totalSummeryIMTRubberGstAmt * $rate) / 100) : 0;
                                $totalSummeryRubberGst = ($totalSummeryRubberGstAmt > 0) ? (($totalSummeryRubberGstAmt * $rate) / 100) : 0;
                                $totalSummeryGlassGst = ($totalSummeryGlassGstAmt > 0) ? (($totalSummeryGlassGstAmt * $rate) / 100) : 0;
                                $totalSummeryFibreGst = ($totalSummeryFibreGstAmt > 0) ? (($totalSummeryFibreGstAmt * $rate) / 100) : 0;
                                $totalSummeryReconditionGst = ($totalSummeryReconditionGstAmt > 0) ? (($totalSummeryReconditionGstAmt * $rate) / 100) : 0;

                                $totalPartsMetalAmtTemp = ($totalSummeryMetalGstAmt + $totalSummeryMetalGst);
                                $totalPartsRubberPlastAmtTemp = ($totalSummeryRubberGstAmt + $totalSummeryRubberGst);
                                $totalPartsGlassAmtTemp = ($totalSummeryGlassGstAmt + $totalSummeryGlassGst);
                                $totalPartsFiberAmtTemp = ($totalSummeryFibreGstAmt + $totalSummeryFibreGst);
                                $totalCostOfEndorsementPartsAmtTemp = ($totalSummeryIMTMetalGstAmt + $totalSummeryIMTRubberGstAmt + $totalSummeryIMTMetalGst + $totalSummeryIMTRubberGst);
                                $totalCostOfReconditionPartsTemp = ($totalSummeryReconditionGstAmt + $totalSummeryReconditionGst);

                                $totalPartsMetalAmt += $totalPartsMetalAmtTemp;
                                $totalPartsRubberPlastAmt += $totalPartsRubberPlastAmtTemp;
                                $totalPartsGlassAmt += $totalPartsGlassAmtTemp;
                                $totalPartsFiberAmt += $totalPartsFiberAmtTemp;
                                $totalCostOfEndorsementPartsAmt += $totalCostOfEndorsementPartsAmtTemp;
                                $totalCostOfReconditionParts += $totalCostOfReconditionPartsTemp;
                                $TotalCostOfPartsAmt += ($totalPartsMetalAmtTemp + $totalPartsRubberPlastAmtTemp + $totalPartsGlassAmtTemp + $totalPartsFiberAmtTemp + $totalCostOfEndorsementPartsAmtTemp);
                            @endphp
                            <tr>
                                <td align="left" valign="top" colspan="8">Total (Parts with {!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} {{ $rate }}%)</td>
                                <td align="right" valign="top">{{ number_format_custom($totalBilledPartAmt, 2) }}</td>
                                <td align="right" valign="top">{{ number_format_custom($totalAssessedAmt, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td align="left" valign="top" colspan="8" style="font-weight:bold;">Total</td>
                            <td align="right" valign="top" style="font-weight:bold;">{{ number_format_custom($subTotalGSTABilledAmt, 2) }}</td>
                            <td align="right" valign="top" style="font-weight:bold;">{{ number_format_custom($subTotalGSTAssessedAmt, 2) }}</td>
                        </tr>
                        @php
                            $netTotalBilledAmt = 0;
                            $netTotalAssAmt = 0;
                        @endphp
                        @foreach($gstIndexWiseAmt as $gst_rate => $item)
                            @php
                                $billedGstAmt = ($item['total_billed_amt'] > 0) ? (($item['total_billed_amt'] * $gst_rate) / 100) : 0;
                                $assGstAmt = ($item['total_ass_amt'] > 0) ? (($item['total_ass_amt'] * $gst_rate) / 100) : 0;
                                $netTotalBilledAmt += ($billedGstAmt + $item['total_billed_amt']);
                                $netTotalAssAmt += ($assGstAmt + $item['total_ass_amt']);
                            @endphp
                            <tr>
                                <td align="left" valign="top" colspan="8">Add {!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} @ {{ $gst_rate }}%</td>
                                <td align="right" valign="top">{{ number_format_custom($billedGstAmt) }}</td>
                                <td align="right" valign="top">{{ number_format_custom($assGstAmt) }}</td>
                            </tr>
                        @endforeach
                        <!-- End the GST Part  -->
                        <tr>
                            <td align="left" valign="top" colspan="8" style="font-weight: bold;">Net Total</td>
                            <td align="right" valign="top" style="font-weight: bold;">{{ number_format_custom($netTotalBilledAmt) }}</td>
                            <td align="right" valign="top" style="font-weight: bold;">{{ number_format_custom($netTotalAssAmt) }}</td>
                        </tr>
                    @endif
                    @if(!empty($IMTPaintingLabAssRemarksShow))
                        <tr>
                            <td align="left" valign="center" colspan="10" style="font-weight: bold; padding: 5px;">* The Star marks denotes against the IMT-21 allowed parts.</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div style="margin-top: 1rem; margin-bottom: 1rem;">
                <div style="border-top: 1px solid #000;"></div>
            </div>

            <div>
                <div style="padding: 0px 3px; font-weight: bold;">LABOUR</div>
            </div>

            <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="font-size: 16px; font-family: Verdana, sans-serif;">
                <tbody>
                    <tr>
                        <th align="left" valign="top" style="width: 10%; font-size: 15px;">S.No</th>
                        <th align="left" valign="top" style="width: 40%; font-size: 15px;">Description of Labour</th>
                        <th align="center" valign="top" style="width: 8%; text-align: left;">{!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} %</th>
                        <th align="center" valign="top" style="width: 16%; font-size: 15px;">Bill Serial No.</th>
                        <th align="center" valign="top" style="width: 13%; font-size: 15px;">Billed Amt.</th>
                        <th align="center" valign="top" style="width: 13%; font-size: 15px;">Ass. Amt.</th>
                    </tr>
                    @php
                        $indexCounter = 0;
                        $labourSupplementaryTitle = null;
                    @endphp
                    @foreach($alldetails as $detail)
                        @if($detail['category'] == 'Supplementary' && !empty($detail['description']))
                            @php
                                $labourSupplementaryTitle = $detail['description'];
                                $continue = ($detail['ass_lab'] > 0) ? false : true;
                                if(!empty($detail['quantities'])) {
                                    $labourSupplementaryTitle = null;
                                }
                                if(!empty($continue)) {
                                    continue;
                                }
                            @endphp
                        @endif
                        @if($detail['ass_lab'] > 0)
                            @php
                                $detailLabourGst = !empty($detail['gst']) ? intval($detail['gst']) : '0'
                            @endphp
                            @if(!empty($labourSupplementaryTitle))
                                <tr>
                                    <td colspan="6" align="left" valign="middle" style="padding: 3px 5px 3px 5px; font-weight: bold;">{{ $labourSupplementaryTitle }}</td>
                                </tr>
                                @php
                                    $labourSupplementaryTitle = null;
                                @endphp
                            @endif
                            <tr>
                                <td align="left" valign="top" style="font-size: 13px;">{{ ++$indexCounter }}</td>
                                <td align="left" valign="top" style=" font-size: 13px;">{{ !empty($detail['labour_type']) ? $detail['labour_type'].' of '.$detail['description'] : $detail['description'] }}</td>
                                <td align="center" valign="top" style="font-size: 13px;">{{ !empty(!empty($MultipleGSTonBilledLabour)) ? $detailLabourGst : array_sum($noneMultipleGSTonBilledLabour) }}</td>
                                <td align="center" valign="top" style="font-size: 13px;">{{ !empty($detail['b_sr_no']) ? $detail['b_sr_no'] : '' }}</td>
                                <td align="right" valign="top" style="font-size: 13px;">{{ ($detail['billed_lab_amt'] > 0) ? number_format_custom($detail['billed_lab_amt']) : '-' }}</td>
                                <td align="right" valign="top" style="font-size: 13px;">{{ ($detail['ass_lab'] > 0) ? number_format_custom($detail['ass_lab']) : '-' }}</td>
                            </tr>
                        @endif
                        @if(!empty($detail['quantities']))
                            @php
                                $quantityIndex = 0;
                            @endphp
                            @foreach($detail['quantities'] as $quantity)
                                @if($quantity['ass_lab'] > 0)
                                    @php
                                        $quantityLabourGst = !empty($quantity['gst']) ? intval($quantity['gst']) : '0'
                                    @endphp
                                    <tr>
                                        <td align="left" valign="top" style="font-size: 12px; font-style: italic;">{{ $indexCounter }}.{{ ++$quantityIndex }}</td>
                                        <td align="left" valign="top" style="font-size: 12px; font-style: italic;">{{ !empty($quantity['labour_type']) ? $quantity['labour_type'].' of '.$quantity['description'] : $quantity['description'] }}</td>
                                        <td align="center" valign="top" style="font-size: 12px; font-style: italic;">{{ !empty(!empty($MultipleGSTonBilledLabour)) ? $quantityLabourGst : array_sum($noneMultipleGSTonBilledLabour) }}</td>
                                        <td align="center" valign="top" style="font-size: 12px; font-style: italic;">{{ !empty($quantity['b_sr_no']) ? $quantity['b_sr_no'] : '' }}</td>
                                        <td align="right" valign="top" style="font-size: 12px; font-style: italic;">{{ ($quantity['billed_lab_amt'] > 0) ? number_format_custom($quantity['billed_lab_amt']) : '-' }}</td>
                                        <td align="right" valign="top" style="font-size: 12px; font-style: italic;">{{ ($quantity['ass_lab'] > 0) ? number_format_custom($quantity['ass_lab']) : '-' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    @php
                        $subTotalBilledAmtWithGst = 0;
                        $subTotalBilledPaintingAmtWithGst = 0;
                        $subTotalAssAmtWithGst = 0;
                    @endphp
                    @if(!empty($uniqueBilledLabourGstValue))
                        @php
                            $subTotalBilledAmt = 0;
                            $subTotalBilledPaintingAmt = 0;
                            $subTotalAssAmt = 0;
                            $gstIndexWise = [];
                        @endphp
                        @foreach($uniqueBilledLabourGstValue as $rate)
                            @php
                                $totalBilledAmt = 0;
                                $totalBilledPaintingAmt = 0;
                                $totalAssAmt = 0;
                                foreach ($alldetails as $detail) {
                                    $detailGst = !empty($detail['gst']) ? intval($detail['gst']) : 0;
                                    if(empty($detail['quantities']) && ($detail['ass_lab'] > 0 || $detail['billed_paint_amt'] > 0)) {
                                        if(!empty($MultipleGSTonBilledLabour) && isset($MultipleGSTonBilledLabour[$rate]) && $detailGst==$rate) {
                                            $totalBilledAmt += ($detail['billed_lab_amt'] > 0 && $detail['ass_lab'] > 0) ? $detail['billed_lab_amt'] : 0;
                                            $totalAssAmt += ($detail['ass_lab'] > 0) ? $detail['ass_lab'] : 0;
                                            $totalBilledPaintingAmt += ($detail['billed_paint_amt'] > 0) ? $detail['billed_paint_amt'] : 0;
                                        } elseif(!empty($noneMultipleGSTonBilledLabour) && isset($noneMultipleGSTonBilledLabour[$rate])) {
                                            $totalBilledAmt += ($detail['billed_lab_amt'] > 0 && $detail['billed_paint_amt'] > 0) ? $detail['billed_lab_amt'] : 0;
                                            $totalAssAmt += ($detail['ass_lab'] > 0) ? $detail['ass_lab'] : 0;
                                            $totalBilledPaintingAmt += ($detail['billed_paint_amt'] > 0) ? $detail['billed_paint_amt'] : 0;
                                        }
                                    }
                                    if(!empty($detail['quantities'])) {
                                        foreach ($detail['quantities'] as $quantities) {
                                            $quantitiesGst = !empty($quantities['gst']) ? intval($quantities['gst']) : 0;
                                            if($quantities['ass_lab'] > 0 || $quantities['billed_paint_amt'] > 0){
                                                if(!empty($MultipleGSTonBilledLabour) && isset($MultipleGSTonBilledLabour[$rate]) && $quantitiesGst==$rate) {
                                                    $totalBilledAmt += ($quantities['billed_lab_amt'] > 0 && $quantities['ass_lab'] > 0) ? $quantities['billed_lab_amt'] : 0;
                                                    $totalAssAmt += ($quantities['ass_lab'] > 0) ? $quantities['ass_lab'] : 0;
                                                    $totalBilledPaintingAmt += ($quantities['billed_paint_amt'] > 0) ? $quantities['billed_paint_amt'] : 0;
                                                } elseif(!empty($noneMultipleGSTonBilledLabour) && isset($noneMultipleGSTonBilledLabour[$rate])) {
                                                    $totalBilledAmt += ($quantities['billed_lab_amt'] > 0 && $quantities['ass_lab'] > 0) ? $quantities['billed_lab_amt'] : 0;
                                                    $totalAssAmt += ($quantities['ass_lab'] > 0) ? $quantities['ass_lab'] : 0;
                                                    $totalBilledPaintingAmt += ($quantities['billed_paint_amt'] > 0) ? $quantities['billed_paint_amt'] : 0;
                                                }
                                            }
                                        }
                                    }
                                }

                                $subTotalBilledAmt += $totalBilledAmt;
                                $subTotalBilledPaintingAmt += $totalBilledPaintingAmt;
                                $subTotalAssAmt += $totalAssAmt;
                                $gstIndexWise[$rate]['subTotalBilledAmt'] = $totalBilledAmt;
                                $gstIndexWise[$rate]['subTotalBilledPaintingAmt'] = $totalBilledPaintingAmt;
                                $gstIndexWise[$rate]['subTotalAssAmt'] = $totalAssAmt;
                            @endphp
                            <tr>
                                <td align="left" valign="top" colspan="4">Total (Labour with {!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} {{ $rate }}%)</td>
                                <td align="right" valign="top">{{ number_format_custom($totalBilledAmt) }}</td>
                                <td align="right" valign="top">{{ number_format_custom($totalAssAmt) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td align="left" valign="top" colspan="4" style="font-size: 13px; font-weight: bold;">Total Labour Charges</td>
                            <td align="right" valign="top" style="font-size: 13px; font-weight: bold;">{{ number_format_custom($subTotalBilledAmt) }}</td>
                            <td align="right" valign="top" style="font-size: 13px; font-weight: bold;">{{ number_format_custom($subTotalAssAmt) }}</td>
                        </tr>
                        @if(!empty($gstIndexWise))
                            @foreach($gstIndexWise as $gst => $item)
                                @php
                                    $billedGstAmt = ($item['subTotalBilledAmt'] > 0) ? (($item['subTotalBilledAmt'] * $gst) / 100) : 0;
                                    $billedPaintingGstAmt = ($item['subTotalBilledPaintingAmt'] > 0) ? (($item['subTotalBilledPaintingAmt'] * $gst) / 100) : 0;
                                    $assGstAmt = ($item['subTotalAssAmt'] > 0) ? (($item['subTotalAssAmt'] * $gst) / 100) : 0;
                                    $subTotalBilledAmtWithGst += ($item['subTotalBilledAmt'] + $billedGstAmt);
                                    $subTotalBilledPaintingAmtWithGst += ($item['subTotalBilledPaintingAmt'] + $billedPaintingGstAmt);
                                    $subTotalAssAmtWithGst += ($item['subTotalAssAmt'] + $assGstAmt);
                                @endphp
                                <tr>
                                    <td align="left" valign="top" colspan="4" style="font-size: 13px;">Add {!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} @ {{ $gst }}%</td>
                                    <td align="right" valign="top" style="font-size: 14px;">{{ number_format_custom($billedGstAmt) }}</td>
                                    <td align="right" valign="top" style="font-size: 14px;">{{ number_format_custom($assGstAmt) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td align="left" valign="top" colspan="4" style="font-weight: bold; font-size: 13px;">Total labour charges with Taxes</td>
                                <td align="right" valign="top" style="font-weight: bold; font-size: 13px;">{{ number_format_custom($subTotalBilledAmtWithGst) }}</td>
                                <td align="right" valign="top" style="font-weight: bold; font-size: 13px;">{{ number_format_custom($subTotalAssAmtWithGst) }}</td>
                            </tr>
                        @endif
                    @endif
                    @php
                        $totalPaintingLabourwithTaxes = (($lossAssessment[0]['totalPainting'] - $lossAssessment[0]['depAmtPainting']) + $lossAssessment[0]['gstPainting']);
                        $totalEndorsementPaintingAmt = (($lossAssessment[0]['totalPaintingIMT'] - $lossAssessment[0]['depAmtPaintingIMT']) + $lossAssessment[0]['gstPaintingIMT']);
                        $netBilledAmt = ($subTotalBilledAmtWithGst + $subTotalBilledPaintingAmtWithGst);
                        $netAssAmt = ($subTotalAssAmtWithGst + $totalPaintingLabourwithTaxes + $totalEndorsementPaintingAmt);
                    @endphp
                    <tr>
                        <td align="left" valign="top" colspan="4" style="font-weight: bold; font-size: 14px;">Painting Labour with Taxes</td>
                        <td align="right" valign="top" style="font-weight: bold; font-size: 13px;">{{ number_format_custom($subTotalBilledPaintingAmtWithGst) }}</td>
                        <td align="right" valign="top" style="font-weight: bold; font-size: 13px;">{{ number_format_custom($totalPaintingLabourwithTaxes) }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" colspan="5" style="font-weight: bold; font-size: 13px;">Total labour charges Endorsement Items with Taxes</td>
                        <td align="right" valign="top" style="font-weight: bold; font-size: 13px;">{{ number_format_custom($totalEndorsementPaintingAmt) }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" colspan="4" style="font-weight: bold; font-size: 14px;">Net Labour Amount</td>
                        <td align="right" valign="top" style="font-weight: bold; font-size: 13px;">{{ number_format_custom($netBilledAmt) }}</td>
                        <td align="right" valign="top" style="font-weight: bold; font-size: 13px;">{{ number_format_custom($netAssAmt) }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 1rem; margin-bottom: 1rem;">
                <div style="border-top: 1px solid #000;"></div>
            </div>

            <table width="100%" cellpadding="2" cellspacing="0" border="0" align="center">
                <tbody>
                    <tr>
                        <th align="left" valign="middle" colspan="3" style="font-size: 16px;">SUMMARY OF ASSESSMENT</th>
                    </tr>
                    <tr>
                        <th align="left" valign="top">Particulars</th>
                        <th align="right" valign="top">Billed</th>
                        <th align="right" valign="top">Assessed</th>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Parts (Metal)</td>
                        <td align="right" valign="top">{{ number_format_custom($totalPartsMetalAmt) }}</td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['partMetalAssamount'] > 0) ? number_format_custom($lossAssessment[0]['partMetalAssamount']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Parts (Rubber / Plastic)</td>
                        <td align="right" valign="top">{{ number_format_custom($totalPartsRubberPlastAmt) }}</td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['partRubberAssamount'] > 0) ? number_format_custom($lossAssessment[0]['partRubberAssamount']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Parts (Glass)</td>
                        <td align="right" valign="top">{{ number_format_custom($totalPartsGlassAmt) }}</td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['partGlassAssamount'] > 0) ? number_format_custom($lossAssessment[0]['partGlassAssamount']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Parts (Fibre)</td>
                        <td align="right" valign="top">{{ number_format_custom($totalPartsFiberAmt) }}</td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['partFibreAssamount'] > 0) ? number_format_custom($lossAssessment[0]['partFibreAssamount']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Total Cost of Endorsement Parts</td>
                        <td align="right" valign="top">{{ number_format_custom($totalCostOfEndorsementPartsAmt) }}</td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['totalendoresmentAss'] > 0) ? number_format_custom($lossAssessment[0]['totalendoresmentAss']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="font-weight: bold;">Total Cost of Parts</td>
                        <td align="right" valign="top" style="font-weight: bold;">{{ number_format_custom($TotalCostOfPartsAmt) }}</td>
                        <td align="right" valign="top" style="font-weight: bold;">{{ ($lossAssessment[0]['totalassparts'] > 0) ? number_format_custom($lossAssessment[0]['totalassparts']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Total Cost of Recondition Parts</td>
                        <td align="right" valign="top">{{ number_format_custom($totalCostOfReconditionParts) }}</td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['totalreconditionAss'] > 0) ? number_format_custom($lossAssessment[0]['totalreconditionAss']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Net Labour Charges</td>
                        <td align="right" valign="top">{{ number_format_custom($netBilledAmt) }}</td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['netlabourAss'] > 0) ? number_format_custom($lossAssessment[0]['netlabourAss']) : '0.00'  }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">&nbsp;</td>
                        <td align="right" valign="top" style="font-weight: bold;">{{ number_format_custom(($netBilledAmt + $TotalCostOfPartsAmt + $totalCostOfReconditionParts)) }}</td>
                        <td align="right" valign="top" style="font-weight: bold;">{{ !empty($lossAssessment[0]['totalass']) ? number_format_custom($lossAssessment[0]['totalass']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Less Imposed Clause <span style="padding-left: 200px;">(-)</span></td>
                        <td align="right" valign="top">&nbsp;</td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['ImposedClause'] > 0) ? number_format_custom($lossAssessment[0]['ImposedClause']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Compulsory Deductible <span style="padding-left: 185px;">(-)</span></td>
                        <td align="right" valign="top">&nbsp;</td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['CompulsoryDeductable'] > 0) ? number_format_custom($lossAssessment[0]['CompulsoryDeductable']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Less Voluntary Excess <span style="padding-left: 185px;">(-)</span></td>
                        <td align="right" valign="top">&nbsp;</td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['less_voluntary_excess'] > 0) ? number_format_custom($lossAssessment[0]['less_voluntary_excess']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Less Salvage <span style="padding-left: 254px;">(-)</span></td>
                        <td align="right" valign="top">&nbsp;</td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['SalvageAmt'] > 0) ? number_format_custom($lossAssessment[0]['SalvageAmt']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Add Towing Charges <span style="padding-left: 200px;">(+)</span></td>
                        <td align="right" valign="top"></td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['TowingCharges'] > 0) ? number_format_custom($lossAssessment[0]['TowingCharges']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Add Additional Towing Charges <span style="padding-left: 200px;">(+)</span></td>
                        <td align="right" valign="top"></td>
                        <td align="right" valign="top">{{ ($lossAssessment[0]['additional_towing'] > 0) ? number_format_custom($lossAssessment[0]['additional_towing']) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="font-weight: bold;">Net Loss </td>
                        <td align="right" valign="top" style="font-weight: bold;">{{ number_format_custom(($netBilledAmt + $TotalCostOfPartsAmt + $totalCostOfReconditionParts)) }}</td>
                        <td align="right" valign="top" style="font-weight: bold;">{{ ($lossAssessment[0]['alltotalass'] > 0) ? number_format_custom($lossAssessment[0]['alltotalass']) : '0.00'  }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            <div style="border-top: 0px solid #000;"></div>
        </div>
        @php
            $alltotalass = ($lossAssessment[0]['alltotalass'] > 0) ? number_format_custom($lossAssessment[0]['alltotalass']) : 0;
            $totalAmountWords = convertNumberToWords($alltotalass);
        @endphp
        <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
            <tbody>
            <tr>
                <td align="left" valign="top" style="padding-top: 10px; font-weight: bold;">Net Liability</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 10px;">Based On Details Provided Above, The Lowest Liability Under The Subject Policy Of Insurance Works Out To ₹{{ number_format_custom($alltotalass)  }}<span style="font-weight: bold;">(Rupees {{ $totalAmountWords }} Only)</span></td>
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
    @endif
</div>
</body>

</html>