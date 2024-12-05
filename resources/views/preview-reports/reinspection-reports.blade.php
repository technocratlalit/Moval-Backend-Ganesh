<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Re Inspectrion Report</title>
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

<div style="font-family: 'Verdana' !important; font-size: 7px;  ">
  @if ($letter_head_img)
    <img src="{{ asset('public/storage/'.$letter_head_img) }}" style="width:auto;">
  @else
    <p>No letter head image available</p>
  @endif
  <div>
    <div style="border-top: 3px solid #000;"></div>
  </div>

  <div>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top: 20px; font-size: 14px; font-family: 'Verdana' !important;">
      <tbody>
      <tr>
        <td align="left" valign="top" style="width: 60%; padding-right: 10px; padding-left: 10px; font-family: 'Verdana' !important;">
          <span style="font-weight: bold;">Ref No.:</span> {{ isset($reinspectionDetails->inspection_reference_no) ? $reinspectionDetails->inspection_reference_no : '' }}
        </td>
        <td align="right" valign="top" style="width: 40%; padding-right: 10px; padding-left: 10px; font-family: 'Verdana' !important; ">
          <span style="font-weight: bold;">Date:</span> {{ !empty($reinspectionDetails->submission_date) ? \Carbon\Carbon::parse($reinspectionDetails->submission_date)->format('d/m/Y') : '' }}
        </td>
      </tr>
      </tbody>
    </table>

    <table width="100%" class="padding-bottom: 20" cellpadding="0" cellspacing="0" border="0" align="center">
      <tbody>
      <tr>
        <td align="center" valign="top" style="padding-top: 5px; font-weight: bold;">RE-INSPECTION REPORT</td>
      </tr>
      <tr>
        <td align="left" valign="top" style="font-weight: bold; padding-top: 10px; padding-left: 50px;">{{ isset($reinspectionDetails->office_name) ? $reinspectionDetails->office_name : '' }}</td>
      </tr>
      <tr>
        <td align="left" valign="top" style="padding-left: 50px; border-bottom: 2px solid #000; padding-bottom: 5px;">{{ isset($reinspectionDetails->office_address) ? $reinspectionDetails->office_address : '' }}</td>
      </tr>
      </tbody>
    </table>
    <br>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
      <tbody>
      <tr>
        <td style="padding-left: 50px; padding-bottom:5px;">
          <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
            <tbody>
            <tr>
              <td  align="left" valign="top" style="width: 30%; padding-top: 5px; font-weight: bold;">Policy No.</td>
              <td  align="left" valign="top" style="width: 10%; padding-top: 5px;">:</td>
              <td  align="left" valign="top" style="width: 60%; padding-top: 5px;">{{ !empty($reinspectionDetails->policy_no) ? $reinspectionDetails->policy_no : '' }}</td>
            </tr>
            <tr>
              <td  align="left" valign="top" style="padding-top: 5px; font-weight: bold;">Insured</td>
              <td  align="left" valign="top" style="padding-top: 5px;">:</td>
              <td  align="left" valign="top" style="padding-top: 5px;">{{ !empty($reinspectionDetails->insured_name) ? $reinspectionDetails->insured_name : '' }}, {{ !empty($reinspectionDetails->insured_address) ? $reinspectionDetails->insured_address : '' }}</td>
            </tr>
            <tr>
              <td  align="left" valign="top" style="padding-top: 5px; font-weight: bold;">Vehicle Regn. No.</td>
              <td  align="left" valign="top" style="padding-top: 5px;">:</td>
              <td  align="left" valign="top" style="padding-top: 5px;">{{ !empty($reinspectionDetails->registration_no) ? $reinspectionDetails->registration_no : '' }}</td>
            </tr>
            <tr>
              <td  align="left" valign="top" style="padding-top: 5px; font-weight: bold;">Chassis No. </td>
              <td  align="left" valign="top" style="padding-top: 5px;">:</td>
              <td  align="left" valign="top" style="padding-top: 5px;">{{ !empty($reinspectionDetails->vehicle_chassis_no) ? $reinspectionDetails->vehicle_chassis_no : '' }}</td>
            </tr>
            <tr>
              <td  align="left" valign="top" style="padding-top: 5px; font-weight: bold;">Engine No.</td>
              <td  align="left" valign="top" style="padding-top: 5px;">:</td>
              <td  align="left" valign="top" style="padding-top: 5px;">{{ !empty($reinspectionDetails->vehicle_engine_no) ? $reinspectionDetails->vehicle_engine_no : '' }}</td>
            </tr>
            <tr>
              <td  align="left" valign="top" style="padding-top: 5px; font-weight: bold;">Date of Reinspection</td>
              <td  align="left" valign="top" style="padding-top: 5px;">:</td>
              <td  align="left" valign="top" style="padding-top: 5px;">{{ !empty($reinspectionDetails->reinspection_date) ? \Carbon\Carbon::parse($reinspectionDetails->reinspection_date)->format('d/m/Y') : '' }}</td>
            </tr>
            <tr>
              <td  align="left" valign="top" style="padding-top: 5px; font-weight: bold;">Place of Reinspection</td>
              <td  align="left" valign="top" style="padding-top: 5px;">:</td>
              <td  align="left" valign="top" style="padding-top: 5px;">{{ !empty($reinspectionDetails->place_reinspection) ? $reinspectionDetails->place_reinspection : '' }}</td>
            </tr>
            <tr>
              <td  align="left" valign="top" style="padding-top: 5px; font-weight: bold;">Date of Accident</td>
              <td  align="left" valign="top" style="padding-top: 5px;">:</td>
              <td  align="left" valign="top" style="padding-top: 5px;">{{ !empty($reinspectionDetails->date_time_accident) ? \Carbon\Carbon::parse($reinspectionDetails->date_time_accident)->format('d/m/Y h:i A') : '' }}</td>
            </tr>
            <tr>
              <td  align="left" valign="top" style="padding-top: 5px; font-weight: bold;">Net Loss Amount</td>
              <td  align="left" valign="top" style="padding-top: 5px;">:</td>
              <td  align="left" valign="top" style="padding-top: 5px;">{{ !empty($reinspectionDetails->alltotalass) ? $reinspectionDetails->alltotalass : 0.00 }}</td>
            </tr>
            <tr>
              <td  align="left" valign="top" style="padding-top: 5px; font-weight: bold;">Salvage Value</td>
              <td  align="left" valign="top" style="padding-top: 5px;">:</td>
              <td  align="left" valign="top" style="padding-top: 5px;">{{ !empty($reinspectionDetails->SalvageAmt) ? $reinspectionDetails->SalvageAmt : 0.00 }}</td>
            </tr>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td style="border-bottom: 2px solid #000;">&nbsp;</td>
      </tr>
      </tbody>
    </table>

    <br>

    <table width="100%"  cellpadding="0" cellspacing="0" border="0" align="center">
      <tbody>
      <tr>
        <td  align="left" valign="top" style="padding-top: 10px;">{!! !empty($reinspectionDetails->observation) ? $reinspectionDetails->observation : '' !!}</td>
      </tr>
      @if(!empty($reinspectionDetails->remarks_status))
        <tr>
          <td  align="left" valign="top" style="padding: 5px 0px  ; text-align: justify;">{!! !empty($reinspectionDetails->list_allowed_parts) ? $reinspectionDetails->list_allowed_parts : '' !!}</td>
        </tr>
      @endif
      </tbody>
    </table>
    <br>
    @if(!empty($reinspectionDetails->list_allow_status))
      <table width="100%" id="design" cellpadding="0" cellspacing="0" align="center">
        <tbody>
        <tr>
          <td  align="center" valign="top" style="width: 13%; padding: 3px; font-weight: bold; border-left: 1px solid #000; border-top: 1px solid #000;">Sr. No.</td>
          <td  align="left" valign="top" style="width: 30%; padding: 3px; font-weight: bold; border-top: 1px solid #000;">Description of Parts</td>
          <td  align="left" valign="top" style="width: 20%; padding: 3px; font-weight: bold; border-top: 1px solid #000;">Replacement</td>
          <td  align="left" valign="top" style="width: 15%; padding: 3px; font-weight: bold; border-top: 1px solid #000;">Salvage</td>
          <td  align="left" valign="top" style="width: 22%; padding: 3px; font-weight: bold; border-top: 1px solid #000;">Remarks</td>
        </tr>

        @if(isset($reinspectionDetails->allowed_parts))
          @php
            $allowParts = json_decode($reinspectionDetails->allowed_parts, true);
          @endphp
          @foreach($allowParts as $index=>$value)

            <tr>
              <td  align="center" valign="top" style="padding: 3px; border-left: 1px solid #000;">{{$index + 1}}</td>
              <td  align="left" valign="top" style="padding: 3px;">{{ !empty($value['description']) ? $value['description'] : ' ' }}</td>
              <td  align="left" valign="top" style="padding: 3px;">{{ !empty($value['replacement']) ? $value['replacement'] : ' ' }}</td>
              <td  align="left" valign="top" style="padding: 3px;">{{ !empty($value['salvage']) ? $value['salvage'] : ' ' }}</td>
              <td  align="left" valign="top" style="padding: 3px;">{{ !empty($value['remarks']) ? $value['remarks'] : ' ' }}</td>
            </tr>
          @endforeach
        @endif
        </tbody>
      </table>
    @endif
    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
      <tbody>
      <tr>
        <td style="padding-top: 10px; padding-bottom: 5px; text-align: start;">{!! !empty($reinspectionDetails->remarks) ? $reinspectionDetails->remarks : '' !!}</td>
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
        <td align="left" valign="top" style="padding-top: 5px;"  colspan="3">{{ !empty($adminHeaderFooter->designation) ? $adminHeaderFooter->designation : '-' }}</td>
      </tr>
      </tbody>
    </table>
  </div>

</div>
</body>
</html>