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
    </style>
</head>

<body>
  <div>
    <div style="border-bottom: 3px solid #00; text-align:center;">
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
    <div style="text-align: center;font-weight: bold; font-family: 'Verdana' !important; ">MOTOR ({{ ($policyDeatils->claim_type == 1) ? 'FINAL' : 'SPOT' }}) SURVEY REPORT</div>
      <div style="text-align: center; font-weight: 400;">Private & Confidential</div>

      <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top: 20px; font-size: 14px; font-family: 'Verdana' !important;">
        <tbody>
          <tr>           
            <td style="width: 80%">
              <span style="font-weight: bold; padding-right: 10px; padding-left: 10px; font-family: 'Verdana' !important; ">Ref No.:</span> {{ isset($policyDeatils->inspection_reference_no) ? $policyDeatils->inspection_reference_no : '' }}
            </td>
            <td style="width: 20%">
              <span  style="font-weight: bold;  padding-right: 10px; padding-left: 10px; font-family: 'Verdana' !important; ">Date:</span> {{ isset($policyDeatils->reportGeneratedOn) ? \Carbon\Carbon::parse($policyDeatils->reportGeneratedOn)->format('d/m/Y') : '' }}
            </td>
          </tr>
        </tbody>
      </table>

      <div style="padding-top: 10px; line-height: 23px;">This report is issued without prejudice, in respect of cause, nature and extent of
        loss/damage and subject to the terms and conditions of the Insurance Policy and Insurer admitting liability.
      </div>

      <div style="padding-top: 10px;"><strong>Subject: </strong> Claim for Veh. Regn. No. {{ !empty($policyDeatils->registration_no) ? $policyDeatils->registration_no : '' }}, <strong>Accident Date : </strong>
      {{ !empty($policyDeatils->date_time_accident) ? \Carbon\Carbon::parse($policyDeatils->date_time_accident)->format('d/m/Y') : '' }}</div>
      <div style="padding-left: 70px;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center"
          style="font-size: 14px; padding-bottom: 20px;">
          <tbody>
            <tr>
              <th style="width: 30%; text-align: left; padding-top: 5px; font-family:Verdana !important;">Insured </th>
              <td style="width: 10%; text-align: left; padding-top: 5px;">:</td>
              <td style="width: 60%; text-align: left; padding-top: 5px; font-family: 'Verdana' !important;">{{ !empty($policyDeatils->insured_name) ? $policyDeatils->insured_name : '' }}</td>
            </tr>
            <tr>
              <th style="text-align: left; padding-top: 5px;">Policy No.</th>
              <td style="text-align: left; padding-top: 5px;">:</td>
              <td style="text-align: left; padding-top: 5px;">{{ !empty($policyDeatils->policy_no) ? $policyDeatils->policy_no : '' }} </td>
            </tr>
            <tr>
              <th style="text-align: left; padding-top: 5px;">Insurance Period</th>
              <td style="text-align: left; padding-top: 5px;">:</td>
              <td style="text-align: left; padding-top: 5px;">{{ !empty($policyDeatils->policy_valid_from) ? \Carbon\Carbon::parse($policyDeatils->policy_valid_from)->format('d/m/Y') : '' }} To {{ !empty($policyDeatils->policy_valid_to) ? \Carbon\Carbon::parse($policyDeatils->policy_valid_to)->format('d/m/Y') : '' }} </td>
            </tr>
            <tr>
              <th style="text-align: left; padding-top: 5px;">Claim No.</th>
              <td style="text-align: left; padding-top: 5px;">:</td>
              <td style="text-align: left; padding-top: 5px;">{{ !empty($policyDeatils->claim_no) ? $policyDeatils->claim_no : '' }}</td>
            </tr>
            <tr>
              <th style="text-align: left; padding-top: 5px;">O. Officer</th>
              <td style="text-align: left; padding-top: 5px;">:</td>
              <td style="text-align: left; padding-top: 5px;">{{ !empty($policyDeatils->operating_officer) ? $policyDeatils->operating_officer : '' }}</td>
            </tr>
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
          <td align="left" valign="top" style="width: 50% padding-top: 10px; font-family:'Verdana' !important;">Insurer</td>
          <td align="left" valign="top" style="width: 5% padding-top: 10px;">:</td>
          <td align="left" valign="top" style="width: 45% padding-top: 10px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDeatils->office_name) ? $policyDeatils->office_name : '' }}</span>
            <br /><span>{{ isset($policyDeatils->office_address) ? $policyDeatils->office_address : '' }}</span>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Appointed By</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; line-height: 18px;"><span style="font-weight: bold;">{{ isset($policyDeatils->office_name) ? $policyDeatils->office_name : '' }}</span>
            <br /><span>{{ isset($policyDeatils->office_address) ? $policyDeatils->office_address : '' }} </span>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Operating Office </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; line-height: 18px;"><span style="font-weight: bold;">OICL Service Center, Jodhpur</span>
            <br /><span>Bhansali Tower, IIIrd Floor, Residency Road, Jodhpur </span>
          </td>
        </tr>

        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Insured </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; line-height: 18px;"><span style="font-weight: bold;">IMAGE_URL</span>
            <br /><span>Insured Address M-9999999999</span>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Policy No./Cover Note No </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">Policy Number </span>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Period of Policy  </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">01/01/2024 To 31/12/2024 </span>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">POLICY TYPE</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">NOT REQUIRED</span>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">STATUS OF 64VB</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">CONFIRMED & ASSESSMENT DONE ACCORDINGLY </span>
          </td>
        </tr>  
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">STATUS OF PREINSPECTION </td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">NOT APPLICABLE</span>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">STATUS OF NCB</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">CONFIRMED & ASSESSMENT DONE ACCORDINGLY</span>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">PAYMENT MODE</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">ISSUED IN FAVOUR OF </span>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">TYPE OF SETTLEMENT</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold;">CASHLESS </span>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">IDV </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->sum_insured) ? $policyDeatils->sum_insured : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 10px;">HPA With</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 10px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 10px;">{{ !empty($policyDeatils->HPA) ? $policyDeatils->HPA : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; font-weight: bold; text-decoration: underline;">Vehicle Particulars</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold;">{{ !empty($policyDeatils->RC) ? $policyDeatils->RC : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 10px;">Registration No.</td>
          <td align="left" valign="top" style="padding-top: 10px;">:</td>
          <td align="left" valign="top" style="padding-top: 10px;font-weight: bold;">{{ !empty($policyDeatils->registration_no) ? $policyDeatils->registration_no : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Registered Owner</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->registured_owner) ? $policyDeatils->registured_owner : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Date of Registration </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->date_of_registration) ? \Carbon\Carbon::parse($policyDeatils->date_of_registration)->format('d/m/Y') : '' }}</td>
        </tr>

        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Chassis No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->chassis_no) ? $policyDeatils->chassis_no : '' }} <span style="padding-left: 10px;font-weight: bold;">Physically Verified : {{ !empty($policyDeatils->chassis_no) ? $policyDeatils->chassis_no : '' }}</span></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Engine No. </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->engine_no) ? $policyDeatils->engine_no : '' }} <span style="padding-left: 10px;font-weight: bold;">Physically Verified : {{ !empty($policyDeatils->engine_no) ? $policyDeatils->engine_no : '' }}</span></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Engine Capacity</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->engine_capacity) ? $policyDeatils->engine_capacity : '' }} {{ !empty($policyDeatils->engine_capacity_unit) ? $policyDeatils->engine_capacity_unit : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Vehicle Make </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->vehicle_make) ? $policyDeatils->vehicle_make : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Vehicle Model</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->vehicle_model) ? $policyDeatils->vehicle_model : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Type of Body </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->body_type) ? $policyDeatils->body_type : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Vehicle Variant</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->vehicle_variant) ? $policyDeatils->vehicle_variant : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Pre- Accident Condition</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->pre_accident_cond) ? $policyDeatils->pre_accident_cond : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Colour of Vehicle</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->vehicle_color) ? $policyDeatils->vehicle_color : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Class of Vehicle</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->vehicle_class) ? $policyDeatils->vehicle_class : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Seating Capacity</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->seating_capacity) ? $policyDeatils->seating_capacity : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Odometer Reading</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->odo_meter_reading) ? $policyDeatils->odo_meter_reading : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Fuel used as per RC</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->fuel) ? $policyDeatils->fuel : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Tax paid upto</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->tax_valid_from_text) ? $policyDeatils->tax_valid_from_text : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Fitness Certificate No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->fitness_number) ? $policyDeatils->fitness_number : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Fitness Certificate validity</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->fitness_valid_from) ? $policyDeatils->fitness_valid_from : '' }} to {{ !empty($policyDeatils->fitness_valid_to) ? $policyDeatils->fitness_valid_to : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Permit No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->permit_number) ? $policyDeatils->permit_number : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Permit validity</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->permit_valid_from) ? $policyDeatils->permit_valid_from : '' }} to {{ !empty($policyDeatils->permit_valid_to) ? $policyDeatils->permit_valid_to : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Permit Type</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->permit_type) ? $policyDeatils->permit_type : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Route / Area of Operation</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->route) ? $policyDeatils->route : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Whether valid for the state in which accident took place?</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->accident_place) ? $policyDeatils->accident_place : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Load Challan No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">Load Chalan <span style="padding-left:80px;">Verified</span></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Gross Vehicle Weight</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->registered_laden_weight) ? $policyDeatils->registered_laden_weight : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Unladen Weight</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->registered_laden_weight) ? $policyDeatils->registered_laden_weight : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom:5px;">If overloaded, whether the overloading is
