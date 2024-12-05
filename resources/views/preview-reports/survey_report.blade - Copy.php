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
        th,td{
          font-family:verdana !important;
        } 

        table { border-collapse: separate; border-spacing: 0; }
        #design td { border: solid 1px #c2c2c2; border-style: none solid solid solid; padding: 10px;}
        #design th { border: solid 1px #c2c2c2; border-style: none solid solid solid; padding: 10px;}
        #design tr:first-child td { border-top-style: 1px solid #c2c2c2; }
        #design tr:first-child th { border-top-style: 1px solid #c2c2c2; }
        #design  tr td:first-child { border-left-style: 1px solid #c2c2c2; } 
        #design  tr th:first-child { border-left-style: 1px solid #c2c2c2; }
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

  <div style="padding: 5px 0px;">
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

      <div style="padding-top: 10px; line-height: 23px;">This report is issued without prejudice, in respect of cause, nature and extent of
        loss/damage and subject to the terms and conditions of the Insurance Policy and Insurer admitting liability.
      </div>

      <div style="padding-top: 10px;"><strong>Subject: </strong> Claim for Veh. Regn. No. {{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}, <strong>Accident Date : </strong>
      {{ !empty($policyDetails->date_time_accident) ? \Carbon\Carbon::parse($policyDetails->date_time_accident)->format('d/m/Y h:i A') : '' }}</div>
      <div style="padding-left: 70px;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center"
          style="font-size: 14px; padding-bottom: 20px;">
          <tbody>
            <tr>
              <th style="width: 30%; text-align: left; padding-top: 5px; font-family:Verdana !important;">Insured </th>
              <td style="width: 10%; text-align: left; padding-top: 5px;">:</td>
              <td style="width: 70%; text-align: left; padding-top: 5px; font-family: 'Verdana' !important;">{{ !empty($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</td>
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
              <th style="text-align: left; padding-top: 5px;">Policy No.</th>
              <td style="text-align: left; padding-top: 5px;">:</td>
              <td style="text-align: left; padding-top: 5px;">{{ !empty($policyDetails->policy_no) ? $policyDetails->policy_no : '' }} </td>
            </tr>
            <tr>
              <th style="text-align: left; padding-top: 5px;">Insurance Period</th>
              <td style="text-align: left; padding-top: 5px;">:</td>
              <td style="text-align: left; padding-top: 5px;">{{ !empty($policyDetails->policy_valid_from) ? \Carbon\Carbon::parse($policyDetails->policy_valid_from)->format('d/m/Y') : '' }} To {{ !empty($policyDetails->policy_valid_to) ? \Carbon\Carbon::parse($policyDetails->policy_valid_to)->format('d/m/Y') : '' }} </td>
            </tr>
            @if(isset($policyDetails->claim_no) && trim($policyDetails->claim_no) !== '')
            <tr>
              <th style="text-align: left; padding-top: 5px;">Claim No.</th>
              <td style="text-align: left; padding-top: 5px;">:</td>
              <td style="text-align: left; padding-top: 5px;">{{ !empty($policyDetails->claim_no) ? $policyDetails->claim_no : '' }}</td>
            </tr>
            @endif
            @if(isset($policyDetails->operating_officer) && trim($policyDetails->operating_officer) !== '')
            <tr>
              <th style="text-align: left; padding-top: 5px;">O. Officer</th>
              <td style="text-align: left; padding-top: 5px;">:</td>
              <td style="text-align: left; padding-top: 5px;">{{ !empty($policyDetails->operating_officer) ? $policyDetails->operating_officer : '' }}</td>
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
          <td align="left" valign="top" style="font-weight: bold; text-decoration: underline;  padding-top: 10px; padding-bottom: 10px;" colspan="3">Insurance Particulars</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="width: 43%; padding-top: 10px; font-family:'Verdana' !important;">Insurer</td>
          <td align="left" valign="top" style="width: 5%; padding-top: 10px;">:</td>
          <td align="left" valign="top" style="width: 52%; padding-top: 10px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->office_name) ? $policyDetails->office_name : '' }}</span>
            <br /><span>{{ isset($policyDetails->office_address) ? $policyDetails->office_address : '' }}</span>
          </td>
        </tr>
        @if(isset($policyDetails->appointing_office_name) && trim($policyDetails->appointing_office_name) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Appointed By</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->appointing_office_name) ? $policyDetails->appointing_office_name : '' }}</span>
            <br /><span>{{ isset($policyDetails->appointing_office_address) ? $policyDetails->appointing_office_address : '' }} </span>
          </td>
        </tr>
        @endif
        @if(isset($policyDetails->operating_office_name) && trim($policyDetails->operating_office_name) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Operating Office </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->operating_office_name) ? $policyDetails->operating_office_name : '' }}</span>
            <br /><span>{{ isset($policyDetails->operating_office_address) ? $policyDetails->operating_office_address : '' }} </span>
          </td>
        </tr>
        @endif
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Insured </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</span>
            <br /><span>{{ isset($policyDetails->insured_address) ? $policyDetails->insured_address : '' }}</span>
          </td>
        </tr>
        @if(isset($policyDetails->policy_no) && trim($policyDetails->policy_no) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Policy No./Cover Note No </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->policy_no) ? $policyDetails->policy_no : '' }} </span>
          </td>
        </tr>
        @endif
        @if(isset($policyDetails->policy_valid_from) && trim($policyDetails->policy_valid_from) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Period of Policy  </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td style="text-align: left; padding-top: 5px;">{{ !empty($policyDetails->policy_valid_from) ? \Carbon\Carbon::parse($policyDetails->policy_valid_from)->format('d/m/Y') : '' }} To {{ !empty($policyDetails->policy_valid_to) ? \Carbon\Carbon::parse($policyDetails->policy_valid_to)->format('d/m/Y') : '' }} </td>
          </td>
        </tr>
        @endif
        @if(isset($policyDetails->policy_type) && trim($policyDetails->policy_type) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">POLICY TYPE</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">{{ !empty($policyDetails->policy_type) ? $policyDetails->policy_type : '' }}</span>
          </td>
        </tr>
        @endif
        @if(isset($policyDetails->status_of_64vb) && trim($policyDetails->status_of_64vb) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">STATUS OF 64VB</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">{{ !empty($policyDetails->status_of_64vb) ? $policyDetails->status_of_64vb : '' }}</span>
          </td>
        </tr>  
        @endif
        @if(isset($policyDetails->status_of_64vb) && trim($policyDetails->status_of_64vb) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">STATUS OF PREINSPECTION </td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">{{ !empty($policyDetails->status_of_pre_insp) ? $policyDetails->status_of_pre_insp : '' }}</span>
          </td>
        </tr>
        @endif
        @if(isset($policyDetails->status_of_NCB) && trim($policyDetails->status_of_NCB) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">STATUS OF NCB</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">{{ !empty($policyDetails->status_of_NCB) ? $policyDetails->status_of_NCB : '' }}</span>
          </td>
        </tr>
        @endif
        @if(isset($policyDetails->payment_mode) && trim($policyDetails->payment_mode) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">PAYMENT MODE</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">{{ !empty($policyDetails->payment_mode) ? $policyDetails->payment_mode : '' }}</span>
          </td>
        </tr>
        @endif
        @if(isset($policyDetails->settlement_type) && trim($policyDetails->settlement_type) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">TYPE OF SETTLEMENT</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">{{ !empty($policyDetails->settlement_type) ? $policyDetails->settlement_type : '' }} </span>
          </td>
        </tr>
        @endif
        @if(isset($policyDetails->sum_insured) && trim($policyDetails->sum_insured) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">IDV </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->sum_insured) ? $policyDetails->sum_insured : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->HPA) && trim($policyDetails->HPA) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 10px;">HPA With</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 10px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 10px;">{{ !empty($policyDetails->HPA) ? $policyDetails->HPA : '' }}</td>
        </tr>
        @endif
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold; text-decoration: underline;">Vehicle Particulars</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold;">RC: {{ !empty($policyDetails->RC) ? $policyDetails->RC : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 10px;">Registration No.</td>
          <td align="left" valign="top" style="padding-top: 10px;">:</td>
          <td align="left" valign="top" style="padding-top: 10px;font-weight: bold;">{{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Registered Owner</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->registured_owner) ? $policyDetails->registured_owner : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Date of Registration </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->date_of_registration) ? \Carbon\Carbon::parse($policyDetails->date_of_registration)->format('d/m/Y') : '' }}</td>
        </tr>
        @if(isset($policyDetails->date_of_transfer) && trim($policyDetails->date_of_transfer) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Date of Transfer</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->date_of_transfer) ? \Carbon\Carbon::parse($policyDetails->date_of_transfer)->format('d/m/Y') : '' }}</td>
        </tr>
       @endif
       @if(isset($policyDetails->transfer_SrNo) && trim($policyDetails->transfer_SrNo) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Transfer Sr No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->transfer_SrNo) ? $policyDetails->transfer_SrNo : '' }}</td>
        </tr>
       @endif
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Chassis No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->vehicle_chassis_no) ? $policyDetails->vehicle_chassis_no : '' }} <span style="padding-left: 10px;font-weight: bold;">Physically Verified : {{ !empty($policyDetails->accident_chassis_no) ? $policyDetails->accident_chassis_no : '' }}</span></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Engine No. </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->vehicle_engine_no) ? $policyDetails->vehicle_engine_no : '' }} <span style="padding-left: 10px;font-weight: bold;">Physically Verified : {{ !empty($policyDetails->accident_engine_no) ? $policyDetails->accident_engine_no : '' }}</span></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Engine Capacity</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->engine_capacity) ? $policyDetails->engine_capacity : '' }} {{ !empty($policyDetails->engine_capacity_unit) ? $policyDetails->engine_capacity_unit : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Vehicle Make </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->vehicle_make) ? $policyDetails->vehicle_make : '' }}</td>
        </tr>
                <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Vehicle Variant</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->vehicle_variant) ? $policyDetails->vehicle_variant : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Vehicle Model</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->vehicle_model) ? (new DateTime($policyDetails->vehicle_model))->format('F Y') : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Type of Body </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->body_type) ? $policyDetails->body_type : '' }}</td>
        </tr>
        @if(isset($policyDetails->pre_accident_cond) && trim($policyDetails->pre_accident_cond) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Pre- Accident Condition</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->pre_accident_cond) ? $policyDetails->pre_accident_cond : '' }}</td>
        </tr>
        @endif
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Colour of Vehicle</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->vehicle_color) ? $policyDetails->vehicle_color : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Class of Vehicle</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->vehicle_class) ? $policyDetails->vehicle_class : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Seating Capacity</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->seating_capacity) ? $policyDetails->seating_capacity : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Odometer Reading</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->odometer_reading) ? $policyDetails->odometer_reading : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Fuel used as per RC</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->fuel) ? $policyDetails->fuel : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Tax paid upto</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->tax_valid_from_text) ? $policyDetails->tax_valid_from_text : '' }}</td>
        </tr>
        @if(isset($policyDetails->fitness_number) && trim($policyDetails->fitness_number) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Fitness Certificate No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->fitness_number) ? $policyDetails->fitness_number : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->fitness_valid_from) && trim($policyDetails->fitness_valid_from) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Fitness Certificate validity</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->fitness_valid_from) ? $policyDetails->fitness_valid_from : '' }} to {{ !empty($policyDetails->fitness_valid_to) ? $policyDetails->fitness_valid_to : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->permit_number) && trim($policyDetails->permit_number) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Permit No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->permit_number) ? $policyDetails->permit_number : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->permit_valid_from) && trim($policyDetails->permit_valid_from) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Permit validity</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->permit_valid_from) ? $policyDetails->permit_valid_from : '' }} to {{ !empty($policyDetails->permit_valid_to) ? $policyDetails->permit_valid_to : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->permit_type) && trim($policyDetails->permit_type) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Permit Type</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->permit_type) ? $policyDetails->permit_type : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->route) && trim($policyDetails->route) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Route / Area of Operation</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->route) ? $policyDetails->route : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->accident_place) && trim($policyDetails->accident_place) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Whether valid for the state in which accident took place?</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->accident_place) ? $policyDetails->accident_place : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->challan_no) && trim($policyDetails->challan_no) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Load Challan No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->challan_no) ? $policyDetails->challan_no : '' }} <span style="padding-left:80px;">{{ !empty($policyDetails->load_chalan) ? $policyDetails->load_chalan : '' }}</span></td>
        </tr>
        @endif
        @if(isset($policyDetails->registered_laden_weight) && trim($policyDetails->registered_laden_weight) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Gross Vehicle Weight</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->registered_laden_weight) ? $policyDetails->registered_laden_weight : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->unladen_weight) && trim($policyDetails->unladen_weight) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Unladen Weight</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->unladen_weight) ? $policyDetails->unladen_weight : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->cause_of_accident) && trim($policyDetails->cause_of_accident) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom:5px;">If overloaded, whether the overloading is the cause of accident? </td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom:5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom:5px;">{{ !empty($policyDetails->cause_of_accident) ? substr(strip_tags($policyDetails->cause_of_accident) ,0,110): " " }}</td>
        </tr>
        @endif
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr> 
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold; text-decoration: underline;">Driver Particulars</td>
          <td align="left" valign="top" style="padding-top: 5px;"></td>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold;">{{ !empty($policyDetails->unladen_weight) ? $policyDetails->unladen_weight : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 10px;">Name of the Driver</td>
          <td align="left" valign="top" style="padding-top: 10px;">:</td>
          <td align="left" valign="top" style="padding-top: 10px;">{{ !empty($policyDetails->driver_name) ? $policyDetails->driver_name : '' }}</td>
        </tr>
        @if(isset($policyDetails->driver_dob) && trim($policyDetails->driver_dob) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Driver DOB</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->driver_dob) ? \Carbon\Carbon::parse($policyDetails->driver_dob)->format('d/m/Y') : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->address) && trim($policyDetails->address) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Driver Address</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->address) ? $policyDetails->address : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->relation_with_insurer) && trim($policyDetails->relation_with_insurer) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Relation with the insured </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->relation_with_insurer) ? $policyDetails->relation_with_insurer : '' }}</td>
        </tr>
        @endif
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Driving License Number</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->dl_no) ? $policyDetails->dl_no : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Valid from</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->issuing_date) ? $policyDetails->issuing_date : '' }} upto {{ !empty($policyDetails->dl_valid_upto) ? $policyDetails->dl_valid_upto : '' }}</td> 
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Issuing Authority</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->issuing_authority) ? $policyDetails->issuing_authority : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Type of License</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->type_of_dl) ? $policyDetails->type_of_dl : '' }}</td>
        </tr>
        @if(isset($policyDetails->dl_renewal_no) && trim($policyDetails->dl_renewal_no) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Liscense Renewal No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->dl_renewal_no) ? $policyDetails->dl_renewal_no : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->vehicle_allowed_to_drive) && trim($policyDetails->vehicle_allowed_to_drive) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Type of Vehicle Allowed To Drive</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->vehicle_allowed_to_drive) ? $policyDetails->vehicle_allowed_to_drive : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->endorsement_detail) && trim($policyDetails->endorsement_detail) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Endorsement Details</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->endorsement_detail) ? $policyDetails->endorsement_detail : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->badge_no) && trim($policyDetails->badge_no) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Badge No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->badge_no) ? $policyDetails->badge_no : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->additional_details) && trim($policyDetails->additional_details) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom:5px;">Additional Comments</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom:5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom:5px;">{{ !empty($policyDetails->additional_details) ? $policyDetails->additional_details : '' }}</td>
        </tr>
        @endif
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Accident Particulars</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 10px;">Date and Time of Accident</td>
          <td align="left" valign="top" style="padding-top: 10px;">:</td>
          <td align="left" valign="top" style="padding-top: 10px;">{{ !empty($policyDetails->date_time_accident) ? \Carbon\Carbon::parse($policyDetails->date_time_accident)->format('d/m/Y h:i A') : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Place of Accident</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->place_accident) ? $policyDetails->place_accident : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Place of Survey</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->place_survey) ? $policyDetails->place_survey : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Date of request for Survey</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->date_of_appointment) ? \Carbon\Carbon::parse($policyDetails->date_of_appointment)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Date and Time of Survey</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->Survey_Date_time) ? \Carbon\Carbon::parse($policyDetails->Survey_Date_time)->format('d/m/Y h:i A') : '' }}</td>
        </tr>
        @if(isset($policyDetails->date_of_under_repair_visit) && trim($policyDetails->date_of_under_repair_visit) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Date of Under Repair visits</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->date_of_under_repair_visit) ? \Carbon\Carbon::parse($policyDetails->date_of_under_repair_visit)->format('d/m/Y') : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->insured_rep_attending_survey) && trim($policyDetails->insured_rep_attending_survey) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Insured's rep. attending survey</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->insured_rep_attending_survey) ? $policyDetails->insured_rep_attending_survey : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->vehicle_left_unattended) && trim($policyDetails->vehicle_left_unattended) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">Was veh. left unattended after accn</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">{{ !empty($policyDetails->vehicle_left_unattended) ? \Carbon\Carbon::parse($policyDetails->vehicle_left_unattended)->format('d/m/Y') : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->anti_theft_fitted) && trim($policyDetails->anti_theft_fitted) !== '')
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;">Anti Theft Device Status </td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">{{ !empty($policyDetails->anti_theft_fitted) ? $policyDetails->anti_theft_fitted : '' }} {{ !empty($policyDetails->anti_theft_type) ? $policyDetails->anti_theft_type : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->previous_claim_details) && trim($policyDetails->previous_claim_details) !== '')
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;font-weight: bold; text-decoration: underline;">Previous Claim Details</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;"><?php echo $policyDetails->previous_claim_details;?></td>
        </tr>
        @endif
        @if(isset($policyDetails->accident_reported_to_police) && trim($policyDetails->accident_reported_to_police) !== '')
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Particulars of Police Report</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 10px;">Has accident been report to Police </td>
          <td align="left" valign="top" style="padding-top: 10px;">:</td>
          <td align="left" valign="top" style="padding-top: 10px;">{{ !empty($policyDetails->accident_reported_to_police) ? $policyDetails->accident_reported_to_police : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Has Panchnama been carried out </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->panchnama) ? $policyDetails->panchnama : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->third_party_injury) && trim($policyDetails->third_party_injury) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;font-weight: bold; text-decoration: underline;">Particulars of Third Party Injury/Loss</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;"><?php echo $policyDetails->third_party_injury;?></td>
        </tr>
        @endif
        @if(isset($policyDetails->injury_to_driver) && trim($policyDetails->injury_to_driver) !== '')
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;font-weight: bold; text-decoration: underline;">Injury to Driver/Occupant (If any)</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;"><?php echo $policyDetails->injury_to_driver;?></td>
        </tr>
        @endif
        @if(isset($policyDetails->spot_survey_by) && trim($policyDetails->spot_survey_by) !== '')
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Spot Survey</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Spot Survey By </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->spot_survey_by) ? $policyDetails->spot_survey_by : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->spot_survey_date) && trim($policyDetails->spot_survey_date) !== '')
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">Spot Survey report received on</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">{{ !empty($policyDetails->spot_survey_date) ? \Carbon\Carbon::parse($policyDetails->spot_survey_date)->format('d/m/Y') : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->passenger_detail) && trim($policyDetails->passenger_detail) !== '')
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="font-weight: bold; text-decoration: underline;" colspan="3">Load/Passenger Details</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;padding-bottom: 5px;" colspan="3"><?php echo $policyDetails->passenger_detail;?>
          </td>
        </tr>
        @endif
        @if(isset($policyDetails->accident_brief_description) && trim($policyDetails->accident_brief_description) !== '')
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Cause and Nature of Accident</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; line-height: 28px; text-align: justify;" colspan="3"><?php echo $policyDetails->accident_brief_description;?></td>
        </tr>
        @endif
        @if(isset($policyDetails->action_of_survey) && trim($policyDetails->action_of_survey) !== '')
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;" colspan="3">Actions of Survey</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; line-height: 28px; text-align: justify;" colspan="3"><?php echo $policyDetails->action_of_survey;?></td>
        </tr>
        @endif
        @if(isset($policyDetails->particular_of_damage) && trim($policyDetails->particular_of_damage) !== '')
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;" colspan="3">Particulars of Loss/Damages</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; line-height: 28px; text-align: justify;" colspan="3"><?php echo $policyDetails->particular_of_damage;?></td>
        </tr>
        @endif
        @if(isset($policyDetails->estimate_no) && trim($policyDetails->estimate_no) !== '')
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;  padding-bottom: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Original Estimate</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; line-height: 28px; text-align: justify;" colspan="3">Estimate no. {{ !empty($policyDetails->estimate_no) ? $policyDetails->estimate_no : '' }} dated {{ !empty($policyDetails->date_of_estimate) ? \Carbon\Carbon::parse($policyDetails->date_of_estimate)->format('d/m/Y') : '' }} for Rs. {{ !empty($policyDetails->totalest) ? $policyDetails->totalest : '' }} was submitted by {{ !empty($policyDetails->workshop_branch_name) ? $policyDetails->workshop_branch_name : '' }} , {{ !empty($policyDetails->workshop_branch_address) ? $policyDetails->workshop_branch_address : '' }}</td>
        </tr>
        @endif
        @if(isset($policyDetails->observation) && trim($policyDetails->observation) !== '')
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Observation</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; line-height: 28px; text-align: justify;" colspan="3"><?php echo $policyDetails->observation;?></td>
        </tr>
        @endif
        @if(isset($dynamicSection) && count($dynamicSection) > 0)
        @foreach($dynamicSection as $key=>$value)  
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;  padding-bottom: 5px;font-weight: bold; text-decoration: underline;" colspan="3">{{ !empty($key) ? $key : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; line-height: 20px; text-align: justify;" colspan="3"><?php echo $value;?></td>
        </tr>
        @endforeach
     @endif
        <tr>
          <td align="left" valign="top" style="padding-top: 10px;font-weight: bold;" colspan="3">(Issued without Prejudice)</td>
        </tr>
       
        <tr>
          <td align="left" valign="top" style="padding-top: 10px;font-weight: bold;" colspan="3">
            @if ($letter_head_img)
            <img src="{{ asset('public/storage/'.$signature_img) }}" style="width:100px;">
              @else
                <p>No letter head image available</p>
              @endif
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top:5px;font-weight: bold;" colspan="3">{{ !empty($adminHeaderFooter->name) ? $adminHeaderFooter->name : '-' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;" colspan="3">{{ !empty($adminHeaderFooter->designation) ? $adminHeaderFooter->designation : '-' }}</td>
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
  <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
  <div id="design" style="margin-bottom: 20px; font-size: 7px !important;">    
    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
      <tbody>        
        <tr>
          <td align="center" valign="top" style="font-weight: bold; padding-top: 5px; border-left: none; border-right: none;">LOSS ASSESSMENT <br />In respect of Vehicle
            Registration No. {{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}, Accident Date : {{ !empty($policyDetails->date_time_accident) ? \Carbon\Carbon::parse($policyDetails->date_time_accident)->format('d/m/Y') : '' }} <bt />(Annexure 'A' attached to Survey Report No. {{ isset($policyDetails->inspection_reference_no) ? $policyDetails->inspection_reference_no : '' }})</td>
        </tr>
      </tbody>
    </table>
    <table width="100%" align="center">
      <tbody>
        <tr>
          <th align="left" valign="top" style="width: 40%; border: none;">ASSESSMENT OF LOSS</th>
          <th align="left" valign="top" style="width: 60%; border: none;">(PARTS)</th>
        </tr>
        <tr>
          <th align="left" valign="top" colspan="2" style="border-bottom: 2px solid #000; line-height:5px; padding: 0px"></th>
        <tr> 
      </tbody>
    </table> 
    <table width="100%" border="0" align="center">
      <tbody>
        <tr>
          <th align="left" valign="top" style="padding: 0px 3px;">Sr. No.</th>
          <th align="left" valign="top" style="padding: 0px 3px;">Description of Parts</th>
          <!-- <th align="left" valign="top" style="padding: 0px 3px;">Bill Sr.No.</th> -->
          @if(isset($lossAssessment[0]['display_bill_sr_no']) && $lossAssessment[0]['display_bill_sr_no'] == 1)
          <th align="left" valign="top" style="padding: 0px 3px;">Bill Sr.No.</th>
          @endif
          @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
          <th align="right" valign="top" style="padding: 0px 3px;">HSN Code</th>
          @endif
          <th align="center" valign="top" style="padding: 0px 3px;">QE</th>
          <th align="center" valign="top" style="padding: 0px 3px;">QA</th>
          <th align="right" valign="top" style="padding: 0px 3px;">Est. Rate</th>
          <th align="right" valign="top" style="padding: 0px 3px;">GST <br />%</th>
          <th align="right" valign="top" style="padding: 0px 3px;">Estimated<br /><span style="font-weight: 400;">(Amt in Rs)</span></th>
          <th align="right" valign="top" colspan="3" style="padding: 0px 3px;">
            <table width="100%" align="center">
              <tbody>
                <tr>
                  <th align="center" valign="top"   colspan="3" style="padding-bottom: 5px; border: none;">Assessed Parts Amount</th>
                </tr>
                <tr>
                  <th align="center" valign="top" style="padding: 0px 3px; border-top: 2px solid #000; border-left: none; border-right: none; border-bottom: none; ">Metal
                  </th>
                  <th align="center" valign="top" style="padding: 0px 3px; border-top: 2px solid #000; border-left: none; border-right: none; border-bottom: none;">
                    Rub/Plast</th>
                  <th align="center" valign="top" style="padding: 0px 3px; border-top: 2px solid #000; border-left: none; border-right: none; border-bottom: none;">Glass
                  </th>
                </tr>
              </tbody>
            </table>
          </th>
          <th align="center" valign="top" style="padding: 0px 3px;">Remark</th>
        </tr>
        @if(isset($lossAssessment[0]['alldetails']))
          @php
          $alldetails = json_decode($lossAssessment[0]['alldetails'], true);
          @endphp
         @if(is_array($alldetails))
         @foreach($alldetails as $index => $detail)
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px;">{{ $index + 1 }}</td>
                <td align="left" valign="top" style="padding: 0px 3px;">{{ $detail['description'] }}</td>
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

            @if (!empty($detail['quantities']))
                @foreach($detail['quantities'] as $quantityIndex => $quantity)
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px; padding-left: 20px; font-style: italic;">{{ $index + 1 }}.{{ $quantityIndex + 1 }}</td>
                        <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;">{{ $quantity['description'] }}</td>
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
                    @endforeach
            @endif
           @endforeach
           @endif
        @if(isset($lossAssessment[0]['totalest'])) 
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
              style="padding: 0px 3px; border-top: 2px solid #000; font-weight: bold;">{{ !empty($lossAssessment[0]['totalest']) ? $lossAssessment[0]['totalest'] : '0.00' }} </td>
            <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">{{ !empty($lossAssessment[0]['partMetalAssamount']) ? $lossAssessment[0]['partMetalAssamount'] : '0.00' }}</td>
            <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">{{ !empty($lossAssessment[0]['partRubberAssamount']) ? $lossAssessment[0]['partRubberAssamount'] : '0.00' }}</td>
            <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">{{ !empty($lossAssessment[0]['partGlassAssamount']) ? $lossAssessment[0]['partGlassAssamount'] : '0.00' }}</td>
            <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
          </tr>
        @endif -->
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Total (Parts with GST 28%)</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">517829.38</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">179262.05</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">102990.15</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Total (Parts with GST 18%)</td>
          <td align="right" valign="top" style="padding: 0px 3px;">517829.38</td>
          <td align="right" valign="top" style="padding: 0px 3px;">179262.05</td>
          <td align="right" valign="top" style="padding: 0px 3px;">102990.15</td>
          <td align="right" valign="top" style="padding: 0px 3px;">3581.59</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Total (Parts with GST 0%)</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
        <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Less Dep @ 0.00% on Metal, 0.00% on Rub/Plast, 0.00% on Glass Parts, 0.00% on Fibre Parts.</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Total (Parts with GST 28%)</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">517829.38</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">179262.05</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">102990.15</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Total (Parts with GST 18%)</td>
          <td align="right" valign="top" style="padding: 0px 3px;">517829.38</td>
          <td align="right" valign="top" style="padding: 0px 3px;">179262.05</td>
          <td align="right" valign="top" style="padding: 0px 3px;">102990.15</td>
          <td align="right" valign="top" style="padding: 0px 3px;">3581.59</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Total (Parts with GST 0%)</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">669781.40</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">195312.63</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">225611.94</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">3581.59</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Add CGST @14.00%</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">25096.69</td>
          <td align="right" valign="top" style="padding: 0px 3px;">14418.62</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Add SGST @14.00%</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">25096.69</td>
          <td align="right" valign="top" style="padding: 0px 3px;">14418.62</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Add CGST @9.00%</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">1444.55</td>
          <td align="right" valign="top" style="padding: 0px 3px;">11035.62</td>
          <td align="right" valign="top" style="padding: 0px 3px;">322.34</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Add SGST @9.00%</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">1444.55</td>
          <td align="right" valign="top" style="padding: 0px 3px;">11035.62</td>
          <td align="right" valign="top" style="padding: 0px 3px;">322.34</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;">Total</td>
          <td align="right" valign="top"  
            style="padding: 0px 3px; border-top: 2px solid #000; font-weight: bold;">669781.40 </td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">195312.63</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">225611.94</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000;">3581.59</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>        
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;font-weight: bold;">Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 2px solid #000; ">&nbsp;</td>
          <td align="right" valign="top"  
            style="padding: 0px 3px; border-top: 2px solid #000;font-weight: bold;">
            248395.11</td>
          <td align="right" valign="top"  
            style="padding: 0px 3px; border-top: 2px solid #000;font-weight: bold;">
            276521.10</td>
          <td align="right" valign="top"  
            style="padding: 0px 3px; border-top: 2px solid #000;font-weight: bold;">4226.28
          </td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="{{ $colspanValue + 6 }}" style="padding: 0px 3px;font-weight: bold;">Total</td>
          <td align="right" valign="top"  
            style="padding: 0px 3px; padding-bottom: 5px; border-bottom: 2px solid #000; border-top: 2px solid #000;">-</td>
          <td align="right" valign="top"  
            style="padding: 0px 3px; padding-bottom: 5px; border-bottom: 2px solid #000; border-top: 2px solid #000;">-</td>
          <td align="right" valign="top"  
            style="padding: 0px 3px; padding-bottom: 5px; border-bottom: 2px solid #000; border-top: 2px solid #000;">-</td>
          <td align="right" valign="top"  
            style="padding: 0px 3px; padding-bottom: 5px; border-bottom: 2px solid #000; border-top: 2px solid #000;font-weight: bold;">
            529142.49</td>
          <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
      </tbody>
    </table>
    @endif
    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="padding-top:20px;">
      <tbody>
        <tr>
          <th align="left" valign="top" style="padding-top: 5px; border: none; border-bottom: 2px solid #000;">LABOUR CHARGES</th>
        </tr>
      </tbody>
    </table>
    <table width="100%" align="center">
      <tbody>
        <tr>
          <th align="center" valign="top" style="padding: 0px 3px; width: 5%;">Sr. No.</th>
          <th align="left" valign="top" style="padding: 0px 3px; width: 35%;">Description of Parts</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 5%;">SAC</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 10%;">Remarks</th>          
          <th align="right" valign="top" style="padding: 0px 3px; width: 15%;">Estimated<br /><span
              style="font-weight: 400;">(Amt in Rs)</span></th>
          <th align="right" valign="top" style="padding: 0px 3px; width: 15%;">O/F & Denting<br /><span
              style="font-weight: 400;">(Amt in Rs)</span></th>
          <th align="right" valign="top" style="padding: 0px 3px; width: 15%;">Painting<br /><span
              style="font-weight: 400;">(Amt in Rs)</span></th>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px;">1</td>
          <td align="left" valign="top" style="padding: 0px 3px;">Ac System Service</td>
          <td align="left" valign="top" style="padding: 0px 3px;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px;">Allowed</td>
          <td align="right" valign="top" style="padding: 0px 3px;">2115.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">2115.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px;">2</td>
          <td align="left" valign="top" style="padding: 0px 3px;">Denting/ Repairing Charges</td>
          <td align="left" valign="top" style="padding: 0px 3px;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px;">Allowed</td>
          <td align="right" valign="top" style="padding: 0px 3px;">19361.24</td>
          <td align="right" valign="top" style="padding: 0px 3px;">6485.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px;">3</td>
          <td align="left" valign="top" style="padding: 0px 3px;">Body Paint Charges</td>
          <td align="left" valign="top" style="padding: 0px 3px;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px;">Allowed</td>
          <td align="right" valign="top" style="padding: 0px 3px;">56500.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">38000.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px;">4</td>
          <td align="left" valign="top" style="padding: 0px 3px;">Opening & Refitting Charges</td>
          <td align="left" valign="top" style="padding: 0px 3px;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px;">Allowed</td>
          <td align="right" valign="top" style="padding: 0px 3px;">32000.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">16770.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px;">5</td>
          <td align="left" valign="top" style="padding: 0px 3px;">Towing Charges</td>
          <td align="left" valign="top" style="padding: 0px 3px;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px;">Disallowed</td>
          <td align="right" valign="top" style="padding: 0px 3px;">1800.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px;">6</td>
          <td align="left" valign="top" style="padding: 0px 3px;">Painting Labour</td>
          <td align="left" valign="top" style="padding: 0px 3px;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px;">25000.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">7300.00</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-style: italic; padding-left: 15px;">1
          </td>
          <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;">Bumper</td>
          <td align="left" valign="top" style="padding: 0px 3px;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">4500.00</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-style: italic; padding-left: 15px;">2
          </td>
          <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;">Bonnet</td>
          <td align="left" valign="top" style="padding: 0px 3px;">-</td>
          <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">-</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">2800.00</td>
        </tr>
        <tr>
          <td align="left" valign="top" colspan="4" style="padding: 0px 3px;">Total</td>
          <td align="right" valign="top" style="padding: 0px 3px;">136776.24</td>
          <td align="right" valign="top" style="padding: 0px 3px;">63370.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">7300.00</td>
        </tr>
        <tr>
          <td align="left" valign="top" colspan="4" style="padding: 0px 3px;">Add CGST @9.00%</td>
          <td align="right" valign="top" style="padding: 0px 3px;">12309.86</td>
          <td align="right" valign="top" style="padding: 0px 3px;">5703.30</td>
          <td align="right" valign="top" style="padding: 0px 3px;">657.00</td>
        </tr>
        <tr>
          <td align="left" valign="top" colspan="4" style="padding: 0px 3px;">Add SGST @9.00%</td>
          <td align="right" valign="top" style="padding: 0px 3px; padding-bottom: 10px;">12309.86</td>
          <td align="right" valign="top" style="padding: 0px 3px; padding-bottom: 10px;">5703.30</td>
          <td align="right" valign="top" style="padding: 0px 3px; padding-bottom: 10px;">657.00</td>
        </tr>
        <tr>
          <td align="left" valign="top" colspan="4" style="padding: 0px 3px; font-weight: bold;">Total</td>
          <td align="right" valign="top"  style="padding: 0px 3px; border-top: 2px solid #000;">12309.86</td>
          <td align="right" valign="top"  style="padding: 0px 3px; border-top: 2px solid #000;">5703.30</td>
          <td align="right" valign="top"  style="padding: 0px 3px; border-top: 2px solid #000;">657.00</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="4" style="padding: 0px 3px;">Less 0.00% on 25.00% of Painting
            Labour</td>
          <td align="right" valign="top"   colspan="3"
            style="padding: 0px 3px; border-top: 2px solid #000;">0.00</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="4" style="padding: 0px 3px; font-weight: bold;">Total </td>
          <td align="right" valign="top"   colspan="3"
            style="padding: 0px 3px; font-weight: bold; border-top: 2px solid #000;">
            8614.00</td>
        </tr>
        <tr>
          <td align="left" valign="top"   colspan="4" style="padding: 0px 3px; font-weight: bold;">Net Labour
            Amount (O/F & D/B + Painting Labour) </td>
          <td align="right" valign="top"   colspan="3"
            style="padding: 0px 3px; font-weight: bold; border-top: 2px solid #000;">
            83390.60 </td>
        </tr>
      </tbody>
    </table>

    <table width="100%" align="center">
      <tbody>
        <tr>
          <th align="left" valign="top" style="padding-top: 10px; border: none;  border-bottom: 2px solid #000;">Summary of Estimate and Assessment of Loss</th>
        </tr>
      </tbody>
    </table>
    <table width="100%" align="center">
      <tbody>
        <tr>
          <th align="left" valign="top" style="padding: 0px 3px; width: 60%;">&nbsp;</th>
          <th align="right" valign="top" style="padding: 0px 3px; width: 20%;">Estimated<br /><span
              style="font-weight: 400;">(Amt in Rs)</span></th>
          <th align="right" valign="top" style="padding: 0px 3px; width: 20%;">Assessed<br /><span
              style="font-weight: 400;">(Amt in Rs)</span></th>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; font-weight: bold;">Total Cost of Parts</td>
          <td align="right" valign="top" style="padding: 0px 3px;">672119.64</td>
          <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px;">Parts (Metal)</td>
          <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px;">248395.11</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px;">Parts (Rub/Plast) </td>
          <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px;">276521.10</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px;">Parts (Glass) </td>
          <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px;">4226.28</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px;">Total Cost of Endorsement Parts</td>
          <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px;">1917.36</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="font-weight: bold;padding: 0px 3px;">Total</td>
          <td align="right" valign="top" style="font-weight: bold; border-top: 1px solid #000; padding: 0px 3px;">672119.64</td>
          <td align="right" valign="top" style="font-weight: bold; border-top: 1px solid #000; padding: 0px 3px;">531059.85</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px;">Total Cost of Recondition Parts</td>
          <td align="right" valign="top" style="padding: 0px 3px;">18001.46</td>
          <td align="right" valign="top" style="padding: 0px 3px;">13906.24</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">690121.10</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">544966.09</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px;">Net Labour Charges</td>
          <td align="right" valign="top" style="padding: 0px 3px;">161395.96</td>
          <td align="right" valign="top" style="padding: 0px 3px;">83390.60</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; font-weight: bold;">TOTAL</td>
          <td align="right" valign="top"  
            style="padding: 0px 3px; font-weight: bold; border-top: 2px solid #000;">851517.06</td>
          <td align="right" valign="top"  
            style="padding: 0px 3px; font-weight: bold; border-top: 2px solid #000;">628356.69</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px;">Compulsory Deductible</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">2000.00</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; font-weight: bold;">TOTAL</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">851517.06</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">626356.69</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; font-weight: bold;">Net Liability on Repair
            Basis</td>
          <td align="right" valign="top"  
            style="padding: 0px 3px; font-weight: bold; border-bottom: 2px solid #000; padding: 0px 3px;">&nbsp;</td>
          <td align="right" valign="top"  
            style="padding: 0px 3px; font-weight: bold; border-bottom: 2px solid #000; padding: 0px 3px;">626357.00</td>
        </tr>

      </tbody>
    </table>

    <table width="100%" align="center">
      <tbody>
        <tr>
          <th align="left" valign="top" style="padding-top: 10px;  border: none; border-bottom: 2px solid #000;">PARTS TAX SUMMARY</th>
        </tr>
      </tbody>
    </table>
    <table width="100%"  align="center">
      <tbody>
        <tr>
          <th align="center" valign="top" style="padding: 0px 3px; width: 5%;">Sr. No.</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">Tax %</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 20%;">Depreciated Amt</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">CGST</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">SGST</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">IGST</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">Amount</th>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; ">1</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">18.00 </td>
          <td align="right" valign="top" style="padding: 0px 3px; ">142253.96</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">12802.86</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">12802.86</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">167859.67</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; ">2</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">28.00 </td>
          <td align="right" valign="top" style="padding: 0px 3px; ">296158.44</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">39515.31</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">39515.31</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">375189.06</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold;">&nbsp;</td>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold;">Grand Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">438412.40</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">52318.16</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">52318.16</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">543048.73</td>
        </tr>
      </tbody>
    </table>

    <table width="100%" align="center">
      <tbody>
        <tr>
          <th align="left" valign="top" style="padding-top: 10px;  border: none; border-bottom: 2px solid #000;">LABOUR TAX SUMMARY</th>
        </tr>
      </tbody>
    </table>
    <table width="100%" align="center">
      <tbody>
        <tr>
          <th align="center" valign="top" style="padding: 0px 3px; width: 5%;">Sr. No.</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">Tax %</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 20%;">Depreciated Amt</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">CGST</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">SGST</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">IGST</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">Amount</th>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px;">1</td>
          <td align="center" valign="top" style="padding: 0px 3px;">18.00 </td>
          <td align="right" valign="top" style="padding: 0px 3px;">63370.96</td>
          <td align="right" valign="top" style="padding: 0px 3px;">5703.86</td>
          <td align="right" valign="top" style="padding: 0px 3px;">5703.86</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">74776.67</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold;">&nbsp;</td>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold;">Grand Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">63370.40</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">5703.16</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">5703.16</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">74776.73</td>
        </tr>
      </tbody>
    </table>

    <table width="100%" align="center">
      <tbody>
        <tr>
          <th align="left" valign="top" style="padding-top: 10px;  border: none; border-bottom: 2px solid #000;">PAINTING LABOUR TAX SUMMARY</th>
        </tr>
      </tbody>
    </table>
    <table width="100%" align="center">
      <tbody>
        <tr>
          <th align="center" valign="top" style="padding: 0px 3px; width: 5%;">Sr. No.</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">Tax %</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 25%;">Depreciated Amt</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">CGST</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">SGST</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">IGST</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">Amount</th>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px;">1</td>
          <td align="center" valign="top" style="padding: 0px 3px;">18.00 </td>
          <td align="right" valign="top" style="padding: 0px 3px;">7300.96</td>
          <td align="right" valign="top" style="padding: 0px 3px;">657.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">657.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">8614.00</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold;">&nbsp;</td>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold;">Grand Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">7300.40</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">657.16</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">657.16</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">8614.00</td>
        </tr>
      </tbody>
    </table>

    <table width="100%" align="center">
      <tbody>
        <tr>
          <th align="left" valign="top" style="padding-top: 10px;  border: none; border-bottom: 2px solid #000;">IMT ENDORSEMENTS PARTS TAX SUMMARY</th>
        </tr>
      </tbody>
    </table>
    <table width="100%" align="center">
      <tbody>
        <tr>
          <th align="center" valign="top" style="padding: 0px 3px; width: 5%;">Sr. No.</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">Tax %</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 20%;">Depreciated Amt</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">CGST</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">SGST</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">IGST</th>
          <th align="center" valign="top" style="padding: 0px 3px; width: 15%;">Amount</th>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px;">1</td>
          <td align="center" valign="top" style="padding: 0px 3px;">18.00 </td>
          <td align="right" valign="top" style="padding: 0px 3px;">356.68</td>
          <td align="right" valign="top" style="padding: 0px 3px;">32.10</td>
          <td align="right" valign="top" style="padding: 0px 3px;">32.10</td>
          <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px;">420.88</td>
        </tr>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold;">&nbsp;</td>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold;">Grand Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">356.68</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">32.10</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">32.10</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">0.00</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">420.88</td>
        </tr>
      </tbody>
    </table>

    <table width="100%" align="center" style="border: none !important;">
      <tbody>
        <tr>
          <td align="left" valign="top" style="padding-top: 10px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px; font-weight: bold; border: none !important;">Net Liability</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px; border: none !important;">Based On Details Provided Above, The Justified
            Liability Under The Subject Policy Of Insurance Works Out To Rs.626357.00 <span
              style="font-weight: bold;">(Rupees Six Lakhs Twenty Six Thousand Three Hundred Fifty Seven Only)</span>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px;  border: none !important;">subject to terms & conditions of Insurance policy & attachment of liability of Insurer.</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px;  font-weight: bold; border: none !important;">(Issued without Prejudice)</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px; border: none !important;"><img src="#" alt="" style="width: 100px; height: 100px;">  </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 50px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px;font-weight: bold; border: none !important;">For XXXX ISLA Pvt. Ltd</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;  padding-left: 0px;  padding-right: 0px; padding-bottom: 0px; border: none !important;">Surveyors & Loss Assessors</td>
        </tr>
      </tbody>
    </table>

  </div>
@endif
</body>
</html>