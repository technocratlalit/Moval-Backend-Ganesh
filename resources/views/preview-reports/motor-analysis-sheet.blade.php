@php
    $select_value = ['Y' => 'Yes', 'N' => 'No'];
@endphp
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Motor Analysis Sheet</title>
        <style>
            @font-face {
                font-family: 'verdana';
                font-weight: normal;
                font-style: normal;
                font-variant: normal;
                src: url('public/fonts/verdana.ttf');
            }

            body {
                font-family: 'verdana';
            }

            table {
                border-collapse: separate;
                border-spacing: 0;
                font-size: 12px !important;
            }

            @page{
                margin-header: 5mm;
            }

            #motor  #motor-table table{
                width: 100%;
                border: 1px solid #000000;
                padding: 5px;
            }
            #motor  #motor-table tbody{
                border: 1px solid #000000;
                padding: 5px;
            }
            #motor  #motor-table tr{
                border: 1px solid #000000;
                padding: 5px;
            }
            #motor  #motor-table td{
                border: 1px solid #000000;
                padding: 5px;
            }
            #motor  #motor-table th{
                border: 1px solid #000000;
                padding: 5px;
            }
        </style>
    </head>

    <body>
{{--        <div style="border-bottom: 3px solid #000; text-align:center;">--}}
{{--            <div style="width: 100%;">--}}
{{--                @if ($letter_head_img)--}}
{{--                    <img src="{{ asset('public/storage/'.$letter_head_img) }}" style="height:auto;">--}}
{{--                @else--}}
{{--                    <p>No letter head image available</p>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}
        <div style="width: 100%; font-family: 'Verdana' !important;">
            <div style="text-align: center; width: 100%;">
                @if(isset($policyDetails->get_appointing_office->office_name))
                    <div style="text-align: center;font-weight: bold; font-family: 'Verdana' !important;">{{ $policyDetails->get_appointing_office->office_name }}</div>
                @endif
                @if(isset($policyDetails->get_appointing_office->office_address))
                    <div style="text-align: center; font-family: 'Verdana' !important; ">{{ $policyDetails->get_appointing_office->office_address }}</div>
                @endif
                <div style="text-align: center; font-family: 'Verdana' !important; margin-bottom: 12px;"><u>MOTOR ANALYSIS SHEET</u></div>
                <table style="font-size: 10px; width: 100%; margin-top: 10px;">
                    <tbody>
                        <tr>
                            <td align="left" width="50%">Claim No. : {{ !empty($policyDetails->claim_no) ? $policyDetails->claim_no : '' }}</td>
                            <td align="right" width="50%">Policy No. : {{ !empty($policyDetails->policy_no) ? $policyDetails->policy_no : '' }}</td>
                        </tr>
                        <tr>
                            <td align="left">Insured as per policy : {{ !empty($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</td>
                            <td align="right">Financer : {{ !empty($policyDetails->HPA) ? $policyDetails->HPA : '' }}</td>
                        </tr>
                        <tr>
                            <td align="left">Name of Registered owner : {{ !empty($policyDetails->registured_owner) ? $policyDetails->registured_owner : '' }}</td>
                            <td align="right"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="width: 100%; font-family: 'Verdana' !important; font-size: 10px; margin-top: 10px;" id="motor">
            <table id="motor-table" style="font-size: 10px; width: 100%; border-collapse: collapse;">
                <tbody>
                    <tr style="vertical-align: middle; padding-left: 2px;">
                        <th align="center" width="35%" colspan="2">Enquiry</th>
                        <th align="center" width="50%" colspan="2">Particulars</th>
                        <td align="center" width="15%" style="border-left: 0px;">If both column justify ( ) claim otherwise ( )</td>
                    </tr>
                    <tr style="vertical-align: middle; border-bottom: 0px;">
                        <td align="left" rowspan="2">1. Date of loss as per intimation letter</td>
                        <td align="left" width="14%" rowspan="2">{{ !empty($policyDetails->date_time_accident) ? date('d/m/Y', strtotime($policyDetails->date_time_accident)) : '' }}</td>
                        <td align="left" colspan="2" style="border-bottom: 0px;">Policy periods from {{ !empty($policyDetails->policy_valid_from) ? date('d/m/Y', strtotime($policyDetails->policy_valid_from)) : '' }} To {{ !empty($policyDetails->policy_valid_to) ? date('d/m/Y', strtotime($policyDetails->policy_valid_to)) : '' }}</td>
                        <td align="center" valign="middle" style="border-left: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" colspan="2" style="border-top: 0px; border-bottom: 0px;">Intimation letter</td>
                        <td align="center" valign="middle" style="border-left: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-bottom: 0px;">
                        <td align="left" colspan="2" rowspan="2"></td>
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Date of Spot Survey</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->date_of_spot_survey) ? date('d/m/Y', strtotime($policyDetails->date_of_spot_survey)) : '' }}</td>
                        <td align="center" valign="middle" style="border-left: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Date of Final Survey</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->Survey_Date_time) ? date('d/m/Y', strtotime($policyDetails->Survey_Date_time)) : '' }}</td>
                        <td align="center" valign="middle" style="border-left: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-bottom: 0px;">
                        <td align="left" rowspan="2">2. Date of loss as per Claim form</td>
                        <td align="left" rowspan="2">{{ !empty($policyDetails->date_time_accident) ? date('d/m/Y', strtotime($policyDetails->date_time_accident)) : '' }}</td>
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Fitness valid up to</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->fitness_valid_to) ? date('d/m/Y', strtotime($policyDetails->fitness_valid_to)) : '' }}</td>
                        <td align="center" style="border-left: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Permit valid upto</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->permit_valid_to) ? date('d/m/Y', strtotime($policyDetails->permit_valid_to)) : '' }}</td>
                        <td align="center" style="border-left: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-bottom: 0px;">
                        <td align="left" rowspan="3">3. Date of loss as per Survey report</td>
                        <td align="left" rowspan="3">{{ !empty($policyDetails->date_time_accident) ? date('d/m/Y', strtotime($policyDetails->date_time_accident)) : '' }}</td>
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Driving license valid up to</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->dl_valid_upto) ? date('d/m/Y', strtotime($policyDetails->dl_valid_upto)) : '' }}</td>
                        <td align="center" style="border-left: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    @php
                        $roadTaxPaidUpto = !empty($policyDetails->tax_valid_from_text) ? $policyDetails->tax_valid_from_text : '';
                        if(empty($policyDetails->tax_valid_from_text) && !empty($policyDetails->tax_paid_to)) {
                            $roadTaxPaidUpto = date('d/m/Y', strtotime($policyDetails->tax_paid_to));
                        }
                    @endphp
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Road Tax Paid up to</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ $roadTaxPaidUpto }}</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Passenger /goods Tax paid</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->tax_paid) ? $policyDetails->tax_paid : '' }}</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-bottom: 0px;">
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">4. Nature of policy Comp / TP</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->nature_of_policy) ? $policyDetails->nature_of_policy : '' }}</td>
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Nature of loss OD / TP</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->nature_of_loss) ? $policyDetails->nature_of_loss : '' }}</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-bottom: 0px;">
                        <td align="left" style="border-top: 0px; border-bottom: 1px;">5. Ends No If any</td>
                        <td align="left" style="border-top: 0px; border-bottom: 1px;">&nbsp;</td>
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Nature of Endorsement</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->endorsement) ? $policyDetails->endorsement : '' }}</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-bottom: 0px;">
                        <td align="left" colspan="2" style="border-top: 0px; border-bottom: 0px;">6. Vehicle Regd. As</td>
                        <td align="left" colspan="2" style="border-top: 0px; border-bottom: 1px;">Premium Charged for</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" colspan="2" style="border-top: 0px; border-bottom: 1px;">{{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}</td>
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 1px;">DL issued for MC/LMV/HMV/Tr.</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 1px;">{{ !empty($policyDetails->vehicle_allowed_to_drive) ? $policyDetails->vehicle_allowed_to_drive : '' }}</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" colspan="2" rowspan="4" style="border-top: 0px; border-bottom: 1px;"></td>
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Invalid Carriage</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->invalid_carriagee) ? $policyDetails->invalid_carriagee : '' }}</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Endt for Transport /Goods /PSV</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->endt_for) ? $policyDetails->endt_for : '' }}</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">TransEndt not (For Comm.vehicle)</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->trans_endt) ? $policyDetails->trans_endt : '' }}</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Badge no.(Passenger vehicle only)</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->badge_no) ? $policyDetails->badge_no : '' }}</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" style="border-right: 0px; border-bottom: 1px; padding-top: 0px; padding-bottom: 0px;">7. Make & Regd No. as per Policy</td>
                        <td align="left" style="border-left: 0px; border-bottom: 1px; padding-top: 0px; padding-bottom: 0px;">{{ !empty($policyDetails->vehicle_make) ? $policyDetails->vehicle_make : '' }}</td>
                        <td align="left" rowspan="2" style="border-top: 0px; border-right: 0px; border-bottom: 0px; padding-top: 0px; padding-bottom: 0px;">Make & Regd No. reported by surveyor</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->vehicle_make) ? $policyDetails->vehicle_make : '' }}</td>
                        <td align="center" rowspan="2" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" style="border-right: 0px; border-bottom: 1px;"></td>
                        <td align="left" style="border-left: 0px; border-right: 0px; border-bottom: 0px; padding-top: 0px; padding-bottom: 0px;">{{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}</td>
                        <td align="left" style="border-left: 0px; border-bottom: 1px; padding-bottom: 0px; padding-top: 0px;">{{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" style="border-right: 0px; border-bottom: 1px;">Engine No.</td>
                        <td align="left" style="border-left: 0px; border-bottom: 1px;">{{ !empty($policyDetails->vehicle_engine_no) ? $policyDetails->vehicle_engine_no : '' }}</td>
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Engine No.</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->vehicle_engine_no) ? $policyDetails->vehicle_engine_no : '' }}</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" valign="top" style="border-right: 0px; border-bottom: 1px;">Chassis No.</td>
                        <td align="left" valign="top" style="border-left: 0px; border-bottom: 1px;">{{ !empty($policyDetails->vehicle_chassis_no) ? $policyDetails->vehicle_chassis_no : '' }}</td>
                        <td align="left" valign="top" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Chassis No.</td>
                        <td align="left" valign="top" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->vehicle_chassis_no) ? $policyDetails->vehicle_chassis_no : '' }}</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-bottom: 0px;">
                        <td align="left" colspan="2" valign="top" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">8. Name of insured as per policy</td>
                        <td align="left" colspan="2" valign="top" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">Name of insured as per surveyor</td>
                        <td align="center" rowspan="2" style="border-top: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-bottom: 0px; border-top: 0px;">
                        <td align="left" colspan="2" style="border-top: 0px; border-bottom: 0px;">{{ !empty($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</td>
                        <td align="left" colspan="2" style="border-top: 0px; border-bottom: 0px;">{{ !empty($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</td>
                    </tr>
                    <tr style="vertical-align: middle; border-bottom: 0px;">
                        <td align="left" style="border-top: 0px; border-bottom: 0px; border-right: 0px;">9. Carrying capacity as per policy</td>
                        <td align="left" style="border-top: 0px; border-bottom: 0px; border-left: 0px;">{{ !empty($policyDetails->carrying_capacity_as_policy) ? $policyDetails->carrying_capacity_as_policy : '' }}</td>
                        <td align="left" style="border-top: 0px; border-bottom: 0px; border-right: 0px;">Carrying capacity as per RC Book</td>
                        <td align="left" style="border-top: 0px; border-bottom: 0px; border-left: 0px;">{{ !empty($policyDetails->carrying_capacity_as_rc) ? $policyDetails->carrying_capacity_as_rc : '' }}</td>
                        <td align="center" style="border-top: 0px; border-bottom: 0px;"></td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" style="border-top: 0px; border-bottom: 0px; border-right: 0px;">GVW</td>
                        <td align="left" style="border-top: 0px; border-bottom: 0px; border-left: 0px;"></td>
                        <td align="left" style="border-top: 0px; border-bottom: 0px; border-right: 0px;">GVW</td>
                        <td align="left" style="border-top: 0px; border-bottom: 0px; border-left: 0px;"></td>
                        <td align="center" style="border-top: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" valign="top" rowspan="2" style="border-top: 0px; border-bottom: 0px; border-right: 0px;">No. of person</td>
                        <td align="left" valign="top" rowspan="2" style="border-top: 0px; border-bottom: 0px; border-left: 0px;">{{ !empty($policyDetails->no_of_persons) ? $policyDetails->no_of_persons : '' }}</td>
                        <td align="left" style="border-top: 0px; border-right: 0px; border-bottom: 0px;">No. of Seats</td>
                        <td align="left" style="border-left: 0px;">{{ !empty($policyDetails->seating_capacity) ? $policyDetails->seating_capacity : '' }}</td>
                        <td align="center" style="border-top: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px; border-bottom: 0px;">
                        <td align="left" style="border-top: 0px; border-bottom: 0px; border-right: 0px;">Loss /Pass,carried at the time of accident</td>
                        <td align="left" style="border-top: 0px; border-bottom: 0px; border-left: 0px;">{{ !empty($policyDetails->load_passenger) ? $policyDetails->load_passenger : '' }}</td>
                        <td align="center" style="border-top: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-bottom: 0px;">
                        <td align="left" style="border-bottom: 1px;">10. Route of permit</td>
                        <td align="left" style="border-bottom: 1px;">{{ !empty($policyDetails->route) ? $policyDetails->route : '' }}</td>
                        <td align="left" style="border-right: 0px; border-bottom: 1px;">Place of accident</td>
                        <td align="left" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">{{ !empty($policyDetails->place_accident) ? $policyDetails->place_accident : '' }}</td>
                        <td align="center" style="border-top: 0px; border-left: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle;">
                        <td align="left" valign="top" rowspan="3" colspan="2" style="border-right: 0px; border-bottom: 1px;">11. Driver name as per claim form</td>
                        <td align="left" style="border-right: 0px; border-bottom: 1px; border-right: 0px;">1. Spot Report</td>
                        <td align="left" style="border-right: 0px; border-bottom: 1px; border-left: 0px;">{{ !empty($policyDetails->driver_name_as_spot) ? $policyDetails->driver_name_as_spot : '' }}</td>
                        <td align="center" style="border-top: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px;">
                        <td align="left" style="border-right: 0px; border-bottom: 1px; border-right: 0px;">2. Final Report</td>
                        <td align="left" style="border-right: 0px; border-bottom: 1px; border-left: 0px;">{{ !empty($policyDetails->driver_name) ? $policyDetails->driver_name : '' }}</td>
                        <td align="center" style="border-top: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle;">
                        <td align="left" style="border-right: 0px; border-bottom: 1px; border-right: 0px;">3. FIR</td>
                        <td align="left" style="border-right: 0px; border-bottom: 1px; border-left: 0px;">{{ !empty($policyDetails->driver_name_as_fir) ? $policyDetails->driver_name_as_fir : '' }}</td>
                        <td align="center" style="border-top: 0px; border-bottom: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-bottom: 0px;">
                        <td align="left" valign="top" colspan="2" style="border-right: 0px;">12. Whether TP loss reported</td>
                        <td align="left" style="border-right: 0px;">FIR lodged</td>
                        <td align="left" style="border-right: 0px;">{{ !empty($policyDetails->fir_lodged) ? $policyDetails->fir_lodged : '' }}</td>
                        <td align="center" style="border-top: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                    <tr style="vertical-align: middle; border-top: 0px;">
                        <td align="left" colspan="2" style="border-right: 0px;">{{ !empty($policyDetails->tp_loss_reported) ? $policyDetails->tp_loss_reported : '' }}</td>
                        <td align="left" style="border-right: 0px; border-top: 0px;">FIR received</td>
                        <td align="left" style="border-right: 0px; border-top: 0px;">{{ !empty($policyDetails->fir_received) ? $policyDetails->fir_received : '' }}</td>
                        <td align="center" style="border-top: 0px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @php
            $netLabour = (!empty($lossAssessment['netlabourAss']) && $lossAssessment['netlabourAss'] > 0) ? $lossAssessment['netlabourAss'] : 0;
            if(!empty($lossAssessment['TowingCharges']) && $lossAssessment['TowingCharges'] > 0) {
                $netLabour += $lossAssessment['TowingCharges'];
            }
            if(!empty($lossAssessment['additional_towing']) && $lossAssessment['additional_towing'] > 0) {
                $netLabour += $lossAssessment['additional_towing'];
            }

            $totalMetals = (!empty($lossAssessment['totalMetalAmt']) && $lossAssessment['totalMetalAmt'] > 0) ? $lossAssessment['totalMetalAmt'] : 0;
            if(!empty($lossAssessment['DepAmtMetal']) && $lossAssessment['DepAmtMetal'] > 0) {
                $totalMetals -= $lossAssessment['DepAmtMetal'];
            }
            $metalAmtAfterDep = $totalMetals;
            if(!empty($lossAssessment['gstAmtMetal']) && $lossAssessment['gstAmtMetal'] > 0) {
                $totalMetals += $lossAssessment['gstAmtMetal'];
            }
            $metalAmtAfterGST = $totalMetals;

            $totalFiber = (!empty($lossAssessment['totalFiber']) && $lossAssessment['totalFiber'] > 0) ? $lossAssessment['totalFiber'] : 0;
            if(!empty($lossAssessment['depFiber']) && $lossAssessment['depFiber'] > 0) {
                $totalFiber -= $lossAssessment['depFiber'];
            }
            $fiberAmtAfterDep = $totalFiber;
            $fiberGst = $lossAssessment['partFibreAssamount'] - $fiberAmtAfterDep;
            $fiberAmtAfterGST = $lossAssessment['partFibreAssamount'];

            $totalRubPlast = (!empty($lossAssessment['totalRubberNonIMT']) && $lossAssessment['totalRubberNonIMT'] > 0) ? $lossAssessment['totalRubberNonIMT'] : 0;
            if(!empty($lossAssessment['depRubberNonIMT']) && $lossAssessment['depRubberNonIMT'] > 0) {
                $totalRubPlast -= $lossAssessment['depRubberNonIMT'];
            }
            $rubPlastAmtAfterDep = $totalRubPlast;
            if(!empty($lossAssessment['gstAmtRubber']) && $lossAssessment['gstAmtRubber'] > 0) {
                $totalRubPlast += $lossAssessment['gstAmtRubber'];
            }
            $rubPlastAmtAfterGST = $totalRubPlast;

            $totalGlass = (!empty($lossAssessment['totalGlass']) && $lossAssessment['totalGlass'] > 0) ? $lossAssessment['totalGlass'] : 0;
            if(!empty($lossAssessment['depGlass']) && $lossAssessment['depGlass'] > 0) {
                $totalGlass -= $lossAssessment['depGlass'];
            }
            $glassAmtAfterDep = $totalGlass;
            if(!empty($lossAssessment['gstAmtGlass']) && $lossAssessment['gstAmtGlass'] > 0) {
                $totalGlass += $lossAssessment['gstAmtGlass'];
            }
            $glassAmtAfterGST = $totalGlass;

            $totalReconditionAmt = ($lossAssessment['totalReconditionAmt'] > 0) ? $lossAssessment['totalReconditionAmt'] : 0;
            $recGstAmt = ($totalReconditionAmt > 0 && $lossAssessment['totalreconditionAss'] > 0) ? ($lossAssessment['totalreconditionAss'] - $totalReconditionAmt) : 0;
            $totalreconditionAss = ($lossAssessment['totalreconditionAss'] > 0) ? $lossAssessment['totalreconditionAss'] : 0;

            $total_1 = ($metalAmtAfterGST + $rubPlastAmtAfterGST + $glassAmtAfterGST + $totalreconditionAss + $fiberAmtAfterGST);
            if($lossAssessment['totalendoresmentAss'] > 0) {
                $total_1 += $lossAssessment['totalendoresmentAss'];
            }
            $total_2 = ($total_1 + $netLabour);
            $total_3 = $total_2;
            if($lossAssessment['ImposedClause'] > 0){
                $total_3 = ($total_3 - $lossAssessment['ImposedClause']);
            }
            if($lossAssessment['CompulsoryDeductable'] > 0){
                $total_3 = ($total_3 - $lossAssessment['CompulsoryDeductable']);
            }
            if($lossAssessment['less_voluntary_excess'] > 0){
                $total_3 = ($total_3 - $lossAssessment['less_voluntary_excess']);
            }
            if($lossAssessment['SalvageAmt'] > 0){
                $total_3 = ($total_3 - $lossAssessment['SalvageAmt']);
            }
        @endphp
        <br>
        <div style="width: 100%; font-family: 'Verdana' !important; font-size: 12px; margin-top: 10px;">
            <div style="text-align: center; width: 100%;">
                <table style="font-size: 12px; width: 100%;" cellpadding="3px">
                    <tbody>
                        <tr>
                            <td colspan="2" align="left" width="50%">13. COMPUTATION OF LIABILITY</td>
                            <td colspan="2" align="left" width="50%">(Worked out after verifying bills reports etc.)</td>
                        </tr>
                        <tr>
                            <td align="left" width="6%"></td>
                            <td align="left" width="44%">1. Labour</td>
                            <td align="right" width="20%" style="font-weight: bold;">{{ number_format_custom($netLabour) }}</td>
                            <td align="left" width="30%"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">(Including Towing)</td>
                            <td align="right"></td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left">a. Metal Parts</td>
                            <td align="right">{{ ($lossAssessment['totalMetalAmt'] > 0) ? number_format_custom($lossAssessment['totalMetalAmt']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Less Dep.</td>
                            <td align="right">{{ ($lossAssessment['DepAmtMetal'] > 0) ? number_format_custom($lossAssessment['DepAmtMetal']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Amt. After Dep</td>
                            <td align="right">{{ number_format_custom($metalAmtAfterDep) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">GST</td>
                            <td align="right">{{ ($lossAssessment['gstAmtMetal'] > 0) ? number_format_custom($lossAssessment['gstAmtMetal']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Net</td>
                            <td align="right" style="font-weight: bold;">{{ number_format_custom($metalAmtAfterGST) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left">b. Rubber Parts</td>
                            <td align="right">{{ ($lossAssessment['totalRubberNonIMT'] > 0) ? number_format_custom($lossAssessment['totalRubberNonIMT']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Less Dep.</td>
                            <td align="right">{{ ($lossAssessment['depRubberNonIMT'] > 0) ? number_format_custom($lossAssessment['depRubberNonIMT']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Amt. After Dep</td>
                            <td align="right">{{ number_format_custom($rubPlastAmtAfterDep) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">GST</td>
                            <td align="right">{{ ($lossAssessment['gstAmtRubber'] > 0) ? number_format_custom($lossAssessment['gstAmtRubber']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Net</td>
                            <td align="right" style="font-weight: bold;">{{ number_format_custom($rubPlastAmtAfterGST) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left">c. Glass Parts</td>
                            <td align="right">{{ ($lossAssessment['totalGlass'] > 0) ? number_format_custom($lossAssessment['totalGlass']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Less Dep.</td>
                            <td align="right">{{ ($lossAssessment['depGlass'] > 0) ? number_format_custom($lossAssessment['depGlass']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Amt. After Dep</td>
                            <td align="right">{{ number_format_custom($glassAmtAfterDep) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">GST</td>
                            <td align="right">{{ ($lossAssessment['gstAmtGlass'] > 0) ? number_format_custom($lossAssessment['gstAmtGlass']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Net</td>
                            <td align="right" style="font-weight: bold;">{{ number_format_custom($glassAmtAfterGST) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left">d. Fiber Parts</td>
                            <td align="right">{{ ($lossAssessment['totalFiber'] > 0) ? number_format_custom($lossAssessment['totalFiber']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Less Dep.</td>
                            <td align="right">{{ ($lossAssessment['depFiber'] > 0) ? number_format_custom($lossAssessment['depFiber']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Amt. After Dep</td>
                            <td align="right">{{ number_format_custom($fiberAmtAfterDep) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">GST</td>
                            <td align="right">{{ number_format_custom($fiberGst) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Net</td>
                            <td align="right" style="font-weight: bold;">{{ number_format_custom($fiberAmtAfterGST) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left">e. Recondition Parts</td>
                            <td align="right">{{ ($lossAssessment['totalReconditionAmt'] > 0) ? number_format_custom($lossAssessment['totalReconditionAmt']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">GST</td>
                            <td align="right">{{ number_format_custom($recGstAmt) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Net</td>
                            <td align="right" style="font-weight: bold;">{{ number_format_custom($totalreconditionAss) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left">f. Endorsement Parts</td>
                            <td align="right" style="font-weight: bold;">{{ ($lossAssessment['totalendoresmentAss'] > 0) ? number_format_custom($lossAssessment['totalendoresmentAss']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left">2. Total (a+b+c+d+e+f)</td>
                            <td align="right">{{ number_format_custom($total_1) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Total (1+2)</td>
                            <td align="right" style="font-weight: bold;">{{ number_format_custom($total_2) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Less Imposed Clause</td>
                            <td align="right">{{ ($lossAssessment['ImposedClause'] > 0) ? number_format_custom($lossAssessment['ImposedClause']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Less Compulsory Deductable</td>
                            <td align="right">{{ ($lossAssessment['CompulsoryDeductable'] > 0) ? number_format_custom($lossAssessment['CompulsoryDeductable']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Less Voluntary Execess</td>
                            <td align="right">{{ ($lossAssessment['less_voluntary_excess'] > 0) ? number_format_custom($lossAssessment['less_voluntary_excess']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Less Salvage / Scrap Value</td>
                            <td align="right">{{ ($lossAssessment['SalvageAmt'] > 0) ? number_format_custom($lossAssessment['SalvageAmt']) : '0.00' }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left">3. Total</td>
                            <td align="right" style="font-weight: bold;">{{ number_format_custom($total_3) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left">4. Net Assessed Loss for Body/Cabin</td>
                            <td align="right" style="font-weight: bold;">0.00</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td align="left" style="padding-left: 18px;">Net Amount Payable (3+4)</td>
                            <td align="right" style="font-weight: bold;">{{ number_format_custom($total_3) }}</td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="left">14. All required documents are verified by me and found in order</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <td colspan="2" align="center">Signature of Surveyor</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="left">15. I/we agree to accept in full and final satisfaction of claim</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <td colspan="2" align="right">Signature of Insured (Claimant)</td>
                        </tr>
                        <tr>
                            <td align="left">16.</td>
                            <th colspan="3" align="left"><u>OFFICE USE ONLY</u></th>
                        </tr>
                        <tr>
                            <td align="left">17.</td>
                            <td colspan="3" align="left">Whether see 64 VB of insurance act complied or not</td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td colspan="2" align="left"> <hr style="border-style: dotted; border-bottom: 1px dotted black; width: 100%;"></td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td colspan="2" align="left"> <hr style="border-style: dotted; border-bottom: 1px dotted black; width: 100%;"></td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td colspan="2" align="left"> <hr style="border-style: dotted; border-bottom: 1px dotted black; width: 100%;"></td>
                            <td align="left"></td>
                        </tr>
                        <tr>
                            <td align="left">18.</td>
                            <td colspan="3" align="left">Amount Recommended</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <td colspan="2" align="right">Recommending Authority/Approving</td>
                        </tr>
                        <tr>
                            <td align="left">19.</td>
                            <td colspan="3" align="left">Passed for Payments</td>
                        </tr>
                        <tr>
                            <td align="left"></td>
                            <td colspan="3" align="left">Date</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                            <td colspan="2" align="center">Sanctioning Authority</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="left">20.</td>
                            <td colspan="3" align="left">DO sanction Control No. BHI/DO/MOT/ ----------------------------------/----------------------------------------</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>