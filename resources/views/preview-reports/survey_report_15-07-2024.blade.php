<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MOTOR ( FINAL ) SURVEY REPORT</title>
    <style>
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
        td{
            font-family:verdana !important;
        }

        /* table { border-collapse: separate; border-spacing: 0; }
        #design td { border: solid 1px #000; border-style: none solid solid solid; padding: 10px;}
        #design tr:first-child td { border-top-style: 1px solid #000 !important; }
        #design tr td:first-child { border-left-style: 1px solid #000 !important; } */


        table {
            border-collapse: separate;
            border-spacing: 0;
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

@if(!empty($finalWithoutAss=='final_without_ass_report') || !empty($finalAssessment=='final_ass_report'))
    <div>
        <div style="border-bottom: 3px solid #000; text-align:center;">
            <div style="width: 100%;">
                @if ($letter_head_img)
                    <img src="{{ asset('public/storage/'.$letter_head_img) }}" style="height:auto;">
                @else
                    <p>No letter head image available</p>
                @endif
            </div>
        </div>
    </div>

    <div style="padding: 5px 0px; width: 100%;">
        <div style="text-align: center;font-weight: bold; font-family: 'Verdana' !important; ">MOTOR ({{ ($policyDetails->claim_type == 1) ? 'FINAL' : 'SPOT' }}) SURVEY REPORT</div>
        <div style="text-align: center; font-weight: 400;">Private & Confidential</div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top: 20px; font-size: 14px; font-family: 'Verdana' !important;">
            <tbody>
            <tr>
                <td style="width: 80%">
                    <span style="font-weight: bold; padding-right: 10px; padding-left: 10px; font-family: 'Verdana' !important; ">Ref No.:</span> {{ isset($policyDetails->inspection_reference_no) ? $policyDetails->inspection_reference_no : '' }}
                </td>
                <td style="width: 20%">
                    <span  style="font-weight: bold;  padding-right: 10px; padding-left: 10px; font-family: 'Verdana' !important; ">Date:</span> {{ isset($policyDetails->reportGeneratedOn) ? \Carbon\Carbon::parse($policyDetails->reportGeneratedOn)->format('d/m/Y') : '' }}
                </td>
            </tr>
            </tbody>
        </table>

        <div style="padding-top: 10px; line-height: 18px;">This report is issued without prejudice, in respect of cause, nature and extent of
            loss/damage and subject to the terms and conditions of the Insurance Policy and Insurer admitting liability.
        </div>

        <div style="padding-top: 10px;"><strong>Subject : </strong> Claim for Veh. Regn. No. {{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}, <strong>Accident Date : </strong>
            {{ !empty($policyDetails->date_time_accident) ? \Carbon\Carbon::parse($policyDetails->date_time_accident)->format('d/m/Y h:i A') : '' }}</div>
        <div style="padding-left: 66px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center"
                   style="font-size: 14px; padding-bottom: 5px;">
                <tbody>
                <tr>
                    <td style="width: 35%; text-align: left; padding-top: 3px; font-family:Verdana !important;">Insured </td>
                    <td style="width: 5%; text-align: left; padding-top: 3px;">:</td>
                    <td style="width: 60%; text-align: left; padding-top: 3px; font-family: 'Verdana' !important;">{{ !empty($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</td>
                </tr>
                @if(isset($policyDetails->bank_name) && trim($policyDetails->bank_name) !== '' && isset($policyDetails->bank_address) && trim($policyDetails->bank_address) !== '' && isset($policyDetails->account_no) && trim($policyDetails->account_no) !== '')
                    <tr>
                        <td style="padding-top: 0px; width: 17%; text-align: left; font-weight: bold; ">Bank Name</td>
                        <td style="padding-top: 0px; width: 3%; text-align: left; font-weight: 400;">:</td>
                        <td style="padding-top: 0px; width: 80%; text-align: left; ">{{ !empty($policyDetails->bank_name) ? $policyDetails->bank_name : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: start; font-weight: bold;">Branch Name</td>
                        <td style="text-align: start; font-weight: 400;">:</td>
                        <td style="text-align: start; ">{{ !empty($policyDetails->bank_address) ? $policyDetails->bank_address : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: start; font-weight: bold;">A/c. No.</td>
                        <td style="text-align: start; font-weight: 400;">:</td>
                        <td style="text-align: start; ">{{ !empty($policyDetails->account_no) ? $policyDetails->account_no : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: start; font-weight: bold;">IFSC Code</td>
                        <td style="text-align: start; font-weight: 400;">:</td>
                        <td style="text-align: start; ">{{ !empty($policyDetails->ifsc_code) ? $policyDetails->ifsc_code : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: start; font-weight: bold;">MICR Code</td>
                        <td style="text-align: start; font-weight: 400;">:</td>
                        <td style="text-align: start; ">{{ !empty($bankDetailsValue['micr']) ? $bankDetailsValue['micr'] : '-' }}</td>
                    </tr>
                @endif

                <tr>
                    <td style="text-align: left; padding-top: 3px;">Policy No.</td>
                    <td style="text-align: left; padding-top: 3px;">:</td>
                    <td style="text-align: left; padding-top: 3px;">{{ !empty($policyDetails->policy_no) ? $policyDetails->policy_no : '' }} </td>
                </tr>
                <tr>
                    <td style="text-align: left; padding-top: 3px;">Insurance Period</td>
                    <td style="text-align: left; padding-top: 3px;">:</td>
                    <td style="text-align: left; padding-top: 3px;">{{ !empty($policyDetails->policy_valid_from) ? \Carbon\Carbon::parse($policyDetails->policy_valid_from)->format('d/m/Y') : '' }} To {{ !empty($policyDetails->policy_valid_to) ? \Carbon\Carbon::parse($policyDetails->policy_valid_to)->format('d/m/Y') : '' }} </td>
                </tr>
                @if(isset($policyDetails->claim_no) && trim($policyDetails->claim_no) !== '')
                    <tr>
                        <td style="text-align: left; padding-top: 3px;">Claim No.</td>
                        <td style="text-align: left; padding-top: 3px;">:</td>
                        <td style="text-align: left; padding-top: 3px;">{{ !empty($policyDetails->claim_no) ? $policyDetails->claim_no : '' }}</td>
                    </tr>
                @endif
                @if(isset($policyDetails->operating_officer) && trim($policyDetails->operating_officer) !== '')
                    <tr>
                        <td style="text-align: left; padding-top: 3px;">O. Officer</td>
                        <td style="text-align: left; padding-top: 3px;">:</td>
                        <td style="text-align: left; padding-top: 3px;">{{ !empty($policyDetails->operating_officer) ? $policyDetails->operating_officer : '' }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

    </div>
    </div>

    <div style="margin-bottom: 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="font-size: 14px;">
            <tbody>
            <tr>
                <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
            </tr>
            <tr>
                <td align="left" valign="top" style="font-weight: bold; text-decoration: underline; padding-top: 10px; padding-bottom: 10px;" colspan="3">Insurance Particulars</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="width: 41%; padding-top: 1px; font-family:'Verdana' !important;">Insurer</td>
                <td align="left" valign="top" style="width: 5%; padding-top: 1px;">:</td>
                <td align="left" valign="top" style="width: 54%; padding-top: 1px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->office_name) ? $policyDetails->office_name : '' }}</span>
                    <br /><span>{{ isset($policyDetails->office_address) ? $policyDetails->office_address : '' }}</span>
                </td>
            </tr>
            @if(isset($policyDetails->appointing_office_name) && trim($policyDetails->appointing_office_name) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Appointed By</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->appointing_office_name) ? $policyDetails->appointing_office_name : '' }}</span>
                        <br /><span>{{ isset($policyDetails->appointing_office_address) ? $policyDetails->appointing_office_address : '' }} </span>
                    </td>
                </tr>
            @endif
            @if(isset($policyDetails->operating_office_name) && trim($policyDetails->operating_office_name) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Operating Office </td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->operating_office_name) ? $policyDetails->operating_office_name : '' }}</span>
                        <br /><span>{{ isset($policyDetails->operating_office_address) ? $policyDetails->operating_office_address : '' }} </span>
                    </td>
                </tr>
            @endif
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Insured </td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</span>
                    <br /><span>{{ isset($policyDetails->insured_address) ? $policyDetails->insured_address : '' }}</span>
                </td>
            </tr>
            @if(isset($policyDetails->insured_mobile_no) && trim($policyDetails->insured_mobile_no) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;"></td>
                    <td align="left" valign="top" style="padding-top: 3px;"></td>
                    <td align="left" valign="top" style="padding-top: 3px; line-height: 18px;"><span>M-{{ isset($policyDetails->insured_mobile_no) ? $policyDetails->insured_mobile_no : '' }}</span>
                    </td>
                </tr>
            @endif
            @if(isset($policyDetails->policy_no) && trim($policyDetails->policy_no) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Policy No./Cover Note No </td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->policy_no) ? $policyDetails->policy_no : '' }} </span>
                    </td>
                </tr>
            @endif
            @if(isset($policyDetails->policy_valid_from) && trim($policyDetails->policy_valid_from) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Period of Policy  </td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td style="text-align: left; padding-top: 3px;">{{ !empty($policyDetails->policy_valid_from) ? \Carbon\Carbon::parse($policyDetails->policy_valid_from)->format('d/m/Y') : '' }} To {{ !empty($policyDetails->policy_valid_to) ? \Carbon\Carbon::parse($policyDetails->policy_valid_to)->format('d/m/Y') : '' }} </td>
                    </td>
                </tr>
            @endif
            @if(isset($policyDetails->policy_type) && trim($policyDetails->policy_type) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; font-style: italic;">POLICY TYPE</td>
                    <td align="left" valign="top" style="padding-top: 3px; ">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; font-style: italic;">{{ !empty($policyDetails->policy_type) ? $policyDetails->policy_type : '' }}</span>
                    </td>
                </tr>
            @endif
            @if(isset($policyDetails->status_of_64vb) && trim($policyDetails->status_of_64vb) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; font-style: italic;">STATUS OF 64VB</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; font-style: italic;">{{ !empty($policyDetails->status_of_64vb) ? $policyDetails->status_of_64vb : '' }}</span>
                    </td>
                </tr>
            @endif
            @if(isset($policyDetails->status_of_64vb) && trim($policyDetails->status_of_64vb) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; font-style: italic;">STATUS OF PREINSPECTION </td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; font-style: italic;">{{ !empty($policyDetails->status_of_pre_insp) ? $policyDetails->status_of_pre_insp : '' }}</span>
                    </td>
                </tr>
            @endif
            @if(isset($policyDetails->status_of_NCB) && trim($policyDetails->status_of_NCB) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; font-style: italic;">STATUS OF NCB</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; font-style: italic;">{{ !empty($policyDetails->status_of_NCB) ? $policyDetails->status_of_NCB : '' }}</span>
                    </td>
                </tr>
            @endif
            @if(isset($policyDetails->payment_mode) && trim($policyDetails->payment_mode) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; font-style: italic;">PAYMENT MODE</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; font-style: italic;">{{ !empty($policyDetails->payment_mode) ? $policyDetails->payment_mode : '' }}</span>
                    </td>
                </tr>
            @endif
            @if(isset($policyDetails->settlement_type) && trim($policyDetails->settlement_type) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; font-style: italic;">TYPE OF SETTLEMENT</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; font-style: italic;">{{ !empty($policyDetails->settlement_type) ? $policyDetails->settlement_type : '' }} </span>
                    </td>
                </tr>
            @endif
            @if(isset($policyDetails->sum_insured) && trim($policyDetails->sum_insured) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">IDV </td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->sum_insured) ? $policyDetails->sum_insured : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->HPA) && trim($policyDetails->HPA) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 10px;">HPA With</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 10px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 10px;">{{ !empty($policyDetails->HPA) ? $policyDetails->HPA : '' }}</td>
                </tr>
            @endif
            <tr>
                <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; text-decoration: underline;">Vehicle Particulars</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;font-weight: bold;">RC: {{ !empty($policyDetails->RC) ? $policyDetails->RC : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Registration No.</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;font-weight: bold;">{{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Registered Owner</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->registured_owner) ? $policyDetails->registured_owner : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Date of Registration </td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->date_of_registration) ? \Carbon\Carbon::parse($policyDetails->date_of_registration)->format('d/m/Y') : '' }}</td>
            </tr>
            @if(isset($policyDetails->date_of_transfer) && trim($policyDetails->date_of_transfer) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Date of Transfer</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->date_of_transfer) ? \Carbon\Carbon::parse($policyDetails->date_of_transfer)->format('d/m/Y') : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->transfer_SrNo) && trim($policyDetails->transfer_SrNo) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Transfer Sr No.</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->transfer_SrNo) ? $policyDetails->transfer_SrNo : '' }}</td>
                </tr>
            @endif
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Chassis No.</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->vehicle_chassis_no) ? $policyDetails->vehicle_chassis_no : '' }} <span style="padding-left: 10px;font-weight: bold;">Physically Verified : {{ !empty($policyDetails->accident_chassis_no) ? $policyDetails->accident_chassis_no : '' }}</span></td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Engine No. </td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->vehicle_engine_no) ? $policyDetails->vehicle_engine_no : '' }} <span style="padding-left: 10px;font-weight: bold;">Physically Verified : {{ !empty($policyDetails->accident_engine_no) ? $policyDetails->accident_engine_no : '' }}</span></td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Engine Capacity</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->engine_capacity) ? $policyDetails->engine_capacity : '' }} {{ !empty($policyDetails->engine_capacity_unit) ? $policyDetails->engine_capacity_unit : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Vehicle Make </td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->vehicle_make) ? $policyDetails->vehicle_make : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Vehicle Variant</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->vehicle_variant) ? $policyDetails->vehicle_variant : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Vehicle Model</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->vehicle_model) ? (new DateTime($policyDetails->vehicle_model))->format('F Y') : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Type of Body </td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->body_type) ? $policyDetails->body_type : '' }}</td>
            </tr>
            @if(isset($policyDetails->pre_accident_cond) && trim($policyDetails->pre_accident_cond) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Pre- Accident Condition</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->pre_accident_cond) ? $policyDetails->pre_accident_cond : '' }}</td>
                </tr>
            @endif
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Colour of Vehicle</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->vehicle_color) ? $policyDetails->vehicle_color : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Class of Vehicle</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->vehicle_class) ? $policyDetails->vehicle_class : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Seating Capacity</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->seating_capacity) ? $policyDetails->seating_capacity : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Odometer Reading</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->odometer_reading) ? $policyDetails->odometer_reading : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Fuel used as per RC</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->fuel) ? $policyDetails->fuel : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Tax paid upto</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->tax_paid_from) ? \Carbon\Carbon::parse($policyDetails->tax_paid_from)->format('d/m/Y') : '' }} {{ !empty($policyDetails->tax_paid_from && $policyDetails->tax_paid_to) ? "to" : '' }} {{ !empty($policyDetails->tax_paid_to) ? \Carbon\Carbon::parse($policyDetails->tax_paid_to)->format('d/m/Y') : '' }} {{ !empty($policyDetails->tax_valid_from_text) ? $policyDetails->tax_valid_from_text : '' }}</td>
            </tr>
            @if(isset($policyDetails->fitness_number) && trim($policyDetails->fitness_number) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Fitness Certificate No.</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->fitness_number) ? $policyDetails->fitness_number : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->fitness_valid_from) && trim($policyDetails->fitness_valid_from) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Fitness Certificate validity</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->fitness_valid_from) ? $policyDetails->fitness_valid_from : '' }} to {{ !empty($policyDetails->fitness_valid_to) ? $policyDetails->fitness_valid_to : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->permit_number) && trim($policyDetails->permit_number) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Permit No.</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->permit_number) ? $policyDetails->permit_number : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->permit_valid_from) && trim($policyDetails->permit_valid_from) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Permit validity</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->permit_valid_from) ? $policyDetails->permit_valid_from : '' }} to {{ !empty($policyDetails->permit_valid_to) ? $policyDetails->permit_valid_to : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->permit_type) && trim($policyDetails->permit_type) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Permit Type</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->permit_type) ? $policyDetails->permit_type : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->route) && trim($policyDetails->route) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Route / Area of Operation</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->route) ? $policyDetails->route : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->accident_place) && trim($policyDetails->accident_place) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Whether valid for the state in which accident took place?</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->accident_place) ? $policyDetails->accident_place : '' }}</td>
                </tr>
            @endif

            @if(isset($policyDetails->authorization_no) && trim($policyDetails->authorization_no) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Authorization No.</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->authorization_no) ? $policyDetails->authorization_no : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->authorization_valid_from) && trim($policyDetails->authorization_valid_from) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Validity</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->authorization_valid_from) ? \Carbon\Carbon::parse($policyDetails->authorization_valid_from)->format('d/m/Y') : '' }} to {{ !empty($policyDetails->authorization_valid_to) ? \Carbon\Carbon::parse($policyDetails->authorization_valid_to)->format('d/m/Y') : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->puc_certificate_no) && trim($policyDetails->puc_certificate_no) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">PUC Certificate No.</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->puc_certificate_no) ? $policyDetails->puc_certificate_no : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->issuing_date_upto) && trim($policyDetails->issuing_date_upto) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">PUC Certificate Issued On</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->issuing_date_upto) ? \Carbon\Carbon::parse($policyDetails->issuing_date_upto)->format('d/m/Y') : '' }}</td>
                </tr>
            @endif

            @if(isset($policyDetails->puc_valid_from) && trim($policyDetails->puc_valid_from) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">PUC Certificate Valid Upto</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->puc_valid_from) ? \Carbon\Carbon::parse($policyDetails->puc_valid_from)->format('d/m/Y') : '' }} to {{ !empty($policyDetails->puc_valid_to) ? \Carbon\Carbon::parse($policyDetails->puc_valid_to)->format('d/m/Y') : '' }}</td>
                </tr>
            @endif

            @if(isset($policyDetails->challan_no) && trim($policyDetails->challan_no) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Load Challan No.</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->challan_no) ? $policyDetails->challan_no : '' }} <span style="padding-left:80px;">{{ !empty($policyDetails->load_chalan) ? $policyDetails->load_chalan : '' }}</span></td>
                </tr>
            @endif

            @if(isset($policyDetails->carrying_capacity) && trim($policyDetails->carrying_capacity) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Carrying Capacity</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->carrying_capacity) ? $policyDetails->carrying_capacity : '' }}</td>
                </tr>
            @endif

            @if(isset($policyDetails->registered_laden_weight) && trim($policyDetails->registered_laden_weight) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Gross Vehicle Weight</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->registered_laden_weight) ? $policyDetails->registered_laden_weight : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->unladen_weight) && trim($policyDetails->unladen_weight) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Unladen Weight</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->unladen_weight) ? $policyDetails->unladen_weight : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->cause_of_accident) && trim($policyDetails->cause_of_accident) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom:5px;">If overloaded, whether the overloading is the cause of accident? </td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom:5px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom:5px;">{{ !empty($policyDetails->cause_of_accident) ? substr(strip_tags($policyDetails->cause_of_accident) ,0,210): " " }}</td>
                </tr>
            @endif
            <tr>
                <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;font-weight: bold; text-decoration: underline;">Driver Particulars</td>
                <td align="left" valign="top" style="padding-top: 3px;"></td>
                <td align="left" valign="top" style="padding-top: 3px;font-weight: bold;">{{ !empty($policyDetails->unladen_weight) ? $policyDetails->unladen_weight : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Name of the Driver</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->driver_name) ? $policyDetails->driver_name : '' }}</td>
            </tr>
            @if(isset($policyDetails->driver_dob) && trim($policyDetails->driver_dob) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Driver DOB</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->driver_dob) ? \Carbon\Carbon::parse($policyDetails->driver_dob)->format('d/m/Y') : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->address) && trim($policyDetails->address) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Driver Address</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->address) ? $policyDetails->address : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->relation_with_insurer) && trim($policyDetails->relation_with_insurer) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Relation with the insured </td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->relation_with_insurer) ? $policyDetails->relation_with_insurer : '' }}</td>
                </tr>
            @endif
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Driving License Number</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->dl_no) ? $policyDetails->dl_no : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Valid from</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->issuing_date) ? $policyDetails->issuing_date : '' }} upto {{ !empty($policyDetails->dl_valid_upto) ? $policyDetails->dl_valid_upto : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Issuing Authority</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->issuing_authority) ? $policyDetails->issuing_authority : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Type of License</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->type_of_dl) ? $policyDetails->type_of_dl : '' }}</td>
            </tr>
            @if(isset($policyDetails->dl_renewal_no) && trim($policyDetails->dl_renewal_no) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Liscense Renewal No.</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->dl_renewal_no) ? $policyDetails->dl_renewal_no : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->vehicle_allowed_to_drive) && trim($policyDetails->vehicle_allowed_to_drive) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Type of Vehicle Allowed To Drive</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->vehicle_allowed_to_drive) ? $policyDetails->vehicle_allowed_to_drive : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->endorsement_detail) && trim($policyDetails->endorsement_detail) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Endorsement Details</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->endorsement_detail) ? $policyDetails->endorsement_detail : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->badge_no) && trim($policyDetails->badge_no) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Badge No.</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->badge_no) ? $policyDetails->badge_no : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->additional_details) && trim($policyDetails->additional_details) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom:5px;">Additional Comments</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom:5px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom:5px;"><?php echo  $policyDetails->additional_details;?></td>
                </tr>
            @endif
            <tr>
                <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;font-weight: bold; text-decoration: underline;" colspan="3">Accident Particulars</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Date and Time of Accident</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->date_time_accident) ? \Carbon\Carbon::parse($policyDetails->date_time_accident)->format('d/m/Y h:i A') : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Place of Accident</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->place_accident) ? $policyDetails->place_accident : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Place of Survey</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->place_survey) ? $policyDetails->place_survey : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Date of request for Survey</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->date_of_appointment) ? \Carbon\Carbon::parse($policyDetails->date_of_appointment)->format('d/m/Y') : '' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" style="padding-top: 3px;">Date and Time of Survey</td>
                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->Survey_Date_time) ? \Carbon\Carbon::parse($policyDetails->Survey_Date_time)->format('d/m/Y h:i A') : '' }}</td>
            </tr>
            @if(isset($policyDetails->date_of_under_repair_visit) && trim($policyDetails->date_of_under_repair_visit) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Date of Under Repair visits</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->date_of_under_repair_visit) ? \Carbon\Carbon::parse($policyDetails->date_of_under_repair_visit)->format('d/m/Y') : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->insured_rep_attending_survey) && trim($policyDetails->insured_rep_attending_survey) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Insured's rep. attending survey</td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->insured_rep_attending_survey) ? $policyDetails->insured_rep_attending_survey : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->vehicle_left_unattended) && trim($policyDetails->vehicle_left_unattended) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">Was veh. left unattended after accn</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">{{ !empty($policyDetails->vehicle_left_unattended) ? ($policyDetails->vehicle_left_unattended=="Y") ? "Yes" : "No" : '' }} {{ !empty($policyDetails->vehicle_left_unattended_desc) ? ($policyDetails->vehicle_left_unattended_desc) : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->anti_theft_fitted) && trim($policyDetails->anti_theft_fitted) !== '')
                <tr>
                    <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;">Anti Theft Device Status </td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">{{ !empty($policyDetails->anti_theft_fitted) ? $policyDetails->anti_theft_fitted : '' }} {{ !empty($policyDetails->anti_theft_type) ? $policyDetails->anti_theft_type : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->previous_claim_details) && trim($policyDetails->previous_claim_details) !== '')
                <tr>
                    <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;font-weight: bold; text-decoration: underline;">Previous Claim Details</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;"><?php echo $policyDetails->previous_claim_details;?></td>
                </tr>
            @endif
            @if(isset($policyDetails->accident_reported_to_police) && trim($policyDetails->accident_reported_to_police) !== '')
                <tr>
                    <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;font-weight: bold; text-decoration: underline;" colspan="3">Particulars of Police Report</td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Has accident been report to Police </td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->accident_reported_to_police) ? ($policyDetails->accident_reported_to_police=="Y") ? "Yes" : "No" : '' }} {{ !empty($policyDetails->fir_description) ? ($policyDetails->fir_description) : '' }}</td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Has Panchnama been carried out </td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->panchnama) ? ($policyDetails->panchnama=="Y") ? "Yes" : "No" : '' }} {{ !empty($policyDetails->panchnama_description) ? ($policyDetails->panchnama_description) : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->third_party_injury) && trim($policyDetails->third_party_injury) !== '')
                <tr>
                    <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;font-weight: bold; text-decoration: underline;">Particulars of Third Party Injury/Loss</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;"><?php echo $policyDetails->third_party_injury;?></td>
                </tr>
            @endif
            @if(isset($policyDetails->injury_to_driver) && trim($policyDetails->injury_to_driver) !== '')
                <tr>
                    <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;font-weight: bold; text-decoration: underline;">Injury to Driver/Occupant (If any)</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;"><?php echo $policyDetails->injury_to_driver;?></td>
                </tr>
            @endif
            @if(isset($policyDetails->spot_survey_by) && trim($policyDetails->spot_survey_by) !== '')
                <tr>
                    <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;font-weight: bold; text-decoration: underline;" colspan="3">Spot Survey</td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;">Spot Survey By </td>
                    <td align="left" valign="top" style="padding-top: 3px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->spot_survey_by) ? $policyDetails->spot_survey_by : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->spot_survey_date) && trim($policyDetails->spot_survey_date) !== '')
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">Spot Survey report received on</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">:</td>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">{{ !empty($policyDetails->spot_survey_date) ? \Carbon\Carbon::parse($policyDetails->spot_survey_date)->format('d/m/Y') : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->passenger_detail) && trim($policyDetails->passenger_detail) !== '')
                <tr>
                    <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; font-weight: bold; text-decoration: underline;" colspan="3">Load/Passenger Details</td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; text-align: justify;" colspan="3"><?php echo $policyDetails->passenger_detail;?>
                    </td>
                </tr>
            @endif
            @if(isset($policyDetails->accident_brief_description) && trim($policyDetails->accident_brief_description) !== '')
                <tr>
                    <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;font-weight: bold; text-decoration: underline;" colspan="3">Cause and Nature of Accident</td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; text-align: justify;" colspan="3"><?php echo $policyDetails->accident_brief_description;?></td>
                </tr>
            @endif
            @if(isset($policyDetails->action_of_survey) && trim($policyDetails->action_of_survey) !== '')
                <tr>
                    <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;" colspan="3">Actions of Survey</td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; text-align: justify;" colspan="3"><?php echo $policyDetails->action_of_survey;?></td>
                </tr>
            @endif
            @if(isset($policyDetails->particular_of_damage) && trim($policyDetails->particular_of_damage) !== '')
                <tr>
                    <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;" colspan="3">Particulars of Loss/Damages</td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; text-align: justify;" colspan="3"><?php echo $policyDetails->particular_of_damage;?></td>
                </tr>
            @endif
            @if(isset($policyDetails->estimate_no) && trim($policyDetails->estimate_no) !== '')
                <tr>
                    <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;" colspan="3">Original Estimate</td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; text-align: justify;" colspan="3">Estimate no. {{ !empty($policyDetails->estimate_no) ? $policyDetails->estimate_no : '' }} dated {{ !empty($policyDetails->date_of_estimate) ? \Carbon\Carbon::parse($policyDetails->date_of_estimate)->format('d/m/Y') : '' }} for Rs. {{ !empty($policyDetails->totalest) ? ($policyDetails->totalest + $policyDetails->total_labourAmtWithGst) : '0.00' }} was submitted by {{ !empty($policyDetails->workshop_branch_name) ? $policyDetails->workshop_branch_name : '' }} , {{ !empty($policyDetails->workshop_branch_address) ? $policyDetails->workshop_branch_address : '' }}</td>
                </tr>
            @endif
            @if(isset($policyDetails->observation) && trim($policyDetails->observation) !== '')
                <tr>
                    <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;font-weight: bold; text-decoration: underline;" colspan="3">Observation</td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; text-align: justify;" colspan="3"><?php echo $policyDetails->observation;?></td>
                </tr>
            @endif
            @if(isset($dynamicSection) && count($dynamicSection) > 0)
                @foreach($dynamicSection as $key=>$value)
                    <tr>
                        <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                    </tr>

                    <tr>
                        <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;" colspan="3">{{ !empty($key) ? $key : '' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="padding-top: 3px; text-align: justify;" colspan="3"><?php echo $value;?></td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td align="left" valign="top" style="padding-top: 10px;font-weight: bold;" colspan="3">(Issued without Prejudice)</td>
            </tr>

            <tr>
                <td align="left" valign="top" style="padding-top: 10px;font-weight: bold;" colspan="3">
                    @if ($signature_img)
                        <img src="{{ asset('public/storage/'.$signature_img) }}" style="width:100px;">
                    @else
                        <p>
                            <br/>
                            <br />
                            <br />
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
            @if(isset($inspectionAttachment) && count($inspectionAttachment) > 0)
                <tr>
                    <td align="left" valign="top" style="padding-top: 20px;font-weight: bold;" colspan="3">Enclosures</td>
                </tr>
                <tr>
                    <td colspan="3">
                        @php
                            $enclosures = '';
                            $counter = 1;
                            $customAttachments = json_decode($inspectionAttachment[0]['custom_attachments'], true);
                        @endphp
                        @if($inspectionAttachment[0]['copy_of_fire'] == 1)
                            @php $enclosures .= $counter++ . '. Copy Of Fir, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['report_in_duplicate'] == 1)
                            @php $enclosures .= $counter++ . '. Report In Duplicate, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['copy_of_load_challan'] == 1)
                            @php $enclosures .= $counter++ . '. Copy Of Load Challan, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['affidavit'] == 1)
                            @php $enclosures .= $counter++ . '. Affidavit, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['bill_invoice'] == 1)
                            @php $enclosures .= $counter++ . '. Bill/invoice, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['copy_of_permit'] == 1)
                            @php $enclosures .= $counter++ . '. Copy Of Permit, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['copy_traffic'] == 1)
                            @php $enclosures .= $counter++ . '. Copy Of Traffic Challan, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['estimate_copy'] == 1)
                            @php $enclosures .= $counter++ . '. Estimate Copy, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['report_in_duplicate'] == 1)
                            @php $enclosures .= $counter++ . '. Report In Duplicate, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['copy_of_fitness'] == 1)
                            @php $enclosures .= $counter++ . '. Copy Of Fitness, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['claim_form'] == 1)
                            @php $enclosures .= $counter++ . '. Claim Form, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['copy_of_RC'] == 1)
                            @php $enclosures .= $counter++ . '. Copy Of R.c, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['insured_discharge_voucher'] == 1)
                            @php $enclosures .= $counter++ . '. Copy Of Letter To Insured Discharge Voucher, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['intimation_letter'] == 1)
                            @php $enclosures .= $counter++ . '. Intimation Letter, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['survey_fee_bill'] == 1)
                            @php $enclosures .= $counter++ . '. Survey Fee Bill, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['letter_by_insured'] == 1)
                            @php $enclosures .= $counter++ . '. Letter By Insured, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['copy_of_DL'] == 1)
                            @php $enclosures .= $counter++ . '. Copy Of D.l, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['policy_note'] == 1)
                            @php $enclosures .= $counter++ . '. Copy Of Policy/c. Note, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['generate_photosheet'] == 1)
                            @php $enclosures .= $counter++ . '. Generate Photosheet, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['medical_papers'] == 1)
                            @php $enclosures .= $counter++ . '. Medical Papers, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['dealer_inv'] == 1)
                            @php $enclosures .= $counter++ . '. Copy Dealer Inv., '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['police_report'] == 1)
                            @php $enclosures .= $counter++ . '. Copy Of Police Report, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['photographs'] == 1)
                            @php $enclosures .= $counter++ . '. Photographs, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['satisfaction_voucher'] == 1)
                            @php $enclosures .= $counter++ . '. Satisfaction Voucher, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['supporting_bills'] == 1)
                            @php $enclosures .= $counter++ . '. Supporting Bills, '; @endphp
                        @endif
                        @if($inspectionAttachment[0]['towing_charge_slip'] == 1)
                            @php $enclosures .= $counter++ . '. Towing Charge Slip, '; @endphp
                        @endif
                        @foreach($customAttachments as $key => $value)
                            @if($value)
                                @php $enclosures .= $counter++ . '. ' . ucfirst($key) . ', '; @endphp
                            @endif
                        @endforeach
                        {{ rtrim($enclosures, ', ') }}
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
@endif
@if($finalAssessment=='final_ass_report')
    <div class="page-break" />
    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
        <tbody>
        <tr>
            <td align="center" valign="top" style="font-weight: bold; padding-top: 3px; border-left: none; border-right: none; text-decoration: underline;">
                LOSS ASSESSMENT </td>
        </tr> <span style="font-weight: bold;"></span>
        <tr>
            <td align="center" valign="top" style="padding-top: 3px;  padding-bottom: 5px;  border-left: none; border-right: none;">In respect of Vehicle
                Registration No. <span style="font-weight: bold;">{{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}</span>, Accident Date : <span style="font-weight: bold;">{{ !empty($policyDetails->date_time_accident) ? \Carbon\Carbon::parse($policyDetails->date_time_accident)->format('d/m/Y') : '' }}</span> <br />(Annexure 'A' attached to Survey Report No. <span style="font-weight: bold;">{{ isset($policyDetails->inspection_reference_no) ? $policyDetails->inspection_reference_no : '' }}</span>)</td>
        </tr>
        </tbody>
    </table>

    @if(
        (isset($lossAssessment[0]['totalFibreAmt']) && $lossAssessment[0]['totalFibreAmt'] == "0.00" && isset($lossAssessment[0]['totalReconditionAmt']) && $lossAssessment[0]['totalReconditionAmt'] != "0.00") ||
        (isset($lossAssessment[0]['totalFibreAmt']) && $lossAssessment[0]['totalFibreAmt'] != "0.00" && isset($lossAssessment[0]['totalReconditionAmt']) && $lossAssessment[0]['totalReconditionAmt'] == "0.00") ||
        (isset($lossAssessment[0]['totalFibreAmt']) && $lossAssessment[0]['totalFibreAmt'] == "0.00" && isset($lossAssessment[0]['totalReconditionAmt']) && $lossAssessment[0]['totalReconditionAmt'] == "0.00")
    )

        <!-- Start 1st table loss of assessment -->
        <table width="100%" align="center">
            <tbody>
            <tr>
                <td align="left" valign="top" style="width: 40%; border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 3px 0px; font-weight: bold;">ASSESSMENT OF LOSS</td>
                <td align="left" valign="top" style="width: 60%; border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 3px 0px; font-weight: bold;">(PARTS)</td>
            </tr>
            </tbody>
        </table>
        <table width="100%" border="0" align="center" id="design" style="font-size: 12px;">
            <tbody>
            <tr>
                <td align="left" valign="top" style="padding: 0px 3px; font-weight:bold; border-left: 1px solid #000;">Sr. No.</td>
                <td align="left" valign="top" style="padding: 0px 3px; font-weight:bold;">Description of Parts</td>
                <!-- <td align="left" valign="top" style="padding: 0px 3px;">Bill Sr.No.</td> -->
                @if(isset($lossAssessment[0]['display_bill_sr_no']) && $lossAssessment[0]['display_bill_sr_no'] == 1)
                    <td align="left" valign="top" style="padding: 0px 3px; font-weight:bold;">Bill Sr.No.</td>
                @endif
                @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight:bold;">HSN Code</td>
                @endif
                <td align="center" valign="top" style="padding: 0px 3px; font-weight:bold;">QE</td>
                <td align="center" valign="top" style="padding: 0px 3px; font-weight:bold;">QA</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight:bold;">Est. Rate</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight:bold;">GST <br />%</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight:bold;">Estimated<br /><span style="font-weight: 400;">(Amt in Rs)</span></td>
                <td align="right" valign="top" colspan="3" style="padding: 0px 3px;">
                    <table width="100%" align="center">
                        <tbody>
                        <tr>
                            <td align="center" valign="top" colspan="3" style="padding-bottom: 5px; border: none; font-weight:bold;">Assessed Parts Amount</td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" style="padding: 0px 3px; border-top: 2px solid #000; border-left: none; border-right: none; border-bottom: none; font-weight:bold;">Metal
                            </td>
                            <td align="center" valign="top" style="padding: 0px 3px; border-top: 2px solid #000; border-left: none; border-right: none; border-bottom: none; font-weight:bold;">
                                Rub/Plast</td>
                            <td align="center" valign="top" style="padding: 0px 3px; border-top: 2px solid #000; border-left: none; border-right: none; border-bottom: none; font-weight:bold;">Glass
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; font-weight:bold;">Remark</td>
            </tr>
            @if(isset($lossAssessment[0]['alldetails']))
                @php
                    $alldetails = json_decode($lossAssessment[0]['alldetails'], true);
                    $subPartUniqueValue=[];
                    $indexCounter = 0;

                @endphp
                @if(is_array($alldetails))
                    @foreach($alldetails as $index => $detail)
                        @if(isset($detail['category']) && ($detail['category'] != null || $detail['category'] !=""))
                            @php
                                $subPartUniqueValue[]= $detail['gst'];
                                $indexCounter++;
                            @endphp

                            <tr>
                                <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">{{ $indexCounter }}</td>
                                @if($detail['imt_23'] == "Yes")
                                    <td align="left" valign="top" style="padding: 0px 3px;"><strong>*</strong> {{ $detail['description'] }}</td>
                                @else
                                    <td align="left" valign="top" style="padding: 0px 3px;">{{ $detail['description'] }}</td>
                                @endif

                                @if(isset($lossAssessment[0]['display_bill_sr_no']) && $lossAssessment[0]['display_bill_sr_no'] == 1)
                                    <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($detail['b_sr_no']) ? $detail['b_sr_no'] : '-' }}</td>
                                @endif
                                @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                                    <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($detail['hsn_code']) ? $detail['hsn_code'] : '-' }}</td>
                                @endif
                                <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($detail['qe']) ? $detail['qe'] : '-' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['qa']) ? $detail['qa'] : '-' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ $detail['est_rate'] }}</td>
                                <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($detail['gst']) ? $detail['gst'] : '0' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['est_amt']) ? $detail['est_amt'] : '0.00' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['category']=="Metal") ? $detail['ass_amt'] : '-' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['category']=="Rubber") ? $detail['ass_amt'] : '-' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['category']=="Glass") ? $detail['ass_amt'] : '-' }}</td>
                                <td align="center" valign="top" style="padding: 0px 3px;">{{ $detail['remarks'] ?? '-' }}</td>
                            </tr>
                        @endif
                        @if (!empty($detail['quantities']))
                            @foreach($detail['quantities'] as $quantityIndex => $quantity)
                                @if(isset($quantity['category']) && ($quantity['category'] != null || $quantity['category'] !=""))
                                    @php
                                        $subPartUniqueValue[]= $quantity['gst'];
                                    @endphp
                                    <tr>
                                        <td align="center" valign="top" style="padding: 0px 3px; padding-left: 20px; font-style: italic; border-left: 1px solid #000;">{{ $indexCounter }}.{{ $quantityIndex + 1 }}</td>
                                        @if($quantity['imt_23'] == "Yes")
                                            <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;"><strong>*</strong> {{ $quantity['description'] }}</td>
                                        @else
                                            <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;">{{ $quantity['description'] }}</td>
                                        @endif

                                        @if(isset($lossAssessment[0]['display_bill_sr_no']) && $lossAssessment[0]['display_bill_sr_no'] == 1)
                                            <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['b_sr_no']) ? $quantity['b_sr_no'] : '-' }}</td>
                                        @endif
                                        @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                                            <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['hsn_code']) ? $quantity['hsn_code'] : '-' }}</td>
                                        @endif
                                        <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">{{ $quantity['qe'] }}</td>
                                        <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">{{ $quantity['qa'] }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{{ $quantity['est_rate'] }}</td>
                                        <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['gst']) ? $quantity['gst'] : '0' }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['est_amt']) ? $quantity['est_amt'] : '0.00' }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['category']=="Metal") ? $quantity['ass_amt'] : '-' }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['category']=="Rubber") ? $quantity['ass_amt'] : '-' }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['category']=="Glass") ? $quantity['ass_amt'] : '-' }}</td>
                                        <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">{{ $quantity['remarks'] ?? '-' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif


                @if(isset($lossAssessment[0]['total_EstAmt']))
                    @php
                        $colspanValue = 0;
                        if(isset($lossAssessment[0]['display_bill_sr_no'])) {
                            $colspanValue += $lossAssessment[0]['display_bill_sr_no'] == 1 ? 1 : 0;
                        }
                        if(isset($lossAssessment[0]['display_hsn'])) {
                            $colspanValue += $lossAssessment[0]['display_hsn'] == 1 ? 1 : 0;
                        }
                    @endphp
                    <!-- <tr>
          <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Total</td>
            <td align="right" valign="top"
              style="padding: 0px 3px; border-top: 2px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }} </td>
            <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? $lossAssessment[0]['totalMetalAmt'] : '0.00' }}</td>
            <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">{{ !empty($lossAssessment[0]['totalRubberAmt']) ? $lossAssessment[0]['totalRubberAmt'] : '0.00' }}</td>
            <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">{{ !empty($lossAssessment[0]['totalGlassAmt']) ? $lossAssessment[0]['totalGlassAmt'] : '0.00' }}</td>
            <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
          </tr>
        @endif -->
                <!-- @php
                    $uniqueGstRates = array_unique(array_column($alldetails, 'gst')); // Get unique GST rates
                    $uniqueCategory = array_unique(array_column($alldetails, 'category')); // Get unique GST rates
                @endphp -->

                @php
                    $uniqueGstRates = []; // Initialize an array to store unique GST rates
                    $subUniqueGstRates = [];
                    $subUniqueGstRates = array_values(array_unique($subPartUniqueValue));
                    sort($subUniqueGstRates);
                    $counter = count($subUniqueGstRates);
                    $totalIMT23MetalAmt = 0;
                    $totalIMT23RubberAmt = 0;
                @endphp

                @foreach($subUniqueGstRates as $value)
                    @unless(in_array($value, $uniqueGstRates))
                        @php
                            $imt23MetalTotalAmount=0;
                            $imt23RubberTotalAmount = 0;
                            $totalEstAmt = 0; // Initialize total estimated amount
                            $totalMetalAmt = 0;
                            $totalRubberAmt = 0; // Initialize total assessed amount
                            $totalGlassAmt = 0;

                            $totalPartEstAmt = 0;
                            $totalPartMetalAmt = 0;
                            $totalPartRubberAmt = 0;
                            $totalPartGlassAmt = 0;
                            $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                        @endphp

                        @foreach($alldetails as $detail)
                            @php
                                if($detail['category'] =="Metal" && $detail['imt_23']=="Yes"){
                                    $imt23MetalTotalAmount += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                 }
                                 if($detail['category'] =="Rubber" && $detail['imt_23']=="Yes"){
                                      $imt23RubberTotalAmount += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                  }
                            @endphp
                            @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
                            @php
                                $totalEstAmt += !empty($detail['est_amt']) ? $detail['est_amt'] : 0; // Sum up estimated amount
                                // Sum up assessed amount for respective categories

                            if(empty($detail['quantities'])){
                                switch ($detail['category']) {
                                    case 'Metal':
                                        $totalMetalAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                        break;
                                    case 'Rubber':
                                        $totalRubberAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                        break;
                                    case 'Glass':
                                        $totalGlassAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                        break;
                                }
                            }

                            @endphp
                            @endif

                            @if(isset($detail['quantities']))
                                @foreach($detail['quantities'] as $partQuantity)
                                    @php
                                        if($partQuantity['category'] =="Metal" && $partQuantity['imt_23']=="Yes"){
                                             $imt23MetalTotalAmount += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                         }

                                         if($partQuantity['category'] =="Rubber" && $partQuantity['imt_23']=="Yes"){
                                             $imt23RubberTotalAmount += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                         }
                                    @endphp
                                    @if($partQuantity['gst'] == $value)
                                        @php
                                            // Calculate totals for subparts with the same GST percentage
                                            switch ($partQuantity['category']) {
                                                case 'Metal':
                                                    $totalPartMetalAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                    break;
                                                case 'Rubber':
                                                    $totalPartRubberAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                    break;
                                                case 'Glass':
                                                    $totalPartGlassAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                    break;
                                            }
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        @if($lossAssessment[0]['MutipleGSTonParts']==1)

                            @if($lossAssessment[0]['GSTEstimatedPartsPer'] !=Null || $lossAssessment[0]['GSTAssessedPartsPer'] !=Null)
                                <tr>
                                    @if($lossAssessment[0]['GSTEstimatedPartsPer']==0 && $lossAssessment[0]['GSTEstimatedPartsPer'] == $value)
                                        <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with GST {{ $value }}%)</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalMetalAmt + $totalPartMetalAmt, 2) }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalRubberAmt + $totalPartRubberAmt, 2) }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalGlassAmt + $totalPartGlassAmt, 2) }}</td>
                                        <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                    @elseif($lossAssessment[0]['GSTAssessedPartsPer']==0 && $lossAssessment[0]['GSTAssessedPartsPer'] == $value)
                                        <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with GST {{ $value }}%)</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstAmt, 2) }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? $lossAssessment[0]['totalMetalAmt'] : '0.00' }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalRubberAmt']) ? $lossAssessment[0]['totalRubberAmt'] : '0.00' }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalGlassAmt']) ? $lossAssessment[0]['totalGlassAmt'] : '0.00' }}</td>
                                        <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                    @else
                                        <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with GST {{ $value }}%)</td>
                                        @if($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTEstimatedPartsPer']==0))
                                            <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                        @elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer']==0))
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstAmt, 2) }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                            <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                        @else
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstAmt, 2) }}</td>
                                        @endif
                                        @if($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer']!=0))
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalMetalAmt + $totalPartMetalAmt, 2) }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalRubberAmt + $totalPartRubberAmt, 2) }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalGlassAmt + $totalPartGlassAmt, 2) }}</td>
                                            <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                        @endif
                                    @endif
                                </tr>
                            @endif
                        @endif
                        @php
                            $totalIMT23MetalAmt = $imt23MetalTotalAmount;
                            $totalIMT23RubberAmt = $imt23RubberTotalAmount;
                        @endphp
                    @endunless
                @endforeach

                @if($lossAssessment[0]['MutipleGSTonParts']==0)
                    @foreach($lossAssessment as $row)
                        <tr>
                            @if($row['GSTEstimatedPartsPer'] == $row['GSTAssessedPartsPer'])
                                <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTEstimatedPartsPer'] }}%)</td>
                            @else
                                <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTEstimatedPartsPer'] }}%)</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">0.00</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">0.00</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">0.00</td>
                                <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTAssessedPartsPer'] }}%)</td>
                            @endif

                            @if($row['GSTEstimatedPartsPer'] == $row['GSTAssessedPartsPer'])
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
                            @else
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">0.00</td>
                            @endif
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? $lossAssessment[0]['totalMetalAmt'] : '0.00' }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalRubberAmt']) ? $lossAssessment[0]['totalRubberAmt'] : '0.00' }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalGlassAmt']) ? $lossAssessment[0]['totalGlassAmt'] : '0.00' }}</td>
                            <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                        </tr>
                    @endforeach
                @endif

                <tr>
                    <td align="right" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? $lossAssessment[0]['totalMetalAmt'] : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalRubberAmt']) ? $lossAssessment[0]['totalRubberAmt'] : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalGlassAmt']) ? $lossAssessment[0]['totalGlassAmt'] : '0.00' }}</td>
                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                </tr>

                <tr>
                    <td align="left" valign="top"  colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">
                        Less Dep @ <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['MetalDepPer']) ? $lossAssessment[0]['MetalDepPer'] : '0.00' }}%</span> on Metal,
                        <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['RubberDepPer']) ? $lossAssessment[0]['RubberDepPer'] : '0.00' }}%</span> on Rub/Plast,
                        <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['GlassDepPer']) ? $lossAssessment[0]['GlassDepPer'] : '0.00' }}%</span> on Glass Parts
                        {{ $lossAssessment[0]['totalFibreAmt'] !== '0.00' ? ( !empty($lossAssessment[0]['FibreDepPer']) ? $lossAssessment[0]['FibreDepPer'] : '0.00' ) . '% on Fibre Parts.' : '' }}
                    </td>
                    <td align="right" valign="top" style="padding: 0px 3px;">-</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">
                        {{ number_format_custom(($lossAssessment[0]['totalMetalAmt'] * $lossAssessment[0]['MetalDepPer']) / 100, 2) }}
                    </td>
                    <td align="right" valign="top" style="padding: 0px 3px;">
                        {{ number_format_custom(($lossAssessment[0]['totalRubberAmt'] * $lossAssessment[0]['RubberDepPer']) / 100, 2) }}
                    </td>
                    <td align="right" valign="top" style="padding: 0px 3px;">
                        {{ number_format_custom(($lossAssessment[0]['totalGlassAmt'] * $lossAssessment[0]['GlassDepPer']) / 100, 2) }}
                    </td>
                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                        {{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                        {{ number_format_custom($lossAssessment[0]['totalMetalAmt'] - ($lossAssessment[0]['totalMetalAmt'] * $lossAssessment[0]['MetalDepPer']) / 100, 2) }}
                    </td>
                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                        {{ number_format_custom($lossAssessment[0]['totalRubberAmt'] - ($lossAssessment[0]['totalRubberAmt'] * $lossAssessment[0]['RubberDepPer']) / 100, 2) }}
                    </td>
                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                        {{ number_format_custom($lossAssessment[0]['totalGlassAmt'] - ($lossAssessment[0]['totalGlassAmt'] * $lossAssessment[0]['GlassDepPer']) / 100, 2) }}
                    </td>
                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                </tr>
                @php
                    $IMTMetalDepAmt = 0;
                    $IMTRubberDepAmt = 0;

                    $totalMetalIMTPercentagesAmount = 0;
                    $totalRubberIMTPercentagesAmount = 0;
                    $totalGlassIMTPercentagesAmount = 0;

                    $subTotalIMTMetalAmt = 0;
                    $subTotalIMTRubberAmt = 0;
                    $subTotalIMTGlassAmt = 0;

                    $IMTMetalDepAmt = floatval($totalIMT23MetalAmt - ($totalIMT23MetalAmt * $lossAssessment[0]['MetalDepPer']) / 100);
                    $IMTRubberDepAmt = floatval($totalIMT23RubberAmt - ($totalIMT23RubberAmt * $lossAssessment[0]['RubberDepPer']) / 100);

                    $totalMetalIMTPercentagesAmount = floatval($totalIMT23MetalAmt - ($totalIMT23MetalAmt * $lossAssessment[0]['IMT23DepPer']) / 100);
                    $totalRubberIMTPercentagesAmount = floatval($totalIMT23RubberAmt - ($totalIMT23RubberAmt * $lossAssessment[0]['IMT23DepPer']) / 100);


                    $subtotalMetalDepAmt = floatval($lossAssessment[0]['totalMetalAmt'] - ($lossAssessment[0]['totalMetalAmt'] * $lossAssessment[0]['MetalDepPer']) / 100);
                    $subTotalIMTMetalAmt =  ($subtotalMetalDepAmt - ($IMTMetalDepAmt * $lossAssessment[0]['IMT23DepPer']) / 100);

                    $subtotalRubberDepAmt = floatval($lossAssessment[0]['totalRubberAmt'] - ($lossAssessment[0]['totalRubberAmt'] * $lossAssessment[0]['RubberDepPer']) / 100);
                    $subTotalIMTRubberAmt =  ($subtotalRubberDepAmt - ($IMTRubberDepAmt * $lossAssessment[0]['IMT23DepPer']) / 100);

                    $subtotalGlassDepAmt = floatval($lossAssessment[0]['totalGlassAmt'] - ($lossAssessment[0]['totalGlassAmt'] * $lossAssessment[0]['GlassDepPer']) / 100);
                    $subTotalIMTGlassAmt =  ($subtotalGlassDepAmt - (floatval($totalGlassIMTPercentagesAmount * $lossAssessment[0]['IMT23DepPer']) / 100));
                @endphp

                @if($totalIMT23MetalAmt !=0 || $totalIMT23RubberAmt != 0)
                    <tr>
                        <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">
                            Less Addl. Deduction of IMT Parts @ <span style="font-weight: bold;">{{$lossAssessment[0]['IMT23DepPer']}}%</span> (*)- Metal - <span style="font-weight: bold;">{{ number_format_custom($totalIMT23MetalAmt - ($totalIMT23MetalAmt * $lossAssessment[0]['MetalDepPer']) / 100, 2) }}</span> Rub/Plast - <span style="font-weight: bold;">{{ number_format_custom($totalIMT23RubberAmt - ($totalIMT23RubberAmt * $lossAssessment[0]['RubberDepPer']) / 100, 2) }}</span></td>
                        <td align="right" valign="top" style="padding: 0px 3px;">-</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">
                            {{ number_format_custom(($IMTMetalDepAmt * $lossAssessment[0]['IMT23DepPer']) / 100, 2) }}
                        </td>
                        <td align="right" valign="top" style="padding: 0px 3px;">
                            {{ number_format_custom(($IMTRubberDepAmt * $lossAssessment[0]['IMT23DepPer']) / 100, 2) }}
                        </td>
                        <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="right" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($subTotalIMTMetalAmt, 2)}}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($subTotalIMTRubberAmt, 2)}}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($subTotalIMTGlassAmt, 2)}}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                    </tr>
                @endif
                <!-- Start Add GST 1st Table Part-->
                @if(isset($lossAssessment[0]['totalFibreAmt']) || isset($lossAssessment[0]['totalReconditionAmt']))
                    @php
                        $uniqueGstRates = []; // Initialize an array to store unique GST rates
                        $subUniqueGstRates = [];
                        $subUniqueGstRates = array_values(array_unique($subPartUniqueValue));
                        sort($subUniqueGstRates);
                        $counter = count($subUniqueGstRates);

                        $imt23MetalTotalAmount=0;
                        $imt23RubberTotalAmount = 0;
                        $imt23FiberTotalAmount = 0;

                        $depAmtIMTMetal =0;
                        $depAmtIMTRubber = 0;
                        $depAmtIMTFibre = 0;
                        $totalIMTMetalAmtAfterIMTDep =0;
                        $totalIMTRubberAmtAfterIMTDep =0;
                        $totalGlassAmtAfterDep = 0;

                        $subTotalEstimateAmt = 0;
                        $subTotalMetalAmt = 0;
                        $subTotalRubberAmt = 0;
                        $subTotalGlassAmt = 0;
                        $subTotalFibreAmt = 0;
                        $subTotalRecondAmt = 0;
                    @endphp
                    @if($lossAssessment[0]['MutipleGSTonParts']==1)

                        @if($lossAssessment[0]['GSTEstimatedPartsPer'] !=Null || $lossAssessment[0]['GSTAssessedPartsPer'] !=Null)

                            @foreach($subUniqueGstRates as $value)
                                @unless(in_array($value, $uniqueGstRates))
                                    @php
                                        $imt23MetalTotalAmountTbl1= 0;
                                        $imt23RubberTotalAmountTbl1 = 0;
                                        $imt23FiberTotalAmountTbl1 = 0;

                                        $depAmtIMTMetalAfterIMTDep =0;
                                        $totalIMTFibreAmtAfterDep =0;
                                        $depAmtIMTFibreAfterIMTDep = 0;
                                        $depAmtIMTRubberAfterIMTDep =0;

                                        $totalEstAmt = 0; // Initialize total estimated amount
                                        $totalEstAmtPercentage = 0;
                                        $totalMetalAmt = 0;
                                        $totalRubberAmt = 0; // Initialize total assessed amount
                                        $totalGlassAmt = 0;
                                        $totalFibreAmt = 0;
                                        $totalReconditionAmt = 0;

                                        $totalPartEstAmt = 0;
                                        $totalPartMetalAmt = 0;
                                        $totalPartRubberAmt = 0;
                                        $totalPartGlassAmt = 0;
                                        $totalPartFibreAmt = 0;
                                        $totalPartReconditionAmt = 0;
                                        $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                                    @endphp

                                    @foreach($alldetails as $detail)
                                        @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
                                        @php

                                            if($detail['category'] =="Metal" && $detail['imt_23']=="Yes"){
                                                $imt23MetalTotalAmountTbl1 += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                             }
                                             if($detail['category'] =="Rubber" && $detail['imt_23']=="Yes"){
                                                  $imt23RubberTotalAmountTbl1 += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                              }

                                              if($detail['category'] =="Fibre" && $detail['imt_23']=="Yes"){
                                                  $imt23FiberTotalAmountTbl1 += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                              }

                                              $totalEstAmt += !empty($detail['est_amt']) ? $detail['est_amt'] : 0; // Sum up estimated amount

                                              // Sum up assessed amount for respective categories
                                          if(empty($detail['quantities'])){
                                              switch ($detail['category']) {
                                                  case 'Metal':
                                                      $totalMetalAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                      break;
                                                  case 'Rubber':
                                                      $totalRubberAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                      break;
                                                  case 'Glass':
                                                      $totalGlassAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                      break;
                                                  case 'Fibre':
                                                          $totalPartFibreAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                          break;
                                                  case 'Recondition':
                                                      $totalPartReconditionAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                      break;
                                                }
                                              }
                                        @endphp
                                        @endif

                                        @if(isset($detail['quantities']))
                                            @foreach($detail['quantities'] as $partQuantity)
                                                @if($partQuantity['gst'] == $value)
                                                    @php
                                                        if($partQuantity['category'] =="Metal" && $partQuantity['imt_23']=="Yes"){
                                                             $imt23MetalTotalAmountTbl1 += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                          }

                                                          if($partQuantity['category'] =="Rubber" && $partQuantity['imt_23']=="Yes"){
                                                              $imt23RubberTotalAmountTbl1 += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                          }

                                                          if($partQuantity['category'] =="Fibre" && $partQuantity['imt_23']=="Yes"){
                                                              $imt23FiberTotalAmountTbl1 += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                          }

                                                          // Calculate totals for subparts with the same GST percentage
                                                          switch ($partQuantity['category']) {
                                                              case 'Metal':
                                                                  $totalPartMetalAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                  break;
                                                              case 'Rubber':
                                                                  $totalPartRubberAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                  break;
                                                              case 'Glass':
                                                                  $totalPartGlassAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                  break;
                                                              case 'Fibre':
                                                                  $totalPartFibreAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                  break;
                                                              case 'Recondition':
                                                              $totalPartReconditionAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                              break;
                                                          }
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                    <tr>
                                        @php
                                            $totalMetalIMTPart1 = $imt23MetalTotalAmountTbl1;
                                            $totalRubberIMTPart1 = $imt23RubberTotalAmountTbl1;
                                            $totalFibreIMTPart1 = $imt23FiberTotalAmountTbl1;

                                            $totalMetalGST = $totalMetalAmt + $totalPartMetalAmt;
                                            $totalRubberGST = $totalRubberAmt + $totalPartRubberAmt;
                                            $totalGlassGST = $totalGlassAmt + $totalPartGlassAmt;
                                            $totalFibreGST = $totalPartFibreAmt;
                                            $totalRecondtionGST = $totalReconditionAmt + $totalPartReconditionAmt;

                                            $depAmtMetal = (($totalMetalGST * $lossAssessment[0]['MetalDepPer']) / 100);
                                            $totalMetalAmtAfterDep = ($totalMetalGST - $depAmtMetal);

                                            $depAmtRubber = (($totalRubberGST * $lossAssessment[0]['RubberDepPer']) / 100);
                                            $totalRubberAmtAfterDep = ($totalRubberGST - $depAmtRubber);

                                            $depAmtGlass = (($totalGlassGST * $lossAssessment[0]['GlassDepPer']) / 100);
                                            $totalGlassAmtAfterDep = ($totalGlassGST - $depAmtGlass);
                                           // dd($totalMetalIMTPart1);
                                            if($imt23MetalTotalAmountTbl1){
                                                $depAmtIMTMetal = (($totalMetalIMTPart1 * $lossAssessment[0]['MetalDepPer']) / 100);
                                                $totalIMTMetalAmtAfterDep = ($totalMetalIMTPart1 - $depAmtIMTMetal);
                                                $depAmtIMTMetalAfterIMTDep = (($totalIMTMetalAmtAfterDep * $lossAssessment[0]['IMT23DepPer']) / 100);
                                                $totalIMTMetalAmtAfterIMTDep = ($totalMetalAmtAfterDep - $depAmtIMTMetalAfterIMTDep);
                                               // dd($totalIMTMetalAmtAfterIMTDep);
                                            }else{
                                                $totalIMTMetalAmtAfterIMTDep = ($totalMetalGST - $depAmtMetal);;
                                            }

                                            if($imt23RubberTotalAmountTbl1){
                                                $depAmtIMTRubber = (($totalRubberIMTPart1 * $lossAssessment[0]['RubberDepPer']) / 100);
                                                $totalIMTRubberAmtAfterDep = ($totalRubberIMTPart1 - $depAmtIMTRubber);
                                                $depAmtIMTRubberAfterIMTDep = (($totalIMTRubberAmtAfterDep * $lossAssessment[0]['IMT23DepPer']) / 100);
                                                $totalIMTRubberAmtAfterIMTDep = ($totalRubberAmtAfterDep - $depAmtIMTRubberAfterIMTDep);
                                            }else{
                                               $totalIMTRubberAmtAfterIMTDep = ($totalRubberGST - $depAmtRubber);
                                            }
                                        @endphp
                                        @if(($lossAssessment[0]['GSTEstimatedPartsPer']==0 || $lossAssessment[0]['GSTEstimatedPartsPer'] == NULL) && ($lossAssessment[0]['GSTAssessedPartsPer'] !=0 || $lossAssessment[0]['GSTAssessedPartsPer'] != NULL))
                                            <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $value }}%</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? number_format_custom(($totalEstAmt * $lossAssessment[0]['GSTEstimatedPartsPer'] / 100), 2) : '0.00' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTMetalAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTRubberAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalGlassAmtAfterDep * $value / 100), 2) : '0.00' }}</td>
                                            <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                        @elseif(($lossAssessment[0]['GSTAssessedPartsPer']==0 || $lossAssessment[0]['GSTAssessedPartsPer'] == NULL) && ($lossAssessment[0]['GSTEstimatedPartsPer'] !=0 || $lossAssessment[0]['GSTEstimatedPartsPer'] != NULL))
                                            <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $value }}%</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? number_format_custom(($totalEstAmt * $value / 100), 2) : '0.00' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTMetalAmtAfterIMTDep * $lossAssessment[0]['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTRubberAmtAfterIMTDep * $lossAssessment[0]['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalGlassAmtAfterDep * $lossAssessment[0]['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                            <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                        @else
                                            <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $value }}%</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? number_format_custom(($totalEstAmt * $value / 100), 2) : '0.00' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTMetalAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTRubberAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalGlassAmtAfterDep * $value / 100), 2) : '0.00' }}</td>
                                            <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                    @endif

                                    <!--@if(($lossAssessment[0]['GSTAssessedPartsPer']==0 || $lossAssessment[0]['GSTAssessedPartsPer'] == NULL) && ($lossAssessment[0]['GSTEstimatedPartsPer'] !=0 || $lossAssessment[0]['GSTEstimatedPartsPer'] != NULL))-->
                                    <!--     <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $value }}%</td>-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? number_format_custom(($lossAssessment[0]['total_EstAmt'] * $value / 100), 2) : '0.00' }}</td>-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTMetalAmtAfterIMTDep * $lossAssessment[0]['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTRubberAmtAfterIMTDep * $lossAssessment[0]['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalGlassAmtAfterDep * $lossAssessment[0]['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>-->
                                        <!--     <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>-->
                                        <!--@endif-->
                                    <!--@if($lossAssessment[0]['GSTEstimatedPartsPer']==0 && $lossAssessment[0]['GSTEstimatedPartsPer'] == $value)-->
                                    <!--     <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $value }}%</td>-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalMetalAmt + $totalPartMetalAmt, 2) }}</td>-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalRubberAmt + $totalPartRubberAmt, 2) }}</td>-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalGlassAmt + $totalPartGlassAmt, 2) }}</td>-->
                                        <!--     <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>-->

                                    <!-- @elseif($lossAssessment[0]['GSTAssessedPartsPer']==0 && $lossAssessment[0]['GSTAssessedPartsPer'] == $value)-->
                                    <!--    <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $value }}%</td>-->
                                    <!--    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalEstAmt * $value / 100), 2) : '0.00' }}</td>-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? $lossAssessment[0]['totalMetalAmt'] : '0.00' }}</td>-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalRubberAmt']) ? $lossAssessment[0]['totalRubberAmt'] : '0.00' }}</td>-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalGlassAmt']) ? $lossAssessment[0]['totalGlassAmt'] : '0.00' }}</td>-->
                                        <!--     <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>-->
                                        <!--     @else-->
                                    <!--     <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $value }}%</td>-->
                                    <!--     @if($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTEstimatedPartsPer']==0))-->
                                        <!--     <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>-->
                                    <!--     @elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer']==0))-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalEstAmt * $value / 100), 2) : '0.00' }}</td>-->
                                        <!--     <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>-->
                                        <!--     <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>-->
                                        <!--     <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>-->
                                        <!--     <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>-->
                                        <!--     @else-->
                                    <!--     <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalEstAmt * $value / 100), 2) : '0.00' }}</td>-->
                                        <!--     @endif-->
                                    <!--     @if($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer']!=0))     -->
                                    <!--       <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTMetalAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>-->
                                    <!--       <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTRubberAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>-->
                                    <!--       <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalGlassAmtAfterDep * $value / 100), 2) : '0.00' }}</td>-->
                                        <!--       <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>-->
                                        <!--     @endif-->
                                        <!-- @endif-->
                                    </tr>
                                    @php
                                        $subTotalEstimateAmt += floatval($totalEstAmt * $value / 100);
                                        $subTotalMetalAmt += floatval($totalIMTMetalAmtAfterIMTDep * $value / 100);
                                        $subTotalRubberAmt += floatval($totalIMTRubberAmtAfterIMTDep * $value / 100);
                                        $subTotalGlassAmt += floatval($totalGlassAmtAfterDep * $value / 100);
                                    @endphp
                                @endunless
                            @endforeach

                        @endif

                    @else
                        @foreach($lossAssessment as $row)
                            <tr>
                                @if($row['GSTEstimatedPartsPer'] == $row['GSTAssessedPartsPer'])
                                    <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTEstimatedPartsPer'] }}%</td>
                                @else
                                    <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTEstimatedPartsPer'] }}%</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? number_format_custom(($lossAssessment[0]['total_EstAmt'] * $row['GSTEstimatedPartsPer'] / 100), 2) : '0.00' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">0.00</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">0.00</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">0.00</td>
                                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="left" valign="top" colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTAssessedPartsPer'] }}%</td>
                                @endif

                                @if($row['GSTEstimatedPartsPer'] != 0 && $row['GSTAssessedPartsPer'] != 0)
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? number_format_custom(($lossAssessment[0]['total_EstAmt'] * $row['GSTEstimatedPartsPer'] / 100), 2) : '0.00' }}</td>
                                @else
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">0.00</td>
                                @endif
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? number_format_custom(($subTotalIMTMetalAmt * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalRubberAmt']) ? number_format_custom(($subTotalIMTRubberAmt * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalGlassAmt']) ? number_format_custom(($subTotalIMTGlassAmt * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                            </tr>
                            @php
                                $subTotalEstimateAmt += floatval($lossAssessment[0]['total_EstAmt'] * $row['GSTEstimatedPartsPer'] / 100);
                                $subTotalMetalAmt += floatval($lossAssessment[0]['totalMetalAmt'] * $row['GSTAssessedPartsPer'] / 100);
                                $subTotalRubberAmt += floatval($lossAssessment[0]['totalRubberAmt'] * $row['GSTAssessedPartsPer'] / 100);
                                $subTotalGlassAmt += floatval($lossAssessment[0]['totalGlassAmt'] * $row['GSTAssessedPartsPer'] / 100);
                            @endphp
                        @endforeach
                    @endif
                @endif
                <!-- End GST Part-->

                @php

                    $totalMetalAmt = $subTotalMetalAmt + $subTotalIMTMetalAmt;
                    $totalRubberAmt = $subTotalRubberAmt + $subTotalIMTRubberAmt;
                    $totalGlassAmt = $subTotalGlassAmt + $subTotalIMTGlassAmt;
                @endphp
                <tr>
                    <td align="right" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                    @if($lossAssessment[0]['GSTEstimatedPartsPer']) !=0)
                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{number_format_custom($subTotalEstimateAmt + (!empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00'), 2) }}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{number_format_custom((!empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00'), 2) }}</td>
                    @endif
                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom((!empty($lossAssessment[0]['partMetalAssamount']) ? ($lossAssessment[0]['partMetalAssamount'] + $lossAssessment[0]['totalMetalIMTAmt']) : '0.00'), 2) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom((!empty($lossAssessment[0]['partRubberAssamount']) ? ($lossAssessment[0]['partRubberAssamount'] + $lossAssessment[0]['totalRubberIMTAmt']) : '0.00'), 2) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom((!empty($lossAssessment[0]['partGlassAssamount']) ? $lossAssessment[0]['partGlassAssamount'] : '0.00'), 2) }}</td>
                <!--<td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($subTotalMetalAmt + $subTotalIMTMetalAmt, 2)}}</td>-->
                <!--<td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($subTotalRubberAmt + $subTotalIMTRubberAmt, 2)}}</td>-->
                <!--<td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($subTotalGlassAmt + $subTotalIMTGlassAmt, 2)}}</td>-->
                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                </tr>
                <!-- End Sub Total GST Part-->
                <tr>
                    <td align="right" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;font-weight: bold; border-left: 1px solid #000;">Total</td>
                    <td align="right" valign="top" colspan="5"
                        style="padding: 0px 3px; border-bottom: 2px solid #000; border-top: 2px solid #000;font-weight: bold;">{{number_format_custom(
            (!empty($lossAssessment[0]['partMetalAssamount']) ? ($lossAssessment[0]['partMetalAssamount'] + $lossAssessment[0]['totalMetalIMTAmt']) : 0.00) +
            (!empty($lossAssessment[0]['partRubberAssamount']) ? ($lossAssessment[0]['partRubberAssamount'] + $lossAssessment[0]['totalRubberIMTAmt']) : 0.00) +
            (!empty($lossAssessment[0]['partGlassAssamount']) ? $lossAssessment[0]['partGlassAssamount'] : 0.00),
            2
        )}}
                    </td>
                </tr>
            </tbody>
        </table>
    @endif

    <!-- End 1st table loss of assessment -->