the cause of accident? </td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom:5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom:5px;">If overloaded</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #00;" colspan="3"></td>
        </tr>
        <tr> 
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold; text-decoration: underline;">Driver Particulars</td>
          <td align="left" valign="top" style="padding-top: 5px;"></td>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold;">{{ !empty($policyDeatils->unladen_weight) ? $policyDeatils->unladen_weight : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 10px;">Name of the Driver</td>
          <td align="left" valign="top" style="padding-top: 10px;">:</td>
          <td align="left" valign="top" style="padding-top: 10px;">{{ !empty($policyDeatils->driver_name) ? $policyDeatils->driver_name : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Driver DOB</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->	driver_dob) ? \Carbon\Carbon::parse($policyDeatils->driver_dob)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Driver Address</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->address) ? $policyDeatils->address : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Relation with the insured </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->relation_with_insurer) ? $policyDeatils->relation_with_insurer : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Driving License Number</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->dl_no) ? $policyDeatils->dl_no : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Valid from</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->dl_valid_upto) ? $policyDeatils->dl_valid_upto : '' }}</td> 
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Issuing Authority</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->issuing_authority) ? $policyDeatils->issuing_authority : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Type of License</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->type_of_dl) ? $policyDeatils->type_of_dl : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Liscense Renewal No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">1111111</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Type of Vehicle Allowed To Drive</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->vehicle_allowed_to_drive) ? $policyDeatils->vehicle_allowed_to_drive : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Endorsement Details</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">1111111</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Badge No.</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">Bage No</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom:5px;">Additional Comments</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom:5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom:5px;">Driver Additional comment</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Accident Particulars</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 10px;">Date and Time of Accident</td>
          <td align="left" valign="top" style="padding-top: 10px;">:</td>
          <td align="left" valign="top" style="padding-top: 10px;">{{ !empty($policyDeatils->date_time_accident) ? \Carbon\Carbon::parse($policyDeatils->date_time_accident)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Place of Accident</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->place_accident) ? $policyDeatils->place_accident : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Place of Survey</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->place_survey) ? $policyDeatils->place_survey : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Date of request for Survey</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->date_of_appointment) ? \Carbon\Carbon::parse($policyDeatils->date_of_appointment)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Date and Time of Survey</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->Survey_Date_time) ? \Carbon\Carbon::parse($policyDeatils->Survey_Date_time)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Date of Under Repair visits</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->date_of_under_repair_visit) ? \Carbon\Carbon::parse($policyDeatils->date_of_under_repair_visit)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Insured's rep. attending survey</td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">Insured</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">Was veh. left unattended after accn</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">{{ !empty($policyDeatils->vehicle_left_unattended) ? \Carbon\Carbon::parse($policyDeatils->vehicle_left_unattended)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;">Anti Theft Device Status </td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">Yes Company fitted</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;font-weight: bold; text-decoration: underline;">Previous Claim Details</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">22222222222222</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Particulars of Police Report</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 10px;">Has accident been report to Police </td>
          <td align="left" valign="top" style="padding-top: 10px;">:</td>
          <td align="left" valign="top" style="padding-top: 10px;">{{ !empty($policyDeatils->accident_reported_to_police) ? $policyDeatils->accident_reported_to_police : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Has Panchnama been carried out </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->panchnama) ? $policyDeatils->panchnama : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;font-weight: bold; text-decoration: underline;">Particulars of Third Party Injury/Loss</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">{{ !empty($policyDeatils->third_party_injury) ? $policyDeatils->third_party_injury : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;font-weight: bold; text-decoration: underline;">Injury to Driver/Occupant (If any)</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">{{ !empty($policyDeatils->injury_to_driver) ? $policyDeatils->injury_to_driver : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Spot Survey</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;">Spot Survey By </td>
          <td align="left" valign="top" style="padding-top: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDeatils->spot_survey_by) ? $policyDeatils->spot_survey_by : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">Spot Survey report received on</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">:</td>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px;">{{ !empty($policyDeatils->spot_survey_date) ? \Carbon\Carbon::parse($policyDeatils->spot_survey_date)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="font-weight: bold; text-decoration: underline;" colspan="3">Load/Passenger Details</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;padding-bottom: 5px;" colspan="3">{{ !empty($policyDeatils->passenger_detail) ? $policyDeatils->passenger_detail : '' }}
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Cause and Nature of Accident</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; line-height: 28px; text-align: justify;" colspan="3">{{ !empty($policyDeatils->accident_brief_description) ? $policyDeatils->accident_brief_description : '' }} </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;" colspan="3">Actions of Survey</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; line-height: 28px; text-align: justify;" colspan="3">{{ !empty($policyDeatils->action_of_survey) ? $policyDeatils->action_of_survey : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; font-weight: bold; text-decoration: underline;" colspan="3">Particulars of Loss/Damages</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; line-height: 28px; text-align: justify;" colspan="3">{{ !empty($policyDeatils->particular_of_damage) ? $policyDeatils->particular_of_damage : '' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;  padding-bottom: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Original Estimate</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; line-height: 28px; text-align: justify;" colspan="3">Estimate no. Estimate No dated 01/01/2024 for Rs.0.00 was submitted by Ace Honda , Naksha Enterprises Pvt Ltd..</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Observation</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; padding-bottom: 5px; line-height: 28px; text-align: justify;" colspan="3">OBERVATION</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-top: 1px solid #000;" colspan="3"></td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;  padding-bottom: 5px;font-weight: bold; text-decoration: underline;" colspan="3">Remark</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px; line-height: 28px; text-align: justify;" colspan="3">This is a dynamic section</td>
        </tr>

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
          <td align="left" valign="top" style="padding-top:5px;font-weight: bold;" colspan="3">SURVEYOR NAME</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 5px;" colspan="3">Engineer, Surveyor & Investigator</td>
        </tr>

        <tr>
          <td align="left" valign="top" style="padding-top: 20px;font-weight: bold;" colspan="3">Enclosures</td>
        </tr>

      </tbody>
    </table>
  </div>
</body>

</html>