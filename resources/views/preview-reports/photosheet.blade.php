<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PhotoSheet</title>
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
    </style>
</head>

<body>



@if($photoSheetType == '2X2_Photosheet')
<div style="font-family: 'Verdana' !important; font-size: 14px; margin: 0px -20px; "> 
      <div style="border-bottom: 2px solid #000; padding: 5px 0px;">Regn No.: <strong>{{ isset($policyDetails[0]['registration_no']) ? $policyDetails[0]['registration_no'] : '' }}</strong>, Insured Name: <strong>{{ isset($policyDetails[0]['insured_name']) ? $policyDetails[0]['insured_name'] : '' }}</strong>, Date of Accn.: <strong>{{ isset($policyDetails[0]['date_time_accident']) ? \Carbon\Carbon::parse($policyDetails[0]['date_time_accident'])->format('d/m/Y') : '' }}</strong>
      </div>
</div>

<div style="font-family: 'Verdana' !important; font-size: 14px; margin: 0px -20px;">    
    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" id="design" >
      <tbody>
        @if(count($uploadedFiles) > 0)
            @foreach(array_chunk($uploadedFiles, 2) as $row)
                <tr>
                    @foreach($row as $file)
                        <td style="width: 50%; padding-top: 10px; border-left: 1px solid #000;">
                            <img src="{{$file}}" alt="" style="width:500px; height:300px;">
                        </td>
                    @endforeach
                </tr>
            @endforeach
        @endif
      
      </tbody>
    </table>
</div>

@elseif($photoSheetType == '3X2_Photosheet')

<!-- <div style="font-family: 'Verdana' !important; font-size: 14px; margin: 0px -20px;">
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
  </div> -->


    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" id="design">
      <tbody>
        @if(count($uploadedFiles) > 0)
            @foreach(array_chunk($uploadedFiles, 2) as $row)
                <tr>
                    @foreach($row as $file)
                        <td style="width: 50%; padding-top: 10px; border-left: 1px solid #000;">
                            <img src="{{$file}}" alt="" style="width:500px; height:300px;">
                        </td>
                    @endforeach
                </tr>
            @endforeach
        @endif    
      </tbody>
    </table>

</div>

@elseif($photoSheetType == '4X2_Photosheet')
<div style="font-family: 'Verdana' !important; font-size: 14px; margin: 0px -20px;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" id="design">
      <tbody>
        @if(count($uploadedFiles) > 0)
            @foreach(array_chunk($uploadedFiles, 2) as $row)
                <tr>
                    @foreach($row as $file)
                        <td style="width: 50%; padding-top: 10px; border-left: 1px solid #000;">
                            <img src="{{$file}}" alt="" style="width:500px; height:300px;">
                        </td>
                    @endforeach
                </tr>
            @endforeach
        @endif  
      </tbody>
    </table>
</div>

@else
 <p>Not Found Reprot</p>
@endif

</body>
</html>
