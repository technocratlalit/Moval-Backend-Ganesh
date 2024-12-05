<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MOTOR CLAIM SCRUTINY</title>
    <style type="text/css" media="all">
        @font-face {
            font-family: 'verdana';
            font-weight: normal;
            font-style: normal;
            font-variant: normal;
            src: url("fonts/verdana.ttf") format('truetype');
        }
        body {
            font-family: 'verdana', sans-serif;
        }
        table {
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12px !important;
        }

        #design td {
            border: solid 1px #000;
            border-style: none solid solid none;
            padding: 10px;
        }

        #design tr:first-child td {
            border-top-style: solid;
        }

        #design tr td:first-child {
            border-left-style: solid;
        }
    </style>
</head>

<body>

    <div style="font-family: 'Verdana' !important; margin: 0px -20px;">
        @if (!empty($letter_head_img))
            <div>
                <img src="{{ asset('/public/storage/'.$letter_head_img) }}" style="width:auto;">
            </div>
        @endif

        <div>
            <div style="border-top: 3px solid #000;"></div>
        </div>
        <div>
            <table id="design" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top: 20px;">
                <tbody>
                    <tr>
                        <td align="center" valign="top" style="font-weight: bold; text-decoration: underline; border-top: 1px solid #000; border-left: 1px solid #000; padding: 0px; font-size: 19px;">MOTOR CLAIM SCRUTINY</td>
                    </tr>
                </tbody>
            </table>

            <table id="design" width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
                <tbody>
                    <tr>
                        <td align="left" valign="top" style="width:15%; border-left: 1px solid #000; padding: 0px 3px;">
                            Claim No.</td>
                        <td align="center" valign="top" style="width:2%; padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="width:26%; padding: 0px 3px;">{{ !empty($policyDetails->claim_no) ? $policyDetails->claim_no : '' }}</td>
                        <td align="left" valign="top" style="width:28%; padding: 0px 3px;">Policy No.</td>
                        <td align="center" valign="top" style="width:2%; padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="width:25%; padding: 0px 3px;">{{ !empty($policyDetails->policy_no) ? $policyDetails->policy_no : '' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">Policy Period
                        </td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->policy_valid_from) ? \Carbon\Carbon::parse($policyDetails->policy_valid_from)->format('d/m/Y') : '' }} To {{ !empty($policyDetails->policy_valid_to) ? \Carbon\Carbon::parse($policyDetails->policy_valid_to)->format('d/m/Y') : '' }}</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Driving License</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->dl_no) ? $policyDetails->dl_no : '' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">Spot Surveyor
                        </td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->spot_survey_by) ? $policyDetails->spot_survey_by : '' }}</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Final Surveyor</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->final_surveyor) ? $policyDetails->final_surveyor : '' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">R.I. Surveyor
                        </td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->final_surveyor) ? $policyDetails->final_surveyor : '' }}</td>
                        <td align="left" valign="top" colspan="2" style="padding: 0px 3px;">Whether Accident Within Five Days  </td>
                        <td align="left" valign="top" style="padding: 0px;">
                            <table width="100%" id="design" cellpadding="0" cellspacing="0" border="0" align="center">
                                <tbody>
                                    <tr>
                                        <td align="center" valign="top" style="border-style: none solid none none; padding: 0px 3px;">Yes</td>
                                        <td align="center" valign="top" style="border:none; padding: 0px 3px;">No</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">New/Renewal
                        </td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;"></td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Rec. Prem. Dt.-</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;"></td>
                    </tr>
                </tbody>
            </table>

            <table id="design" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="Margin-top:5px;">
                <tbody>
                    <tr>
                        <td align="left" valign="top" colspan="4"
                            style="width:40%; font-weight: bold; border-left: 1px solid #000; border-top: 1px solid #000;  padding: 5px;">Particulars
                            As Per Vehicular Document</td>
                        <td align="left" valign="top" colspan="4" style="width:60%; font-weight: bold; border-top: 1px solid #000;  padding: 5px;">
                            Particulars As Per Vehicular Policy</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="width:5%; border-left: 1px solid #000; padding: 0px 3px;">1
                        </td>
                        <td align="left" valign="top" style="width:18%; padding: 0px 3px;">Owner</td>
                        <td align="center" valign="top" style="width:2%; padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="width:20%; padding: 0px 3px;">{{ !empty($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</td>

                        <td align="center" valign="top" style="width:5%; padding: 0px 3px;">1</td>
                        <td align="left" valign="top" style="width:22%; padding: 0px 3px;">Owner</td>
                        <td align="center" valign="top" style="width:2%; padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="width:23%; padding: 0px 3px;">{{ !empty($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">2</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Vehicle No</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">2</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Vehicle No</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">3</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Chassis No</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->vehicle_chassis_no) ? $policyDetails->vehicle_chassis_no : '' }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">3</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Chassis No</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->vehicle_chassis_no) ? $policyDetails->vehicle_chassis_no : '' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">4</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Engine No.</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->vehicle_engine_no) ? $policyDetails->vehicle_engine_no : '' }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">4</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Engine No.</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->vehicle_engine_no) ? $policyDetails->vehicle_engine_no : '' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">5</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">HPN</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->HPA) ? $policyDetails->HPA : '' }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">5</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">HPN</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->HPA) ? $policyDetails->HPA : '' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">6</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Carrying Capacity</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->carrying_capacity) ? $policyDetails->carrying_capacity : '' }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">6</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Carrying Capacity</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->carrying_capacity) ? $policyDetails->carrying_capacity : '' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">7</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Driver Name</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->driver_name) ? $policyDetails->driver_name : '' }}
                        </td>
                        <td align="center" valign="top" style="padding: 0px 3px;">7</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Excess Clause</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->ImposedClause) ? $policyDetails->ImposedClause : '' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">8</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Date of Exp. D.L.</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->issuing_date) ? date('d/m/Y', strtotime($policyDetails->issuing_date)) : '' }} upto {{ !empty($policyDetails->dl_valid_upto) ? date('d/m/Y', strtotime($policyDetails->dl_valid_upto)) : '' }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">8</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Endtt. No.</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;"></td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">9</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Type Of License</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->type_of_dl) ? $policyDetails->type_of_dl : '' }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">9</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Claim Form Sign By</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->insured_rep_attending_survey) ? $policyDetails->insured_rep_attending_survey : '' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">10</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Load Challan Dt.</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->challan_no) ? $policyDetails->challan_no : '' }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">10</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Date of Intimation</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->date_of_appointment) ? \Carbon\Carbon::parse($policyDetails->date_of_appointment)->format('d/m/Y') : '' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">11</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Fitness No. /Exp Dt. </td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->fitness_number) ? $policyDetails->fitness_number : '' }}, {{ !empty($policyDetails->fitness_valid_to) ? \Carbon\Carbon::parse($policyDetails->fitness_valid_to)->format('d/m/Y') : '' }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">11</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Date of Accident</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->date_time_accident) ? \Carbon\Carbon::parse($policyDetails->date_time_accident)->format('d/m/Y') : '' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">12</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Permit No./Exp Dt.</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->permit_number) ? $policyDetails->permit_number : '' }}, {{ !empty($policyDetails->permit_valid_to) ? date('d/m/Y', strtotime($policyDetails->permit_valid_to)) : '' }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">12</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Date of Survey</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->Survey_Date_time) ? \Carbon\Carbon::parse($policyDetails->Survey_Date_time)->format('d/m/Y') : '' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">13</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Route of Permit</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($policyDetails->route) ? $policyDetails->route : '' }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">13</td>
                        <td align="left" valign="top"  colspan="2" style="padding: 0px 3px;">Sec. 64VB Complied With
                        </td>
                        <td align="left" valign="top" style="padding: 0px;">
                            <table width="100%" id="design" cellpadding="0" cellspacing="0" border="0" align="center">
                                <tbody>
                                    <tr>
                                        <td align="center" valign="top"
                                            style="border-style: none solid none none;padding: 0px 3px;">Yes</td>
                                        <td align="center" valign="top" style="border:none; padding: 0px 3px;">No</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">14</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Tax Paid Up To</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" colspan="5" style="padding: 0px 3px;">{{ !empty($policyDetails->tax_paid_to) ? \Carbon\Carbon::parse($policyDetails->tax_paid_to)->format('d/m/Y') : '' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">15</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Police Report At</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" colspan="5" style="padding: 0px 3px;">{{ !empty($policyDetails->accident_reported_to_police) ? ($policyDetails->accident_reported_to_police=="Y") ? "Yes" : "No" : '' }}{!! !empty($policyDetails->fir_description) ? ', '.$policyDetails->fir_description : '' !!}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 0px 3px;">16</td>
                        <td align="left" valign="top" style="padding: 0px 3px;">Third Party Loss</td>
                        <td align="center" valign="top" style="padding: 0px 1px;">:</td>
                        <td align="left" valign="top" colspan="5" style="padding: 0px 3px;">{!! !empty($policyDetails->third_party_injury) ? $policyDetails->third_party_injury : ''!!}</td>
                    </tr>
                </tbody>
            </table>
            <table id="design" width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="Margin-top:5px; width: 100%;">
                <tbody>
                    <tr>
                        <td align="left" valign="top" colspan="4" style="width: 50%; font-weight: bold; border-top: 1px solid #000; border-left: 1px solid #000; padding: 5px;">Paper Available In File</td>
                        <td align="left" valign="top" colspan="6" style="width: 50%; font-weight: bold; border-top: 1px solid #000; padding: 5px;" >Final Assessment</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 1px 3px;">1</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Claim Intimation Letter</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Yes</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">No</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">&nbsp;</td>
                        <td align="right" valign="top" style="font-weight: bold; padding: 1px 3px;">Assessed</td>
                        <td align="right" valign="top" style="font-weight: bold; padding: 1px 3px;">Dep. Amt.</td>
                        <td align="right" valign="top" style="font-weight: bold; padding: 1px 3px;">Amt. After Dep.</td>
                        <td align="right" valign="top" style="font-weight: bold; padding: 1px 3px;">GST Amt.</td>
                        <td align="right" valign="top" style="font-weight: bold; padding: 1px 3px;">Net Amt.</td>
                    </tr>
                    @php
                        $metalDep = (!empty($policyDetails->totalMetalNonIMT) && $policyDetails->totalMetalNonIMT > 0) ? ($policyDetails->totalMetalNonIMT - $policyDetails->depMetalNonIMT) : 0;
                        $rubberPlastDep = (!empty($policyDetails->totalRubberNonIMT) && $policyDetails->totalRubberNonIMT > 0) ? ($policyDetails->totalRubberNonIMT - $policyDetails->depRubberNonIMT) : 0;
                        $glassDep = (!empty($policyDetails->totalGlass) && $policyDetails->totalGlass > 0) ? ($policyDetails->totalGlass - $policyDetails->depGlass) : 0;
                        $metalIMTDep = (!empty($policyDetails->totalMetalIMT) && $policyDetails->totalMetalIMT > 0) ? ($policyDetails->totalMetalIMT - $policyDetails->DepMetalIMT) : 0;
                        $rubberIMTDep = (!empty($policyDetails->totalRubberIMT) && $policyDetails->totalRubberIMT > 0) ? ($policyDetails->totalRubberIMT - $policyDetails->DepRubberIMT) : 0;
                        $labourDep = !empty($policyDetails->totalRubberIMT) ? (($policyDetails->totallabour + $policyDetails->totalPainting + $policyDetails->totalPaintingIMT) - ($policyDetails->depAmtPainting + $policyDetails->depAmtPaintingIMT)) : 0;
                        $totalAmtAfterDep = ($metalDep + $rubberPlastDep + $glassDep + $metalIMTDep + $rubberIMTDep + $labourDep);
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 1px 3px;">2</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Claim From / WD Claim From</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Yes</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">No</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Metallic</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->totalMetalNonIMT) ? $policyDetails->totalMetalNonIMT : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->depMetalNonIMT) ? $policyDetails->depMetalNonIMT : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($metalDep) }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->gstAmtMetal) ? $policyDetails->gstAmtMetal : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->totalMetalNonIMT) ? number_format_custom(($policyDetails->totalMetalNonIMT - $policyDetails->depMetalNonIMT + $policyDetails->gstAmtMetal),2, '.', '') : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 1px 3px;">3</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Estimate</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Yes</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">No</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Plastic</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->totalRubberNonIMT) ? $policyDetails->totalRubberNonIMT : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->depRubberNonIMT) ? $policyDetails->depRubberNonIMT : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($rubberPlastDep) }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->gstAmtRubber) ? $policyDetails->gstAmtRubber : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->totalRubberNonIMT) ? number_format_custom(($policyDetails->totalRubberNonIMT - $policyDetails->depRubberNonIMT + $policyDetails->gstAmtRubber),2, '.', '') : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 1px 3px;">4</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">RC & DL</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Yes</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">No</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Glass</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->totalGlass) ? $policyDetails->totalGlass : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->depGlass) ? $policyDetails->depGlass : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($glassDep) }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->gstAmtGlass) ? $policyDetails->gstAmtGlass : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->totalGlass) ? number_format_custom(($policyDetails->totalGlass - $policyDetails->depGlass + $policyDetails->gstAmtGlass),2, '.', '') : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 1px 3px;">5</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Load Chillan Copy</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Yes</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">No</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Metal IMT</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->totalMetalIMT) ? $policyDetails->totalMetalIMT : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->DepMetalIMT) ? $policyDetails->DepMetalIMT : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($metalIMTDep) }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->gstAmtIMTMetal) ? $policyDetails->gstAmtIMTMetal : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->totalMetalIMT) ? number_format_custom(($policyDetails->totalMetalIMT - $policyDetails->DepMetalIMT + $policyDetails->gstAmtIMTMetal),2, '.', '') : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 1px 3px;">6</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">FIR / FR</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Yes</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">No</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Plastic IMT</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->totalRubberIMT) ? number_format_custom($policyDetails->totalRubberIMT) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->DepRubberIMT) ? number_format_custom($policyDetails->DepRubberIMT) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($rubberIMTDep) }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->gstAmtIMTRubber) ? number_format_custom($policyDetails->gstAmtIMTRubber) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->totalRubberIMT) ? number_format_custom(($policyDetails->totalRubberIMT - $policyDetails->DepRubberIMT + $policyDetails->gstAmtIMTRubber),2, '.', '') : '0.00' }}</td>
                    </tr>
                    @php
                        $sr = 6;
                        $totalAssessedAmt = ($policyDetails->totalMetalNonIMT + $policyDetails->totalRubberNonIMT + $policyDetails->totalGlass + $policyDetails->totalMetalIMT + $policyDetails->totalRubberIMT + ($policyDetails->totallabour + $policyDetails->totalPainting + $policyDetails->totalPaintingIMT) + $policyDetails->TowingCharges);
                        $totalDepAmt = ($policyDetails->depMetalNonIMT + $policyDetails->depRubberNonIMT + $policyDetails->depGlass + $policyDetails->DepMetalIMT + $policyDetails->DepRubberIMT + ($policyDetails->depAmtPainting + $policyDetails->depAmtPaintingIMT));
                        //$totalAmtAfterDep = (($policyDetails->totalMetalNonIMT - $policyDetails->depMetalNonIMT) + ($policyDetails->totalRubberNonIMT - $policyDetails->depRubberNonIMT) + ($policyDetails->totalGlass - $policyDetails->depGlass) + ($policyDetails->totalMetalIMT - $policyDetails->DepMetalIMT) + ($policyDetails->totalRubberIMT - $policyDetails->DepRubberIMT) +(($policyDetails->totallabour + $policyDetails->totalPainting + $policyDetails->totalPaintingIMT) - ($policyDetails->depAmtPainting + $policyDetails->depAmtPaintingIMT)));
                        $totalGSTAmt = ($policyDetails->gstAmtMetal + $policyDetails->gstAmtRubber + $policyDetails->gstAmtGlass + $policyDetails->gstAmtIMTMetal + $policyDetails->gstAmtIMTRubber +  ($policyDetails->gstlabour + $policyDetails->gstPainting + $policyDetails->gstPaintingIMT));
                        $totalNetAmt = (($policyDetails->totalMetalNonIMT - $policyDetails->depMetalNonIMT + $policyDetails->gstAmtMetal) + ($policyDetails->totalRubberNonIMT - $policyDetails->depRubberNonIMT + $policyDetails->gstAmtRubber) + ($policyDetails->totalGlass - $policyDetails->depGlass + $policyDetails->gstAmtGlass) + ($policyDetails->totalMetalIMT - $policyDetails->DepMetalIMT + $policyDetails->gstAmtIMTMetal) + ($policyDetails->totalRubberIMT - $policyDetails->DepRubberIMT + $policyDetails->gstAmtIMTRubber) + ((($policyDetails->totallabour + $policyDetails->totalPainting + $policyDetails->totalPaintingIMT) - ($policyDetails->depAmtPainting + $policyDetails->depAmtPaintingIMT)) + ($policyDetails->gstlabour + $policyDetails->gstPainting + $policyDetails->gstPaintingIMT)) + $policyDetails->TowingCharges);
                    @endphp
                    {{--Dyanmic Case for Fiber & Recondition Start--}}
                    @if(!empty($policyDetails->totalFiber) && $policyDetails->totalFiber > 0)
                        @php
                            $totalFiberAfterDep = $policyDetails->totalFiber;
                            if($policyDetails->depFiber > 0) {
                                $totalFiberAfterDep = ($policyDetails->totalFiber - $policyDetails->depFiber);
                                $totalDepAmt += $policyDetails->depFiber;
                            }
                            $fiberGstAmt = ($policyDetails->partFibreAssamount - $totalFiberAfterDep);
                            $totalAssessedAmt += $policyDetails->totalFiber;
                            $totalAmtAfterDep += $totalFiberAfterDep;
                            $totalGSTAmt += $fiberGstAmt;
                            $totalNetAmt += $policyDetails->partFibreAssamount;
                        @endphp
                        <tr>
                            <td align="center" valign="top" style="border-left: 1px solid #000; padding: 1px 3px;"></td>
                            <td align="left" valign="top" style="padding: 1px 3px;"></td>
                            <td align="left" valign="top" style="padding: 1px 3px;"></td>
                            <td align="left" valign="top" style="padding: 1px 3px;"></td>
                            <td align="left" valign="top" style="padding: 1px 3px;">Fiber</td>
                            <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($policyDetails->totalFiber) }}</td>
                            <td align="right" valign="top" style="padding: 1px 3px;">{{ ($policyDetails->depFiber > 0) ? number_format_custom($policyDetails->depFiber) : '0.00' }}</td>
                            <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($totalFiberAfterDep) }}</td>
                            <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($fiberGstAmt) }}</td>
                            <td align="right" valign="top" style="padding: 1px 3px;">{{ ($policyDetails->partFibreAssamount > 0) ? number_format_custom($policyDetails->partFibreAssamount) : '0.00' }}</td>
                        </tr>
                    @endif

                    @if(!empty($policyDetails->totalReconditionAmt) && $policyDetails->totalReconditionAmt > 0)
                        @php
                            $totalReconditionAmt = $policyDetails->totalReconditionAmt;
                            $recGstAmt = ($policyDetails->totalreconditionAss - $policyDetails->totalReconditionAmt);
                            $totalAssessedAmt += $policyDetails->totalReconditionAmt;
                            $totalAmtAfterDep += $policyDetails->totalReconditionAmt;
                            $totalGSTAmt += $recGstAmt;
                            $totalNetAmt += $policyDetails->totalreconditionAss;
                        @endphp
                        <tr>
                            <td align="center" valign="top" style="border-left: 1px solid #000; padding: 1px 3px;"></td>
                            <td align="left" valign="top" style="padding: 1px 3px;"></td>
                            <td align="left" valign="top" style="padding: 1px 3px;"></td>
                            <td align="left" valign="top" style="padding: 1px 3px;"></td>
                            <td align="left" valign="top" style="padding: 1px 3px;">Recondition</td>
                            <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($totalReconditionAmt) }}</td>
                            <td align="right" valign="top" style="padding: 1px 3px;">{{ '0.00' }}</td>
                            <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($totalReconditionAmt) }}</td>
                            <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($recGstAmt) }}</td>
                            <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($policyDetails->totalreconditionAss) }}</td>
                        </tr>
                    @endif
                    {{--Dyanmic Case for Fiber & Recondition End--}}

                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 1px 3px;">{{ ++$sr }}</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Final Bills</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Yes</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">No</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Labour</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->totallabour) ? number_format_custom(($policyDetails->totallabour + $policyDetails->totalPainting + $policyDetails->totalPaintingIMT),2, '.', '') : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->depAmtPainting) ? number_format_custom($policyDetails->depAmtPainting + $policyDetails->depAmtPaintingIMT) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ number_format_custom($labourDep) }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->gstAmtIMTRubber) ? number_format_custom($policyDetails->gstlabour + $policyDetails->gstPainting + $policyDetails->gstPaintingIMT) : 0.00 }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->totalRubberIMT) ? number_format_custom(((($policyDetails->totallabour + $policyDetails->totalPainting + $policyDetails->totalPaintingIMT) - ($policyDetails->depAmtPainting + $policyDetails->depAmtPaintingIMT)) + ($policyDetails->gstlabour + $policyDetails->gstPainting + $policyDetails->gstPaintingIMT)),2, '.', '') : '0.00' }}</td>
                    </tr>
                    @php
                        $rowSpan = '';
                        if(!empty($policyDetails->additional_towing) && $policyDetails->additional_towing > 0) {
                            $rowSpan = 2;
                        }
                    @endphp
                    <tr>
                        <td align="center" valign="top" rowspan="{{ $rowSpan }}" style="border-left: 1px solid #000; padding: 1px 3px;">{{ ++$sr }}</td>
                        <td align="left" valign="top" rowspan="{{ $rowSpan }}" style="padding: 1px 3px;">Salvage Collected</td>
                        <td align="left" valign="top" rowspan="{{ $rowSpan }}" style="padding: 1px 3px;">Yes</td>
                        <td align="left" valign="top" rowspan="{{ $rowSpan }}" style="padding: 1px 3px;">No</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Towing</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ ($policyDetails->TowingCharges > 0) ? number_format_custom($policyDetails->TowingCharges) : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 1px 3px;"></td>
                        <td align="right" valign="top" style="padding: 1px 3px;"></td>
                        <td align="right" valign="top" style="padding: 1px 3px;"></td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ ($policyDetails->TowingCharges > 0) ? number_format_custom($policyDetails->TowingCharges) : '0.00' }}</td>
                    </tr>
                    @if(!empty($policyDetails->additional_towing) && $policyDetails->additional_towing > 0)
                        @php
                            $totalAssessedAmt += $policyDetails->additional_towing;
                            $totalNetAmt += $policyDetails->additional_towing;
                        @endphp
                        <tr>
                            <td align="left" valign="top" style="padding: 1px 3px;">Additional Towing</td>
                            <td align="right" valign="top" style="padding: 1px 3px;">{{ ($policyDetails->additional_towing > 0) ? number_format_custom($policyDetails->additional_towing) : '0.00' }}</td>
                            <td align="right" valign="top" style="padding: 1px 3px;"></td>
                            <td align="right" valign="top" style="padding: 1px 3px;"></td>
                            <td align="right" valign="top" style="padding: 1px 3px;"></td>
                            <td align="right" valign="top" style="padding: 1px 3px;">{{ ($policyDetails->additional_towing > 0) ? number_format_custom($policyDetails->additional_towing) : '0.00' }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td align="center" valign="top" style="border-left: 1px solid #000; padding: 1px 3px;">{{ ++$sr }}</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Permit, Fitness & Tax Receipt Etc</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">Yes</td>
                        <td align="left" valign="top" style="padding: 1px 3px;">No</td>
                        <td align="left" valign="top" style="font-weight: bold; padding: 1px 3px;">Total</td>
                        <td align="right" valign="top" style="font-weight: bold; padding: 1px 3px;">{{ (!empty($totalAssessedAmt) && $totalAssessedAmt > 0) ? number_format_custom($totalAssessedAmt, 2) : '0.00' }}</td>
                        <td align="right" valign="top" style="font-weight: bold; padding: 1px 3px;">{{ (!empty($totalDepAmt) && $totalDepAmt > 0) ? number_format_custom($totalDepAmt, 2) : '0.00' }}</td>
                        <td align="right" valign="top" style="font-weight: bold; padding: 1px 3px;">{{ (!empty($totalAmtAfterDep) && $totalAmtAfterDep > 0) ? number_format_custom($totalAmtAfterDep, 2) : '0.00' }}</td>
                        <td align="right" valign="top" style="font-weight: bold; padding: 1px 3px;">{{ (!empty($totalGSTAmt) && $totalGSTAmt > 0) ? number_format_custom($totalGSTAmt, 2) : '0.00' }}</td>
                        <td align="right" valign="top" style="font-weight: bold; padding: 1px 3px;">{{ (!empty($totalNetAmt) && $totalNetAmt > 0) ? number_format_custom($totalNetAmt, 2) : '0.00' }}</td>
                    </tr>

                    <tr>
                        <td align="right" valign="top" colspan="7" style="padding: 1px 3px; border-left: 1px solid #000; border-top: 1px solid #000;">&nbsp;</td>
                        <td align="left" valign="top" colspan="2" style="padding: 1px 3px; border-top: 1px solid #000;">Less Imposed Clause</td>
                        <td align="right" valign="top" style="padding: 1px 3px; border-top: 1px solid #000;">{{ !empty($policyDetails->ImposedClause) ? $policyDetails->ImposedClause : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" colspan="7" style="padding: 1px 3px; border-left: 1px solid #000; ">&nbsp;</td>
                        <td align="left" valign="top" colspan="2" style="padding: 1px 3px;">Less Compulsory Deductable</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->CompulsoryDeductable) ? $policyDetails->CompulsoryDeductable : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" colspan="7" style="padding: 1px 3px;border-left: 1px solid #000; ">&nbsp;</td>
                        <td align="left" valign="top" colspan="2" style="padding: 1px 3px;">Less Voluntary Execess</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->less_voluntary_excess) ? $policyDetails->less_voluntary_excess : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" colspan="7" style="padding: 1px 3px;border-left: 1px solid #000; ">&nbsp;</td>
                        <td align="left" valign="top" colspan="2" style="padding: 1px 3px;">Less Salvage / Scrap Value</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->SalvageAmt) ? $policyDetails->SalvageAmt : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" colspan="7" style="padding: 1px 3px;border-left: 1px solid #000; ">&nbsp;</td>
                        <td align="left" valign="top" colspan="2" style="padding: 1px 3px; font-weight: bold;">NET LIABILITY</td>
                        @php
                            $insurer_liability = $totalNetAmt - ($policyDetails->ImposedClause + $policyDetails->CompulsoryDeductable + $policyDetails->less_voluntary_excess + $policyDetails->SalvageAmt);
                        @endphp
                        <td align="right" valign="top" style="padding: 1px 3px; font-weight: bold;">{{ number_format_custom($insurer_liability) }}</td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" colspan="7" style="padding: 1px 3px;border-left: 1px solid #000; ">&nbsp;</td>
                        <td align="left" valign="top" colspan="2" style="padding: 1px 3px;">Survey Fee</td>
                        <td align="right" valign="top" style="padding: 1px 3px;">{{ !empty($policyDetails->TotalAmountWithoutGST) ? number_format_custom($policyDetails->TotalAmountWithoutGST) : '0.00' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" colspan="10" style="font-weight: bold; border-left: 1px solid #000; padding: 1px 3px; height:50px;"> REMARK: -  </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>