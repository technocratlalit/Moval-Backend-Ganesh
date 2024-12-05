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

            table {
                border-collapse: collapse;
                border-spacing: 0;
            }

            #design td {
                border: solid 1px #000;
                border-style: none solid solid none;
                padding: 10px;
            }

            #design thead {
                border: solid 1px #000;
            }

            #design tr {
                border: solid 1px #000;
            }

            #design th {
                border: solid 1px #000;
                padding: 1px;
            }

            #design-cabin td {
                border: solid 1px #000;
                padding: 10px;
            }

            #design-cabin thead {
                border: solid 1px #000;
            }

            #design-cabin tr {
                border: solid 1px #000;
            }

            #design-cabin th {
                border: solid 1px #000;
                padding: 1px;
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
        @php

            $alldetails = !empty($lossAssessment[0]['alldetails']) ? json_decode($lossAssessment[0]['alldetails'], true) : [];
            $get_cabin_load_body_ass = isset($policyDetails->get_cabin_load_body_ass) ? $policyDetails->get_cabin_load_body_ass->toArray() : [];
            $cabinDetails = (isset($get_cabin_load_body_ass['alldetails']) && !empty($get_cabin_load_body_ass['alldetails'])) ? json_decode($get_cabin_load_body_ass['alldetails'], true) : [];
            $cabinDetailsCalculation = isset($get_cabin_load_body_ass['details_calculation']) ? json_decode($get_cabin_load_body_ass['details_calculation'], true) : [];
            $totalAssPartsCabin = isset($cabinDetailsCalculation[1]['parts_total']['assessed']) ? array_sum(array_values($cabinDetailsCalculation[1]['parts_total']['assessed'])) : 0;
            $totalAssPartsLoadBody = isset($cabinDetailsCalculation[2]['parts_total']['assessed']) ? array_sum(array_values($cabinDetailsCalculation[2]['parts_total']['assessed'])) : 0;
            $less_cabin_salvage = (isset($get_cabin_load_body_ass['less_cabin_salvage']) && $get_cabin_load_body_ass['less_cabin_salvage'] > 0) ? $get_cabin_load_body_ass['less_cabin_salvage'] : 0;
            $less_load_body_salvage = (isset($get_cabin_load_body_ass['less_load_body_salvage']) && $get_cabin_load_body_ass['less_load_body_salvage'] > 0) ? $get_cabin_load_body_ass['less_load_body_salvage'] : 0;

            $getUniquePartsAndLabourGST = getUniquePartsAndLabourGST($alldetails);
            $uniqueGstValue = $getUniquePartsAndLabourGST['uniqueGstValue'];
            $uniqueLabourGstValue = $getUniquePartsAndLabourGST['uniqueLabourGstValue'];
            sort($uniqueGstValue);
            $part_tax_summary_details = [];
            $part_tax_cate_summary_details = [];
            $labour_category_tax_summery_details = [];

        @endphp

        @if(!empty($finalWithoutAss=='final_without_ass_report') || !empty($finalAssessment=='final_ass_report'))
            <div style="border-bottom: 3px solid #000; text-align:center;">
                <div style="width: 100%;">
                    @if ($letter_head_img)
                        <img src="{{ asset('public/storage/'.$letter_head_img) }}" style="height:auto;">
                    @else
                        <p>No letter head image available</p>
                    @endif
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
                            <span style="font-weight: bold;  padding-right: 10px; padding-left: 10px; font-family: 'Verdana' !important; ">Date:</span> {{ isset($policyDetails->reportGeneratedOn) ? \Carbon\Carbon::parse($policyDetails->reportGeneratedOn)->format('d/m/Y') : '' }}
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div style="padding-top: 10px; line-height: 18px;">This report is issued without prejudice, in respect of cause, nature and extent of loss/damage and subject to the terms and conditions of the Insurance Policy and Insurer admitting liability.</div>

                <div style="padding-top: 10px;"><strong>Subject : </strong> Claim for Veh. Regn. No. {{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}, <strong>Accident Date : </strong>{{ !empty($policyDetails->date_time_accident) ? \Carbon\Carbon::parse($policyDetails->date_time_accident)->format('d/m/Y h:i A') : '' }}</div>
                <div style="padding-left: 66px;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="font-size: 14px; padding-bottom: 5px;">
                        <tbody>
                        <tr>
                            <td style="width: 35%; text-align: left; padding-top: 3px; font-family:Verdana !important;">Insured</td>
                            <td style="width: 5%; text-align: left; padding-top: 3px;">:</td>
                            <td style="width: 60%; text-align: left; padding-top: 3px; font-family: 'Verdana' !important;">{{ !empty($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</td>
                        </tr>

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

                <div>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="font-size: 14px; padding-bottom: 5px;">
                        <tbody>

                        @if(isset($policyDetails->policy_type) && trim($policyDetails->policy_type) !== '')
                            <tr>
                                <td align="left" valign="top" style="width: 41%; padding-top: 3px;">Policy Type</td>
                                <td align="left" valign="top" style="width: 5%; padding-top: 3px; ">:</td>
                                <td align="left" valign="top" style="width: 54%; padding-top: 3px;">{{ !empty($policyDetails->policy_type) ? $policyDetails->policy_type : '' }}</span></td>
                            </tr>
                        @endif
                        @if(isset($policyDetails->status_of_64vb) && trim($policyDetails->status_of_64vb) !== '')
                            <tr>
                                <td align="left" valign="top" style="padding-top: 3px;">Status of 64VB</td>
                                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                                <td align="left" valign="top"
                                    style="padding-top: 3px;">{{ !empty($policyDetails->status_of_64vb) ? $policyDetails->status_of_64vb : '' }}</span>
                                </td>
                            </tr>
                        @endif
                        @if(isset($policyDetails->status_of_64vb) && trim($policyDetails->status_of_64vb) !== '')
                            <tr>
                                <td align="left" valign="top" style="padding-top: 3px;">Status of Preinspection</td>
                                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                                <td align="left" valign="top"
                                    style="padding-top: 3px;">{{ !empty($policyDetails->status_of_pre_insp) ? $policyDetails->status_of_pre_insp : '' }}</span>
                                </td>
                            </tr>
                        @endif
                        @if(isset($policyDetails->status_of_NCB) && trim($policyDetails->status_of_NCB) !== '')
                            <tr>
                                <td align="left" valign="top" style="padding-top: 3px;">Status of NCB</td>
                                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                                <td align="left" valign="top"
                                    style="padding-top: 3px;">{{ !empty($policyDetails->status_of_NCB) ? $policyDetails->status_of_NCB : '' }}</span>
                                </td>
                            </tr>
                        @endif
                        @if(isset($policyDetails->payment_mode) && trim($policyDetails->payment_mode) !== '')
                            <tr>
                                <td align="left" valign="top" style="padding-top: 3px;">Payment Mode</td>
                                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                                <td align="left" valign="top"
                                    style="padding-top: 3px;">{{ !empty($policyDetails->payment_mode) ? $policyDetails->payment_mode : '' }}</span>
                                </td>
                            </tr>
                        @endif
                        @if(isset($policyDetails->settlement_type) && trim($policyDetails->settlement_type) !== '')
                            <tr>
                                <td align="left" valign="top" style="padding-top: 3px;">Type of settlement</td>
                                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                                <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->settlement_type) ? $policyDetails->settlement_type : '' }} </span></td>
                            </tr>
                        @endif

                        </tbody>
                    </table>
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
                        <td align="left" valign="top" style="width: 54%; padding-top: 1px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->office_name) ? $policyDetails->office_name : '' }}</span><br/><span>{{ isset($policyDetails->office_address) ? $policyDetails->office_address : '' }}</span></td>
                    </tr>
                    @if(isset($policyDetails->appointing_office_name) && trim($policyDetails->appointing_office_name) !== '')
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Appointed By</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->appointing_office_name) ? $policyDetails->appointing_office_name : '' }}</span><br/><span>{{ isset($policyDetails->appointing_office_address) ? $policyDetails->appointing_office_address : '' }} </span></td>
                        </tr>
                    @endif

                    @if(isset($policyDetails->operating_office_name) && trim($policyDetails->operating_office_name) !== '')
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Operating Office</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->operating_office_name) ? $policyDetails->operating_office_name : '' }}</span><br/><span>{{ isset($policyDetails->operating_office_address) ? $policyDetails->operating_office_address : '' }} </span></td>
                        </tr>
                    @endif

                    @if(isset($policyDetails->thirdParty_insured_name) && trim($policyDetails->thirdParty_insured_name) !== '')
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Third Party Insurer</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->thirdParty_insured_name) ? $policyDetails->thirdParty_insured_name : '' }}</span><br/><span>{{ isset($policyDetails->thirdParty_insured_branch_name) ? $policyDetails->thirdParty_insured_branch_name : '' }} </span></td>
                        </tr>
                    @endif

                    @if(isset($policyDetails->thirdParty_policy_no) && trim($policyDetails->thirdParty_policy_no) !== '')
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Third Party Policy No.</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ $policyDetails->thirdParty_policy_no }}</td>
                        </tr>
                    @endif

                    @if(!empty($policyDetails->thirdParty_policy_valid_from) && !empty($policyDetails->thirdParty_policy_valid_to))
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Third Party Policy Validity</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ date('d/m/Y', strtotime($policyDetails->thirdParty_policy_valid_from)) }} To {{ date('d/m/Y', strtotime($policyDetails->thirdParty_policy_valid_to)) }}</td>
                        </tr>
                    @endif

                    <tr>
                        <td align="left" valign="top" style="padding-top: 3px;">Insured</td>
                        <td align="left" valign="top" style="padding-top: 3px;">:</td>
                        <td align="left" valign="top" style="padding-top: 3px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</span><br/><span>{{ isset($policyDetails->insured_address) ? $policyDetails->insured_address : '' }}</span></td>
                    </tr>
                    @if(isset($policyDetails->insured_mobile_no) && trim($policyDetails->insured_mobile_no) !== '')
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;"></td>
                            <td align="left" valign="top" style="padding-top: 3px;"></td>
                            <td align="left" valign="top" style="padding-top: 3px; line-height: 18px;"><span>M-{{ isset($policyDetails->insured_mobile_no) ? $policyDetails->insured_mobile_no : '' }}</span></td>
                        </tr>
                    @endif
                    @if(isset($policyDetails->bank_name) && trim($policyDetails->bank_name) !== '' && isset($policyDetails->bank_address) && trim($policyDetails->bank_address) !== '' && isset($policyDetails->account_no) && trim($policyDetails->account_no) !== '')
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Bank Name</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->bank_name) ? $policyDetails->bank_name : '-' }}</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Branch Name</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->bank_address) ? $policyDetails->bank_address : '-' }}</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">A/c. No.</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->account_no) ? $policyDetails->account_no : '-' }}</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">IFSC Code</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->ifsc_code) ? $policyDetails->ifsc_code : '-' }}</td>
                        </tr>
                        @if(!empty($bankDetailsValue['micr']))
                            <tr>
                                <td align="left" valign="top" style="padding-top: 3px;">MICR Code</td>
                                <td align="left" valign="top" style="padding-top: 3px;">:</td>
                                <td align="left" valign="top" style="padding-top: 3px;">{{ $bankDetailsValue['micr'] }}</td>
                            </tr>
                        @endif
                    @endif
                    @if(isset($policyDetails->sum_insured) && trim($policyDetails->sum_insured) !== '')
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">IDV</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->sum_insured) ? '₹ '.$policyDetails->sum_insured : '' }}</td>
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
                        <td align="left" valign="top" style="padding-top: 3px;">{!! (isset($policyDetails->temp_registration_no) && $policyDetails->temp_registration_no == 'Y') ? 'Temporary ' : '' !!}Registration No.</td>
                        <td align="left" valign="top" style="padding-top: 3px;">:</td>
                        <td align="left" valign="top" style="padding-top: 3px;font-weight: bold;">{{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}</td>
                    </tr>

                    @if(isset($policyDetails->temp_registration_no) && $policyDetails->temp_registration_no == 'Y')
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Validity</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->issued_on) ? date('d/m/Y', strtotime($policyDetails->issued_on)) : '' }} To {{ !empty($policyDetails->valid_to) ? date('d/m/Y', strtotime($policyDetails->valid_to)) : '' }}</td>
                        </tr>
                    @endif
                    @if(!empty($policyDetails->rc_valid_to))
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">RC Valid Upto</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->rc_valid_to) ? date('d/m/Y', strtotime($policyDetails->rc_valid_to)) : '' }}</td>
                        </tr>
                    @endif
                    @if(!empty($policyDetails->registured_owner))
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Registered Owner</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->registured_owner) ? $policyDetails->registured_owner : '' }}</td>
                        </tr>
                    @endif
                    @if(!empty($policyDetails->date_of_purchase))
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Date of Purchase</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ date('d/m/Y', strtotime($policyDetails->date_of_purchase)) }}</td>
                        </tr>
                    @endif
                    @if(!empty($policyDetails->date_of_registration))
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Date of Registration</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->date_of_registration) ? \Carbon\Carbon::parse($policyDetails->date_of_registration)->format('d/m/Y') : '' }}</td>
                        </tr>
                    @endif
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
                    @if(!empty($policyDetails->vehicle_chassis_no))
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Chassis No.</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->vehicle_chassis_no) ? $policyDetails->vehicle_chassis_no : '' }} <span style="padding-left: 10px;font-weight: bold;">Physically Verified : {{ !empty($policyDetails->accident_chassis_no) ? $policyDetails->accident_chassis_no : '' }}</span></td>
                        </tr>
                    @endif
                    <tr>
                        <td align="left" valign="top" style="padding-top: 3px;">Engine No.</td>
                        <td align="left" valign="top" style="padding-top: 3px;">:</td>
                        <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->vehicle_engine_no) ? $policyDetails->vehicle_engine_no : '' }} <span style="padding-left: 10px;font-weight: bold;"> Physically Verified : {{ !empty($policyDetails->accident_engine_no) ? $policyDetails->accident_engine_no : '' }}</span></td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="padding-top: 3px;">Engine Capacity</td>
                        <td align="left" valign="top" style="padding-top: 3px;">:</td>
                        <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->engine_capacity) ? $policyDetails->engine_capacity : '' }} {{ !empty($policyDetails->engine_capacity_unit) ? $policyDetails->engine_capacity_unit : '' }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="padding-top: 3px;">Vehicle Make</td>
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
                        <td align="left" valign="top" style="padding-top: 3px;">Type of Body</td>
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
                        <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->fuel) ? ucfirst($policyDetails->fuel ) : '' }}</td>
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
                            <td align="left" valign="top" style="padding-top: 3px;">PUC Certificate Validity</td>
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
                            <td align="left" valign="top" style="padding-top: 3px; padding-bottom:5px;">If overloaded, whether the overloading is the cause of accident?</td>
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
                        <td align="left" valign="top" style="padding-top: 3px;font-weight: bold;">{{ !empty($policyDetails->DL) ? $policyDetails->DL : '' }}</td>
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
                            <td align="left" valign="top" style="padding-top: 3px;">Relation with the insured</td>
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
                            <td align="left" valign="top" style="padding-top: 3px; padding-bottom:5px;"><?php echo $policyDetails->additional_details;?></td>
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

                    @if(isset($policyDetails->fuel_kit) && $policyDetails->fuel_kit != 'NA')
                        <tr>
                            <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;">CNG/LPG KIT</td>
                            <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px;">{!! strtoupper($policyDetails->fuel_kit) !!} Kit Fitted</td>
                        </tr>
                    @endif

                    @if(isset($policyDetails->anti_theft_fitted) && trim($policyDetails->anti_theft_fitted) !== '')
                        <tr>
                            <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;">Anti Theft Device Status</td>
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
                            <td align="left" valign="top" style="padding-top: 3px;">Has accident been report to Police</td>
                            <td align="left" valign="top" style="padding-top: 3px;">:</td>
                            <td align="left" valign="top" style="padding-top: 3px;">{{ !empty($policyDetails->accident_reported_to_police) ? ($policyDetails->accident_reported_to_police=="Y") ? "Yes" : "No" : '' }} {{ !empty($policyDetails->fir_description) ? ($policyDetails->fir_description) : '' }}</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px;">Has Panchnama been carried out</td>
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
                            <td align="left" valign="top" style="padding-top: 3px;">Spot Survey By</td>
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
                            <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; text-align: justify;" colspan="3"><?php echo $policyDetails->passenger_detail;?></td>
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
                    @if(!empty(json_decode($estimates,true)))
                            @include('preview-reports.final-report.estimate_details')

                    @elseif(isset($policyDetails->estimate_no) && trim($policyDetails->estimate_no) !== '')
                        <tr>
                            <td align="left" valign="top" style="border-bottom: 1px solid #000; padding-top: 6px;" colspan="3"></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;" colspan="3">Original Estimate</td>
                        </tr>
                        <tr>
                            <td>estimate details</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="padding-top: 3px; padding-bottom: 5px; text-align: justify;" colspan="3">Estimate no. {{ !empty($policyDetails->estimate_no) ? $policyDetails->estimate_no : '' }} dated {{ !empty($policyDetails->date_of_estimate) ? \Carbon\Carbon::parse($policyDetails->date_of_estimate)->format('d/m/Y') : '' }} for ₹{{ ($policyDetails->totalest > 0) ? $policyDetails->totalest : '0.00' }} was submitted by {{ !empty($policyDetails->workshop_branch_name) ? $policyDetails->workshop_branch_name : '' }}, {{ !empty($policyDetails->workshop_branch_address) ? $policyDetails->workshop_branch_address : '' }}</td>
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
                            @if (!empty($signature_img))
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
                                    if($inspectionAttachment[0]['copy_of_fire'] == 1){
                                        $enclosures .= $counter++ . '. Copy Of Fir, ';
                                    }
                                    if($inspectionAttachment[0]['report_in_duplicate'] == 1) {
                                        $enclosures .= $counter++ . '. Report In Duplicate, ';
                                    }
                                    if($inspectionAttachment[0]['copy_of_load_challan'] == 1){
                                        $enclosures .= $counter++ . '. Copy Of Load Challan, ';
                                    }
                                    if($inspectionAttachment[0]['affidavit'] == 1){
                                        $enclosures .= $counter++ . '. Affidavit, ';
                                    }
                                    if($inspectionAttachment[0]['bill_invoice'] == 1){
                                        $enclosures .= $counter++ . '. Bill/invoice, ';
                                    }
                                    if($inspectionAttachment[0]['copy_of_permit'] == 1){
                                        $enclosures .= $counter++ . '. Copy Of Permit, ';
                                    }
                                    if($inspectionAttachment[0]['copy_traffic'] == 1){
                                        $enclosures .= $counter++ . '. Copy Of Traffic Challan, ';
                                    }
                                    if($inspectionAttachment[0]['estimate_copy'] == 1) {
                                        $enclosures .= $counter++ . '. Estimate Copy, ';
                                    }
                                    if($inspectionAttachment[0]['report_in_duplicate'] == 1){
                                        $enclosures .= $counter++ . '. Report In Duplicate, ';
                                    }

                                    if($inspectionAttachment[0]['copy_of_fitness'] == 1){
                                        $enclosures .= $counter++ . '. Copy Of Fitness, ';
                                    }
                                    if($inspectionAttachment[0]['claim_form'] == 1){
                                        $enclosures .= $counter++ . '. Claim Form, ';
                                    }
                                    if($inspectionAttachment[0]['copy_of_RC'] == 1){
                                        $enclosures .= $counter++ . '. Copy Of R.c, ';
                                    }
                                    if($inspectionAttachment[0]['insured_discharge_voucher'] == 1){
                                        $enclosures .= $counter++ . '. Copy Of Letter To Insured Discharge Voucher, ';
                                    }
                                    if($inspectionAttachment[0]['intimation_letter'] == 1){
                                        $enclosures .= $counter++ . '. Intimation Letter, ';
                                    }
                                    if($inspectionAttachment[0]['survey_fee_bill'] == 1){
                                        $enclosures .= $counter++ . '. Survey Fee Bill, ';
                                    }
                                    if($inspectionAttachment[0]['letter_by_insured'] == 1){
                                        $enclosures .= $counter++ . '. Letter By Insured, ';
                                    }
                                    if($inspectionAttachment[0]['copy_of_DL'] == 1){
                                        $enclosures .= $counter++ . '. Copy Of D.l, ';
                                    }
                                    if($inspectionAttachment[0]['policy_note'] == 1){
                                        $enclosures .= $counter++ . '. Copy Of Policy/c. Note, ';
                                    }
                                    if($inspectionAttachment[0]['generate_photosheet'] == 1){
                                        $enclosures .= $counter++ . '. Generate Photosheet, ';
                                    }
                                    if($inspectionAttachment[0]['medical_papers'] == 1){
                                        $enclosures .= $counter++ . '. Medical Papers, ';
                                    }
                                    if($inspectionAttachment[0]['dealer_inv'] == 1){
                                        $enclosures .= $counter++ . '. Copy Dealer Inv., ';
                                    }
                                    if($inspectionAttachment[0]['police_report'] == 1){
                                        $enclosures .= $counter++ . '. Copy Of Police Report, ';
                                    }
                                    if($inspectionAttachment[0]['photographs'] == 1){
                                        $enclosures .= $counter++ . '. Photographs, ';
                                    }
                                    if($inspectionAttachment[0]['satisfaction_voucher'] == 1){
                                        $enclosures .= $counter++ . '. Satisfaction Voucher, ';
                                    }
                                    if($inspectionAttachment[0]['supporting_bills'] == 1){
                                        $enclosures .= $counter++ . '. Supporting Bills, ';
                                    }
                                    if($inspectionAttachment[0]['towing_charge_slip'] == 1){
                                        $enclosures .= $counter++ . '. Towing Charge Slip, ';
                                    }
                                    foreach($customAttachments as $key => $value){
                                        if(!empty($value)) {
                                            $enclosures .= $counter++ . '. ' . ucwords(str_replace("_", " ", $key)) . ', ';
                                        }
                                    }
                                @endphp
                                {!! rtrim($enclosures, ', ') !!}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        @endif

        @if($finalAssessment=='final_ass_report' && !empty($alldetails))
            @php
                $displayFiber = (isset($lossAssessment[0]['totalFibreAmt']) && $lossAssessment[0]['totalFibreAmt'] > 0) ? true : false;
                $displayRecond = (isset($lossAssessment[0]['totalReconditionAmt']) && $lossAssessment[0]['totalReconditionAmt'] > 0) ? true : false;
                $totalColForSup = 10;
                $tdColspanWithFiberRecon = 0;
                $remarkTdColspanWithFiberRecon = 0;
                if(!empty($displayFiber)) {
                    ++$tdColspanWithFiberRecon;
                    $totalColForSup++;
                }
                if(!empty($displayRecond)) {
                    ++$tdColspanWithFiberRecon;
                    $totalColForSup++;
                }
                if(empty($displayRecond) && empty($displayFiber)) {
                    $remarkTdColspanWithFiberRecon = 1;
                }
                $rightColSpan = 6;
                if(isset($lossAssessment[0]['display_bill_sr_no']) && $lossAssessment[0]['display_bill_sr_no'] == 1){
                    ++$rightColSpan;
                    $totalColForSup++;
                }
                if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1){
                    ++$rightColSpan;
                    $totalColForSup++;
                }
                if($tdColspanWithFiberRecon < 2) {
                    $totalColForSup++;
                }
            @endphp
            <br>
            <div class="page-break"></div>
            <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
                <tbody>
                    <tr>
                        <td align="center" valign="top" style="font-weight: bold; padding-top: 3px; border-left: none; border-right: none; text-decoration: underline;"> LOSS ASSESSMENT </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="padding-top: 3px;  padding-bottom: 5px;  border-left: none; border-right: none;">In respect of Vehicle Registration No. <span style="font-weight: bold;">{{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}</span>, Accident Date : <span style="font-weight: bold;">{{ !empty($policyDetails->date_time_accident) ? \Carbon\Carbon::parse($policyDetails->date_time_accident)->format('d/m/Y') : '' }}</span> <br />(Annexure 'A' attached to Survey Report No. <span style="font-weight: bold;">{{ isset($policyDetails->inspection_reference_no) ? $policyDetails->inspection_reference_no : '' }}</span>)</td>
                    </tr>
                </tbody>
            </table>
            <!-- Start 1st table loss of assessment -->
            <table width="100%" align="center">
                <tbody>
                    <tr>
                        <td align="left" valign="top" style="width: 40%; border-top: 2px solid #000; padding: 3px 0px; font-weight: bold;">ASSESSMENT OF LOSS</td>
                        <td align="left" valign="top" style="width: 60%; border-top: 2px solid #000; padding: 3px 0px; font-weight: bold;">(PARTS)</td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" border="0" align="center" id="design" style="font-size: 12px;">
                <tbody>
                    <tr>
                        <th align="left" valign="top" rowspan="2">Sr.No.</th>
                        <th align="left" valign="top" rowspan="2">Description of Parts</th>
                        @if(isset($lossAssessment[0]['display_bill_sr_no']) && $lossAssessment[0]['display_bill_sr_no'] == 1)
                            <th align="center" valign="top"  rowspan="2">Bill Sr.No.</th>
                        @endif
                        @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                            <th align="center" valign="top" rowspan="2">HSN Code</th>
                        @endif
                        <th align="center" valign="top" rowspan="2">QE</th>
                        <th align="center" valign="top" rowspan="2">QA</th>
                        <th align="right" valign="top" rowspan="2">Est. Rate</th>
                        <th align="center" valign="top" rowspan="2">{!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} %</th>
                        <th align="right" valign="top" rowspan="2">Estimated (Amt in ₹)</th>
                        <th align="center" valign="top" colspan="{{ intval($tdColspanWithFiberRecon + 3) }}">Assessed Parts Amount</th>
                        @if($tdColspanWithFiberRecon <= 1)
                            <th align="center" valign="top"  rowspan="2">Remark</th>
                        @endif
                    </tr>
                    <tr>
                        <th>Metal</th>
                        <th>Rub/Plast</th>
                        <th>Glass</th>
                        @if(isset($lossAssessment[0]['totalFibreAmt']) && $lossAssessment[0]['totalFibreAmt'] > 0)
                            <th>Fibre</th>
                        @endif
                        @if(isset($lossAssessment[0]['totalReconditionAmt']) && $lossAssessment[0]['totalReconditionAmt'] > 0)
                            <th>Recond</th>
                        @endif
                    </tr>
                    @if(!empty($alldetails) && is_array($alldetails))
                        @php
                            $indexCounter = 0;
                            $getPartsGstCondition = getPartsGstCondition($lossAssessment[0], $uniqueGstValue);
                            $multipleEstGSTonParts = $getPartsGstCondition['MultipleEstPartsGst'];
                            $nonMultipleEstGSTonParts = $getPartsGstCondition['nonMultipleEstPartsGst'];
                            $multipleAssGSTonParts = $getPartsGstCondition['MultipleAssPartsGst'];
                            $nonMultipleAssGSTonParts = $getPartsGstCondition['nonMultipleAssPartsGst'];
                            $uniqueGstValue = array_unique(array_merge($multipleEstGSTonParts, $nonMultipleEstGSTonParts, $multipleAssGSTonParts, $nonMultipleAssGSTonParts));
                            sort($uniqueGstValue);
                            $partSupplementaryTitle = null;
                        @endphp
                        @foreach($alldetails as $detail)
                            @if($detail['category'] == 'Supplementary' && !empty($detail['description']))
                                @php
                                    $partSupplementaryTitle = $detail['description'];
                                    if(empty($detail['quantities'])) {
                                        continue;
                                    }
                                    $partSupplementaryTitle = null;
                                @endphp
                            @endif
                            @if(isset($detail['category']) && !empty($detail['category']))
                                @php
                                    $detailMetal = 0;
                                    $detailRubber = 0;
                                    $detailGlass = 0;
                                    $detailFibre = 0;
                                    $detailRecondition = 0;
                                    if(empty($detail['quantities']) && !empty($detail['category'])) {
                                        switch ($detail['category']) {
                                            case 'Metal':
                                                $detailMetal = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                break;
                                            case 'Rubber':
                                                $detailRubber = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                break;
                                            case 'Glass':
                                                $detailGlass = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                break;
                                            case 'Fibre':
                                                $detailFibre = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                break;
                                            case 'Recondition':
                                                $detailRecondition = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                break;
                                            default:break;
                                        }
                                    }
                                    $detailsGst = !empty($detail['gst']) ? intval($detail['gst']) : 0;
                                @endphp
                                @if(!empty($partSupplementaryTitle))
                                    <tr>
                                        <td colspan="{{ intval($totalColForSup) }}" align="left" valign="middle" style="padding: 3px 5px 3px 5px; font-weight: bold;">{{ $partSupplementaryTitle }}</td>
                                    </tr>
                                    @php
                                        $partSupplementaryTitle = null;
                                    @endphp
                                @endif
                                <tr>
                                    <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">{{ intval(++$indexCounter) }}</td>
                                    <td align="left" valign="top" style="padding: 0px 3px;">{!! ($detail['imt_23'] == "Yes") ? '<strong>*</strong>' : '' !!} {{ $detail['description'] }}</td>
                                    @if(isset($lossAssessment[0]['display_bill_sr_no']) && $lossAssessment[0]['display_bill_sr_no'] == 1)
                                        <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($detail['b_sr_no']) ? $detail['b_sr_no'] : '-' }}</td>
                                    @endif
                                    @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                                        <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($detail['hsn_code']) ? $detail['hsn_code'] : '-' }}</td>
                                    @endif
                                    <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($detail['qe']) ? $detail['qe'] : '-' }}</td>
                                    <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($detail['qa']) ? $detail['qa'] : '-' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ ($detail['est_rate'] > 0) ? $detail['est_rate'] : '-' }}</td>
                                    <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($multipleAssGSTonParts) ? $detailsGst : array_sum($nonMultipleAssGSTonParts) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ ($detail['est_amt'] > 0) ? $detail['est_amt'] : '-' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detailMetal) ? number_format_custom($detailMetal) : '-' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detailRubber) ? number_format_custom($detailRubber) : '-' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detailGlass) ? number_format_custom($detailGlass) : '-' }}</td>
                                    @if(!empty($displayFiber))
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detailFibre) ? number_format_custom($detailFibre) : '-' }}</td>
                                    @endif
                                    @if(!empty($displayRecond))
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detailRecondition) ? number_format_custom($detailRecondition) : '-' }}</td>
                                    @endif
                                    @if($tdColspanWithFiberRecon <= 1)
                                        <td align="center" valign="top" style="padding: 0px 3px;">{!! !empty($detail['remarks']) ? $detail['remarks'] : '-' !!}</td>
                                    @endif
                                </tr>
                            @endif

                            @if (!empty($detail['quantities']))
                                @php
                                    $quantityIndex = 0;
                                @endphp
                                @foreach($detail['quantities'] as $quantity)
                                    @if(isset($quantity['category']) && !empty($quantity['category']))
                                        @php
                                            $quantityMetal = 0;
                                            $quantityRubber = 0;
                                            $quantityGlass = 0;
                                            $quantityFibre = 0;
                                            $quantityRecondition = 0;
                                            if(!empty($quantity['category'])) {
                                                switch ($quantity['category']) {
                                                    case 'Metal':
                                                        $quantityMetal = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                        break;
                                                    case 'Rubber':
                                                        $quantityRubber = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                        break;
                                                    case 'Glass':
                                                        $quantityGlass = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                        break;
                                                    case 'Fibre':
                                                        $quantityFibre = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                        break;
                                                    case 'Recondition':
                                                        $quantityRecondition = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                        break;
                                                    default:break;
                                                }
                                            }
                                            $quantityGst = !empty($quantity['gst']) ? intval($quantity['gst']) : 0;
                                        @endphp
                                        <tr>
                                            <td align="center" valign="top" style="padding: 0px 3px 0px 13px; font-style: italic; border-left: 1px solid #000;">{!!$indexCounter.'.'.intval(++$quantityIndex)!!}</td>
                                            <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;">{!! ($quantity['imt_23'] == "Yes") ? '<strong>*</strong>' : '' !!} {{ $quantity['description'] }}</td>
                                            @if(isset($lossAssessment[0]['display_bill_sr_no']) && $lossAssessment[0]['display_bill_sr_no'] == 1)
                                                <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">{{ !empty($quantity['b_sr_no']) ? $quantity['b_sr_no'] : '-' }}</td>
                                            @endif
                                            @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                                                <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">{{ !empty($quantity['hsn_code']) ? $quantity['hsn_code'] : '-' }}</td>
                                            @endif
                                            <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">{{ $quantity['qe'] }}</td>
                                            <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">{{ $quantity['qa'] }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ ($quantity['est_rate'] > 0) ? $quantity['est_rate'] : '-' }}</td>
                                            <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">{{ !empty($multipleAssGSTonParts) ? $quantityGst : array_sum($nonMultipleAssGSTonParts) }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ ($quantity['est_amt'] > 0) ? $quantity['est_amt'] : '-' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{{ !empty($quantityMetal) ? number_format_custom($quantityMetal) : '-' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{{ !empty($quantityRubber) ? number_format_custom($quantityRubber) : '-' }}</td>
                                            <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{{ !empty($quantityGlass) ? number_format_custom($quantityGlass) : '-' }}</td>
                                            @if(!empty($displayFiber))
                                                <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{{ !empty($quantityFibre) ? number_format_custom($quantityFibre) : '-' }}</td>
                                            @endif
                                            @if(!empty($displayRecond))
                                                <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{{ !empty($quantityRecondition) ? number_format_custom($quantityRecondition) : '-' }}</td>
                                            @endif
                                            @if($tdColspanWithFiberRecon <= 1)
                                                <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">{!! !empty($quantity['remarks']) ? $quantity['remarks'] : '-' !!}</td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        @if(!empty($uniqueGstValue))
                            @php
                                $gstIndexWiseAmt = [];
                                //Sub Total Counting Variable Start
                                $SubTotalEstimatedAmt = 0;
                                $SubTotalAssessedMetalAmt = 0;
                                $subTotalAssessedRubPlastAmt = 0;
                                $SubTotalAssessedGlassAmt = 0;
                                $SubTotalAssessedFiberAmt = 0;
                                $SubTotalAssessedReconditionAmt = 0;

                                //IMT Sub Total
                                $subTotalAssessedMetalIMTAmt = 0;
                                $SubTotalAssessedRubPlastIMTAmt = 0;
                                $subTotalAssessedGlassIMTAmt = 0;
                                //SubTotal Counting Variable End
                            @endphp
                            @foreach($uniqueGstValue as $rate)
                                @php
                                    //Total Counting Variable Start
                                    $totalEstimatedAmt = 0;
                                    $totalAssessedMetalAmt = 0;
                                    $totalAssessedRubPlastAmt = 0;
                                    $totalAssessedGlassAmt = 0;
                                    $totalAssessedFiberAmt = 0;
                                    $totalAssessedReconditionAmt = 0;

                                    //IMT Total
                                    $totalAssessedMetalIMTAmt = 0;
                                    $totalAssessedRubPlastIMTAmt = 0;
                                    $totalAssessedGlassIMTAmt = 0;
                                    //Total Counting Variable End
                                @endphp
                                @foreach($alldetails as $detail)
                                    @php
                                        $detailGst = !empty($detail['gst']) ? intval($detail['gst']) : 0;;
                                        if(isset($detail['category']) && !empty($detail['category'])) {
                                            $detailMetal = 0;
                                            $detailRubber = 0;
                                            $detailGlass = 0;
                                            $detailFibre = 0;
                                            $detailRecondition = 0;

                                            $detailIMTMetal = 0;
                                            $detailIMTRubber = 0;
                                            $detailIMTGlass = 0;

                                            if(empty($detail['quantities']) && !empty($detail['category'])) {
                                                switch ($detail['category']) {
                                                    case 'Metal':
                                                        if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                                            $detailIMTMetal = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                        } else {
                                                            $detailMetal = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                        }
                                                        break;
                                                    case 'Rubber':
                                                        if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                                            $detailIMTRubber = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                        } else {
                                                            $detailRubber = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                        }
                                                        break;
                                                    case 'Glass':
                                                        if(!empty($detail['imt_23']) && $detail['imt_23'] == 'Yes') {
                                                            $detailIMTGlass = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                        } else {
                                                            $detailGlass = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                        }
                                                        break;
                                                    case 'Fibre':
                                                            $detailFibre = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                        break;
                                                    case 'Recondition':
                                                            $detailRecondition = ($detail['ass_amt'] > 0) ? $detail['ass_amt'] : 0;
                                                        break;
                                                    default:break;
                                                }
                                            }

                                            if(!empty($multipleEstGSTonParts) && isset($multipleEstGSTonParts[$rate]) && $detailGst==$rate) {
                                                $totalEstimatedAmt += ($detail['est_amt'] > 0) ? $detail['est_amt'] : 0;
                                            } elseif(!empty($nonMultipleEstGSTonParts) && isset($nonMultipleEstGSTonParts[$rate])) {
                                                $totalEstimatedAmt += ($detail['est_amt'] > 0) ? $detail['est_amt'] : 0;
                                            }

                                            if(!empty($multipleAssGSTonParts) && isset($multipleAssGSTonParts[$rate]) && $detailGst==$rate) {
                                                $totalAssessedMetalAmt += $detailMetal;
                                                $totalAssessedRubPlastAmt += $detailRubber;
                                                $totalAssessedGlassAmt += $detailGlass;

                                                $totalAssessedMetalIMTAmt += $detailIMTMetal;
                                                $totalAssessedRubPlastIMTAmt += $detailIMTRubber;
                                                $totalAssessedGlassIMTAmt += $detailIMTGlass;
                                                $totalAssessedFiberAmt += $detailFibre;
                                                $totalAssessedReconditionAmt += $detailRecondition;
                                            } elseif(!empty($nonMultipleAssGSTonParts) && isset($nonMultipleAssGSTonParts[$rate])) {
                                                $totalAssessedMetalAmt += $detailMetal;
                                                $totalAssessedRubPlastAmt += $detailRubber;
                                                $totalAssessedGlassAmt += $detailGlass;
                                                $totalAssessedFiberAmt += $detailFibre;
                                                $totalAssessedReconditionAmt += $detailRecondition;

                                                $totalAssessedMetalIMTAmt += $detailIMTMetal;
                                                $totalAssessedRubPlastIMTAmt += $detailIMTRubber;
                                                $totalAssessedGlassIMTAmt += $detailIMTGlass;
                                            }
                                        }

                                        if(!empty($detail['quantities'])) {
                                            foreach($detail['quantities'] as $quantity) {
                                                $quantityGst = !empty($quantity['gst']) ? intval($quantity['gst']) : 0;
                                                if(isset($quantity['category']) &&  !empty($quantity['category'])){
                                                    $quantityMetal = 0;
                                                    $quantityRubber = 0;
                                                    $quantityGlass = 0;
                                                    $quantityFiber = 0;
                                                    $quantityRecondition = 0;

                                                    $quantityIMTMetal = 0;
                                                    $quantityIMTRubber = 0;
                                                    $quantityIMTGlass = 0;

                                                    switch ($quantity['category']) {
                                                        case 'Metal':
                                                            if(!empty($quantity['imt_23']) && $quantity['imt_23'] == 'Yes') {
                                                                $quantityIMTMetal = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                            } else {
                                                                $quantityMetal = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                            }
                                                            break;
                                                        case 'Rubber':
                                                            if(!empty($quantity['imt_23']) && $quantity['imt_23'] == 'Yes') {
                                                                $quantityIMTRubber = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                            } else {
                                                                $quantityRubber = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                            }
                                                            break;
                                                        case 'Glass':
                                                            if(!empty($quantity['imt_23']) && $quantity['imt_23'] == 'Yes') {
                                                                $quantityIMTGlass = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                            } else {
                                                                $quantityGlass = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                            }
                                                            break;
                                                        case 'Fibre':
                                                            $quantityFiber = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                            break;
                                                        case 'Recondition':
                                                            $quantityRecondition = ($quantity['ass_amt'] > 0) ? $quantity['ass_amt'] : 0;
                                                            break;
                                                        default:break;
                                                    }

                                                    if(!empty($multipleAssGSTonParts) && isset($multipleAssGSTonParts[$rate]) && $quantityGst==$rate) {
                                                        $totalAssessedMetalAmt += $quantityMetal;
                                                        $totalAssessedRubPlastAmt += $quantityRubber;
                                                        $totalAssessedGlassAmt += $quantityGlass;
                                                        $totalAssessedFiberAmt += $quantityFiber;
                                                        $totalAssessedReconditionAmt += $quantityRecondition;

                                                        $totalAssessedMetalIMTAmt += $quantityIMTMetal;
                                                        $totalAssessedRubPlastIMTAmt += $quantityIMTRubber;
                                                        $totalAssessedGlassIMTAmt += $quantityIMTGlass;
                                                    } elseif(!empty($nonMultipleAssGSTonParts) && isset($nonMultipleAssGSTonParts[$rate])) {
                                                        $totalAssessedMetalAmt += $quantityMetal;
                                                        $totalAssessedRubPlastAmt += $quantityRubber;
                                                        $totalAssessedGlassAmt += $quantityGlass;
                                                        $totalAssessedFiberAmt += $quantityFiber;
                                                        $totalAssessedReconditionAmt += $quantityRecondition;

                                                        $totalAssessedMetalIMTAmt += $quantityIMTMetal;
                                                        $totalAssessedRubPlastIMTAmt += $quantityIMTRubber;
                                                        $totalAssessedGlassIMTAmt += $quantityIMTGlass;
                                                    }
                                                }
                                            }
                                        }
                                    @endphp
                                @endforeach

                                @php
                                    $gstIndexWiseAmt[$rate]['est_amt'] = $totalEstimatedAmt;
                                    $gstIndexWiseAmt[$rate]['ass_metal_amt'] = $totalAssessedMetalAmt;
                                    $gstIndexWiseAmt[$rate]['ass_rubber_plast_amt'] = $totalAssessedRubPlastAmt;
                                    $gstIndexWiseAmt[$rate]['ass_glass_amt'] = $totalAssessedGlassAmt;
                                    $gstIndexWiseAmt[$rate]['ass_metal_amt_imt'] = $totalAssessedMetalIMTAmt;
                                    $gstIndexWiseAmt[$rate]['ass_rubber_plast_amt_imt'] = $totalAssessedRubPlastIMTAmt;
                                    $gstIndexWiseAmt[$rate]['ass_glass_amt_imt'] = $totalAssessedGlassIMTAmt;
                                    $gstIndexWiseAmt[$rate]['ass_fiber_amt'] = $totalAssessedFiberAmt;
                                    $gstIndexWiseAmt[$rate]['ass_recondition_amt'] = $totalAssessedReconditionAmt;

                                    $part_tax_summary_details['parts'][$rate]['amount'] = ($totalAssessedMetalAmt + $totalAssessedRubPlastAmt + $totalAssessedGlassAmt + $totalAssessedFiberAmt + $totalAssessedReconditionAmt);
                                    $part_tax_summary_details['imt_parts'][$rate]['amount'] = ($totalAssessedMetalIMTAmt + $totalAssessedRubPlastIMTAmt + $totalAssessedGlassIMTAmt);

                                    $part_tax_cate_summary_details['metal'][$rate]['amount'] = $totalAssessedMetalAmt;
                                    $part_tax_cate_summary_details['imt_metal'][$rate]['amount'] = $totalAssessedMetalIMTAmt;
                                    $part_tax_cate_summary_details['glass'][$rate]['amount'] = $totalAssessedGlassAmt;
                                    $part_tax_cate_summary_details['rubber_plast'][$rate]['amount'] = $totalAssessedRubPlastAmt;
                                    $part_tax_cate_summary_details['imt_rubber_plast'][$rate]['amount'] = $totalAssessedRubPlastIMTAmt;
                                    $part_tax_cate_summary_details['fiber'][$rate]['amount'] = $totalAssessedFiberAmt;
                                    $part_tax_cate_summary_details['recondition'][$rate]['amount'] = $totalAssessedReconditionAmt;
                                    $SubTotalEstimatedAmt += $totalEstimatedAmt;
                                    $SubTotalAssessedMetalAmt += $totalAssessedMetalAmt;
                                    $subTotalAssessedRubPlastAmt += $totalAssessedRubPlastAmt;
                                    $SubTotalAssessedGlassAmt += $totalAssessedGlassAmt;
                                    $SubTotalAssessedFiberAmt += $totalAssessedFiberAmt;
                                    $SubTotalAssessedReconditionAmt += $totalAssessedReconditionAmt;

                                    $subTotalAssessedMetalIMTAmt += $totalAssessedMetalIMTAmt;
                                    $SubTotalAssessedRubPlastIMTAmt += $totalAssessedRubPlastIMTAmt;
                                    $subTotalAssessedGlassIMTAmt += $totalAssessedGlassIMTAmt;


                                @endphp
                                <tr>
                                    <td align="left" valign="top" colspan="{{ $rightColSpan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Parts with {{ ($lossAssessment[0]['IGSTonPartsAndLab'] == 0) ? "GST" : "IGST" }} {{ $rate }}%)</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ number_format_custom($totalEstimatedAmt) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ number_format_custom(($totalAssessedMetalAmt + $totalAssessedMetalIMTAmt)) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ number_format_custom(($totalAssessedRubPlastAmt + $totalAssessedRubPlastIMTAmt)) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ number_format_custom(($totalAssessedGlassAmt + $totalAssessedGlassIMTAmt)) }}</td>
                                    @if(!empty($displayFiber))
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ number_format_custom($totalAssessedFiberAmt) }}</td>
                                    @endif
                                    @if(!empty($displayRecond))
                                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">{{ number_format_custom($totalAssessedReconditionAmt) }}</td>
                                    @endif
                                    @if($tdColspanWithFiberRecon <= 1)
                                        <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td align="right" valign="top" colspan="{{ $rightColSpan }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Total</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($SubTotalEstimatedAmt) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($SubTotalAssessedMetalAmt + $subTotalAssessedMetalIMTAmt) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($subTotalAssessedRubPlastAmt + $SubTotalAssessedRubPlastIMTAmt) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($SubTotalAssessedGlassAmt) }}</td>
                                @if(!empty($displayFiber))
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($SubTotalAssessedFiberAmt) }}</td>
                                @endif
                                @if(!empty($displayRecond))
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($SubTotalAssessedReconditionAmt) }}</td>
                                @endif
                                @if($tdColspanWithFiberRecon <= 1)
                                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                @endif

                            </tr>
                            @php
                                $MetalDepPer = ($lossAssessment[0]['MetalDepPer'] > 0 && empty($lossAssessment[0]['IsZeroDep'])) ? $lossAssessment[0]['MetalDepPer'] : '0';
                                $RubberDepPer = ($lossAssessment[0]['RubberDepPer'] > 0 && empty($lossAssessment[0]['IsZeroDep'])) ? $lossAssessment[0]['RubberDepPer'] : '0';
                                $GlassDepPer = ($lossAssessment[0]['GlassDepPer'] > 0 && empty($lossAssessment[0]['IsZeroDep'])) ? $lossAssessment[0]['GlassDepPer'] : '0';
                                $FibreDepPer = ($lossAssessment[0]['FibreDepPer'] > 0 && empty($lossAssessment[0]['IsZeroDep'])) ? $lossAssessment[0]['FibreDepPer'] : '0';

                                //Depratioin on IMT
                                $lessDepMetal = (($SubTotalAssessedMetalAmt * $MetalDepPer) / 100);
                                $lessDepMetalIMT = (($subTotalAssessedMetalIMTAmt * $MetalDepPer) / 100);

                                $lessDepRubber = (($subTotalAssessedRubPlastAmt * $RubberDepPer) / 100);
                                $lessDepRubberIMT = (($SubTotalAssessedRubPlastIMTAmt * $RubberDepPer) / 100);

                                $lessDepGlass = (($SubTotalAssessedGlassAmt * $GlassDepPer) / 100);
                                $lessDepGlassIMT = (($totalAssessedGlassIMTAmt * $GlassDepPer) / 100);

                                $lessDepFiber = (($SubTotalAssessedFiberAmt * $FibreDepPer) / 100);

                                $finalSubTotalMetal = ($SubTotalAssessedMetalAmt + $subTotalAssessedMetalIMTAmt) - ($lessDepMetal + $lessDepMetalIMT);
                                $finalSubTotalRubPlast = ($subTotalAssessedRubPlastAmt + $SubTotalAssessedRubPlastIMTAmt) - ($lessDepRubber + $lessDepRubberIMT);
                                $finalSubTotalGlass = ($SubTotalAssessedGlassAmt + $subTotalAssessedGlassIMTAmt) - ($lessDepGlass + $lessDepGlassIMT);
                                $finalSubTotalFiber = ($SubTotalAssessedFiberAmt - $lessDepFiber);
                                $finalSubTotalRecondition = $SubTotalAssessedReconditionAmt;


                            @endphp

                            <tr>
                                <td align="left" valign="top" colspan="{{ $rightColSpan }}" style="padding: 0px 3px; border-left: 1px solid #000;"> Less Dep @ <span style="font-weight: bold;">{{ $MetalDepPer }}%</span> on Metal, <span style="font-weight: bold;">{{ $RubberDepPer }}%</span> on Rub/Plast, <span style="font-weight: bold;">{{ $GlassDepPer }}%</span> on Glass Parts{{ ($lossAssessment[0]['totalFibreAmt'] > 0 && $FibreDepPer > 0) ? ', '.$FibreDepPer.'% on Fibre Parts.' : '' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($lessDepMetal + $lessDepMetalIMT)) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($lessDepRubber + $lessDepRubberIMT)) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($lessDepGlass + $lessDepGlassIMT)) }}</td>
                                @if(!empty($displayFiber))
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($lessDepFiber) }}</td>
                                @endif
                                @if(!empty($displayRecond))
                                    <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                @endif
                                @if($tdColspanWithFiberRecon <= 1)
                                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                @endif
                            </tr>

                            <tr>
                                <td align="right" valign="top" colspan="{{ $rightColSpan }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($SubTotalEstimatedAmt) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($finalSubTotalMetal) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($finalSubTotalRubPlast) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($finalSubTotalGlass) }}</td>
                                @if(!empty($displayFiber))
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($finalSubTotalFiber) }}</td>
                                @endif
                                @if(!empty($displayRecond))
                                    <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ number_format_custom($finalSubTotalRecondition) }}</td>
                                @endif
                                @if($tdColspanWithFiberRecon <= 1)
                                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                @endif
                            </tr>

                            @if($subTotalAssessedMetalIMTAmt > 0)
                                @php
                                    $addLessMetal = ((($subTotalAssessedMetalIMTAmt - $lessDepMetalIMT) * $lossAssessment[0]['IMT23DepPer']) / 100);
                                    $addLesRubPlast = ((($SubTotalAssessedRubPlastIMTAmt - $lessDepRubberIMT) * $lossAssessment[0]['IMT23DepPer']) / 100);
                                    $finalSubTotalMetal -= $addLessMetal;
                                    $finalSubTotalRubPlast -= $addLesRubPlast;
                                @endphp
                                <tr>
                                    <td align="left" valign="top" colspan="{{ $rightColSpan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Less Addl. Deduction of IMT Parts @ <span style="font-weight: bold;">{{ $lossAssessment[0]['IMT23DepPer'] }}%</span> (*)- Metal - <span style="font-weight: bold;">{{ number_format_custom(($subTotalAssessedMetalIMTAmt - $lessDepMetalIMT)) }}</span> Rub/Plast - <span style="font-weight: bold;">{{ number_format_custom(($SubTotalAssessedRubPlastIMTAmt - $lessDepRubberIMT)) }}</span></td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addLessMetal) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addLesRubPlast) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                    @if(!empty($displayFiber))
                                        <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                    @endif
                                    @if(!empty($displayRecond))
                                        <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                    @endif
                                    @if($tdColspanWithFiberRecon <= 1)
                                        <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                    @endif
                                </tr>

                                <tr>
                                    <td align="right" valign="top" colspan="{{ $rightColSpan }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($SubTotalEstimatedAmt) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($finalSubTotalMetal) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($finalSubTotalRubPlast) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($finalSubTotalGlass) }}</td>
                                    @if(!empty($displayFiber))
                                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($finalSubTotalFiber) }}</td>
                                    @endif
                                    @if(!empty($displayRecond))
                                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($finalSubTotalRecondition) }}</td>
                                    @endif
                                    @if($tdColspanWithFiberRecon <= 1)
                                        <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                    @endif
                                </tr>
                            @endif

                            @php
                                $grandTotalEstimatedAmt = $SubTotalEstimatedAmt;
                                $grandTotalAssessedPartsAmount = ($finalSubTotalMetal + $finalSubTotalRubPlast +$finalSubTotalGlass + $finalSubTotalFiber + $finalSubTotalRecondition);
                                $imtNoteShow = false;
                            @endphp

                            @if(!empty($gstIndexWiseAmt))
                                @php
                                    $grandTotalEstimatedAmt = 0;
                                    $grandTotalMetal = 0;
                                    $grandTotalRubberPlast = 0;
                                    $grandTotalGlass = 0;
                                    $grandTotalFiber = 0;
                                    $grandTotalRecondition = 0;
                                @endphp
                                @foreach ($gstIndexWiseAmt as $gst => $item)
                                    @php
                                        //Metal Calculation
                                        $metalAmt = $item['ass_metal_amt'];
                                        $metalIMTAmt = $item['ass_metal_amt_imt'];
                                        $metalAmtDep = 0;
                                        $metalIMTAmtDep = 0;
                                        if($lossAssessment[0]['MetalDepPer'] > 0 && empty($lossAssessment[0]['IsZeroDep'])) {
                                            $metalAmtDep = (($metalAmt * $lossAssessment[0]['MetalDepPer']) / 100);
                                            $metalIMTAmtDep = (($metalIMTAmt * $lossAssessment[0]['MetalDepPer']) / 100);
                                            $metalAmt -= $metalAmtDep;
                                            $metalIMTAmt -= $metalIMTAmtDep;
                                        }

                                        //Rubber/Plast Calculation
                                        $rubberPlastAmt = $item['ass_rubber_plast_amt'];
                                        $rubberPlastIMTAmt = $item['ass_rubber_plast_amt_imt'];
                                        $rubberPlastAmtDep = 0;
                                        $rubberPlastIMTAmtDep = 0;
                                        if($lossAssessment[0]['RubberDepPer'] > 0 && empty($lossAssessment[0]['IsZeroDep'])) {
                                            $rubberPlastAmtDep = (($rubberPlastAmt * $lossAssessment[0]['RubberDepPer']) / 100);
                                            $rubberPlastIMTAmtDep = (($rubberPlastIMTAmt * $lossAssessment[0]['RubberDepPer']) / 100);
                                            $rubberPlastAmt -= $rubberPlastAmtDep;
                                            $rubberPlastIMTAmt -= $rubberPlastIMTAmtDep;
                                        }

                                        //Glass Calculation
                                        $glassAmt = $item['ass_glass_amt'];
                                        $glassIMTAmt = $item['ass_glass_amt_imt'];
                                        $glassAmtDep = 0;
                                        $glassIMTAmtDep = 0;
                                        if($lossAssessment[0]['GlassDepPer'] > 0 && empty($lossAssessment[0]['IsZeroDep'])) {
                                            $glassAmtDep = (($glassAmt * $lossAssessment[0]['GlassDepPer']) / 100);
                                            $glassIMTAmtDep = (($glassIMTAmt * $lossAssessment[0]['GlassDepPer']) / 100);
                                            $glassAmt -= $glassAmtDep;
                                            $glassIMTAmt -= $glassIMTAmtDep;
                                        }

                                        //Fiber Calculation
                                        $fiberAmt = isset($item['ass_fiber_amt']) ? $item['ass_fiber_amt'] : 0;
                                        $fiberAmtDep = 0;
                                        if($lossAssessment[0]['FibreDepPer'] > 0 && empty($lossAssessment[0]['IsZeroDep']) && $fiberAmt > 0) {
                                            $fiberAmtDep = (($fiberAmt * $lossAssessment[0]['FibreDepPer']) / 100);
                                            $fiberAmt -= $fiberAmtDep;
                                        }

                                        //Recobdition Calculation
                                        $reconditionAmt = isset($item['ass_recondition_amt']) ? $item['ass_recondition_amt'] : 0;
                                        $addLessMetalImt = 0;
                                        if($metalIMTAmt > 0) {
                                            if(empty($imtNoteShow)){
                                                $imtNoteShow = true;
                                            }
                                            $addLessMetalImt = (($metalIMTAmt * $lossAssessment[0]['IMT23DepPer']) / 100);
                                            $metalIMTAmt -= $addLessMetalImt;
                                        }
                                        $addLesRubPlastImt = 0;
                                        if($rubberPlastIMTAmt > 0) {
                                            $addLesRubPlastImt = (($rubberPlastIMTAmt * $lossAssessment[0]['IMT23DepPer']) / 100);
                                            $rubberPlastIMTAmt -= $addLesRubPlastImt;
                                        }

                                        //Adding Gst
                                        $addingGstOnEstAmt = ($item['est_amt'] > 0) ? (($item['est_amt'] * $gst) / 100) : 0;
                                        $totalMetalAmtForAddGst = ($metalAmt + $metalIMTAmt);
                                        $totalRubberPlasAmtForAddGst = ($rubberPlastAmt + $rubberPlastIMTAmt);
                                        $totalGlassAmtForAddGst = ($glassAmt + $glassIMTAmt);

                                        $addingGstOnMetal = ($totalMetalAmtForAddGst > 0 && $gst > 0) ? (($totalMetalAmtForAddGst * $gst) / 100) : 0;
                                        $addingGstOnRubberPlast = ($totalRubberPlasAmtForAddGst > 0 && $gst > 0) ? (($totalRubberPlasAmtForAddGst * $gst) / 100) : 0;
                                        $addingGstOnGlasses = ($totalGlassAmtForAddGst > 0 && $gst > 0) ? (($totalGlassAmtForAddGst * $gst) / 100) : 0;
                                        $addingGstOnFiber = ($fiberAmt > 0 && $gst > 0) ? (($fiberAmt * $gst) / 100) : 0;
                                        $addingGstOnRecondition = ($reconditionAmt > 0 && $gst > 0) ? (($reconditionAmt * $gst) / 100) : 0;

                                        $grandTotalEstimatedAmt += ($item['est_amt'] + $addingGstOnEstAmt);
                                        $grandTotalMetal += ($totalMetalAmtForAddGst + $addingGstOnMetal);
                                        $grandTotalRubberPlast += ($totalRubberPlasAmtForAddGst + $addingGstOnRubberPlast);
                                        $grandTotalGlass += ($totalGlassAmtForAddGst + $addingGstOnGlasses);
                                        $grandTotalFiber += ($fiberAmt + $addingGstOnFiber);
                                        $grandTotalRecondition += ($reconditionAmt + $addingGstOnRecondition);

                                        $part_tax_summary_details['parts'][$gst]['dep'] = ($metalAmtDep + $rubberPlastAmtDep + $fiberAmtDep);
                                        $part_tax_summary_details['imt_parts'][$gst]['dep'] = ($metalIMTAmtDep + $rubberPlastIMTAmtDep);
                                        $part_tax_summary_details['imt_parts'][$gst]['imt_dep'] = ($addLesRubPlastImt + $addLessMetalImt);

                                        $part_tax_cate_summary_details['metal'][$gst]['dep'] = $metalAmtDep;
                                        $part_tax_cate_summary_details['imt_metal'][$gst]['dep'] = $metalIMTAmtDep;
                                        $part_tax_cate_summary_details['imt_metal'][$gst]['add_dep'] = $addLessMetalImt;
                                        $part_tax_cate_summary_details['glass'][$gst]['dep'] = $glassAmtDep;
                                        $part_tax_cate_summary_details['rubber_plast'][$gst]['dep'] = $rubberPlastAmtDep;
                                        $part_tax_cate_summary_details['imt_rubber_plast'][$gst]['dep'] = $rubberPlastIMTAmtDep;
                                        $part_tax_cate_summary_details['imt_rubber_plast'][$gst]['add_dep'] = $addLesRubPlastImt;
                                        $part_tax_cate_summary_details['fiber'][$gst]['dep'] = $fiberAmtDep;
                                        $part_tax_cate_summary_details['recondition'][$gst]['dep'] = 0;

                                    @endphp

                                    <tr>
                                        <td align="left" valign="top" colspan="{{ $rightColSpan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add {!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} @ {{ $gst }}%</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addingGstOnEstAmt) }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addingGstOnMetal) }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addingGstOnRubberPlast) }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addingGstOnGlasses) }}</td>
                                        @if(!empty($displayFiber))
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addingGstOnFiber) }}</td>
                                        @endif
                                        @if(!empty($displayRecond))
                                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addingGstOnRecondition) }}</td>
                                        @endif
                                        @if($tdColspanWithFiberRecon <= 1)
                                            <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                        @endif
                                    </tr>
                                @endforeach
                                <tr>
                                    <td align="right" valign="top" colspan="{{ $rightColSpan }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalEstimatedAmt) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalMetal) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalRubberPlast) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalGlass) }}</td>
                                    @if(!empty($displayFiber))
                                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalFiber) }}</td>
                                    @endif
                                    @if(!empty($displayRecond))
                                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalRecondition) }}</td>
                                    @endif
                                    @if($tdColspanWithFiberRecon <= 1)
                                        <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                                    @endif
                                </tr>
                                @php
                                    $grandTotalAssessedPartsAmount = ($grandTotalMetal + $grandTotalRubberPlast + $grandTotalGlass + $grandTotalFiber + $grandTotalRecondition);
                                @endphp
                            @endif
                            @php
                                if($tdColspanWithFiberRecon <= 1) {
                                    $remarkTdColspanWithFiberRecon = 1;
                                }
                            @endphp
                            <tr>
                                <td align="right" valign="top" colspan="{{ $rightColSpan }}" style="padding: 0px 3px;font-weight: bold; border-left: 1px solid #000;">Total</td>
                                <td align="right" valign="top" colspan="{{ intval($tdColspanWithFiberRecon + $remarkTdColspanWithFiberRecon + 4) }}" style="padding: 0px 3px; border-bottom: 2px solid #000; font-weight: bold;">{{ number_format_custom($grandTotalAssessedPartsAmount) }}</td>
                            </tr>

                            @if(!empty($imtNoteShow))
                                <tr>
                                    <td align="left" valign="center" colspan="{{ intval($rightColSpan + $tdColspanWithFiberRecon + $remarkTdColspanWithFiberRecon + 4) }}" style="padding: 5px; font-weight: bold; font-size: 14px;">* The Star marks denotes against the IMT-21 Parts allowed.</td>
                                </tr>
                            @endif
                        @endif
                    @endif
                </tbody>
            </table>

            {{--LABOUR CHARGES File--}}
            @php
                //Labour Gst
                $getLabourGstCondition = getLabourGstCondition($lossAssessment[0], $uniqueLabourGstValue);
                $labourEstMultipleGst = $getLabourGstCondition['labourEstMultipleGst'];
                $labourEstNoneMultipleGst = $getLabourGstCondition['labourEstNoneMultipleGst'];
                $labourAssMultipleGst = $getLabourGstCondition['labourAssMultipleGst'];
                $labourAssNoneMultipleGst = $getLabourGstCondition['labourAssNoneMultipleGst'];
                $labourUniqueGst = array_unique(array_merge($labourEstMultipleGst, $labourEstNoneMultipleGst, $labourAssMultipleGst, $labourAssNoneMultipleGst));
                sort($labourUniqueGst);
                $labourIndexCounter = 0;
                $labColSpan = (isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1) ? 5 : 4;
                $labSuppColSpan = (isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1) ? 8 : 7;
                $labourSupplementaryTitle = null;
            @endphp
            <br>

            <table width="100%" id="design" cellpadding="0" cellspacing="0" border="0" align="center" style="padding-top:20px; border-top: 2px solid #333; border-bottom: 0px; border-right: 0px; border-left: 0px;">
                <tbody style="border-top: 2px solid #333; border-bottom: 0px; border-right: 0px; border-left: 0px;">
                    <tr style="border-top: 2px solid #333; border-bottom: 0px; border-right: 0px; border-left: 0px;"><td align="left" valign="top" style="font-weight: bold; border-top: 2px solid #333; border-bottom: 0px; border-right: 0px; border-left: 0px; line-height:0px; padding: 3px 0px;">LABOUR CHARGES</td></tr>
                </tbody>
            </table>
            <table width="100%" align="center" id="design" style="font-size: 12px;">
                <tbody>
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; width: 5%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
                    <td align="left" valign="top" style="padding: 0px 3px; width: 25%; font-weight: bold;">Description of Labour</td>
                    @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                        <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">SAC</td>
                    @endif
                    <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">Remarks</td>
                    <td align="center" valign="top" style="padding: 0px 3px; width: 5%; font-weight: bold;">{!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} %</td>
                    <td align="right" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Estimated<br/><span style="font-weight: 400;">(Amt in ₹)</span></td>
                    <td align="right" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">O/F & Denting<br/><span style="font-weight: 400;">(Amt in ₹)</span></td>
                    <td align="right" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Painting<br/><span style="font-weight: 400;">(Amt in ₹)</span></td>
                </tr>

                @foreach($alldetails as $labour)
                    @if($labour['category'] == 'Supplementary' && !empty($labour['description']))
                        @php
                            $labourSupplementaryTitle = $labour['description'];
                            $continue = ($labour['ass_lab'] > 0 || $labour['est_lab'] > 0 || $labour['painting_lab'] > 0) ? false : true;
                            if(!empty($labour['quantities'])) {
                                $labourSupplementaryTitle = null;
                            }
                            if(!empty($continue)) {
                                continue;
                            }
                        @endphp
                    @endif
                    @if($labour['ass_lab'] > 0 || $labour['est_lab'] > 0 || $labour['painting_lab'] > 0)
                        @php
                            $detailsLabourGst = !empty($labour['gst']) ? intval($labour['gst']) : 0;
                        @endphp
                        @if(!empty($labourSupplementaryTitle))
                            <tr>
                                <td colspan="{{ intval($labSuppColSpan) }}" align="left" valign="middle" style="padding: 3px 5px 3px 5px; font-weight: bold;">{{ $labourSupplementaryTitle }}</td>
                            </tr>
                            @php
                                $labourSupplementaryTitle = null;
                            @endphp
                        @endif
                        <tr>
                            <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">{{ ++$labourIndexCounter }}</td>
                            <td align="left" valign="top" style="padding: 0px 3px;">{!! ($labour['imt_23'] == "Yes") ? '<strong>*</strong>' : ''!!} {{ !empty($labour['labour_type']) ? $labour['labour_type'].' of '.$labour['description'] : $labour['description'] }}</td>
                            @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                                <td align="left" valign="top" style="padding: 0px 3px;">{{ (!empty($labour['sac']) && $labour['sac'] > 0) ? $labour['sac'] : '-' }}</td>
                            @endif
                            <td align="left" valign="top" style="padding: 0px 3px;">{!! !empty($labour['remarks']) ? $labour['remarks'] : '-' !!}</td>
                            <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($labourAssMultipleGst) ? $detailsLabourGst : array_sum($labourAssNoneMultipleGst) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{!! (!empty($labour['est_lab']) && $labour['est_lab'] > 0) ? number_format_custom($labour['est_lab']) : '-' !!}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{!! (empty($labour['quantities']) && $labour['ass_lab'] > 0) ? number_format_custom($labour['ass_lab']) : '-' !!}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{!! (empty($labour['quantities']) && $labour['painting_lab'] > 0) ? number_format_custom($labour['painting_lab']) : '-' !!}</td>
                        </tr>
                    @endif
                    @if(!empty($labour['quantities']))
                        @php
                            $quantityLabourIndex = 0;
                        @endphp
                        @foreach($labour['quantities'] as $quantityLabour)
                            @if($quantityLabour['ass_lab'] > 0 || $quantityLabour['est_lab'] > 0 || $quantityLabour['painting_lab'] > 0)
                                @php
                                    $quantityLabourGst = !empty($quantityLabour['gst']) ? intval($quantityLabour['gst']) : 0;
                                @endphp
                                <tr>
                                    <td align="center" valign="top" style="padding: 0px 3px 0px 13px; font-style: italic; border-left: 1px solid #000;">{!! $labourIndexCounter.'.'.intval(++$quantityLabourIndex) !!}</td>
                                    <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;">{!! ($quantityLabour['imt_23'] == "Yes") ? '<strong>*</strong>' : ''!!} {{ !empty($quantityLabour['labour_type']) ? $quantityLabour['labour_type'].' of '.$quantityLabour['description'] : $quantityLabour['description'] }}</td>
                                    @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                                        <td align="left" valign="top" style="padding: 0px 3px;">{{ (!empty($quantityLabour['sac']) && $quantityLabour['sac'] > 0) ? $quantityLabour['sac'] : '-' }}</td>
                                    @endif
                                    <td align="left" valign="top" style="padding: 0px 3px;">{!! !empty($quantityLabour['remarks']) ? $quantityLabour['remarks'] : '-' !!}</td>
                                    <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($labourAssMultipleGst) ? $quantityLabourGst : array_sum($labourAssNoneMultipleGst) }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">-</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{!!  ($quantityLabour['ass_lab'] > 0) ? number_format_custom($quantityLabour['ass_lab']) : '-' !!}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{!! ($quantityLabour['painting_lab'] > 0) ? number_format_custom($quantityLabour['painting_lab']) : '-' !!}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if(!empty($labourUniqueGst))
                    @php
                        $gstLabourIndexWiseAmt = [];
                        //Sub Total Counting Variable Start
                        $SubTotalEstimatedLabourAmt = 0;
                        $SubTotalAssLabourAmt = 0;
                        $SubTotalPaintingAmt = 0;
                        $SubTotalPaintingAmtImt = 0;
                    @endphp
                    @foreach($labourUniqueGst as $gst)
                        @php
                            $totalEstimatedLabourAmt = 0;
                            $totalAssLabourAmt = 0;
                            $totalPaintingAmt = 0;
                            $totalPaintingAmtImt = 0;
                        @endphp
                        @foreach($alldetails as $labour)
                            @php
                                $labourGst = !empty($labour['gst']) ? $labour['gst'] : 0;
                                if(!empty($labourEstMultipleGst) && isset($labourEstMultipleGst[$gst]) && $labourGst==$gst) {
                                    $totalEstimatedLabourAmt += ($labour['est_lab'] > 0) ? $labour['est_lab'] : 0;
                                } elseif(!empty($labourEstNoneMultipleGst) && isset($labourEstNoneMultipleGst[$gst])){
                                    $totalEstimatedLabourAmt += ($labour['est_lab'] > 0) ? $labour['est_lab'] : 0;
                                }
                                if(empty($labour['quantities'])){
                                    if(!empty($labourAssMultipleGst) && isset($labourAssMultipleGst[$gst]) && $labourGst==$gst) {
                                        $totalAssLabourAmt += ($labour['ass_lab'] > 0) ? $labour['ass_lab'] : 0;
                                        if($labour['imt_23'] == "Yes") {
                                            $totalPaintingAmtImt += ($labour['painting_lab'] > 0) ? $labour['painting_lab'] : 0;
                                        } else {
                                            $totalPaintingAmt += ($labour['painting_lab'] > 0) ? $labour['painting_lab'] : 0;
                                        }
                                    } elseif(!empty($labourAssNoneMultipleGst) && isset($labourAssNoneMultipleGst[$gst])) {
                                        $totalAssLabourAmt += ($labour['ass_lab'] > 0) ? $labour['ass_lab'] : 0;
                                        if($labour['imt_23'] == "Yes") {
                                            $totalPaintingAmtImt += ($labour['painting_lab'] > 0) ? $labour['painting_lab'] : 0;
                                        } else {
                                            $totalPaintingAmt += ($labour['painting_lab'] > 0) ? $labour['painting_lab'] : 0;
                                        }
                                    }
                                }

                                if(!empty($labour['quantities'])) {
                                    foreach ($labour['quantities'] as $quantities) {
                                        $labourQuntityGst = !empty($quantities['gst']) ? $quantities['gst'] : 0;
                                        if(!empty($labourAssMultipleGst) && isset($labourAssMultipleGst[$gst]) && $labourQuntityGst==$gst) {
                                            $totalAssLabourAmt += ($quantities['ass_lab'] > 0) ? $quantities['ass_lab'] : 0;
                                            if($quantities['imt_23'] == "Yes") {
                                                $totalPaintingAmtImt += ($quantities['painting_lab'] > 0) ? $quantities['painting_lab'] : 0;
                                            } else {
                                                $totalPaintingAmt += ($quantities['painting_lab'] > 0) ? $quantities['painting_lab'] : 0;
                                            }
                                        } elseif(!empty($labourAssNoneMultipleGst) && isset($labourAssNoneMultipleGst[$gst])) {
                                            $totalAssLabourAmt += ($quantities['ass_lab'] > 0) ? $quantities['ass_lab'] : 0;
                                            if($quantities['imt_23'] == "Yes") {
                                                $totalPaintingAmtImt += ($quantities['painting_lab'] > 0) ? $quantities['painting_lab'] : 0;
                                            } else {
                                                $totalPaintingAmt += ($quantities['painting_lab'] > 0) ? $quantities['painting_lab'] : 0;
                                            }
                                        }
                                    }
                                }
                            @endphp
                        @endforeach
                        @php
                            $SubTotalEstimatedLabourAmt += $totalEstimatedLabourAmt;
                            $SubTotalAssLabourAmt += $totalAssLabourAmt;
                            $SubTotalPaintingAmt += $totalPaintingAmt;
                            $SubTotalPaintingAmtImt += $totalPaintingAmtImt;
                            $gstLabourIndexWiseAmt[$gst]['ess_lab_amt'] = $totalEstimatedLabourAmt;
                            $gstLabourIndexWiseAmt[$gst]['ass_lab_amt'] = $totalAssLabourAmt;
                            $gstLabourIndexWiseAmt[$gst]['ass_painting_amt'] = $totalPaintingAmt;
                            $gstLabourIndexWiseAmt[$gst]['ass_painting_imt_amt'] = $totalPaintingAmtImt;

                            $labour_category_tax_summery_details['labour'][$gst]['total'] = $totalAssLabourAmt;
                            $labour_category_tax_summery_details['painting_labour'][$gst]['total'] = $totalPaintingAmt;
                            $labour_category_tax_summery_details['imt_painting_labour'][$gst]['total'] = $totalPaintingAmtImt;
                            $cabin_tax_summary["labour"][$gst]=$totalEstimatedLabourAmt+$totalAssLabourAmt+$totalPaintingAmt + $totalPaintingAmtImt;
                        @endphp
                        <tr>
                            <td align="left" valign="top" colspan="{{ $labColSpan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with {!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} {{$gst}}%)</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstimatedLabourAmt) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalAssLabourAmt) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalPaintingAmt + $totalPaintingAmtImt) }}</td>
                        </tr>
                    @endforeach
                    @php
                        $imtNoteShowPainting = false;
                        $totalPaintingWithImtPaint = ($SubTotalPaintingAmt + $SubTotalPaintingAmtImt);
                        $less50DepPainting = 0;
                        $less50DepPaintingImt = 0;
                        if(empty($lossAssessment[0]['IsZeroDep']) && $lossAssessment[0]['IsZeroDep']==0) {
                            $less50DepPainting = ((($SubTotalPaintingAmt * 25) / 100) / 2);
                            $less50DepPaintingImt = ((($SubTotalPaintingAmtImt * 25) / 100) / 2);
                        }
                        $totalPaintingLess = ($less50DepPainting + $less50DepPaintingImt);
                        $totalLessPaintingWithImtPaint = ($totalPaintingWithImtPaint - $totalPaintingLess);
                    @endphp
                    <tr>
                        <td align="right" valign="top" colspan="{{ $labColSpan }}" style="padding: 0px 3px; font-weight: bold;  border-left: 1px solid #000;">Total</td>
                        <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($SubTotalEstimatedLabourAmt) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($SubTotalAssLabourAmt) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($totalPaintingWithImtPaint) }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" colspan="{{ $labColSpan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Less <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['IsZeroDep']==1) ? 0 : 50 }}%</span> on <span style="font-weight: bold;">25%</span> of Painting Material of <span style=" font-weight: bold;">Rs.{{ number_format_custom($totalPaintingWithImtPaint) }}</span></td>
                        <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalPaintingLess) }}</td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" colspan="{{ $labColSpan }}" style="padding: 0px 3px; font-weight: bold;  border-left: 1px solid #000;">Sub Total</td>
                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($SubTotalEstimatedLabourAmt) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($SubTotalAssLabourAmt) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($totalLessPaintingWithImtPaint) }}</td>
                    </tr>
                    @if($SubTotalPaintingAmtImt > 0)
                        @php
                            $totalIMTPaintingAmtAfterDep = ($SubTotalPaintingAmtImt - $less50DepPaintingImt);
                            $addLessPaintingOnImt = (($totalIMTPaintingAmtAfterDep * $lossAssessment[0]['IMT23DepPer']) / 100);
                        @endphp
                        <tr>
                            <td align="left" valign="top" colspan="{{ $labColSpan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Less Addl. Deduction <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['IMT23DepPer']) ? number_format_custom($lossAssessment[0]['IMT23DepPer'], 2) : '0' }}% </span> on IMT Painting of <span style=" font-weight: bold;">Rs.{{ number_format_custom($totalIMTPaintingAmtAfterDep)}}</span>
                            <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addLessPaintingOnImt)}}</td>
                        </tr>
                        <tr>
                            <td align="right" valign="top" colspan="{{ $labColSpan }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Sub Total</td>
                            <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($SubTotalEstimatedLabourAmt) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($SubTotalAssLabourAmt) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; border-top: 1px solid #000;">{{ number_format_custom($totalLessPaintingWithImtPaint - $addLessPaintingOnImt) }}</td>
                        </tr>
                    @endif

                    @php
                        $grandTotalEstimatedLabourAmt = 0;
                        $grandTotalAssLabourAmt = 0;
                        $grandTotalPaintingAmt = 0;
                    @endphp
                    @foreach($labourUniqueGst as $gst)
                        @php
                            $totalEstLabAmt = $gstLabourIndexWiseAmt[$gst]['ess_lab_amt'];
                            $totalAssLabAmt = $gstLabourIndexWiseAmt[$gst]['ass_lab_amt'];
                            $totalPaintingAmt = $gstLabourIndexWiseAmt[$gst]['ass_painting_amt'];
                            $totalPaintingImtAmt = $gstLabourIndexWiseAmt[$gst]['ass_painting_imt_amt'];

                            $totalPaintingLess = 0;
                            $totalPaintingImtLess = 0;
                            if(empty($lossAssessment[0]['IsZeroDep']) && $lossAssessment[0]['IsZeroDep']==0) {
                                $totalPaintingLess = ((($totalPaintingAmt * 25) / 100) / 2);
                                $totalPaintingImtLess = ((($totalPaintingImtAmt * 25) / 100) / 2);
                            }
                            $totalPaintingAmt -= $totalPaintingLess;
                            $totalPaintingImtAmt -= $totalPaintingImtLess;
                            $addLessOnImt = 0;
                            if($totalPaintingImtAmt > 0) {
                                if(empty($imtNoteShowPainting)) {
                                    $imtNoteShowPainting = true;
                                }
                                $addLessOnImt = (($totalPaintingImtAmt * $lossAssessment[0]['IMT23DepPer']) / 100);
                                $totalPaintingImtAmt -= $addLessOnImt;
                            }
                            $subTotalPaintingAmt = ($totalPaintingAmt + $totalPaintingImtAmt);

                            $addingEstLabGst = (($totalEstLabAmt * $gst) / 100);
                            $addingAssLabGst = (($totalAssLabAmt * $gst) / 100);
                            $addingPaintingGst = (($subTotalPaintingAmt * $gst) / 100);
                            $grandTotalEstimatedLabourAmt += ($totalEstLabAmt + $addingEstLabGst);
                            $grandTotalAssLabourAmt += ($totalAssLabAmt + $addingAssLabGst);
                            $grandTotalPaintingAmt += ($subTotalPaintingAmt + $addingPaintingGst);

                            $labour_category_tax_summery_details['labour'][$gst]['gst_amt'] = $addingAssLabGst;
                            $labour_category_tax_summery_details['painting_labour'][$gst]['less'] = $totalPaintingLess;
                            $labour_category_tax_summery_details['imt_painting_labour'][$gst]['less'] = $totalPaintingImtLess;
                            $labour_category_tax_summery_details['imt_painting_labour'][$gst]['add_imt_less'] = $addLessOnImt;
                        @endphp
                        <tr>
                            <td align="left" valign="top" colspan="{{ $labColSpan }}" style="padding: 0px 3px; border-left: 1px solid #000;">Add {!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} @ {{ $gst }}%</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addingEstLabGst) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addingAssLabGst) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addingPaintingGst) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td align="right" valign="top" colspan="{{ $labColSpan }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Total</td>
                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{ number_format_custom($grandTotalEstimatedLabourAmt) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{ number_format_custom($grandTotalAssLabourAmt) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{ number_format_custom($grandTotalPaintingAmt) }}</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" colspan="{{ $labColSpan }}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Net Labour Amount (O/F & D/B + Painting Labour)</td>
                        <td align="right" valign="top" colspan="3" style="padding: 0px 3px; font-weight: bold; border-top: 1px solid #000;">{{ number_format_custom($grandTotalAssLabourAmt + $grandTotalPaintingAmt) }}</td>
                    </tr>
                    @if(!empty($imtNoteShowPainting))
                        <tr>
                            <td align="left" valign="top" colspan="{{ intval($labColSpan + 3) }}" style="padding: 5px; font-weight: bold; font-size: 14px;">* The Star marks denotes against the IMT-21  Painting allowed.</td>
                        </tr>
                    @endif
                @endif
                </tbody>
            </table>

            @if(!empty($totalAssPartsCabin))
                <br>
                @include('preview-reports.final-report.cabin-body-assessment')
            @endif

            <br>
            @include('preview-reports.final-report.summary-assessment-loss')

            @if(isset($lossAssessment[0]['display_gst_summary']) && $lossAssessment[0]['display_gst_summary'] == 1)
                @include('preview-reports.final-report.part_tax_summary')
            @endif

            @if(isset($lossAssessment[0]['display_gst_summary_part_category_wise']) && $lossAssessment[0]['display_gst_summary_part_category_wise'] == 1)
                @include('preview-reports.final-report.category_part_tax_summary')
            @endif

            @include('preview-reports.final-report.labour_tax_summary')
            @php
            $cabinRecords = count($cabinDetailsCalculation)>0 ?Collect($cabinDetailsCalculation[1]['parts_total']['assessed'])->some(function ($item) {return $item > 0;}):false;
            @endphp
            @if ($cabinRecords)
                @include("preview-reports.final-report.cabin-body-tax-summary")
            @endif
            <div style="margin-top: 1rem;">
                <div style="border-top: 0px solid #000;"></div>
            </div>

            <table width="100%" align="center" style="border: none !important;">
                <tbody>
                    <tr>
                        <td align="left" valign="top" style="padding-top: 10px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px; font-weight: bold; border: none !important;">Net Liability</td>
                    </tr>
                    @php
                        $netLibility = (isset($lossAssessment[0]['alltotalass']) && $lossAssessment[0]['alltotalass'] > 0) ? number_format_custom(round($lossAssessment[0]['alltotalass'])) : '0.00';
                        $totalAmountWords = convertNumberToWords($netLibility);
                    @endphp
                    <tr>
                        <td align="left" valign="top" style="padding-top: 3px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px; border: none !important;">Based On Details Provided Above, The Justified Liability Under The Subject Policy Of Insurance Works Out To ₹{{ $netLibility }} <span style="font-weight: bold;">(Rupees {{$totalAmountWords}} Only)</span></td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="padding-top: 3px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px;  border: none !important;">{!! !empty($lossAssessment[0]['comment']) ? $lossAssessment[0]['comment'] : '' !!}</td>
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