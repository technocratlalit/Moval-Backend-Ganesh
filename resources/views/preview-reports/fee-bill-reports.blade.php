<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GST Tax Invoice Survey Fee Bill</title>
    <style>
        @font-face {
            font-family: 'verdana';
            font-weight: normal;
            font-style: normal;
            font-variant: normal;
            src: url("fonts/verdana.ttf") format('truetype');
        }

        body {
            font-family: 'verdana';
        }

        th {
            font-family: verdana !important;
        }
        td, label {
            display: block;
        }
    </style>
</head>

<body>
<div style="font-family: 'Roboto' !important;font-size: 14px; margin: 0px 0px; ">
    @if (!empty($letter_head_img))
        <div>
            <img src="{{ asset('/public/storage/'.$letter_head_img) }}" style="width:auto;">
        </div>
    @endif

    <div>
        <div style="border-top: 3px solid #000;"></div>
    </div>

    @if(isset($job->getClient) && !empty($job->getClient->contact_details))
        <div style="text-align: right;">Surveyor ID : {{ $job->getClient->contact_details }}</div>
    @endif
    @if(isset($policyDetails->cgst_percentage) && trim($policyDetails->cgst_percentage) !== ''  || isset($policyDetails->igst_percentage) && trim($policyDetails->igst_percentage) !== '' || isset($policyDetails->sgst_percentage) && trim($policyDetails->sgst_percentage) !== '')
        <div style="font-weight: bold; text-align: center; padding-top:5px;">
            <string> GST Tax Invoice Survey Fee Bill</string>
        </div>
    @else
        <div style="font-weight: bold; text-align: center;"><b>Professional Survey Fee Bill </b></div>
    @endif

    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top: 20px; font-size: 14px; font-family: Verdana, sans-serif;">
        <tbody>
        <tr>
            <td style="width: 77%">
                <span style="font-weight: bold; padding-right:15px; padding-bottom: 20p; margin-bottom:20px"> Bill No.:</span>
                <span style="padding-left:10px;">{{ isset($policyDetails->bill_no) ? $policyDetails->bill_no : '' }}</span>
            </td>
            <td style="width: 23%; text-align: right;">
                <span style="font-weight: bold;"> Date:</span> <span >{{ isset($policyDetails->bill_date) ? \Carbon\Carbon::parse($policyDetails->bill_date)->format('d/m/Y') : '' }}</span>
            </td>
        </tr>
        </tbody>
    </table>

    <div style="padding-top: 20px;">
        @if($policyDetails->issued_to =='1')
            <div style="font-weight: bold;">{{ isset($policyDetails->appointing_office_name) ? $policyDetails->appointing_office_name : '' }}</div>
            {{ isset($policyDetails->appointing_office_address) ? $policyDetails->appointing_office_address : '' }}</div>
    @if(!empty($policyDetails->appointing_gst_no))
        <div>GST No. : <span style="font-weight: bold;">{{ isset($policyDetails->appointing_gst_no) ? $policyDetails->appointing_gst_no : '' }}</span></div>
    @endif
    @elseif($policyDetails->issued_to =='2')
        <div style="font-weight: bold;">{{ isset($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</div>
        <div>{{ isset($policyDetails->insured_address) ? $policyDetails->insured_address : '' }}</div>
    @elseif($policyDetails->issued_to =='3')
        <div style="font-weight: bold;">{{ isset($policyDetails->office_name) ? $policyDetails->office_name : '' }}</div>
        <div>{{ isset($policyDetails->office_address) ? $policyDetails->office_address : '' }}</div>
        @if(!empty($policyDetails->gst_no))
            <div>GST No. : <span style="font-weight: bold;">{{ isset($policyDetails->gst_no) ? $policyDetails->gst_no : '' }}</span></div>
        @endif
    @elseif($policyDetails->issued_to =='4')
        <div style="font-weight: bold;">{{ isset($policyDetails->operating_office_name) ? $policyDetails->operating_office_name : '' }}</div>
        <div>{{ isset($policyDetails->operating_office_address) ? $policyDetails->operating_office_address : '' }}</div>
        @if(!empty($policyDetails->operation_gst_no))
            <div>GST No. : <span style="font-weight: bold;">{{ isset($policyDetails->operation_gst_no) ? $policyDetails->operation_gst_no : '' }}</span></div>
        @endif
    @elseif($policyDetails->issued_to =='5')
        <div style="font-weight: bold;">{{ isset($policyDetails->workshop_branch_name) ? $policyDetails->workshop_branch_name : '' }}</div>
        <div>{{ isset($policyDetails->workshop_branch_address) ? $policyDetails->workshop_branch_address : '' }}</div>
        @if(!empty($policyDetails->workshop_gst_no))
            <div>GST No. : <span style="font-weight: bold;">{{ isset($policyDetails->workshop_gst_no) ? $policyDetails->workshop_gst_no : '' }}</span></div>
        @endif
    @endif
</div>

<div>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="padding-top:20px; font-size: 14px; font-family: Verdana, sans-serif;">
        <tbody>
        <tr>
            <td align="left" valign="top" style="font-weight: bold; width: 10%">Subject :</td>
            <td align="left" valign="top" style="width: 30%;">Survey of vehicle, Regn No.</td>
            <td align="left" valign="top" style="width: 5%;">:</td>
            <td align="left" valign="top"style="width: 35%;">{{ !empty($policyDetails->registration_no) ? $policyDetails->registration_no : '' }}</td>
            <td align="left" valign="top"style="width: 20%;">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
            <td align="left" valign="top" style="padding-top: 5px;">Date of Accident</td>
            <td align="left" valign="top" style="padding-top: 5px;">:</td>
            <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->date_time_accident) ? \Carbon\Carbon::parse($policyDetails->date_time_accident)->format('d/m/Y') : '' }}</td>
            <td align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
            <td align="left" valign="top" style="padding-top: 5px;">Date of Survey</td>
            <td align="left" valign="top" style="padding-top: 5px;">:</td>
            <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->Survey_Date_time) ? \Carbon\Carbon::parse($policyDetails->Survey_Date_time)->format('d/m/Y') : '' }}</td>
            <td align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
            <td align="left" valign="top" style="padding-top: 5px;">Policy Number</td>
            <td align="left" valign="top" style="padding-top: 5px;">:</td>
            <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->policy_no) ? $policyDetails->policy_no : '' }}</td>
            <td align="left" valign="top">&nbsp;</td>
        </tr>
        @if(!empty($policyDetails->claim_no))
            <tr>
                <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                <td align="left" valign="top" style="padding-top: 5px;">Claim Number</td>
                <td align="left" valign="top" style="padding-top: 5px;">:</td>
                <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->claim_no) ? $policyDetails->claim_no : '' }}</td>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
        @endif
        <tr>
            <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
            <td align="left" valign="top" style="padding-top: 5px;">Place of Survey</td>
            <td align="left" valign="top" style="padding-top: 5px;">:</td>
            <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->place_survey) ? $policyDetails->place_survey : '' }}</td>
            <td align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
            <td align="left" valign="top" style="padding-top: 5px;">Insured</td>
            <td align="left" valign="top" style="padding-top: 5px;">:</td>
            <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->insured_name) ? $policyDetails->insured_name : '' }}</td>
            <td align="left" valign="top">&nbsp;</td>
        </tr>

        @if($policyDetails->issued_to !='3')
            <tr>
                <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                <td align="left" valign="top" style="padding-top: 5px;">Insurer</td>
                <td align="left" valign="top" style="padding-top: 5px;">:</td>
                <td align="left" valign="top" style="padding-top: 5px;">{{ !empty($policyDetails->office_name) ? $policyDetails->office_name : '' }}<br/>{{ !empty($policyDetails->office_address) ? $policyDetails->office_address : '' }}</td>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
        @endif
        <tr>
            <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
            <td colspan="3" style="border-bottom: 2px solid #000; font-weight: bold;">PARTICULARS</td>
            <td style="border-bottom: 2px solid #000; font-weight: bold; text-align: right;"><span style="font-weight: bold">AMOUNT </span> (<span style="font-weight: 400;">in</span> <span style="font-weight: bold;">&#8377;</span>)</td>
        </tr>
        @if(isset($policyDetails->surveyFee) && count($policyDetails->surveyFee) > 0)
            @foreach($policyDetails->surveyFee as $surveyfeeValue)
                <tr>
                    <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                    <td colspan="3" style="padding-top: 5px;">{{ !empty($surveyfeeValue['surveyType']) ? $surveyfeeValue['surveyType'] : '-' }}</td>
                    <td style="padding-top: 5px; font-weight: bold; text-align: right;">{{ (!empty($surveyfeeValue['feeAmount']) && $surveyfeeValue['feeAmount'] > 0) ? number_format_custom($surveyfeeValue['feeAmount']) : 0.00 }}</td>
                </tr>
            @endforeach
        @endif
        @if(isset($policyDetails->conveyanceFee) && count($policyDetails->conveyanceFee) > 0)
            <tr>
                <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                <td colspan="4" style="padding-top: 5px; font-weight: bold;">Conveyance</td>
            </tr>
            @foreach($policyDetails->conveyanceFee as $conveyfeeValue)
                <tr>
                    <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                    <td colspan="2" style="padding-top: 5px; padding-left: 5px; padding-right: 10px;">{{ !empty($conveyfeeValue['particular']) ? $conveyfeeValue['particular'] : '-' }}</td>
                    <td style="padding-top: 5px;">{{ !empty($conveyfeeValue['noOfVisit']) ? $conveyfeeValue['noOfVisit'] : 0 }} {{ !empty($conveyfeeValue['conveyance_unit']) ? $conveyfeeValue['conveyance_unit'] : ' ' }} @ {{ (!empty($conveyfeeValue['perVisitRate']) && $conveyfeeValue['perVisitRate'] > 0) ? number_format_custom($conveyfeeValue['perVisitRate']) : 0.00 }}</td>
                    <td style="padding-top: 5px; font-weight: bold; text-align: right;">{{ (!empty($conveyfeeValue['totalAmount']) && $conveyfeeValue['totalAmount'] > 0) ? number_format_custom($conveyfeeValue['totalAmount']) : 0.00 }}</td>
                </tr>
            @endforeach
        @endif
        @if(isset($policyDetails->vehiclePhotographs) && count($policyDetails->vehiclePhotographs) > 0 && $policyDetails->vehiclePhotographs[0]['particular']!="" && $policyDetails->vehiclePhotographs[0]['totalAmount']!="")
            <tr>
                <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                <td colspan="4" style="padding-top: 5px; font-weight: bold;">Photographs</td>
            </tr>
            @foreach($policyDetails->vehiclePhotographs as $vehiclePhotoValue)
                <tr>
                    <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                    <td colspan="2" style="padding-top: 5px; padding-left: 5px; padding-right: 10px;">{{ !empty($vehiclePhotoValue['particular']) ? $vehiclePhotoValue['particular'] : '-' }}</td>
                    <td style="padding-top: 5px;">{{ !empty($vehiclePhotoValue['noOfPhotos']) ? $vehiclePhotoValue['noOfPhotos'] : 0 }} {{ !empty($vehiclePhotoValue['CD']) ? $vehiclePhotoValue['CD'] : ' ' }} @ {{ (!empty($vehiclePhotoValue['rate']) && $vehiclePhotoValue['rate'] > 0) ? number_format_custom($vehiclePhotoValue['rate']) : 0.00 }}</td>
                    <td style="padding-top: 5px; font-weight: bold; text-align: right;">{{ (!empty($vehiclePhotoValue['totalAmount']) && $vehiclePhotoValue['totalAmount'] > 0) ? number_format_custom($vehiclePhotoValue['totalAmount']) : 0.00 }}</td>
                </tr>
            @endforeach
        @endif
        @if(isset($policyDetails->miscellaneous) && count($policyDetails->miscellaneous) > 0 && $policyDetails->miscellaneous[0]['description']!="" && $policyDetails->miscellaneous[0]['amount']!="")
            <tr>
                <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                <td colspan="4" style="padding-top: 5px; font-weight: bold;">Miscellaneous</td>
            </tr>
            @foreach($policyDetails->miscellaneous as $miscellValue)
                <tr>
                    <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                    <td colspan="2" style="padding-top: 2px; padding-left: 2px; padding-right: 5px;">{{ !empty($miscellValue['description']) ? $miscellValue['description'] : '-' }} </td>
                    <td style="padding-top: 2px;"></td>
                    <td style="padding-top: 2px; font-weight: bold; text-align: right;">{{ (!empty($miscellValue['amount']) && $miscellValue['amount'] > 0) ? number_format_custom($miscellValue['amount']) : 0.00 }} </td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
            <td colspan="3" style="font-weight: bold; text-align: right; border-top: 1px solid #000;">Sub Total</td>
            <td style="font-weight: bold; text-align: right; border-top: 1px solid #000;">{{ (!empty($policyDetails->amount_before_tax) && $policyDetails->amount_before_tax > 0) ? number_format_custom($policyDetails->amount_before_tax) : 0.00 }}</td>
        </tr>
        @if(isset($policyDetails->cgst_percentage) && trim($policyDetails->cgst_percentage) !== '')
            <tr>
                <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                <td colspan="3" style="padding-top: 1px; font-weight: bold; text-align: right;">CGST<span style="font-weight: 400;"> @ {{ !empty($policyDetails->cgst_percentage) ? $policyDetails->cgst_percentage : 0.00 }}%</span></td>
                <td style="padding-top: 1px; font-weight: bold; text-align: right;">{{ !empty($policyDetails->gst_amount/2) ? number_format_custom($policyDetails->gst_amount/2) : 0.00 }}</td>
            </tr>
        @endif
        @if(isset($policyDetails->sgst_percentage) && trim($policyDetails->sgst_percentage) !== '')
            <tr>
                <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                <td colspan="3" style="padding-top: 1px; font-weight: bold; text-align: right;">SGST<span style="font-weight: 400;"> @ {{ !empty($policyDetails->sgst_percentage) ? $policyDetails->sgst_percentage : 0.00 }}%</span></td>
                <td style="padding-top: 1px; font-weight: bold; text-align: right;">{{ !empty($policyDetails->gst_amount/2) ? number_format_custom($policyDetails->gst_amount/2) : 0.00 }}</td>
            </tr>
        @endif
        @if(isset($policyDetails->igst_percentage) && trim($policyDetails->igst_percentage) !== '')
            <tr>
                <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                <td colspan="3" style="padding-top: 1px; font-weight: bold; text-align: right;">IGST<span style="font-weight: 400;"> @ {{ !empty($policyDetails->igst_percentage) ? $policyDetails->igst_percentage : 0.00 }}%</span></td>
                <td style="padding-top: 1px; font-weight: bold; text-align: right;">{{ !empty($policyDetails->gst_amount) ? number_format_custom($policyDetails->gst_amount) : 0.00 }}</td>
            </tr>
        @endif
        <tr>
            <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
            <td colspan="3" style="text-align: right; font-weight: bold; padding-top: 1px; padding-bottom: 1px; border-bottom: 1px solid #000; border-top: 1px solid #000;">Net to Receive</td>
            <td style="font-weight: bold; text-align: right; padding-top: 1px; padding-bottom: 1px; border-bottom: 1px solid #000; border-top: 1px solid #000;">{{ (!empty($policyDetails->amount_after_tax) && $policyDetails->amount_after_tax > 0) ? number_format_custom($policyDetails->amount_after_tax) : 0.00 }}</td>
        </tr>
        @php
            $amountAfterTax = !empty($policyDetails->amount_after_tax) ? $policyDetails->amount_after_tax : 0.00;
            $amountInWords = convertNumberToWords(number_format_custom($amountAfterTax));
        @endphp
        <tr>
            <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
            <td colspan="4" style="text-align: right; font-weight: bold; padding-top: 1px; padding-bottom: 1px;">Rupees {{ ucfirst($amountInWords) }} Only</td>
        </tr>
        @if(!empty($policyDetails->comment))
            @php
                $ckeditorData = preg_replace('/<p\b[^>]*>/', '<tr><td align="left" valign="top" style="font-weight: bold;">&nbsp;</td><td colspan="4">',$policyDetails->comment);
                $ckeditorData = preg_replace('/<\/p>/', '</tr></td>', $ckeditorData);
                echo $ckeditorData;
            @endphp
        @endif

        @if(isset($policyDetails->get_bank_details) && !empty($policyDetails->get_bank_details) && isset($policyDetails->get_bank_details->bank_code) && !empty($policyDetails->get_bank_details->bank_code))
            <tr>
                <td align="left" valign="top" style="font-weight: bold;">&nbsp;</td>
                <td colspan="4" style="text-align: left; padding-top: 5px; padding-bottom: 2px; font-weight: bold; text-decoration: underline;">Bank Account Details</td>
            </tr>
            <tr>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top" style="font-weight: bold">Bank Name</td>
                <td align="left" valign="top" style="font-weight: bold">:</td>
                <td align="left" valign="top">{{ !empty($policyDetails->get_bank_details->bank_name) ? $policyDetails->get_bank_details->bank_name : '-' }}</td>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top" style="font-weight: bold">Branch Name</td>
                <td align="left" valign="top" style="font-weight: bold">:</td>
                <td align="left" valign="top">{{ !empty($policyDetails->get_bank_details->branch_address) ? $policyDetails->get_bank_details->branch_address : '-' }}</td>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top" style="font-weight: bold">A/c. No.</td>
                <td align="left" valign="top" style="font-weight: bold">:</td>
                <td align="left" valign="top">{{ !empty($policyDetails->get_bank_details->account_number) ? $policyDetails->get_bank_details->account_number : '-' }}</td>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
            @if(isset($policyDetails->get_bank_details->ifsc) && trim($policyDetails->get_bank_details->ifsc) !== '')
                <tr>
                    <td align="left" valign="top">&nbsp;</td>
                    <td align="left" valign="top" style="font-weight: bold">IFSC Code</td>
                    <td align="left" valign="top" style="font-weight: bold">:</td>
                    <td align="left" valign="top">{{ !empty($policyDetails->get_bank_details->ifsc) ? $policyDetails->get_bank_details->ifsc : '-' }}</td>
                    <td align="left" valign="top">&nbsp;</td>
                </tr>
            @endif
            @if(isset($policyDetails->get_bank_details->micr) && trim($policyDetails->get_bank_details->micr) !== '')
                <tr>
                    <td align="left" valign="top">&nbsp;</td>
                    <td align="left" valign="top" style="font-weight: bold">MICR Code</td>
                    <td align="left" valign="top" style="font-weight: bold">:</td>
                    <td align="left" valign="top">{{ !empty($policyDetails->get_bank_details->micr) ? $policyDetails->get_bank_details->micr : '-' }}</td>
                    <td align="left" valign="top">&nbsp;</td>
                </tr>
            @endif
        @endif
        <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td colspan="4" valign="top" style="vertical-align: middle;">
                <br>
                @if (!empty($signature_img))
                    <img src="{{ asset('public/storage/'.$signature_img) }}" style="width:100px;">
                @else
                    <p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                    </p>
                @endif
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">&nbsp;</td>
            <td colspan="2" align="left" valign="top" style="font-weight: bold; padding-top: 6px;">{{ !empty($adminHeaderFooter->name) ? $adminHeaderFooter->name : '' }}</td>
            <td colspan="2" align="right" valign="top" style="font-weight: bold; padding-top: 6px;">{{ (isset($policyDetails->cash_receipted) && trim($policyDetails->cash_receipted) !== '') ? 'Cash Receipted' : 'Pre Receipted' }}</td>
        </tr>
        </tbody>
    </table>
</div>
</div>
</body>

</html>