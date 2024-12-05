<html lang="en">
<head>
    <meta charset="utf-8"/>
    <!-- CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>ILA Report</title>
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
    <!-- JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        @font-face {
            font-family: verdana !important;
            font-weight: normal;
            font-style: normal;
            font-variant: normal;
            src: asset("public/fonts/verdana.ttf") format('truetype');
        }
        body {
            font-family: 'verdana';
        }
        .col1 {
            width: 90px;
        }

        td > tr > .col2 {
            width: 20px !important;
        }

        td {
            width: 58%;
            border: 3px solid black;
            font-size: 35px !important;
            padding: 10px;
            color: rgb(0, 0, 0);
            font-family:verdana !important;
        }

        tr {
            height: 10px;
            border: 3px solid black;
            font-size: 35px !important;
            color: rgb(0, 0, 0);
        }
    </style>
</head>
<body>
<div class="" style="padding:0px;margin:0px">
    <div class="container" style="padding:0px;margin:0px">
        <div class="row">
            <div class="col-sm-12">
                <div class="card" style="padding:0px;margin:0px">
                    <div class="card-body" style="padding:0px;margin:0px">
                        @if($type =='ilarwl')
                            <div style="width: 100%;">
                                <img style="width:230px" src="{{public_path()}}/test.png"/>
                            </div>
                        @endif
                        @if($type =='ilarwol')
                            <div style="border-bottom: 3px solid #000; text-align:center;">
                                <div style="width: 100%;">
                                    @if ($letter_head_img)
                                        <img src="{{ asset('public/storage/'.$letter_head_img) }}" style="height:auto;">
                                    @else
                                        <p>No letter head image available</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="row" style="widht:100%">
                            <div style="width:50%; float:left; padding-top:15px;"><span style="font-weight:bold">Ref No. : </span>{{$Reference_No}}</div>
                            <div style="width:50%;padding-top:15px;float:left; text-align:right;"><span style="font-weight:bold">Date : </span>{{ !empty($ILA_Submitted_on) ? date("d-m-Y", strtotime($ILA_Submitted_on)) : '--/--/----' }}</div>
                        </div>

                        <h6 class="text-center fw-bold" style="font-weight: bold;">INTIAL LOSS ASSESSMENT (ILA) REPORT</h6>
                        <table class="table" style="width:100%; margin-left:-25px; margin-right:-15px">
                            <tbody>
                            <tr>
                                <td class="col1"></td>
                                <td colspan="2">Name & Contact of the Insured</td>
                                <td colspan="2">{{$insured_name}} {{ !empty($insured_mobile_no) ? ' & '.$insured_mobile_no : '' }}</td>
                            </tr>
                            <tr>
                                <td class="col1"></td>
                                <td colspan="2" class="col2">Claim No.</td>
                                <td colspan="2">{{$claim_no}}</td>
                            </tr>
                            <tr>
                                <td class="col1"></td>
                                <td colspan="2" class="col2">Policy No, Policy Period</td>
                                <td colspan="2">
                                    <div>{{ $policy_no }} {!! (!empty($policy_no) && !empty($policy_valid_from)) ? ', ' : '' !!}{{ formatdate($policy_valid_from) }}{!! (!empty($policy_valid_to) && !empty($policy_valid_from)) ? ' to ' : '' !!}{{ formatdate($policy_valid_to )}}</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="col1"></td>
                                <td colspan="2">Date of Accident</td>
                                <td colspan="2"> {{formatdate($date_time_accident)}}</td>
                            </tr>
                            <tr>
                                <td class="col1"></td>
                                <td colspan="2">Vehicle Registration No.</td>
                                <td colspan="2">{{$vehicle_reg_no}}</td>
                            </tr>
                            <tr>
                                <td class="col1"></td>
                                <td>Chassis No.</td>
                                <td style="">Engine No.</td>
                                <td colspan="1" style="width:90%;">{{$chassis_no}}</td>
                                <td colspan="1">{{$engine_no}}</td>

                            </tr>
                            <tr>
                                <td class="col1"></td>
                                <td colspan="2">(as per physical verfication )</td>
                                <td colspan="1" style="width:90%;">{{$Chassis_No_PV}}</td>
                                <td colspan="1">{{$Engine_No_PV}}</td>
                            </tr>
                            <tr>
                                <td class="col1"></td>
                                <td colspan="1">Date of appointment</td>
                                <td colspan="1">Date & Time of first visit for inspection</td>
                                <td style="width:90%;">{{formatdate($date_of_appointment)}}</td>
                                <td>{{formatdate($Survey_Date_time,'t')}}</td>
                            </tr>
                            <tr>
                                <td class="col1"></td>
                                <td colspan="2">Place of inspection <br>( if different from the repairers workshop )</td>
                                <td colspan="2">{{$place_survey}}</td>
                            </tr>
                            @php
                                $Briefdescriptionofaccident = !empty($accident_brief_description) ? $accident_brief_description : '';
                                if(!empty($accident_brief_description) && !empty($damage_corroborates_with_cause_of_loss)) {
                                    $Briefdescriptionofaccident .= '<br>';
                                }
                                $Briefdescriptionofaccident .= !empty($damage_corroborates_with_cause_of_loss) ? $damage_corroborates_with_cause_of_loss : '';
                            @endphp

                            @if(!empty($Briefdescriptionofaccident))
                                <tr>
                                    <td class="col1"></td>
                                    <td colspan="2">Brief description of accident</td>
                                    <td rowspan="2" colspan="2">@php echo $Briefdescriptionofaccident; @endphp</td>
                                </tr>
                                <tr>
                                    <td class="col1"></td>
                                    <td colspan="2">Whether the nature of damage corroborates <br>with the cause of loss</td>
                                </tr>
                            @endif

                            @if(!empty($accompanied_insurer_officer_details))
                                <tr>
                                    <td class="col1"></td>
                                    <td colspan="2">Whether accompanied by{{ ($type =='ilarwl') ? ' NIC ' : ' ' }}Officer.<br> if yes Name, & Designation of the Office</td>
                                    <td colspan="2">@php echo $accompanied_insurer_officer_details @endphp</td>
                                </tr>
                            @endif

                            <tr>
                                <td class="col1"></td>
                                <td colspan="2">Loss Estimate</td>
                                <td colspan="2">₹{!! ($loss_estimate != '') ? number_format_custom(str_replace(',', '', $loss_estimate)) : '0.00' !!}</td>
                            </tr>
                            <tr>
                                <td class="col1"></td>
                                <td colspan="2">Major damage physically noticed</td>
                                <td colspan="2">@php echo !empty($major_physical_damages) ? $major_physical_damages : '' @endphp</td>
                            </tr>
                            <tr>
                                <td class="col1"></td>
                                <td colspan="2">Suspected Internal damage</td>
                                <td colspan="2">@php echo !empty($suspected_Internal_damages) ? $suspected_Internal_damages : '' @endphp</td>
                            </tr>
                            @if(!empty($spot_Survey_details))
                                <tr>
                                    <td class="col1"></td>
                                    <td colspan="2">Whether Spot Survey report/ photographs received,<br> if yes, any further damage observed subsequently after <br> shifting the vehicle from the spot</td>
                                    <td colspan="2">@php echo $spot_Survey_details @endphp</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="col1"></td>
                                <td colspan="2">Any pre-existing/old damage</td>
                                <td colspan="2">@php echo !empty($preexisting_old_damages) ? $preexisting_old_damages : '' @endphp</td>
                            </tr>
                            <tr>
                                <td class="col1"></td>
                                <td colspan="2">Perferred Mode of Assessment (Repair/CTL)</td>
                                <td colspan="2">@php echo !empty($preferred_mode_of_assessment) ? $preferred_mode_of_assessment : '' @endphp</td>
                            </tr>
                            <tr>
                                <td class="col1"></td>
                                <td colspan="2">Insurers approx. liability</td>
                                <td colspan="2">₹{{ ($insurer_liability != '') ? number_format_custom(str_replace(',', '', $insurer_liability)) : '0.00' }}</td>
                            </tr>
                            @if(!empty($Vehicular_document_observation))
                                <tr>
                                    <td class="col1"></td>
                                    <td colspan="2">Other Information relevent for assessement of <br> loss & discrepaincies observed in vehicular document <br> (if any)</td>
                                    <td colspan="2">@php echo $Vehicular_document_observation @endphp</td>
                                </tr>
                            @endif

                            @if(!empty($surveyor_APP_token_number))
                                <tr>
                                    <td class="col1"></td>
                                    <td colspan="2">Surveyor APP Token Number/s</td>
                                    <td colspan="2">@php echo $surveyor_APP_token_number @endphp</td>
                                </tr>
                            @endif

                            @if(!empty($ILA_discussed_with))
                                <tr>
                                    <td class="col1"></td>
                                    <td colspan="2">Name of the{{ ($type =='ilarwl') ? ' NIC ' : ' ' }}officer with whom ILA discussed</td>
                                    <td colspan="2">@php echo $ILA_discussed_with @endphp</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="5">
                                    &emsp;1.&emsp; ATTACH A FEW PHOTOGRAPHS CLEARLY SHOWING THE DAMAGES<br/>
                                    &emsp;2.&emsp; In case CTL, submit preliminary report within 7 days with at-least
                                    2/3 online/offline wreck quotation.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="row" style="float:left; padding-left: 8px;">
                            @if (!empty($signature_img))
                                <div style="margin-top:50px; margin-bottom:10px;">
                                    <img src="{{ asset('public/storage/'.$signature_img) }}" style="width:100px;">
                                </div>
                            @else
                                <p>
                                    <br/>
                                    <br/>
                                    <br/>
                                </p>
                            @endif
                            <p style="font-weight: bold; margin: 0 0 2px !important;">{!! !empty($adminHeaderFooter->name) ? $adminHeaderFooter->name : '-' !!}</p>
                            <p>{!! !empty($adminHeaderFooter->designation) ? $adminHeaderFooter->designation : '-' !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
