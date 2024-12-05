<html lang="en">

<head>

    <title>Work Approval Sheet</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        .col2 {
            color: rbg(0, 0, 0);
            font-weight: bold;
            font-family: 'Roboto';
        }

        .table>tbody>tr>td {
            padding: 0px 8px;
        }

        table {
            border-bottom: 2px solid #428a5a !important;
            width: 100%;
            max-width: 1300px;
            table-layout: fixed;
        }

        p {
            margin: -5px !important;
        }

        th,
        td {
            padding: 10px;
            text-align: left;

        }

        .colth {
            font-size: 10px;
        }

        .coltd {
            /* font-family: arial,sans-serif;*/
            font-size: 8px;
        }

        th {
            border-bottom: 2px solid #428a5a !important;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="">
        <div class="container mt-5">
            <div class="row">
                <div class="col-sm-12">
                    <div class="">
                        <div class="card-body">
                            <div class="row fw-bold">
                                <div class="col-sm-12">
                                    <p class="text-right">Generated On {{ date('jS F Y H:i') }}</p>
                                </div>
                            </div>
                            <!--<div class="row fw-bold text-right">
                                   <img height="100px" src="{{ public_path() }}/test.png" alt="ADMIN LOGO" />
                                     <h5 class="">ADMIN LOGO REQUIRED HERE</h5>
                                    
                                </div>-->

                            <div class="row fw-bold">
                                <div class="col-sm-12">1
                                    <img style="width:250px;padding-top:30px;padding-bottom:20px"
                                        src="{{ public_path() }}/wa.png" alt="ADMIN LOGO" />

                                    <!-- <h2 class="text-primary" style="font-family:'Roboto';">WORK APPROVAL</h2>-->
                                </div>
                            </div>

                            <div class="row fw-bold">
                                <div class="col-sm-12 text-center">
                                    <img style="width:250px;padding-bottom:5px" src="{{ public_path() }}/status.png" />
                                    <!-- <h4 class="text-success text-center" style="font-family: Roboto;"> STATUS: Approved by CSM</h4>-->
                                </div>
                            </div>
                            @php

                                // $insepection =$data['insepection'];
                                // $files =$data['files'];
                                // $assisment =$data['assisment'];
                                // $taxsetting =$data['taxsetting'];

                                // echo "<pre>"; print_r($insepection); die;
                            @endphp

                            <table class="table table-bordered border-gray table-center"
                                style="width: 100%; max-width: 1300px; table-layout: fixed; border:2px solid gray">
                                <tbody>
                                    <tr style=" border:2px solid gray">
                                        <td class="col1 colth">
                                            <p class="col2">Insurer Name</p><span
                                                class="text-primary">{{ isset($insepection['client_id']) ? getclientname($insepection['client_id']) : '' }}</span>
                                        </td>
                                        <td class="colth" style=" border:2px solid gray">
                                            <p class="col2">Branch</p><span
                                                class="text-primary">{{ isset($insepection['client_branch_id']) ? getclientbranchname($insepection['client_branch_id']) : '' }}</span>
                                        </td>
                                        <td class="colth">
                                            <p class="col2">Insured Name</p><span
                                                class="text-primary">{{ isset($insepection['insured_name']) ?? $insepection['insured_name'] }}</span>
                                        </td>
                                    </tr>
                                    <tr style=" border:2px solid gray">
                                        <td class="col1 colth">
                                            <p class="col2">Insured Phone</p><span
                                                class="text-primary">{{ isset($insepection['insured_mobile_no']) ?? $insepection['insured_mobile_no'] }}</span>
                                        </td>
                                        </td>
                                        <td class="col2 colth" style=" border:2px solid gray">
                                            <p class="col2">Insured Address</p><span
                                                class="text-primary">{{ isset($insepection['insured_address']) ?? $insepection['insured_address'] }}</span>
                                        </td>
                                        <td class="colth">
                                            <p class="col2">Workshop Name</p><span
                                                class="text-primary">{{ isset($insepection['workshop_name']) ?? getworkshopnamebyid($insepection['workshop_name']) }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>



                            <table class="table table-bordered border-dark table-center"
                                style="width: 100%; max-width: 1300px; table-layout: fixed; border:2px solid gray">
                                <tbody>
                                    <tr style=" border:2px solid gray">
                                        <td class="col1 colth"><span class="col2">Vehicle Number</span><br><span
                                                class="text-primary">
                                                {{ isset($insepection['vehicle_regn_no']) ?? $insepection['vehicle_regn_no'] }}
                                            </span>
                                        </td>
                                        <td class="colth" style=" border:2px solid gray">
                                            <p class="col2"> Registration Date</p><span class="text-primary">
                                                {{ isset($insepection['date_registration']) ?? date('d-m-Y', strtotime($insepection['date_registration'])) }}</span>
                                        </td>
                                    </tr>
                                    <tr style=" border:2px solid gray">
                                        <td class="col1 colth">
                                            <p class="col2">Chassis Number</p><span
                                                class="text-primary">{{ isset($insepection['chassis_no']) ?? $insepection['chassis_no'] }}</span>
                                        </td>
                                        <td class="colth" style=" border:2px solid gray">
                                            <p class="col2">Odometer reading</p><span
                                                class="text-primary">{{ isset($insepection['odometer_reading']) ?? $insepection['odometer_reading'] }}</span>
                                        </td>
                                    </tr>
                                    <tr style=" border:2px solid gray">
                                        <td class="col1 colth">
                                            <p class="col2">Vehicle Manufacturer</p><span class="text-primary">
                                                {{ isset($insepection['vehicle_make']) ?? $insepection['vehicle_make'] }}</span>
                                        </td>
                                        <td class="colth" style=" border:2px solid gray">
                                            <p class="col2">Vehicle Model</p><span class="text-primary">
                                                {{ isset($insepection['vehicle_model']) ?? $insepection['vehicle_model'] }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- <div class="row fw-bold">
                                <div class="col-sm-12 col2"> Remarks - ....</div>
                            </div>-->

                            <div class="row fw-bold" style="padding-top:6px;margin-top:0px">
                                <div class="col-sm-12 text-info">
                                    <h5 class="text-primary" style="font-family:'Roboto';font-weight:bold">Spare Parts
                                        (all Replace)</h5>
                                </div>
                            </div>

                            <table class="" style="">
                                <thead>
                                    <tr>
                                        <th class="coltd">S.No.</th>
                                        <th class="coltd" colspan="2">Panel </th>
                                        <!-- <th class="coltd"> Damages</th>   -->
                                        <th class="coltd">Remarks </th>
                                        <th class="coltd">HSN Code</th>
                                        <th class="coltd" style="text-align: right;">Assessed For <br> (in INR)</th>
                                        <th class="coltd" style="text-align: right;">Rate of GST(%)</th>
                                        <th class="coltd" style="text-align: right;">Amount of GST Tax</th>
                                        <th class="coltd" style="text-align: right;">Final Amount</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $z = 1;
                                        $totalas = 0;
                                        $total = 0;
                                        $totaltax = 0;
                                    @endphp
                                    @if (!empty($assisment))

                                        @for ($i = 0; $i < count($assisment); $i++)
                                            @php

                                                $assamount = $assisment[$i]->ass_amt;

                                                $totalas = $totalas + $assamount;

                                                $gst = getgstamount($assisment[$i]->ass_amt, $assisment[$i]->gst);
                                                $totaltax = $totaltax + $gst;
                                                $total = $total + $assamount + $gst;

                                            @endphp


                                            <tr>
                                                <td class="coltd">{{ $z }}</td>
                                                <td class="coltd" colspan="2">{{ $assisment[$i]->description }}
                                                </td>
                                                <!--<td class="coltd">{{ $assisment[$i]->category }}</td>-->
                                                <td class="coltd">{{ $assisment[$i]->remarks }}</td>
                                                <td class="coltd">{{ $assisment[$i]->hsn_code }}</td>

                                                <td class="coltd" style="text-align: right;">
                                                    {{ number_format_custom($assamount, 2) }}</td>

                                                <td class="coltd" style="text-align: right;">
                                                    {{ $assisment[$i]->gst }}</td>

                                                <td class="coltd" style="text-align: right;">
                                                    {{ number_format_custom($gst, 2) }} </td>

                                                <td class="coltd" style="text-align: right;">@php echo number_format_custom($assamount+$gst, 2); @endphp</td>
                                            </tr>
                                            @php $z++; @endphp
                                        @endfor

                                        <tr class="coltd">
                                            <td class="coltd"></td>
                                            <td class="fw-bold">Total</td>
                                            <td class="fw-bold"></td>
                                            <td class="fw-bold"></td>
                                            <td class="fw-bold"></td>
                                            <td class="fw-bold" style="text-align: right;">
                                                {{ number_format_custom($totalas, 2) }}</td>
                                            <td class="fw-bold"></td>
                                            <td class="fw-bold" style="text-align: right;">
                                                {{ number_format_custom($totaltax, 2) }}</td>
                                            <td class="fw-bold" style="text-align: right;">
                                                {{ number_format_custom($total, 2) }}</td>
                                        </tr>
                                </tbody>
                            </table>
                            <div class="row fw-bold" style="padding-top:6px;margin-top:35px">
                                <div class="col-sm-12 text-info">
                                    <h5 class="col2 text-primary">Labour Charges (all Replace + all Repair)</h5>
                                </div>
                            </div>

                            <table class="" style="">

                                <tr>
                                    <th class="coltd">S.No.</th>
                                    <th class="coltd" colspan="2">Panel </th>
                                    <!--  <th class="coltd"Action </th>-->
                                    <th class="coltd">Remarks </th>
                                    <th class="coltd"></th>
                                    <th class="coltd" style="text-align: right;">Assessed For (in INR) </th>
                                    <th class="coltd" style="text-align: right;">Rate of GST(%)</th>
                                    <th class="coltd" style="text-align: right;">Amount of GST <br> Tax</th>
                                    <th class="coltd" style="text-align: right;">Final Amount</th>
                                </tr>

                                @endif




                                @php
                                    $z = 1;
                                    $totalas = 0;
                                    $totall = 0;
                                    $totaltax = 0;
                                @endphp

                                @if (!empty($assisment))
                                    @for ($i = 0; $i < count($assisment); $i++)
                                        @if ($assisment[$i]->est_lab != 0 && $assisment[$i]->est_lab != '')
                                            @php
                                                $totalas = $totalas + $assisment[$i]->ass_lab;

                                                $ass_labour = $assisment[$i]->ass_lab;

                                                $labourtax = $taxsetting['GSTLabourPer'];
                                                $taxvalue = getgstamount($ass_labour, $labourtax);

                                                $totaltax = $totaltax + $taxvalue;

                                                $totall = $taxvalue + $ass_labour + $totall;
                                            @endphp
                                            <tr style="border-top:2px solid #428a5a;">
                                                <td class="coltd">{{ $z }}</td>
                                                <td class="coltd" colspan="2">{{ $assisment[$i]->description }}
                                                </td>
                                                <!--<td class="coltd">Insured Address</td>-->
                                                <td colspan="2" class="coltd">{{ $assisment[$i]->remarks }}</td>
                                                <td class="coltd" style="text-align: right;">
                                                    {{ number_format_custom($ass_labour, 2) }}</td>
                                                <td class="coltd" style="text-align: right;">{{ $labourtax }}
                                                </td>
                                                <td class="coltd" style="text-align: right;">
                                                    {{ number_format_custom($taxvalue, 2) }}</td>
                                                <td class="coltd" style="text-align: right;">@php echo number_format_custom($taxvalue+$ass_labour, 2); @endphp</td>
                                            </tr>

                                            @php $z++;  @endphp
                                        @endif
                                    @endfor
                                @endif

                                <tr>
                                    <td class="coltd"></td>
                                    <td class="fw-bold" colspan="2">Total</td>
                                    <td class="fw-bold"></td>
                                    <td class="fw-bold"></td>
                                    <td class="fw-bold" style="text-align: right;">{{ number_format_custom($totalas, 2) }}
                                    </td>
                                    <td class="fw-bold"> </td>
                                    <td class="fw-bold" style="text-align: right;">{{ number_format_custom($totaltax, 2) }}
                                    </td>
                                    <td class="fw-bold" style="text-align: right;">{{ number_format_custom($totall, 2) }}
                                    </td>
                                </tr>

                            </table>
                            <div class="row fw-bold" style="padding-top:6px;margin-top:35px">
                                <div class="col-sm-12 text-info">
                                    <h5 class="col2 text-primary">Assessed For</h5>
                                </div>
                            </div>

                            <table class="" style="width: 50%; max-width: 1300px; table-layout: fixed;">

                                <tr style="border-bottom:2px solid #006400">
                                    <th class="coltd">S.No.</th>
                                    <th class="coltd">Assessed For</th>
                                    <th class="coltd" class="coltd"></th>

                                </tr>

                                <tbody>
                                    <tr>
                                        <td class="coltd">1</td>
                                        <td class="coltd">Total Labour Charges</td>
                                        <td class="coltd" style="text-align:right">{{ number_format_custom($totall, 2) }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="coltd">2</td>
                                        <td class="coltd">Total Cost of Parts</td>
                                        <td class="coltd" style="text-align:right">{{ number_format_custom($total, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="coltd">3</td>
                                        <td class="coltd">Less Excess (-)</td>
                                        <td class="coltd" style="text-align:right">
                                            {{ isset($insepection['lessimpose']) ?? number_format_custom($insepection['lessimpose'], 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="coltd">4</td>
                                        <td class="coltd">Less Additional Excess (-)</td>
                                        <td class="coltd" style="text-align:right">
                                            {{ isset($insepection['LessCompulsoryDeductable']) ?? number_format_custom($insepection['LessCompulsoryDeductable'], 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="coltd">5</td>
                                        <td class="coltd">Less Salvage cost (-)</td>
                                        <td class="coltd" style="text-align:right">
                                            {{ isset($insepection['ScrapValue']) ?? number_format_custom($insepection['ScrapValue'], 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="coltd">5</td>
                                        <td class="coltd">Add Towing Charges (+)</td>
                                        <td class="coltd" style="text-align:right">
                                            {{ isset($insepection['TowingCharges']) ?? number_format_custom($insepection['TowingCharges'], 2) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="coltd"></td>
                                        <td class="fw-bold">Total</td>
                                        <td class="fw-bold" style="text-align:right">
                                            {{-- {{ 
                                                number_format_custom($totall + $total + $insepection['TowingCharges'] - $insepection['lessimpose'] - $insepection['ScrapValue'] - $insepection['LessCompulsoryDeductable'], 2)
                                                
                                            }} --}}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <div style="page-break-after:always"></div>
                            <div class="row fw-bold" style="padding-top:6px;margin-top:35px;">
                                <div class="col-sm-12 text-info">
                                    <h5 class="col2 text-primary">VEHICLE IMAGES</h5>
                                </div>
                            </div>






                            @if (!empty($files))

              
                                @for ($i = 0; $i <= count($files) / 2; $i = $i + 2)
                                    @if (isset($files[$i]) && $files[$i]->original_file_name != 'assets/img/no-image.jpg')
                                        <div class="row" style="width:100%;  margin-right:-15px">
                                            <div style="width:49%; float:left;padding-top:10px">
                                                <h5 style="text-align:center;font-weight:bold">
                                                    {{ $files[$i]->sop_label }}</h5><img
                                                    style="height:250px;width:315px"
                                                    src="{{ public_path() }}/job_files/{{ $files[$i]->job_id }}/{{ $files[$i]->original_file_name }}"
                                                    alt=" Image" /><br>&emsp;&emsp;&emsp;&emsp; <span
                                                    style="font-size:10px"> Download link : <a
                                                        href="{{ url('/') }}/public/job_files/{{ $files[$i]->job_id }}/{{ $files[$i]->original_file_name }}"
                                                        target="_blank" download>Click here</a></span>
                                            </div>
                                    @endif

                                    @if (isset($files[$i]) && $files[$i + 1]->original_file_name != 'assets/img/no-image.jpg' && count($files) > 1)
                                       
                                        <div style="width:2%; float:left;padding-top:10px"> </div>

                                        <div style="width:49%; float:left;padding-top:10px;">
                                            <h5 style="text-align:center;font-weight:bold">
                                                {{ $files[$i]->sop_label }}</h5> <img style="height:250px;width:315px"
                                                src="{{ public_path() }}/job_files/{{ $files[$i]->job_id }}/{{ $files[$i + 1]->original_file_name }}"
                                                alt="images" /><br>&emsp;&emsp;&emsp;&emsp;<span
                                                style="font-size:10px;text-align:center"> Download link : <a
                                                    href="{{ url('/') }}/public/job_files/{{ $files[$i]->job_id }}/{{ $files[$i + 1]->original_file_name }}"
                                                    target="_blank" download>Click here</a></span>
                                        </div>
                        </div>
                        @endif
                        @endfor
                        @endif




                        <!--<div style="page-break-after:always"></div>
                                    <div class="row fw-bold"  style="padding-top:6px; margin-top:50px">
                                                            <div class="col-sm-12 text-info"> <h5 class="col2 text-success" style="font-family:'Roboto';">INSPECTION DETAILS</h5> </div>
                                                        </div>
                                    <div class="row" style="width:100%">
                                            <div  style="width:40%; float:left;padding-top:20px"><img src="{{ public_path() }}/test.png" alt="ADMIN LOGO REQUIRED HERE" /> </div>
                                            <div  style="width:2%; float:left;padding-top:20px"> </div>
                                            <div  style="width:58%; float:left;padding-top:20px" >
                                            
                                            
                                            
                                            <div><img  style="height:20px;width:20px;margin:10px" src="{{ public_path() }}/yellow.jpeg" />&emsp;<span style="margin:10px">Repair</div>
                                            <div><img style="height:20px;width:20px;margin:10px" src="{{ public_path() }}/pink.jpeg"/>&emsp;<span style="margin:10px">Replace</div>
                                            <div><img style="height:20px;width:20px;margin:10px" src="{{ public_path() }}/blue.jpeg"/>&emsp;<span style="margin:10px">Not Under Claim</div>
                                            <div><img style="height:20px;width:20px;margin:10px" src="{{ public_path() }}/gray.png"/>&emsp;<span style="margin:10px">Intact</div>
                                    
                                        
                                            
                                            </div>
                                        </div>-->



                        <div style="margin-top:100px;font-family:italic">
                            <div> <span class="col2">Note:</span><span style="font-size:12px"> Approval subject to
                                    policy terms & conditions. Contact {{ $email }} for Re-inspection.
                                </span> </div>
                        </div>

                        <br /><br /><br />
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>
