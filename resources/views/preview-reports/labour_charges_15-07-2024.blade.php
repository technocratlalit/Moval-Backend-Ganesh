@if(isset($lossAssessment[0]['alldetails']))
    @php
        $allLabourCharges = json_decode($lossAssessment[0]['alldetails'], true);
        $labcolspan = 4; // Default colspan value
        if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1){
              $labcolspan++;
            }
        $indexCounter = 0;
        $gstUniqueValue =[];
    @endphp
    @if(is_array($allLabourCharges))
        <table width="100%" id="design" cellpadding="0" cellspacing="0" border="0" align="center" style="padding-top:20px;">
            <tbody>
            <tr>
                <td align="left" valign="top" style="border-top: 2px solid #000; font-weight: bold; border-right:none; line-height:0px; padding: 3px 0px;">LABOUR CHARGES</td>
            </tr>
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
                <td align="center" valign="top" style="padding: 0px 3px; width: 5%; font-weight: bold;">GST <br />%</td>
                <td align="right" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Estimated<br /><span style="font-weight: 400;">(Amt in Rs)</span></td>
                <td align="right" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">O/F & Denting<br /><span style="font-weight: 400;">(Amt in Rs)</span></td>
                <td align="right" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Painting<br /><span style="font-weight: 400;">(Amt in Rs)</span></td>
            </tr>
            @foreach($allLabourCharges as $index => $detail)
                @if(isset($detail['est_lab']) && $detail['est_lab'] != 0 || $detail['painting_lab'] != 0 || $detail['ass_lab'] != 0)
                    @php
                        $indexCounter++;
                        $gstUniqueValue[]= $detail['gst'];
                    @endphp
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">{{ $indexCounter }}</td>
                        @if($detail['imt_23'] == "Yes")
                            <td align="left" valign="top" style="padding: 0px 3px;"><strong>*</strong> {{ $detail['description'] }}</td>
                        @else
                            <td align="left" valign="top" style="padding: 0px 3px;">{{ $detail['description'] }}</td>
                        @endif
                        @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                            <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($detail['sac']) ? $detail['sac'] : '-' }}</td>
                        @endif
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ $detail['remarks'] ?? '-' }}</td>
                        @if($lossAssessment[0]['MultipleGSTonLab']==1)
                            <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($detail['gst']) ? $detail['gst'] : '0' }}</td>
                        @else
                            <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['GSTLabourPer']) ? $lossAssessment[0]['GSTLabourPer'] : '0' }}</td>
                        @endif
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['est_lab']) ? $detail['est_lab'] : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['ass_lab']) ? $detail['ass_lab'] : '0.00' }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($detail['painting_lab']) ? $detail['painting_lab'] : '0.00' }}</td>
                    </tr>
                @endif
                @if (!empty($detail['quantities']))
                    @foreach($detail['quantities'] as $quantityIndex => $quantity)
                        @if(isset($detail['painting_lab']) && $quantity['painting_lab'] != 0 || $quantity['ass_lab'] != 0)
                            @php
                                $gstUniqueValue[]= $quantity['gst'];
                            @endphp
                            <tr>
                                <td align="center" valign="top" style="padding: 0px 3px; font-style: italic; padding-left: 15px; border-left: 1px solid #000;">{{ $indexCounter }}.{{ $quantityIndex + 1 }}</td>
                                @if($quantity['imt_23'] == "Yes")
                                    <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;"><strong>*</strong> {{ $quantity['description'] }}</td>
                                @else
                                    <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;">{{ $quantity['description'] }}</td>
                                @endif
                                @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                                    <td align="left" valign="top" style="padding: 0px 3px;">{{ !empty($quantity['sac']) ? $quantity['sac'] : '-' }}</td>
                                @endif
                                <td align="left" valign="top" style="padding: 0px 3px;">{{ $quantity['remarks'] ?? '-' }}</td>

                                @if($lossAssessment[0]['MultipleGSTonLab']==1)
                                    <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">{{ !empty($quantity['gst']) ? $quantity['gst'] : '0' }}</td>
                                @else
                                    <td align="center" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['GSTLabourPer']) ? $lossAssessment[0]['GSTLabourPer'] : '0' }}</td>
                                @endif


                                <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{{ !empty($quantity['est_lab']) ? $quantity['est_lab'] : '0.00' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{{ !empty($quantity['ass_lab']) ? $quantity['ass_lab'] : '0.00' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{{ !empty($quantity['painting_lab']) ? $quantity['painting_lab'] : '0.00' }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @php
                $uniqueGstRates = []; // Initialize an array to store unique GST rates
                $subUniqueGstRates = array_values(array_unique($gstUniqueValue));
                sort($subUniqueGstRates);


                $totalIMTLabourAmtAfterDep=0;
                $totalIMTPaintingLabourAmt =0;
                $totalPaintingMaterialAmt = 0;
            @endphp

            @foreach($subUniqueGstRates as $value)
                @unless(in_array($value, $uniqueGstRates))
                    @php
                        $totalEstlabAmt = 0; // Initialize total estimated amount
                        $totalAsslabAmt = 0;
                        $totalPaintinglabAmt = 0;
                        $imt23PaintinglabTotalAmount=0;
                        $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                        $IMT23PaintingLabTotalEstAmt = 0;
                    @endphp

                    @foreach($allLabourCharges as $detail)
                        @php
                            if($detail['gst'] == $value) {
                               $totalEstlabAmt += !empty($detail['est_lab']) ? $detail['est_lab'] : 0;

                                  if($detail['imt_23']=="Yes"){
                                      $IMT23PaintingLabTotalEstAmt += !empty($detail['est_lab']) ? $detail['est_lab'] : 0;
                                    }
                                  if(empty($detail['quantities'])){

                                      if($detail['imt_23']=="Yes"){

                                          $imt23PaintinglabTotalAmount += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;

                                      }else{
                                         $totalPaintinglabAmt += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                                     }
                                         $totalAsslabAmt += !empty($detail['ass_lab']) ? $detail['ass_lab'] : 0;
                                    }
                               }
                        @endphp

                        @if(isset($detail['quantities']))
                            @foreach($detail['quantities'] as $partQuantity)
                                @php
                                    if($partQuantity['gst'] == $value){

                                           if($partQuantity['imt_23']=="Yes"){
                                              $imt23PaintinglabTotalAmount += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                           }

                                         $totalAsslabAmt += !empty($partQuantity['ass_lab']) ? $partQuantity['ass_lab'] : 0;
                                         $totalPaintinglabAmt += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                    }
                                @endphp

                            @endforeach
                        @endif
                    @endforeach

                    @if($lossAssessment[0]['MultipleGSTonLab']==1)
                        <tr>
                            <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{$value}}%)</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstlabAmt, 2) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalAsslabAmt, 2) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($totalPaintinglabAmt + $imt23PaintinglabTotalAmount), 2) }}</td>
                        </tr>
                    @else

                        @if($lossAssessment[0]['GSTonEstimatedLab']=="Y" && $lossAssessment[0]['GstonAssessedLab']=="Y")
                            <tr>
                                <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{ $lossAssessment[0]['GSTLabourPer'] }} %)</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstlabAmt, 2) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalAsslabAmt, 2) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($totalPaintinglabAmt + $imt23PaintinglabTotalAmount), 2) }}</td>
                            </tr>
                        @elseif($lossAssessment[0]['GSTonEstimatedLab']=="Y" && $lossAssessment[0]['GstonAssessedLab']=="N")
                            @foreach($lossAssessment as $row)
                                @if($row['GSTonEstimatedLab']=="Y")
                                    <tr>
                                        <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{ $row['GSTLabourPer'] }} %)</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstlabAmt, 2) }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                    </tr>
                                @endif
                            @endforeach

                            @foreach($lossAssessment as $row)
                                @if($row['GstonAssessedLab']=="N")
                                    <tr>
                                        <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST 0 %)</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalAsslabAmt, 2) }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($totalPaintinglabAmt + $imt23PaintinglabTotalAmount), 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach

                        @elseif($lossAssessment[0]['GSTonEstimatedLab']=="N" && $lossAssessment[0]['GstonAssessedLab']=="Y")
                            @foreach($lossAssessment as $row)
                                @if($row['GSTonEstimatedLab']=="N")
                                    <tr>
                                        <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST 0 %)</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstlabAmt, 2) }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                    </tr>
                                @endif
                            @endforeach

                            @foreach($lossAssessment as $row)
                                @if($row['GstonAssessedLab']=="Y")
                                    <tr>
                                        <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{ $row['GSTLabourPer'] }} %)</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalAsslabAmt, 2) }}</td>
                                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($totalPaintinglabAmt + $imt23PaintinglabTotalAmount), 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @elseif($lossAssessment[0]['GSTonEstimatedLab']=="N" && $lossAssessment[0]['GstonAssessedLab']=="N")
                            <tr>
                                <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST 0 %)</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstlabAmt, 2) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalAsslabAmt, 2) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($totalPaintinglabAmt + $imt23PaintinglabTotalAmount), 2) }}</td>
                            </tr>
                        @endif
                    @endif

                    @php
                        $totalIMTPaintingLabourAmt += $imt23PaintinglabTotalAmount;
                    @endphp
                @endunless
            @endforeach

            @php
                $subTotalafterDeduction= 0;
                $totalPaintingMaterialAmt =($lossAssessment[0]['total_paintingassAmtWithoutGST'] * 25) / 100;
                $totalPaintingDepAmt = (($totalPaintingMaterialAmt * 50) / 100);
                if($lossAssessment[0]['IsZeroDep']==1){
                  $totalPaintingDepAmt = 0.00;
                }
                $totalIMTPaintingPerAmt = (($totalIMTPaintingLabourAmt) * 25) / 100;

                $totalIMTLabourAmtAfterDep = ($totalIMTPaintingLabourAmt - ($totalIMTPaintingPerAmt / 2));
                $subTotalafterDeduction = ($totalIMTLabourAmtAfterDep * $lossAssessment[0]['IMT23DepPer'] / 100);
            @endphp

            <tr>
                <td align="right" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; font-weight: bold;  border-left: 1px solid #000;">Total</td>
                <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ !empty($lossAssessment[0]['total_labestAmtWithoutGST']) ? $lossAssessment[0]['total_labestAmtWithoutGST'] : '0.00' }}</td>
                <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ !empty($lossAssessment[0]['total_labassAmtWithoutGST']) ? $lossAssessment[0]['total_labassAmtWithoutGST'] : '0.00' }}</td>
                <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ !empty($lossAssessment[0]['total_paintingassAmtWithoutGST']) ? $lossAssessment[0]['total_paintingassAmtWithoutGST'] : '0.00' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Less <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['IsZeroDep']==1) ? 0 : 50 }}%</span> on <span style="font-weight: bold;">25%</span> of Painting
                    Material of <span style=" font-weight: bold;">Rs.{{ !empty($totalPaintingMaterialAmt) ? $totalPaintingMaterialAmt : '0.00' }}</span></td>
                <td align="right" valign="top"  style="padding: 0px 3px;">&nbsp;</td>
                <td align="right" valign="top"  style="padding: 0px 3px;">&nbsp;</td>
                <td align="right" valign="top"   style="padding: 0px 3px;">{{ !empty($totalPaintingDepAmt) ? $totalPaintingDepAmt : '0.00' }}</td>
            </tr>
            <tr>
                <td align="right" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; font-weight: bold;  border-left: 1px solid #000;">Sub Total</td>
                <td align="right" valign="top"  style="padding: 0px 3px; font-weight: bold;">{{ !empty($lossAssessment[0]['total_labestAmtWithoutGST']) ? $lossAssessment[0]['total_labestAmtWithoutGST'] : '0.00' }}</td>
                <td align="right" valign="top"  style="padding: 0px 3px; font-weight: bold;">{{ !empty($lossAssessment[0]['total_labassAmtWithoutGST']) ? $lossAssessment[0]['total_labassAmtWithoutGST'] : '0.00' }}</td>
                <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ ($lossAssessment[0]['total_paintingassAmtWithoutGST'] - $totalPaintingDepAmt) }}</td>
            </tr>
            @if($lossAssessment[0]['IMTPaintingLabAss'] != 0.00)
                <tr>
                    <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Less Addl. Deduction 	<span style="font-weight: bold;">{{ !empty($lossAssessment[0]['IMT23DepPer']) ? $lossAssessment[0]['IMT23DepPer'] : '0' }}%</span> on IMT Labour of <span style=" font-weight: bold;">Rs.{{ number_format_custom(($totalIMTLabourAmtAfterDep))}}</span>
                    <td align="right" valign="top"  style="padding: 0px 3px;">&nbsp;</td>
                    <td align="right" valign="top"  style="padding: 0px 3px;">&nbsp;</td>
                    <td align="right" valign="top"   style="padding: 0px 3px;">{{ number_format_custom(($subTotalafterDeduction),2) }}</td>
                </tr>
                <tr>
                    <td align="right" valign="top"   colspan="{{$labcolspan}}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Sub Total </td>
                    <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ !empty($lossAssessment[0]['total_labestAmtWithoutGST']) ? $lossAssessment[0]['total_labestAmtWithoutGST'] : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ !empty($lossAssessment[0]['total_labassAmtWithoutGST']) ? $lossAssessment[0]['total_labassAmtWithoutGST'] : '0.00' }}</td>
                    <td align="right" valign="top"  style="padding: 0px 3px; font-weight: bold; border-top: 1px solid #000;">{{ (($lossAssessment[0]['total_paintingassAmtWithoutGST'] - $totalPaintingDepAmt) - $subTotalafterDeduction) }}</td>
                </tr>
            @endif

            @php
                $paintingMaterialDepPer=25;
                if($lossAssessment[0]['IsZeroDep']==1){
                   $paintingMaterialDepPer = 0;
                }
                $uniqueGstRates = []; // Initialize an array to store unique GST rates
                $subUniqueGstRates = array_values(array_unique($gstUniqueValue));
                sort($subUniqueGstRates);
                $subTotalEstlabAmt = 0;
                $subTotalAsslabAmt = 0;
                $subTotalLabourAfterAmt = 0;

                $totalPaintingAmtAfterIMT23Dep = (($lossAssessment[0]['total_paintingassAmtWithoutGST'] - $totalPaintingDepAmt) - $subTotalafterDeduction);
            @endphp
            @if($lossAssessment[0]['MultipleGSTonLab']==1)
                @foreach($subUniqueGstRates as $value)
                    @unless(in_array($value, $uniqueGstRates))
                        @php
                            $totalEstlabAmt = 0; // Initialize total estimated amount
                            $totalAsslabAmt = 0;
                            $totalPaintinglabAmt = 0;
                            $imt23PaintinglabAmount=0;
                            $imt23WithoutlabTotalAmount = 0;
                            $totalIMTLaboutAmt=0;
                            $totalPaintingAfterIMTDep=0;

                            $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                        @endphp

                        @foreach($allLabourCharges as $detail)
                            @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
                            @php
                                $totalEstlabAmt += !empty($detail['est_lab']) ? $detail['est_lab'] : 0; // Sum up estimated amount

                                if(empty($detail['quantities'])){
                                       if($detail['imt_23']=="Yes"){
                                             $imt23PaintinglabAmount += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                                         }else{
                                             $imt23WithoutlabTotalAmount += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                                         }
                                       $totalAsslabAmt += !empty($detail['ass_lab']) ? $detail['ass_lab'] : 0;
                                       $totalPaintinglabAmt += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                                   }
                            @endphp
                            @endif

                            @if(isset($detail['quantities']))
                                @foreach($detail['quantities'] as $partQuantity)
                                    @if($partQuantity['gst'] == $value)
                                        @php
                                            if($partQuantity['imt_23']=="Yes"){
                                                $imt23PaintinglabAmount += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                                $totalPaintingPercentage = ($imt23PaintinglabTotalAmount * 25) / 100;
                                                $totalMaterialDepAmt = ($totalPaintingPercentage/2);
                                                $totalAfterDepAmt = ($imt23PaintinglabTotalAmount - $totalMaterialDepAmt);
                                                $totalDepAmt = ($totalAfterDepAmt * $lossAssessment[0]['IMT23DepPer'] / 100);
                                                $totalLabourAfterIMTDep = ($totalAfterDepAmt - $totalDepAmt);
                                            }else{
                                                $imt23WithoutlabTotalAmount += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                                $totalPaintingPercentage = ($imt23WithoutlabTotalAmount * 25) / 100;
                                                $totalMaterialDepAmt = ($totalPaintingPercentage/2);
                                                $totalLabourAfterIMTDep = ($imt23PaintinglabTotalAmount - $totalMaterialDepAmt);
                                            }
                                            $totalAsslabAmt += !empty($partQuantity['ass_lab']) ? $partQuantity['ass_lab'] : 0;
                                            $totalPaintinglabAmt += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        @php

                            $paintingLabourDepAmt = ((($totalPaintinglabAmt * $paintingMaterialDepPer) / 100) / 2);
                            $paintingLabourAfterDep = ($totalPaintinglabAmt - $paintingLabourDepAmt);


                            $imt23PaintingIMTDepAmt = ($paintingLabourAfterDep * $lossAssessment[0]['IMT23DepPer'] / 100);

                            $totalPaintingAfterIMTDep += ($paintingLabourAfterDep - $imt23PaintingIMTDepAmt);

                        @endphp

                        <tr>
                            <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $value }}%</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalEstlabAmt * $value / 100), 2) : '0.00' }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">
                                {{ $value != 0 ? number_format_custom(($totalAsslabAmt * $value / 100), 2) : '0.00' }}
                            </td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ $value != 0 ? number_format_custom(($totalPaintingAfterIMTDep * $value / 100), 2) : '0.00' }}</td>
                        </tr>

                        @php
                            $subTotalEstlabAmt += floatval($totalEstlabAmt *  $value / 100);
                            $subTotalAsslabAmt += ($totalAsslabAmt *  $value / 100);
                            $subTotalLabourAfterAmt += floatval($totalPaintingAfterIMTDep *  $value / 100);
                        @endphp

                    @endunless
                @endforeach

            @else

                @if($lossAssessment[0]['GSTonEstimatedLab']=="Y" && $lossAssessment[0]['GstonAssessedLab']=="Y")
                    <tr>
                        <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{ $lossAssessment[0]['GSTLabourPer'] }} %)</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($lossAssessment[0]['total_labestAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100), 2) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($lossAssessment[0]['total_labassAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100), 2) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($totalPaintingAmtAfterIMT23Dep * $lossAssessment[0]['GSTLabourPer'] / 100), 2) }}</td>
                    </tr>
                    @php
                        $subTotalEstlabAmt += floatval($lossAssessment[0]['total_labestAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100);
                        $subTotalAsslabAmt += ($lossAssessment[0]['total_labassAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100);
                        $subTotalLabourAfterAmt += floatval($totalPaintingAmtAfterIMT23Dep * $lossAssessment[0]['GSTLabourPer'] / 100);
                    @endphp

                @elseif($lossAssessment[0]['GSTonEstimatedLab']=="Y" && $lossAssessment[0]['GstonAssessedLab']=="N")
                    @if($lossAssessment[0]['GSTonEstimatedLab']=="Y")
                        <tr>
                            <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{ $lossAssessment[0]['GSTLabourPer'] }} %)</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($lossAssessment[0]['total_labestAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100), 2) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                        </tr>

                        @php
                            $subTotalEstlabAmt += floatval($lossAssessment[0]['total_labestAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100);
                            $subTotalAsslabAmt += ($lossAssessment[0]['total_labassAmtWithoutGST'] * 0 / 100);
                            $subTotalLabourAfterAmt += floatval($totalPaintingAmtAfterIMT23Dep * 0 / 100);
                        @endphp
                    @endif

                    @if($lossAssessment[0]['GstonAssessedLab']=="N")
                        <tr>
                            <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST 0 %)</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($lossAssessment[0]['total_labassAmtWithoutGST'] * 0 / 100), 2) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($totalPaintingAmtAfterIMT23Dep * 0 / 100), 2) }}</td>
                        </tr>

                        @php
                            $subTotalEstlabAmt += floatval($lossAssessment[0]['total_labestAmtWithoutGST'] * 0 / 100);
                            $subTotalAsslabAmt += ($lossAssessment[0]['total_labassAmtWithoutGST'] * 0 / 100);
                            $subTotalLabourAfterAmt += floatval($totalPaintingAmtAfterIMT23Dep * 0 / 100);
                        @endphp
                    @endif

                @elseif($lossAssessment[0]['GSTonEstimatedLab']=="N" && $lossAssessment[0]['GstonAssessedLab']=="Y")

                    @if($lossAssessment[0]['GstonAssessedLab']=="Y")
                        <tr>
                            <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{ $lossAssessment[0]['GSTLabourPer'] }} %)</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($lossAssessment[0]['total_labassAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100), 2) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($totalPaintingAmtAfterIMT23Dep * $lossAssessment[0]['GSTLabourPer'] / 100), 2) }}</td>
                        </tr>
                        @php
                            $subTotalEstlabAmt += floatval($lossAssessment[0]['total_labestAmtWithoutGST'] * 0 / 100);
                            $subTotalAsslabAmt += ($lossAssessment[0]['total_labassAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100);
                            $subTotalLabourAfterAmt += floatval($totalPaintingAmtAfterIMT23Dep * $lossAssessment[0]['GSTLabourPer'] / 100);
                        @endphp
                    @endif
                @elseif($lossAssessment[0]['GSTonEstimatedLab']=="N" && $lossAssessment[0]['GstonAssessedLab']=="N")
                    <tr>
                        <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST 0 %)</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($lossAssessment[0]['total_labestAmtWithoutGST'] * 0 / 100), 2) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($lossAssessment[0]['total_labassAmtWithoutGST'] * 0 / 100), 2) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($totalPaintingAmtAfterIMT23Dep * 0 / 100), 2) }}</td>
                    </tr>
                    @php
                        $subTotalEstlabAmt += floatval($lossAssessment[0]['total_labestAmtWithoutGST'] * 0 / 100);
                        $subTotalAsslabAmt += ($lossAssessment[0]['total_labassAmtWithoutGST'] * 0 / 100);
                        $subTotalLabourAfterAmt += floatval($totalPaintingAmtAfterIMT23Dep * 0 / 100);
                    @endphp
                @endif
            @endif

            <tr>
                <td align="right" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Total</td>
                <td align="right" valign="top"  style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{number_format_custom($subTotalEstlabAmt + (!empty($lossAssessment[0]['total_labestAmtWithoutGST']) ? $lossAssessment[0]['total_labestAmtWithoutGST'] : '0.00'), 2)}}</td>
                <td align="right" valign="top"  style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{number_format_custom($subTotalAsslabAmt + (!empty($lossAssessment[0]['total_labassAmtWithoutGST']) ? $lossAssessment[0]['total_labassAmtWithoutGST'] : '0.00'), 2)}}</td>
                <td align="right" valign="top"  style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{number_format_custom(($totalPaintingAmtAfterIMT23Dep + $subTotalLabourAfterAmt), 2)}}</td>
            </tr>
            <tr>
                <td align="left" valign="top"   colspan="{{$labcolspan}}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Net Labour
                    Amount (O/F & D/B + Painting Labour) </td>
                <td align="right" valign="top"   colspan="3" style="padding: 0px 3px; font-weight: bold; border-top: 1px solid #000;">{{ number_format_custom(($subTotalAsslabAmt + $lossAssessment[0]['total_labassAmtWithoutGST'] + $totalPaintingAmtAfterIMT23Dep + $subTotalLabourAfterAmt), 2) }}</td>
            </tr>
            </tbody>
        </table>
    @endif
@endif