@else


    <div class="page-break" />
    <!-- Start 2nd table loss of assessment -->
    @if(!empty($lossAssessment) && isset($lossAssessment[0]['totalReconditionAmt']) && isset($lossAssessment[0]['totalFibreAmt']) && ($lossAssessment[0]['totalReconditionAmt'] !== "0.00" || $lossAssessment[0]['totalFibreAmt'] !== "0.00"))
        @php
            $colspan = 3; // Default colspan value
            $maincolspan = 0;
            // Checking if the conditions are met to increase colspan

           $dynamicColspan = $colspan;
            if ($lossAssessment[0]['totalFibreAmt'] == '0.00' && $lossAssessment[0]['totalReconditionAmt'] !== '0.00') {
                $dynamicColspan++; // Decrease colspan by 1 if totalFibreAmt is '0.00'
            } elseif ($lossAssessment[0]['totalReconditionAmt'] == '0.00' && $lossAssessment[0]['totalFibreAmt'] !== '0.00') {
                $dynamicColspan++; // Decrease colspan by 1 if totalReconditionAmt is '0.00'
            }

            // Check if both are non-zero
            if ($lossAssessment[0]['totalFibreAmt'] !== '0.00' && $lossAssessment[0]['totalReconditionAmt'] !== '0.00') {
                $dynamicColspan = 4; // Set colspan to 5 if both are non-zero

                $maincolspan = $dynamicColspan + 1;

            }

            //dd($dynamicColspan);
        @endphp

        <!--LOSS ASSESSMENT All Category Show  -->
        <div style="width: 100%;">
            <table width="100%" align="center">
                <tbody>
                <tr>
                    <td align="left" valign="top" style="width: 40%; font-weight:bold; border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 3px 0px;  ">ASSESSMENT OF LOSS</td>
                    <td align="left" valign="top" style="width: 60%; font-weight:bold; border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 3px 0px;">(PARTS)</td>
                </tr>
                </tbody>
            </table>
            <table width="100%" border="0" align="center" id="design" style="font-size: 12px;">
                <tbody>
                <tr>
                    <td align="left" valign="top" style="padding: 0px 1px; font-weight:bold; border-left: solid 1px #000;">Sr. No.</td>
                    <td align="left" valign="top" style="padding: 0px 1px; font-weight:bold; width:25%;">Description <br />of Parts</td>
                    <td align="center" valign="top" style="padding: 0px 1px; font-weight:bold; width:10%;">Remark</td>
                    <td align="right" valign="top" style="padding: 0px 1px; font-weight:bold; width:5%;">GST <br />%</td>
                    <td align="right" valign="top" style="padding: 0px 1px; font-weight:bold; width:10%;">Estimated<br /><span style="font-weight: 400;">(Amt in Rs)</span></td>
                    <td align="right" valign="top" colspan="{{ ($maincolspan == 0) ? $dynamicColspan : $maincolspan }}" style="padding: 0px 1px; width:45%;">
                        <table width="100%" align="center">
                            <tbody>
                            <tr>
                                <td align="center" valign="top" colspan="{{ ($maincolspan == 0) ? $dynamicColspan : $maincolspan }}" style="padding-bottom: 5px; border: none; font-weight: bold;">Assessed Parts Amount</td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" style="width: 20%; padding: 0px 3px; border-top: 2px solid #000; border-left: 0px solid #000; border-right: none; border-bottom: none; ">Metal
                                </td>
                                <td align="center" valign="top" style="width: 20%; padding: 0px 3px; border-top: 2px solid #000; border-left: 0px solid #000; border-right: none; border-bottom: none;">
                                    Rub/Plast</td>
                                <td align="center" valign="top" style="width: 20%; padding: 0px 3px; border-top: 2px solid #000; border-left: 0px solid #000; border-right: none; border-bottom: none;">Glass
                                </td>
                                @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                    <td align="center" valign="top" style="width: 20%; padding: 0px 3px; border-top: 2px solid #000; border-left: 0px solid #000; border-right: none; border-bottom: none;">Fibre
                                    </td>
                                @endif
                                @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                    <td align="center" valign="top" style="width: 20%; padding: 0px 3px; border-top: 2px solid #000; border-left: 0px solid #000; border-right: none; border-bottom: none;">Recond.
                                    </td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                @if(isset($lossAssessment[0]['alldetails']))
                    @php
                        $alldetails = json_decode($lossAssessment[0]['alldetails'], true);
                        $subPartUniqueValue=[];
                        $indexCounter = 0;

                    @endphp
                    @if(is_array($alldetails))
                        @foreach($alldetails as $index => $detail)
                            @if(isset($detail['category']) && ($detail['category'] != null || $detail['category'] !=""))
                                @php
                                    $subPartUniqueValue[]= $detail['gst'];
                                    $indexCounter++;
                                @endphp

                                <tr>
                                    <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">{{ $indexCounter }}</td>
                                    @if($detail['imt_23'] == "Yes")
                                        <td align="left" valign="top" style="padding: 0px 3px;"><strong>*</strong> {{ $detail['description'] }}</td>
                                    @else
                                        <td align="left" valign="top" style="padding: 0px 3px;">{{ $detail['description'] }}</td>
                                    @endif
                                    <td align="center" valign="top" style="padding: 0px 3px;">{{ $detail['remarks'] ?? '-' }}</td>
                                    <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($detail['gst']) ? $detail['gst'] : '0' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['est_amt']) ? $detail['est_amt'] : '0.00' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['category']=="Metal") ? $detail['ass_amt'] : '-' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['category']=="Rubber") ? $detail['ass_amt'] : '-' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['category']=="Glass") ? $detail['ass_amt'] : '-' }}</td>
                                    @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['category']=="Fibre") ? $detail['ass_amt'] : '-' }}</td>
                                    @endif
                                    @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['category']=="Recondition") ? $detail['ass_amt'] : '-' }}</td>
                                    @endif
                                </tr>
                            @endif
                            @if (!empty($detail['quantities']))
                                @foreach($detail['quantities'] as $quantityIndex => $quantity)
                                    @if(isset($quantity['category']) && ($quantity['category'] != null || $quantity['category'] !=""))
                                        @php
                                            $subPartUniqueValue[]= $quantity['gst'];
                                        @endphp
                                        <tr>
                                            <td align="center" valign="top" style="padding: 0px 3px; padding-left: 20px; font-style: italic; border-left: 1px solid #000;">{{ $indexCounter }}.{{ $quantityIndex + 1 }}</td>
                                            @if($quantity['imt_23'] == "Yes")
                                                <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;"><strong>*</strong> {{ $quantity['description'] }}</td>
                                            @else
                                                <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;">{{ $quantity['description'] }}</td>
                                            @endif
                                            <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">{{ $quantity['remarks'] ?? '-' }}</td>
                                            <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['gst']) ? $quantity['gst'] : '0' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['est_amt']) ? $quantity['est_amt'] : '0.00' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['category']=="Metal") ? $quantity['ass_amt'] : '-' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['category']=="Rubber") ? $quantity['ass_amt'] : '-' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['category']=="Glass") ? $quantity['ass_amt'] : '-' }}</td>
                                            @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                                <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['category']=="Fibre") ? $quantity['ass_amt'] : '-' }}</td>
                                            @endif
                                            @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                                <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['category']=="Recondition") ? $quantity['ass_amt'] : '-' }}</td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif


                    @if(isset($lossAssessment[0]['totalFibreAmt']) || isset($lossAssessment[0]['totalReconditionAmt']))
                        @php
                            $uniqueGstRates = []; // Initialize an array to store unique GST rates
                            $subUniqueGstRates = [];
                            $subUniqueGstRates = array_values(array_unique($subPartUniqueValue));
                            sort($subUniqueGstRates);
                            $counter = count($subUniqueGstRates);
                            $totalIMT23MetalAmt = 0;
                            $totalIMT23RubberAmt = 0;
                            $totalIMT23FibreAmt = 0;

                            $imt23MetalTotalAmount=0;
                            $imt23RubberTotalAmount = 0;
                            $imt23FiberTotalAmount =0;
                        @endphp
                        @if($lossAssessment[0]['MutipleGSTonParts']==1)

                            @if($lossAssessment[0]['GSTEstimatedPartsPer'] !=Null || $lossAssessment[0]['GSTAssessedPartsPer'] !=Null)

                                @foreach($subUniqueGstRates as $value)
                                    @unless(in_array($value, $uniqueGstRates))
                                        @php
                                            $totalEstAmt = 0; // Initialize total estimated amount
                                            $totalMetalAmt = 0;
                                            $totalRubberAmt = 0; // Initialize total assessed amount
                                            $totalGlassAmt = 0;
                                            $totalFibreAmt = 0;
                                            $totalReconditionAmt = 0;

                                            $imt23MetalTotalAmount=0;
                                            $imt23RubberTotalAmount = 0;
                                            $imt23FiberTotalAmount =0;

                                            $totalPartEstAmt = 0;
                                            $totalPartMetalAmt = 0;
                                            $totalPartRubberAmt = 0;
                                            $totalPartGlassAmt = 0;
                                            $totalPartFibreAmt = 0;
                                            $totalPartReconditionAmt = 0;
                                            $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                                        @endphp

                                        @foreach($alldetails as $detail)
                                            @php
                                                if($detail['category'] =="Metal" && $detail['imt_23']=="Yes"){
                                                    $imt23MetalTotalAmount += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                 }
                                                 if($detail['category'] =="Rubber" && $detail['imt_23']=="Yes"){
                                                      $imt23RubberTotalAmount += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                  }

                                                  if($detail['category'] =="Fibre" && $detail['imt_23']=="Yes"){
                                                      $imt23FiberTotalAmount += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                  }
                                            @endphp
                                            @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
                                            @php

                                                // Sum up assessed amount for respective categories
                                            if(empty($detail['quantities'])){

                                                $totalEstAmt += !empty($detail['est_amt']) ? $detail['est_amt'] : 0;
                                                switch ($detail['category']) {
                                                    case 'Metal':
                                                        $totalMetalAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                        break;
                                                    case 'Rubber':
                                                        $totalRubberAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                        break;
                                                    case 'Glass':
                                                        $totalGlassAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                        break;
                                                    case 'Fibre':
                                                            $totalPartFibreAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                            break;
                                                    case 'Recondition':
                                                        $totalPartReconditionAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                        break;
                                                  }
                                                }
                                            @endphp
                                            @endif

                                            @if(isset($detail['quantities']))
                                                @foreach($detail['quantities'] as $partQuantity)
                                                    @php
                                                        if($partQuantity['category'] =="Metal" && $partQuantity['imt_23']=="Yes"){
                                                             $imt23MetalTotalAmount += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                         }

                                                         if($partQuantity['category'] =="Rubber" && $partQuantity['imt_23']=="Yes"){
                                                             $imt23RubberTotalAmount += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                         }

                                                         if($partQuantity['category'] =="Fibre" && $partQuantity['imt_23']=="Yes"){
                                                             $imt23FiberTotalAmount += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                         }
                                                    @endphp
                                                    @if($partQuantity['gst'] == $value)
                                                        @php
                                                            // Calculate totals for subparts with the same GST percentage
                                                            switch ($partQuantity['category']) {
                                                                case 'Metal':
                                                                    $totalPartMetalAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                    break;
                                                                case 'Rubber':
                                                                    $totalPartRubberAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                    break;
                                                                case 'Glass':
                                                                    $totalPartGlassAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                    break;
                                                                case 'Fibre':
                                                                    $totalPartFibreAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                    break;
                                                                case 'Recondition':
                                                                $totalPartReconditionAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                break;
                                                            }
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach


                                        <tr>
                                            @if($lossAssessment[0]['GSTEstimatedPartsPer']==0 && $lossAssessment[0]['GSTEstimatedPartsPer'] == $value)

                                                <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with GST {{ $value }}%)</td>
                                                <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
                                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalMetalAmt + $totalPartMetalAmt, 2) }}</td>
                                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalRubberAmt + $totalPartRubberAmt, 2) }}</td>
                                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalGlassAmt + $totalPartGlassAmt, 2) }}</td>
                                                @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalFibreAmt + $totalPartFibreAmt, 2) }}</td>
                                                @endif
                                                @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalReconditionAmt + $totalPartReconditionAmt, 2) }}</td>
                                                @endif
                                            @elseif($lossAssessment[0]['GSTAssessedPartsPer']==0 && $lossAssessment[0]['GSTAssessedPartsPer'] == $value)
                                                <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with GST {{ $value }}%)</td>
                                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstAmt, 2) }}</td>
                                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? $lossAssessment[0]['totalMetalAmt'] : '0.00' }}</td>
                                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalRubberAmt']) ? $lossAssessment[0]['totalRubberAmt'] : '0.00' }}</td>
                                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalGlassAmt']) ? $lossAssessment[0]['totalGlassAmt'] : '0.00' }}</td>
                                                @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalFibreAmt']) ? $lossAssessment[0]['totalFibreAmt'] : '0.00' }}</td>
                                                @endif
                                                @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalReconditionAmt']) ? $lossAssessment[0]['totalReconditionAmt'] : '0.00' }}</td>
                                                @endif
                                            @else
                                                <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with GST {{ $value }}%)</td>
                                                @if($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTEstimatedPartsPer']==0))
                                                    <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                                @elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer']==0))
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstAmt, 2) }}</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                                @else
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstAmt, 2) }}</td>
                                                @endif
                                                @if($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer']!=0))
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalMetalAmt + $totalPartMetalAmt, 2) }}</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalRubberAmt + $totalPartRubberAmt, 2) }}</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalGlassAmt + $totalPartGlassAmt, 2) }}</td>
                                                    @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalFibreAmt + $totalPartFibreAmt, 2) }}</td>
                                                    @endif
                                                    @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalReconditionAmt + $totalPartReconditionAmt, 2) }}</td>
                                                    @endif
                                                @endif

                                            @endif
                                        </tr>
                                        @php
                                            $totalIMT23MetalAmt = $imt23MetalTotalAmount;
                                            $totalIMT23RubberAmt = $imt23RubberTotalAmount;
                                            $totalIMT23FibreAmt = $imt23FiberTotalAmount;
                                        @endphp
                                    @endunless
                                @endforeach
                            @endif
                        @else

                            @foreach($lossAssessment as $row)
                                <tr>
                                    @if($row['GSTEstimatedPartsPer'] == $row['GSTAssessedPartsPer'])
                                        <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTEstimatedPartsPer'] }}%)</td>
                                    @else
                                        <td align="left" valign="top" colspan="{{ !empty($lossAssessment[0]['totalReconditionAmt']) ? $colspan - 1 : $colspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTEstimatedPartsPer'] }}%)</td>
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
                                        @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
                                        @endif
                                        @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
                                        @endif
                                </tr>
                                <tr>
                                    <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTAssessedPartsPer'] }}%)</td>
                                    @endif

                                    @if($row['GSTEstimatedPartsPer'] == $row['GSTAssessedPartsPer'])
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
                                    @else
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
                                    @endif
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? $lossAssessment[0]['totalMetalAmt'] : '0.00' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalRubberAmt']) ? $lossAssessment[0]['totalRubberAmt'] : '0.00' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalGlassAmt']) ? $lossAssessment[0]['totalGlassAmt'] : '0.00' }}</td>
                                    @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalFibreAmt']) ? $lossAssessment[0]['totalFibreAmt'] : '0.00' }}</td>
                                    @endif
                                    @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalReconditionAmt']) ? $lossAssessment[0]['totalReconditionAmt'] : '0.00' }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                        <tr>
                            <td align="right" valign="top"   colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Total</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? $lossAssessment[0]['totalMetalAmt'] : '0.00' }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalRubberAmt']) ? $lossAssessment[0]['totalRubberAmt'] : '0.00' }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalGlassAmt']) ? $lossAssessment[0]['totalGlassAmt'] : '0.00' }}</td>
                            @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalFibreAmt']) ? $lossAssessment[0]['totalFibreAmt'] : '0.00' }}</td>
                            @endif
                            @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalReconditionAmt']) ? $lossAssessment[0]['totalReconditionAmt'] : '0.00' }}</td>
                            @endif
                        </tr>

                        <tr>
                            <td align="left" valign="top"  colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">
                                Less Dep @ <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['MetalDepPer']) ? $lossAssessment[0]['MetalDepPer'] : '0.00' }}%</span> on Metal,
                                <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['RubberDepPer']) ? $lossAssessment[0]['RubberDepPer'] : '0.00' }}%</span>on Rub/Plast,
                                <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['GlassDepPer']) ? $lossAssessment[0]['GlassDepPer'] : '0.00' }}%</span> on Glass Parts
                                {{ $lossAssessment[0]['totalFibreAmt'] !== '0.00' ? ( !empty($lossAssessment[0]['FibreDepPer']) ? $lossAssessment[0]['FibreDepPer'] : '0.00' ) . '% on Fibre Parts.' : '' }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px;">-</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">
                                {{ number_format_custom(($lossAssessment[0]['totalMetalAmt'] * $lossAssessment[0]['MetalDepPer']) / 100, 2) }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px;">
                                {{ number_format_custom(($lossAssessment[0]['totalRubberAmt'] * $lossAssessment[0]['RubberDepPer']) / 100, 2) }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px;">
                                {{ number_format_custom(($lossAssessment[0]['totalGlassAmt'] * $lossAssessment[0]['GlassDepPer']) / 100, 2) }}
                            </td>
                            @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                <td align="right" valign="top" style="padding: 0px 3px;">
                                    {{ number_format_custom(($lossAssessment[0]['totalFibreAmt'] * $lossAssessment[0]['FibreDepPer']) / 100, 2) }}
                                </td>
                            @endif
                            @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                            @endif
                        </tr>

                        <tr>
                            <td align="right" valign="top"   colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                                {{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                                {{ number_format_custom($lossAssessment[0]['totalMetalAmt'] - ($lossAssessment[0]['totalMetalAmt'] * $lossAssessment[0]['MetalDepPer']) / 100, 2) }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                                {{ number_format_custom($lossAssessment[0]['totalRubberAmt'] - ($lossAssessment[0]['totalRubberAmt'] * $lossAssessment[0]['RubberDepPer']) / 100, 2) }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                                {{ number_format_custom($lossAssessment[0]['totalGlassAmt'] - ($lossAssessment[0]['totalGlassAmt'] * $lossAssessment[0]['GlassDepPer']) / 100, 2) }}
                            </td>
                            @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                                    {{ number_format_custom($lossAssessment[0]['totalFibreAmt'] - ($lossAssessment[0]['totalFibreAmt'] * $lossAssessment[0]['FibreDepPer']) / 100, 2) }}
                                </td>
                            @endif
                            @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                                    {{ !empty($lossAssessment[0]['totalReconditionAmt']) ? $lossAssessment[0]['totalReconditionAmt'] : '0.00' }}</td>
                            @endif
                        </tr>

                        @php
                            $IMTMetalDepAmt = 0;
                            $IMTRubberDepAmt =0;
                            $IMTFibreDepAmt = 0;

                           $totalMetalIMTPercentagesAmount = 0;
                           $totalRubberIMTPercentagesAmount = 0;
                           $totalGlassIMTPercentagesAmount = 0;
                           $totalFiberIMTPercentagesAmount = 0;
                           $totalRecondIMTPercentagesAmount = 0;

                           $subTotalIMTMetalAmt = 0;
                           $subTotalIMTRubberAmt = 0;
                           $subTotalIMTGlassAmt = 0;
                           $subTotalIMTFiberAmt = 0;
                           $subTotalIMTRecondAmt = 0;

                           $IMTMetalDepAmt = floatval($totalIMT23MetalAmt - ($totalIMT23MetalAmt * $lossAssessment[0]['MetalDepPer']) / 100);
                           $IMTRubberDepAmt = floatval($totalIMT23RubberAmt - ($totalIMT23RubberAmt * $lossAssessment[0]['RubberDepPer']) / 100);
                           $IMTFibreDepAmt = floatval($totalIMT23FibreAmt - ($totalIMT23FibreAmt * $lossAssessment[0]['FibreDepPer']) / 100);

                           $totalMetalIMTPercentagesAmount = floatval($imt23MetalTotalAmount - ($imt23MetalTotalAmount * $lossAssessment[0]['IMT23DepPer']) / 100);
                           $totalRubberIMTPercentagesAmount = floatval($imt23RubberTotalAmount - ($imt23RubberTotalAmount * $lossAssessment[0]['IMT23DepPer']) / 100);
                           $totalFiberIMTPercentagesAmount = floatval($imt23FiberTotalAmount - ($imt23FiberTotalAmount * $lossAssessment[0]['IMT23DepPer']) / 100);


                           $subtotalMetalDepAmt = floatval($lossAssessment[0]['totalMetalAmt'] - ($lossAssessment[0]['totalMetalAmt'] * $lossAssessment[0]['MetalDepPer']) / 100);
                           $subTotalIMTMetalAmt =  ($subtotalMetalDepAmt - ($IMTMetalDepAmt * $lossAssessment[0]['IMT23DepPer']) / 100);

                           $subtotalRubberDepAmt = floatval($lossAssessment[0]['totalRubberAmt'] - ($lossAssessment[0]['totalRubberAmt'] * $lossAssessment[0]['RubberDepPer']) / 100);
                           $subTotalIMTRubberAmt =  ($subtotalRubberDepAmt - ($IMTRubberDepAmt * $lossAssessment[0]['IMT23DepPer']) / 100);

                           $subtotalGlassDepAmt = floatval($lossAssessment[0]['totalGlassAmt'] - ($lossAssessment[0]['totalGlassAmt'] * $lossAssessment[0]['GlassDepPer']) / 100);
                           $subTotalIMTGlassAmt =  ($subtotalGlassDepAmt - (floatval($totalGlassIMTPercentagesAmount * $lossAssessment[0]['IMT23DepPer']) / 100));

                           $subtotalFiberDepAmt = floatval($lossAssessment[0]['totalFibreAmt'] - ($lossAssessment[0]['totalFibreAmt'] * $lossAssessment[0]['FibreDepPer']) / 100);
                           $subTotalIMTFiberAmt =  ($subtotalFiberDepAmt - ($IMTFibreDepAmt * $lossAssessment[0]['IMT23DepPer']) / 100);
                        @endphp
                        @if($totalIMT23MetalAmt !=0 || $totalIMT23RubberAmt != 0)
                            <tr>
                                <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">
                                    Less Addl. Deduction of IMT Parts (*)- Metal - <span style="font-weight: bold;">{{ number_format_custom($totalIMT23MetalAmt - ($totalIMT23MetalAmt * $lossAssessment[0]['MetalDepPer']) / 100, 2) }}</span> Rub/Plast - <span style="font-weight: bold;">{{ number_format_custom($totalIMT23RubberAmt - ($totalIMT23RubberAmt * $lossAssessment[0]['RubberDepPer']) / 100, 2) }}</span> Fiber - <span style="font-weight: bold;">{{ number_format_custom($totalIMT23FibreAmt - ($totalIMT23FibreAmt * $lossAssessment[0]['FibreDepPer']) / 100, 2) }}</span>
                                </td>
                                <td align="right" valign="top" style="padding: 0px 3px;">-</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">
                                    {{ number_format_custom(($IMTMetalDepAmt * $lossAssessment[0]['IMT23DepPer']) / 100, 2) }}
                                </td>
                                <td align="right" valign="top" style="padding: 0px 3px;">
                                    {{ number_format_custom(($IMTRubberDepAmt * $lossAssessment[0]['IMT23DepPer']) / 100, 2) }}
                                </td>
                                <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                    <td align="right" valign="top" style="padding: 0px 3px;">
                                        {{ number_format_custom(($IMTFibreDepAmt * $lossAssessment[0]['IMT23DepPer']) / 100, 2) }}
                                    </td>
                                @endif
                                @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                    <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                @endif
                            </tr>
                            <tr>
                                <td align="right" valign="top"   colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($subTotalIMTMetalAmt, 2)}}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($subTotalIMTRubberAmt, 2)}}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($subTotalIMTGlassAmt, 2)}}</td>
                                @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($subTotalIMTFiberAmt, 2)}}</td>
                                @endif
                                @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{ !empty($lossAssessment[0]['totalReconditionAmt']) ? $lossAssessment[0]['totalReconditionAmt'] : '0.00' }}</td>
                                @endif
                            </tr>
                        @endif
                        <!-- Start Add GST 2nd Table Part-->
                        @if(isset($lossAssessment[0]['totalFibreAmt']) || isset($lossAssessment[0]['totalReconditionAmt']))
                            @php
                                $uniqueGstRates = []; // Initialize an array to store unique GST rates
                                $subUniqueGstRates = [];
                                $subUniqueGstRates = array_values(array_unique($subPartUniqueValue));
                                sort($subUniqueGstRates);
                                $counter = count($subUniqueGstRates);

                                $totalFibreGST=0;
                                $totalIMTMetalAmtAfterIMTDep =0;
                                $depAmtIMTMetal =0;
                                $depAmtIMTRubber = 0;
                                $depAmtIMTFibre = 0;
                                $totalIMTRubberAmtAfterIMTDep =0;
                                $totalIMTFibreAmtAfterIMTDep =0;
                                $totalGlassAmtAfterDep = 0;

                                $subTotalEstimateAmt = 0;
                                $subTotalMetalAmt = 0;
                                $subTotalRubberAmt = 0;
                                $subTotalGlassAmt = 0;
                                $subTotalFibreAmt = 0;
                                $subTotalRecondAmt = 0;
                            @endphp
                            @if($lossAssessment[0]['MutipleGSTonParts']==1)

                                @if($lossAssessment[0]['GSTEstimatedPartsPer'] !=Null || $lossAssessment[0]['GSTAssessedPartsPer'] !=Null)

                                    @foreach($subUniqueGstRates as $value)
                                        @unless(in_array($value, $uniqueGstRates))
                                            @php

                                                $imt23MetalTotalAmountTbl2= 0;
                                                $imt23RubberTotalAmountTbl2 = 0;
                                                $imt23FiberTotalAmountTbl2 = 0;

                                                $totalEstAmt = 0; // Initialize total estimated amount
                                                $totalEstAmtPercentage = 0;
                                                $totalMetalAmt = 0;
                                                $totalRubberAmt = 0; // Initialize total assessed amount
                                                $totalGlassAmt = 0;
                                                $totalFibreAmt = 0;
                                                $totalReconditionAmt = 0;

                                                $depAmtIMTMetal =0;
                                                $depAmtIMTMetalAfterIMTDep =0;
                                                $totalIMTFibreAmtAfterDep =0;
                                                $depAmtIMTFibreAfterIMTDep = 0;
                                                $depAmtIMTRubberAfterIMTDep =0;

                                                $totalPartEstAmt = 0;
                                                $totalPartMetalAmt = 0;
                                                $totalPartRubberAmt = 0;
                                                $totalPartGlassAmt = 0;
                                                $totalPartFibreAmt = 0;
                                                $totalPartReconditionAmt = 0;

                                                $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                                            @endphp

                                            @foreach($alldetails as $detail)

                                                @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
                                                @php
                                                    if($detail['category'] =="Metal" && $detail['imt_23']=="Yes"){
                                                       $imt23MetalTotalAmountTbl2 += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                    }
                                                    if($detail['category'] =="Rubber" && $detail['imt_23']=="Yes"){
                                                       $imt23RubberTotalAmountTbl2 += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                     }
                                                     if($detail['category'] =="Fibre" && $detail['imt_23']=="Yes"){
                                                       $imt23FiberTotalAmountTbl2 += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                     }
                                                     // Sum up assessed amount for respective categories
                                                 $totalEstAmt += !empty($detail['est_amt']) ? $detail['est_amt'] : 0; // Sum up estimated amount

                                                 if(empty($detail['quantities'])){
                                                     switch ($detail['category']) {
                                                         case 'Metal':
                                                             $totalMetalAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                             break;
                                                         case 'Rubber':
                                                             $totalRubberAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                             break;
                                                         case 'Glass':
                                                             $totalGlassAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                             break;
                                                         case 'Fibre':
                                                                 $totalPartFibreAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                                 break;
                                                         case 'Recondition':
                                                             $totalPartReconditionAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                                             break;
                                                       }
                                                     }
                                                @endphp
                                                @endif

                                                @if(isset($detail['quantities']))
                                                    @foreach($detail['quantities'] as $partQuantity)
                                                        @php

                                                                @endphp
                                                        @if($partQuantity['gst'] == $value)
                                                            @php
                                                                if($partQuantity['category'] =="Metal" && $partQuantity['imt_23']=="Yes"){
                                                                       $imt23MetalTotalAmountTbl2 += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                  }
                                                                  if($partQuantity['category'] =="Rubber" && $partQuantity['imt_23']=="Yes"){
                                                                        $imt23RubberTotalAmountTbl2 += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                    }
                                                                  if($partQuantity['category'] =="Fibre" && $partQuantity['imt_23']=="Yes"){
                                                                        $imt23FiberTotalAmountTbl2 += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                   }
                                                                    // Calculate totals for subparts with the same GST percentage
                                                                    switch ($partQuantity['category']) {
                                                                        case 'Metal':
                                                                            $totalPartMetalAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                            break;
                                                                        case 'Rubber':
                                                                            $totalPartRubberAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                            break;
                                                                        case 'Glass':
                                                                            $totalPartGlassAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                            break;
                                                                        case 'Fibre':
                                                                            $totalPartFibreAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                            break;
                                                                        case 'Recondition':
                                                                        $totalPartReconditionAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                                        break;
                                                                    }
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                            @php

                                                $totalMetalIMTPart = $imt23MetalTotalAmountTbl2;
                                                $totalRubberIMTPart = $imt23RubberTotalAmountTbl2;
                                                $totalFibreIMTPart = $imt23FiberTotalAmountTbl2;

                                                $totalMetalGST = $totalMetalAmt + $totalPartMetalAmt;
                                                $totalRubberGST = $totalRubberAmt + $totalPartRubberAmt;
                                                $totalGlassGST = $totalGlassAmt + $totalPartGlassAmt;
                                                $totalFibreGST = $totalPartFibreAmt;
                                                $totalRecondtionGST = $totalReconditionAmt + $totalPartReconditionAmt;

                                                $depAmtMetal = (($totalMetalGST * $lossAssessment[0]['MetalDepPer']) / 100);
                                                $totalMetalAmtAfterDep = ($totalMetalGST - $depAmtMetal);

                                                $depAmtRubber = (($totalRubberGST * $lossAssessment[0]['RubberDepPer']) / 100);
                                                $totalRubberAmtAfterDep = ($totalRubberGST - $depAmtRubber);

                                                $depAmtGlass = (($totalGlassGST * $lossAssessment[0]['GlassDepPer']) / 100);
                                                $totalGlassAmtAfterDep = ($totalGlassGST - $depAmtGlass);

                                                $depAmtFibre = (($totalFibreGST * $lossAssessment[0]['FibreDepPer']) / 100);
                                                $totalFibreAmtAfterDep = ($totalFibreGST - $depAmtFibre);

                                                if($imt23MetalTotalAmountTbl2){
                                                    $depAmtIMTMetal = (($totalMetalIMTPart * $lossAssessment[0]['MetalDepPer']) / 100);
                                                    $totalIMTMetalAmtAfterDep = ($totalMetalIMTPart - $depAmtIMTMetal);
                                                    $depAmtIMTMetalAfterIMTDep = (($totalIMTMetalAmtAfterDep * $lossAssessment[0]['IMT23DepPer']) / 100);
                                                    $totalIMTMetalAmtAfterIMTDep = ($totalMetalAmtAfterDep - $depAmtIMTMetalAfterIMTDep);
                                                }else{
                                                  $totalIMTMetalAmtAfterIMTDep = ($totalMetalGST - $depAmtMetal);
                                                }

                                                if($imt23RubberTotalAmountTbl2){
                                                    $depAmtIMTRubber = (($totalRubberIMTPart * $lossAssessment[0]['RubberDepPer']) / 100);
                                                    $totalIMTRubberAmtAfterDep = ($totalRubberIMTPart - $depAmtIMTRubber);
                                                    $depAmtIMTRubberAfterIMTDep = (($totalIMTRubberAmtAfterDep * $lossAssessment[0]['IMT23DepPer']) / 100);
                                                    $totalIMTRubberAmtAfterIMTDep = ($totalRubberAmtAfterDep - $depAmtIMTRubberAfterIMTDep);
                                                }else{
                                                   $totalIMTRubberAmtAfterIMTDep = ($totalRubberGST - $depAmtRubber);
                                                }

                                                if($imt23FiberTotalAmountTbl2){
                                                    $depAmtIMTFibre = (($totalFibreIMTPart * $lossAssessment[0]['FibreDepPer']) / 100);
                                                    $totalIMTFibreAmtAfterDep = ($totalFibreIMTPart - $depAmtIMTFibre);
                                                    $depAmtIMTFibreAfterIMTDep = (($totalIMTFibreAmtAfterDep * $lossAssessment[0]['IMT23DepPer']) / 100);
                                                    $totalIMTFibreAmtAfterIMTDep = ($totalFibreAmtAfterDep - $depAmtIMTFibreAfterIMTDep);
                                                }else{
                                                   $totalIMTFibreAmtAfterIMTDep = ($totalFibreGST - $depAmtFibre);
                                                }
                                            @endphp
                                            <tr>
                                                @if(($lossAssessment[0]['GSTEstimatedPartsPer']==0 || $lossAssessment[0]['GSTEstimatedPartsPer'] == NULL) && ($lossAssessment[0]['GSTAssessedPartsPer'] !=0 || $lossAssessment[0]['GSTAssessedPartsPer'] != NULL))
                                                    <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $value }}%</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? number_format_custom(($totalEstAmt * $lossAssessment[0]['GSTEstimatedPartsPer'] / 100), 2) : '0.00' }}</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTMetalAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTRubberAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalGlassAmtAfterDep * $value / 100), 2) : '0.00' }}</td>
                                                    @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTFibreAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
                                                    @endif

                                                    @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalReconditionAmt + $totalPartReconditionAmt, 2) }}</td>
                                                    @endif

                                                @elseif(($lossAssessment[0]['GSTAssessedPartsPer']==0 || $lossAssessment[0]['GSTAssessedPartsPer'] == NULL) && ($lossAssessment[0]['GSTEstimatedPartsPer'] !=0 || $lossAssessment[0]['GSTEstimatedPartsPer'] != NULL))
                                                    <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $value }}%</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? number_format_custom(($totalEstAmt * $value / 100), 2) : '0.00' }}</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTMetalAmtAfterIMTDep * $lossAssessment[0]['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTRubberAmtAfterIMTDep * $lossAssessment[0]['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalGlassAmtAfterDep * $lossAssessment[0]['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                                    @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTFibreAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
                                                    @endif

                                                    @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom((($totalReconditionAmt + $totalPartReconditionAmt) * $value / 100), 2) }}</td>
                                                    @endif
                                                @else
                                                    <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $value }}%</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? number_format_custom(($totalEstAmt * $value / 100), 2) : '0.00' }}</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTMetalAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTRubberAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalGlassAmtAfterDep * $value / 100), 2) : '0.00' }}</td>
                                                    @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTFibreAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
                                                    @endif

                                                    @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom((($totalReconditionAmt + $totalPartReconditionAmt) * $value / 100), 2) }}</td>
                                                    @endif
                                                @endif
                                            </tr>
                                            <!-- <tr>
     @if($lossAssessment[0]['GSTEstimatedPartsPer']==0 && $lossAssessment[0]['GSTEstimatedPartsPer'] == $value)
                                                <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add CGST {{ $value }}%</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalMetalAmt + $totalPartMetalAmt, 2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalRubberAmt + $totalPartRubberAmt, 2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalGlassAmt + $totalPartGlassAmt, 2) }}</td>
          @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalFibreAmt + $totalPartFibreAmt, 2) }}</td>
          @endif
                                                @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalReconditionAmt + $totalPartReconditionAmt, 2) }}</td>
          @endif

                                            @elseif($lossAssessment[0]['GSTAssessedPartsPer']==0 && $lossAssessment[0]['GSTAssessedPartsPer'] == $value)
                                                <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add CGST {{ $value }}%</td>
         <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalEstAmt * $value / 100), 2) : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? $lossAssessment[0]['totalMetalAmt'] : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalRubberAmt']) ? $lossAssessment[0]['totalRubberAmt'] : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalGlassAmt']) ? $lossAssessment[0]['totalGlassAmt'] : '0.00' }}</td>
          @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalFibreAmt']) ? $lossAssessment[0]['totalFibreAmt'] : '0.00' }}</td>
          @endif
                                                @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalReconditionAmt']) ? $lossAssessment[0]['totalReconditionAmt'] : '0.00' }}</td>
          @endif

                                            @else
                                                <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add CGST {{ $value }}%</td>
          @if($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTEstimatedPartsPer']==0))
                                                    <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
@elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer']==0))
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalEstAmt * $value / 100), 2) : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          @else
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalEstAmt * $value / 100), 2) : '0.00' }}</td>
          @endif
                                                @if($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer']!=0))
                                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTMetalAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTRubberAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalGlassAmtAfterDep * $value / 100), 2) : '0.00' }}</td>
            @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalIMTFibreAmtAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
            @endif
                                                    @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalRecondtionGST * $value / 100), 2) : '0.00' }}</td>
          @endif
                                                @endif
                                            @endif
                                                    </tr> -->
                                            @php
                                                $subTotalEstimateAmt += floatval($totalEstAmt * $value / 100);
                                                $subTotalMetalAmt += floatval($totalIMTMetalAmtAfterIMTDep * $value / 100);
                                                $subTotalRubberAmt += floatval($totalIMTRubberAmtAfterIMTDep * $value / 100);
                                                $subTotalGlassAmt += floatval($totalGlassAmtAfterDep * $value / 100);
                                                $subTotalFibreAmt += floatval($totalIMTFibreAmtAfterIMTDep * $value / 100);
                                                $subTotalRecondAmt += floatval($totalRecondtionGST * $value / 100);
                                            @endphp
                                        @endunless
                                    @endforeach
                                @endif

                            @else
                                @foreach($lossAssessment as $row)
                                    <tr>
                                        @if($row['GSTEstimatedPartsPer'] == $row['GSTAssessedPartsPer'])
                                            <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTEstimatedPartsPer'] }}%</td>
                                        @else
                                            <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTEstimatedPartsPer'] }}%</td>
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? number_format_custom(($lossAssessment[0]['total_EstAmt'] * $row['GSTEstimatedPartsPer'] / 100), 2) : '0.00' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">0.00</td>
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">0.00</td>
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">0.00</td>
                                            @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
                                            @endif

                                            @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
                                            @endif
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTAssessedPartsPer'] }}%</td>
                                        @endif

                                        @if($row['GSTEstimatedPartsPer'] != 0 && $row['GSTAssessedPartsPer'] != 0)
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? number_format_custom(($lossAssessment[0]['total_EstAmt'] * $row['GSTEstimatedPartsPer'] / 100), 2) : '0.00' }}</td>
                                        @else
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">0.00</td>
                                        @endif
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? number_format_custom(($subTotalIMTMetalAmt * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalRubberAmt']) ? number_format_custom(($subTotalIMTRubberAmt * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalGlassAmt']) ? number_format_custom(($subTotalIMTGlassAmt * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                        @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalFibreAmt']) ? number_format_custom(($subTotalIMTFiberAmt * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                        @endif

                                        @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalReconditionAmt']) ? number_format_custom(($lossAssessment[0]['totalReconditionAmt'] * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
                                        @endif
                                    </tr>
                                    <!-- <tr>
          @if($row['GSTEstimatedPartsPer'] == $row['GSTAssessedPartsPer'])
                                        <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add CGST {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTEstimatedPartsPer'] }}%</td>
          @else
                                        <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add CGST {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTEstimatedPartsPer'] }}%</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? number_format_custom(($lossAssessment[0]['total_EstAmt'] * $row['GSTEstimatedPartsPer'] / 100), 2) : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
          @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
@endif
                                        @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
@endif
                                                </tr>
                                                <tr>
                                                    <td align="left" valign="top" colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add CGST {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? "GST" : "IGST" }} {{ $row['GSTAssessedPartsPer'] }}%</td>
          @endif

                                    @if($row['GSTEstimatedPartsPer'] == $row['GSTAssessedPartsPer'])
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00' }}</td>
          @else
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">0.00</td>
@endif
                                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalMetalAmt']) ? number_format_custom(($lossAssessment[0]['totalMetalAmt'] * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalRubberAmt']) ? number_format_custom(($lossAssessment[0]['totalRubberAmt'] * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalGlassAmt']) ? number_format_custom(($lossAssessment[0]['totalGlassAmt'] * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
          @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalFibreAmt']) ? number_format_custom(($lossAssessment[0]['totalFibreAmt'] * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
          @endif
                                    @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ !empty($lossAssessment[0]['totalReconditionAmt']) ? number_format_custom(($lossAssessment[0]['totalReconditionAmt'] * $row['GSTAssessedPartsPer'] / 100), 2) : '0.00' }}</td>
          @endif
                                            </tr> -->

                                    @php
                                        $subTotalEstimateAmt += floatval($lossAssessment[0]['total_EstAmt'] * $row['GSTEstimatedPartsPer'] / 100);
                                        $subTotalMetalAmt += floatval($lossAssessment[0]['totalMetalAmt'] * $row['GSTAssessedPartsPer'] / 100);
                                        $subTotalRubberAmt += floatval($lossAssessment[0]['totalRubberAmt'] * $row['GSTAssessedPartsPer'] / 100);
                                        $subTotalGlassAmt += floatval($lossAssessment[0]['totalGlassAmt'] * $row['GSTAssessedPartsPer'] / 100);
                                        $subTotalFibreAmt += floatval($lossAssessment[0]['totalFibreAmt'] * $row['GSTAssessedPartsPer'] / 100);
                                        $subTotalRecondAmt += floatval($lossAssessment[0]['totalReconditionAmt'] * $row['GSTAssessedPartsPer'] / 100);
                                    @endphp
                                @endforeach
                            @endif
                        @endif
                        <!-- End GST Part-->


                        <!-- Start Sub Total GST Part-->
                        @php
                            $totalMetalAmt = $subTotalMetalAmt + $subTotalIMTMetalAmt;
                            $totalRubberAmt = $subTotalRubberAmt + $subTotalIMTRubberAmt;
                            $totalGlassAmt = $subTotalGlassAmt + $subTotalIMTGlassAmt;
                            $totalFibreAmt = $subTotalFibreAmt + $subTotalIMTFiberAmt;
                            $totalRecondAmt = $subTotalRecondAmt + (!empty($lossAssessment[0]['totalReconditionAmt']) ? $lossAssessment[0]['totalReconditionAmt'] : '0.00');
                        @endphp
                        <tr>
                            <td align="right" valign="top"   colspan="{{ $dynamicColspan }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{number_format_custom($subTotalEstimateAmt + (!empty($lossAssessment[0]['total_EstAmt']) ? $lossAssessment[0]['total_EstAmt'] : '0.00'), 2)}}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($subTotalMetalAmt + $subTotalIMTMetalAmt, 2)}}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($subTotalRubberAmt + $subTotalIMTRubberAmt, 2)}}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($subTotalGlassAmt + $subTotalIMTGlassAmt, 2)}}</td>
                            @if($lossAssessment[0]['totalFibreAmt'] !== "0.00")
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($subTotalFibreAmt + $subTotalIMTFiberAmt, 2)}}</td>
                            @endif
                            @if($lossAssessment[0]['totalReconditionAmt'] !== "0.00")
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($subTotalRecondAmt + (!empty($lossAssessment[0]['totalReconditionAmt']) ? $lossAssessment[0]['totalReconditionAmt'] : '0.00'),2)}}</td>
                            @endif
                        </tr>
                        <!-- End Sub Total GST Part-->
                        <tr>
                            <td align="right" valign="top"   colspan="{{ $dynamicColspan }}" style="padding: 0px 3px;font-weight: bold; border-left: 1px solid #000;">Total</td>
                            <td align="right" valign="top" colspan="{{ ($maincolspan == 0) ? $dynamicColspan +1 : $maincolspan + 1 }}"
                                style="padding: 0px 3px; border-bottom: 2px solid #000; border-top: 2px solid #000;font-weight: bold;">{{number_format_custom($totalMetalAmt + $totalRubberAmt + $totalGlassAmt + $totalFibreAmt + $totalRecondAmt,2)}}</td>

                        </tr>
                </tbody>
            </table>
        @endif

        @endif
        @endif
        <!-- End 2nd table loss of assessment -->
        @endif


        @include('preview-reports.labour_charges')

        @include('preview-reports.summary-assessment-loss')

        @if(!empty($lossAssessment) && ($lossAssessment[0]['display_gst_summary']==1))
            @include('preview-reports.imt-parts-summary-tax.part_tax_summary')
        @else
            @include('preview-reports.imt-parts-summary-tax.category_part_tax_summary')
        @endif

        @include('preview-reports.imt-parts-summary-tax.category_IMT_part_tax_summary')

        @include('preview-reports.imt-parts-summary-tax.labour_tax_summary')


        <!--
    <table width="100%" align="center" id="design">
      <tbody>
        <tr>
          <td align="left" valign="top" style="padding-top: 10px;  border: none; border-bottom: 2px solid #000; font-weight: bold;">GST SUMMARY HSN WISE</td>
        </tr>
      </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 12px;">
      <tbody>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; width: 5%; border-left: 1px solid #000; font-weight: bold;">Sr. No.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">HSN Code</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 25%; font-weight: bold;">Total Amt.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">GST Amt. </td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">IGST Amt.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Total Amt.</td>
        </tr>

        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">1</td>
          <td align="center" valign="top" style="padding: 0px 3px;">HSN-1223</td>
          <td align="right" valign="top" style="padding: 0px 3px;">7300.96</td>
          <td align="right" valign="top" style="padding: 0px 3px;">657.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">657.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">8614.00</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">2</td>
          <td align="center" valign="top" style="padding: 0px 3px;">HSN-423423</td>
          <td align="right" valign="top" style="padding: 0px 3px;">7300.96</td>
          <td align="right" valign="top" style="padding: 0px 3px;">657.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">657.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">8614.00</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">7300.40</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">7300.40</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">657.16</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">8614.00</td>
        </tr>
      </tbody>
    </table>

    <table width="100%" align="center" id="design" style="margin-top: 20px;" style="font-size: 12px;">
      <tbody>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; width: 5%; border-left: 1px solid #000;  border-top: 1px solid #000; font-weight: bold;">Sr. No.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold; border-top: 1px solid #000;">SAC</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 25%; font-weight: bold; border-top: 1px solid #000;">Total Amt.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold; border-top: 1px solid #000;">Dep. Amt.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold; border-top: 1px solid #000;">GST Amt. </td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold; border-top: 1px solid #000;">IGST Amt.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold; border-top: 1px solid #000;">Total Amt.</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">1</td>
          <td align="center" valign="top" style="padding: 0px 3px;">SAC-1223</td>
          <td align="right" valign="top" style="padding: 0px 3px;">7300.96</td>
          <td align="right" valign="top" style="padding: 0px 3px;">657.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">657.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">8614.00</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">2</td>
          <td align="center" valign="top" style="padding: 0px 3px;">SAC-423423</td>
          <td align="right" valign="top" style="padding: 0px 3px;">7300.96</td>
          <td align="right" valign="top" style="padding: 0px 3px;">657.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">657.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">8614.00</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">7300.40</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">7300.40</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">657.16</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">8614.00</td>
        </tr>
      </tbody>
    </table> -->

            <table width="100%" align="center" style="border: none !important;">
                <tbody>
                <tr>
                    <td align="left" valign="top" style="padding-top: 10px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px; font-weight: bold; border: none !important;">Net Liability</td>
                </tr>
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
                <tr>
                    <td align="left" valign="top" style="padding-top: 3px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px; border: none !important;">Based On Details Provided Above, The Justified
                        Liability Under The Subject Policy Of Insurance Works Out To Rs.
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
                    <td align="left" valign="top" style="padding-top: 3px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px;  border: none !important;"><?php echo isset($lossAssessment[0]['comment']) ? $lossAssessment[0]['comment'] : ''; ?></td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="padding-top: 10px;font-weight: bold;" colspan="3">(Issued without Prejudice)</td>
                </tr>

                <tr>
                    <td align="left" valign="top" style="padding-top: 10px;font-weight: bold;" colspan="3">
                        @if(!empty($signature_img))
                            <img src="{{ asset('public/storage/'.$signature_img) }}" style="width:100px;">
                        @else
                            <p>
                                <br/>
                                <br />
                                <br />
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

</body>
</html>