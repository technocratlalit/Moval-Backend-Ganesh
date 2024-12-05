
<html lang="en">
   <head>
      <meta charset="utf-8" />

     <!-- CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<title>ILA Report</title>
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">

<!-- JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


      <style>
         .col1 {
         width: 90px;
         }
         td>tr>.col2{
             width:20px!important;
         }
         td{
         width:65%;
         height:60px;
         border:3px solid black;
         font-size:28px;
         padding:10px;

         color: rgb(0, 0, 0);

         }
         }
         tr{
         height:60px;
         border:3px solid black;
         font-size:28px;

         color: rgb(0, 0, 0);
         }
         }
      </style>
   </head>
   <body>
      <div class="" style="padding:0px;margin:0px">
         <div class="container"  style="padding:0px;margin:0px">
            <div class="row">
               <div class="col-sm-12">
                  <div class="card"  style="padding:0px;margin:0px">
                     <div class="card-body"  style="padding:0px;margin:0px">

                           <div>

                    <img style="width:230px" src="{{public_path()}}/test.png" />
                           </div>

          <div class="row" style="widht:100%">
                  <div style="width:50%; float:left; padding-top:15px;"><span style="font-weight:bold">Ref No. : </span> {{$Reference_No}}</div>
                 <div style="width:50%;padding-top:15px;float:left; text-align:right;"><span style="font-weight:bold">Date : </span>@php $date=date_create($ILA_Submitted_on);
echo date_format($date,"d-m-Y"); @endphp</div>

</div>

             <h6 class="text-center fw-bold" style="font-weight: bold;">INTIAL LOSS ASSESSMENT (ILA) REPORT</h6>
                        <table class="table" style="width:100%" style="margin-left:-25px;margin-right:-15px">
                           <tbody>
                              <tr>
                                 <td class="col1"
                                    ></td>
                                 <td colspan="2">Name & Contact of the Insured</td>
                                 <td colspan="2">{{$insured_name}} & {{$insured_address}}</td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2" class="col2">Claim No.</td>
                                 <td colspan="2">{{$claim_no}}</td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td  colspan="2" class="col2">Policy No , Policy Period</td>
                                 <!--<td class="col2"></td>-->
                                 <td  colspan="2"><div>{{$policy_no}} ,  {{formatdate($policy_valid_from)}} to  {{formatdate($policy_valid_to)}}</div></td>
                              <!-- <td  colspan="2"> <div style="text-align:centre"> </div></td>-->
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
                                 <td>Engine No.</td>
                                 <td colspan="2">{{$chassis_no}} , {{$engine_no}} </td>

                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td>(as per physical verfication )</td>
                                 <!--<td></td>-->
                                 <td class="text-center" colspan="2"><div style="width:100%">{{$Chassis_No_PV}} ,  {{$Engine_No_PV}}</div></td>

                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">
                                    Date of appointment ,  <br> Date & Time of first visit for inspection
                                 </td>
                                 <td>{{formatdate($date_of_appointment)}}</td>
                                 <td>{{formatdate($Survey_Date_time,'t')}}</td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">
                                    Place of inspection <br>( if different from the repairers workshop )
                                 </td>
                                 <td colspan="2">{{$place_survey}}</td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">Brief descriptoin of accident</td>
                                 <td rowspan="2" colspan="2">@php echo $accident_brief_description @endphp ,  @php echo $damage_corroborates_with_cause_of_loss @endphp </td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">
                                    Whether the nature of damage corroborates <br>with the cause of loss
                                 </td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">
                                    Whether accompanied by NIC Officer.<br> if yes Name, & Designation of the
                                    Office
                                 </td>
                                 <td colspan="2">@php echo $accompanied_insurer_officer_details @endphp  </td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">Loss Estimate</td>
                                 <td colspan="2">{{ ($loss_estimate!='')?str_replace(',','',number_format_custom($loss_estimate,2)):0.00 }}</td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">Major damage physically noticed</td>
                                 <td colspan="2">@php echo $major_physical_damages @endphp </td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">Suspected Internal damage</td>
                                 <td colspan="2">@php echo $suspected_Internal_damages @endphp </td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">
                                    Whether Spot Survey report/ photographs received,<br> if yes, any further
                                    damage observed subsequently after <br> shifting the vehicle from the spot
                                 </td>
                                 <td colspan="2">@php echo $spot_Survey_details @endphp </td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">Any pre-existing/old damage</td>
                                 <td colspan="2">@php echo $preexisting_old_damages @endphp </td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">Perferred Mode of Assessment (Repair/CTL)</td>
                                 <td colspan="2">@php echo $preferred_mode_of_assessment @endphp</td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">Insurers approx. liability</td>
                                 <td colspan="2">{{ ($insurer_liability!='')?str_replace(',','',number_format_custom($insurer_liability,2)):0.00 }}</td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">
                                    Other Information relevent for assessement of loss & discrepaincies
                                    observed in vehicluar document (if any)
                                 </td>
                                 <td colspan="2">@php echo $Vehicular_document_observation @endphp</td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">Surveyor APP Token Number/s</td>
                                 <td colspan="2">@php echo $surveyor_APP_token_number ; @endphp</td>
                              </tr>
                              <tr>
                                 <td class="col1"></td>
                                 <td colspan="2">Name of the NIC officer with whom ILA discussed</td>
                                 <td colspan="2">@php echo $ILA_discussed_with ; @endphp</td>
                              </tr>
                              <tr>
                                 <td colspan="5">
                                    &emsp;1.&emsp; ATTACH A FEW PHOTOGRAPHS CLEARLY SHOWING THE DAMAGES<br />
                                    &emsp;2.&emsp; In case CTL, submit preliminary report within 7 days with at-least
                                    2/3 online/offline wreck quotation.
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        <div class="row" style="float:right">

                           <div class="text-right" style="float:right;margin-top:50px;font-weight:bold;">Signature</div>
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
