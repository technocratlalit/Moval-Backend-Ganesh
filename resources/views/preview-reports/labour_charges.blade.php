@if(isset($lossAssessment[0]['alldetails']))
    @php
        $IMTPaintingLabAssRemarksShow = false;
        $allLabourCharges = json_decode($lossAssessment[0]['alldetails'], true);
        $labcolspan = 4; // Default colspan value
        if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1){
              $labcolspan++;
            }
        $indexCounter = 0;
        $gstUniqueValue =[];
        $totalPaintingAmtAfterIMT23DepSubTotal = 0;
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
                <td align="center" valign="top" style="padding: 0px 3px; width: 5%; font-weight: bold;">GST <br/>%</td>
                <td align="right" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Estimated<br/><span style="font-weight: 400;">(Amt in Rs)</span></td>
                <td align="right" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">O/F & Denting<br/><span style="font-weight: 400;">(Amt in Rs)</span></td>
                <td align="right" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Painting<br/><span style="font-weight: 400;">(Amt in Rs)</span></td>
            </tr>
            @foreach($allLabourCharges as $index => $detail)
                @if($detail['est_lab'] > 0 || $detail['painting_lab'] > 0 || $detail['ass_lab'] > 0)
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
                            <td align="left" valign="top" style="padding: 0px 3px;">{{ (!empty($detail['sac']) && $detail['sac'] > 0) ? $detail['sac'] : '-' }}</td>
                        @endif
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ $detail['remarks'] ?? '-' }}</td>
                        @if($lossAssessment[0]['MultipleGSTonLab']==1)
                            <td align="center" valign="top" style="padding: 0px 3px;">{!! (!empty($detail['gst']) && $detail['gst'] > 0) ? $detail['gst'] : '0' !!}</td>
                        @else
                            <td align="center" valign="top" style="padding: 0px 3px;">{!! (!empty($lossAssessment[0]['GSTLabourPer']) && $lossAssessment[0]['GSTLabourPer'] > 0) ? $lossAssessment[0]['GSTLabourPer'] : '-' !!}</td>
                        @endif
                        <td align="right" valign="top" style="padding: 0px 3px;">{!! (!empty($detail['est_lab']) && $detail['est_lab'] > 0) ? $detail['est_lab'] : '-' !!}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{!! (empty($detail['quantities']) && !empty($detail['ass_lab']) && $detail['ass_lab'] > 0) ? $detail['ass_lab'] : '-' !!}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{!! (empty($detail['quantities']) && !empty($detail['painting_lab']) && $detail['painting_lab'] > 0) ? number_format_custom($detail['painting_lab'], 2, '.', '') : '-' !!}</td>
                    </tr>
                @endif
                @if (!empty($detail['quantities']))
                    @foreach($detail['quantities'] as $quantityIndex => $quantity)
                        @if($quantity['est_lab'] > 0 || $quantity['painting_lab'] > 0 || $quantity['ass_lab'] > 0)
                            @php
                                $gstUniqueValue[]= $quantity['gst'];
                            @endphp
                            <tr>
                                <td align="center" valign="top" style="padding: 0px 3px 0px 13px; font-style: italic; border-left: 1px solid #000;">{!! $indexCounter.'.'.intval($quantityIndex+1) !!}</td>
                                @if($quantity['imt_23'] == "Yes")
                                    <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;"><strong>*</strong> {{ $quantity['description'] }}</td>
                                @else
                                    <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;">{{ $quantity['description'] }}</td>
                                @endif
                                @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                                    <td align="left" valign="top" style="padding: 0px 3px;">{{ (!empty($quantity['sac']) && $quantity['sac'] > 0) ? $quantity['sac'] : '-' }}</td>
                                @endif
                                <td align="left" valign="top" style="padding: 0px 3px;">{{ $quantity['remarks'] ?? '-' }}</td>
                                @if($lossAssessment[0]['MultipleGSTonLab']==1)
                                    <td align="center" valign="top" style="padding: 0px 3px; font-style: italic;">{{ (!empty($quantity['gst']) && $quantity['gst'] > 0) ? $quantity['gst'] : '0' }}</td>
                                @else
                                    <td align="center" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['GSTLabourPer']) && $lossAssessment[0]['GSTLabourPer'] > 0) ? $lossAssessment[0]['GSTLabourPer'] : '-' }}</td>
                                @endif
                                <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{!! (!empty($quantity['est_lab']) && $quantity['est_lab'] > 0) ? number_format_custom($quantity['est_lab'], 2, '.', '') : '-' !!}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{!!  (!empty($quantity['ass_lab']) && $quantity['ass_lab'] > 0) ? number_format_custom($quantity['ass_lab'], 2, '.', '') : '-' !!}</td>
                                <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{!! (!empty($quantity['painting_lab']) && $quantity['painting_lab'] > 0) ? number_format_custom($quantity['painting_lab'], 2, '.', '') : '-' !!}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @php
                $uniqueGstRates = []; // Initialize an array to store unique GST rates
                $subUniqueGstRates = array_values(array_unique($gstUniqueValue));
                $MultipleGSTonLab = isset($lossAssessment[0]['MultipleGSTonLab']) ? $lossAssessment[0]['MultipleGSTonLab'] : '';
                if(empty($MultipleGSTonLab) || $MultipleGSTonLab == 0) {
                    $subUniqueGstRates = [0];
                    if(!empty($lossAssessment[0]['GSTLabourPer'])) {
                        $subUniqueGstRates[] = $lossAssessment[0]['GSTLabourPer'];
                    }
                }
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
                            if($detail['gst'] == $value && ($detail['est_lab'] > 0 || $detail['painting_lab'] > 0 || $detail['ass_lab'] > 0)) {
                                $totalEstlabAmt += !empty($detail['est_lab']) ? $detail['est_lab'] : 0;
                                if($detail['imt_23']=="Yes"){
                                    $IMT23PaintingLabTotalEstAmt += !empty($detail['est_lab']) ? $detail['est_lab'] : 0;
                                }
                                if(empty($detail['quantities'])){
                                    if($detail['imt_23']=="Yes"){
                                        $imt23PaintinglabTotalAmount += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                                    }else{
                                        $totalPaintinglabAmt += (empty($detail['quantities']) && !empty($detail['painting_lab'])) ? $detail['painting_lab'] : 0;
                                    }
                                    $totalAsslabAmt += !empty($detail['ass_lab']) ? $detail['ass_lab'] : 0;
                                }
                            }
                        @endphp

                        @if(isset($detail['quantities']))
                            @foreach($detail['quantities'] as $partQuantity)
                                @php
                                    if($partQuantity['gst'] == $value && ($partQuantity['est_lab'] > 0 || $partQuantity['painting_lab'] > 0 || $partQuantity['ass_lab'] > 0)){
                                        if($partQuantity['imt_23']=="Yes"){
                                          $imt23PaintinglabTotalAmount += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                        } else {
                                            $totalPaintinglabAmt += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                        }
                                        $totalAsslabAmt += !empty($partQuantity['ass_lab']) ? $partQuantity['ass_lab'] : 0;
                                    }
                                @endphp

                            @endforeach
                        @endif
                    @endforeach

                    @if($lossAssessment[0]['MultipleGSTonLab']==1)
                        <tr>
                            <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{$value}}%)</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{!! (!empty($totalEstlabAmt ) && $totalEstlabAmt > 0) ? number_format_custom($totalEstlabAmt, 2, '.', '') : '0.00' !!}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{!! (!empty($totalAsslabAmt ) && $totalAsslabAmt > 0) ? number_format_custom($totalAsslabAmt, 2, '.', '') : '0.00' !!}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(($totalPaintinglabAmt + $imt23PaintinglabTotalAmount), 2, '.', '') }}</td>
                        </tr>
                    @else
                        @if($lossAssessment[0]['GSTonEstimatedLab']=="Y" && $lossAssessment[0]['GstonAssessedLab']=="Y")
                            @if($value > 0)
                                <tr>
                                    <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{ $value }} %)</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['total_labestAmtWithoutGST']) && $lossAssessment[0]['total_labestAmtWithoutGST'] > 0) ? number_format_custom($lossAssessment[0]['total_labestAmtWithoutGST']) : '0.00' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['total_labassAmtWithoutGST']) && $lossAssessment[0]['total_labassAmtWithoutGST'] > 0) ? number_format_custom($lossAssessment[0]['total_labassAmtWithoutGST']) : '0.00' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['total_paintingassAmtWithoutGST']) && $lossAssessment[0]['total_paintingassAmtWithoutGST'] > 0) ? number_format_custom($lossAssessment[0]['total_paintingassAmtWithoutGST']) : '0.00' }}</td>
                                </tr>
                            @endif
                        @elseif($lossAssessment[0]['GSTonEstimatedLab']=="Y" && $lossAssessment[0]['GstonAssessedLab']=="N")
                            <tr>
                                <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{ $value }} %)</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['total_labestAmtWithoutGST']) && $lossAssessment[0]['total_labestAmtWithoutGST'] > 0 && $value > 0) ? number_format_custom($lossAssessment[0]['total_labestAmtWithoutGST']) : '0.00' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['total_labassAmtWithoutGST']) && $lossAssessment[0]['total_labassAmtWithoutGST'] > 0 && $value == 0) ? number_format_custom($lossAssessment[0]['total_labassAmtWithoutGST']) : '0.00' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['total_paintingassAmtWithoutGST']) && $lossAssessment[0]['total_paintingassAmtWithoutGST'] > 0 && $value == 0) ? number_format_custom($lossAssessment[0]['total_paintingassAmtWithoutGST']) : '0.00' }}</td>
                            </tr>
                        @elseif($lossAssessment[0]['GSTonEstimatedLab']=="N" && $lossAssessment[0]['GstonAssessedLab']=="Y")
                            <tr>
                                <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{ $value }} %)</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['total_labestAmtWithoutGST']) && $lossAssessment[0]['total_labestAmtWithoutGST'] > 0 && $value == 0) ? number_format_custom($lossAssessment[0]['total_labestAmtWithoutGST']) : '0.00' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['total_labassAmtWithoutGST']) && $lossAssessment[0]['total_labassAmtWithoutGST'] > 0 && $value > 0) ? number_format_custom($lossAssessment[0]['total_labassAmtWithoutGST']) : '0.00' }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['total_paintingassAmtWithoutGST']) && $lossAssessment[0]['total_paintingassAmtWithoutGST'] > 0 && $value > 0) ? number_format_custom($lossAssessment[0]['total_paintingassAmtWithoutGST']) : '0.00' }}</td>
                            </tr>
                        @elseif($lossAssessment[0]['GSTonEstimatedLab']=="N" && $lossAssessment[0]['GstonAssessedLab']=="N")
                            @if($value == 0)
                                <tr>
                                    <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{ $value }} %)</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['total_labestAmtWithoutGST']) && $lossAssessment[0]['total_labestAmtWithoutGST'] > 0) ? number_format_custom($lossAssessment[0]['total_labestAmtWithoutGST']) : '0.00' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['total_labassAmtWithoutGST']) && $lossAssessment[0]['total_labassAmtWithoutGST'] > 0) ? number_format_custom($lossAssessment[0]['total_labassAmtWithoutGST']) : '0.00' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($lossAssessment[0]['total_paintingassAmtWithoutGST']) && $lossAssessment[0]['total_paintingassAmtWithoutGST'] > 0) ? number_format_custom($lossAssessment[0]['total_paintingassAmtWithoutGST']) : '0.00' }}</td>
                                </tr>
                            @endif
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

                $totalSubEstimatedAmt = (!empty($lossAssessment[0]['total_labestAmtWithoutGST']) && $lossAssessment[0]['total_labestAmtWithoutGST'] > 0) ? $lossAssessment[0]['total_labestAmtWithoutGST'] : 0;
                $totalSubDentingAmt = (!empty($lossAssessment[0]['total_labassAmtWithoutGST']) && $lossAssessment[0]['total_labassAmtWithoutGST'] > 0) ? $lossAssessment[0]['total_labassAmtWithoutGST'] : 0;
                $totalSubPaintingAmt = ($lossAssessment[0]['total_paintingassAmtWithoutGST'] - $totalPaintingDepAmt);

            @endphp

            <tr>
                <td align="right" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; font-weight: bold;  border-left: 1px solid #000;">Total</td>
                <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ (!empty($lossAssessment[0]['total_labestAmtWithoutGST']) && $lossAssessment[0]['total_labestAmtWithoutGST'] > 0) ? $lossAssessment[0]['total_labestAmtWithoutGST'] : '0.00' }}</td>
                <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ (!empty($lossAssessment[0]['total_labassAmtWithoutGST']) && $lossAssessment[0]['total_labassAmtWithoutGST'] > 0) ? $lossAssessment[0]['total_labassAmtWithoutGST'] : '0.00' }}</td>
                <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ (!empty($lossAssessment[0]['total_paintingassAmtWithoutGST']) && $lossAssessment[0]['total_paintingassAmtWithoutGST'] > 0) ? $lossAssessment[0]['total_paintingassAmtWithoutGST'] : '0.00' }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Less <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['IsZeroDep']==1) ? 0 : 50 }}%</span> on <span style="font-weight: bold;">25%</span> of Painting Material of <span style=" font-weight: bold;">Rs.{{ (!empty($totalPaintingMaterialAmt) && $totalPaintingMaterialAmt > 0) ? number_format_custom($totalPaintingMaterialAmt) : '0.00' }}</span></td>
                <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($totalPaintingDepAmt) && $totalPaintingDepAmt > 0) ? number_format_custom($totalPaintingDepAmt, 2) : '0.00' }}</td>
            </tr>
            <tr>
                <td align="right" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; font-weight: bold;  border-left: 1px solid #000;">Sub Total</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($totalSubEstimatedAmt) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($totalSubDentingAmt) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($totalSubPaintingAmt) }}</td>
            </tr>
            @if($lossAssessment[0]['IMTPaintingLabAss'] > 0)
                @php
                    $IMTPaintingLabAssRemarksShow = true;
                    $totalPaintingAmtAfterIMT23DepSubTotal = (($lossAssessment[0]['total_paintingassAmtWithoutGST'] - $totalPaintingDepAmt) - $subTotalafterDeduction);
                    $totalSubPaintingAmt = (!empty($subTotalafterDeduction) && $subTotalafterDeduction > 0) ? ($totalSubPaintingAmt - $subTotalafterDeduction) : $totalSubPaintingAmt;
                @endphp
                <tr>
                    <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Less Addl. Deduction <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['IMT23DepPer']) ? number_format_custom($lossAssessment[0]['IMT23DepPer'], 2) : '0' }}% </span> on IMT Labour of <span style=" font-weight: bold;">Rs.{{ number_format_custom($totalIMTLabourAmtAfterDep, 2, '.', '')}}</span>
                    <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ (!empty($subTotalafterDeduction) && $subTotalafterDeduction > 0) ? number_format_custom(($subTotalafterDeduction),2) : '0.00' }}</td>
                </tr>
                <tr>
                    <td align="right" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Sub Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ (!empty($lossAssessment[0]['total_labestAmtWithoutGST']) && $lossAssessment[0]['total_labestAmtWithoutGST'] > 0) ? number_format_custom($lossAssessment[0]['total_labestAmtWithoutGST'], 2, '.', '') : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ (!empty($lossAssessment[0]['total_labassAmtWithoutGST']) && $lossAssessment[0]['total_labassAmtWithoutGST'] > 0) ? number_format_custom($lossAssessment[0]['total_labassAmtWithoutGST'], 2, '.', '') : '0.00' }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; border-top: 1px solid #000;">{{ ($totalPaintingAmtAfterIMT23DepSubTotal > 0) ? number_format_custom($totalPaintingAmtAfterIMT23DepSubTotal) : '0.00' }}</td>
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
                $totalPaintingAmtAfterIMT23Dep = ($totalPaintingAmtAfterIMT23DepSubTotal > 0) ? $totalPaintingAmtAfterIMT23DepSubTotal : 0;
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
                            $totalAfterDepAmt = 0;
                        @endphp

                        @foreach($allLabourCharges as $key => $detail)
                            @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
                                @php
                                    $totalEstlabAmt += (!empty($detail['est_lab']) && $detail['est_lab'] > 0) ? $detail['est_lab'] : 0; // Sum up estimated amount
                                    if(empty($detail['quantities'])){
                                        if($detail['imt_23']=="Yes"){
                                            $imt23PaintinglabAmount += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                                            $totalPaintingPercentage = ($detail['painting_lab'] * 25) / 100;
                                            $totalPaintingDepAmt = ($totalPaintingPercentage/2);
                                            $totalAfterDepAmtTemp = ($detail['painting_lab'] - $totalPaintingDepAmt);
                                            $totalAfterDepAmt = $totalAfterDepAmtTemp;
                                            $totalDepAmt = ($totalAfterDepAmtTemp * $lossAssessment[0]['IMT23DepPer'] / 100);
                                            $totalPaintingAfterIMTDep += ($totalAfterDepAmt - $totalDepAmt);
                                        }else{
                                            $imt23WithoutlabTotalAmount += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                                            $totalPaintingPercentage = ($detail['painting_lab'] * 25) / 100;
                                            $totalPaintingDepAmt = ($totalPaintingPercentage/2);
                                            $totalPaintingAfterIMTDep += ($detail['painting_lab'] - $totalPaintingDepAmt);
                                            $totalAsslabAmt += !empty($detail['ass_lab']) ? $detail['ass_lab'] : 0;
                                            $totalPaintinglabAmt += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                                        }
                                    }
                                @endphp
                            @endif

                            @if(isset($detail['quantities']))
                                @foreach($detail['quantities'] as $sub_key => $partQuantity)
                                    @php
                                        $totalEstlabAmt += (!empty($partQuantity['est_lab']) && $partQuantity['est_lab'] > 0) ? $partQuantity['est_lab'] : 0; // Sum up estimated amount
                                        if($partQuantity['gst'] == $value) {
                                            if($partQuantity['imt_23']=="Yes"){
                                                $imt23PaintinglabAmount += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                                $totalPaintingPercentage = ($partQuantity['painting_lab'] * 25) / 100;
                                                $totalPaintingDepAmt = ($totalPaintingPercentage/2);
                                                $totalAfterDepAmtTemp = ($partQuantity['painting_lab'] - $totalPaintingDepAmt);
                                                $totalAfterDepAmt = $totalAfterDepAmtTemp;
                                                $totalDepAmt = ($totalAfterDepAmtTemp * $lossAssessment[0]['IMT23DepPer'] / 100);
                                                $totalPaintingAfterIMTDep += ($totalAfterDepAmt - $totalDepAmt);
                                            }else{
                                                $imt23WithoutlabTotalAmount += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                                $totalPaintingPercentage = ($partQuantity['painting_lab'] * 25) / 100;
                                                $totalPaintingDepAmt = ($totalPaintingPercentage/2);
                                                $totalPaintingAfterIMTDep += ($partQuantity['painting_lab'] - $totalPaintingDepAmt);
                                                $totalAsslabAmt += !empty($partQuantity['ass_lab']) ? $partQuantity['ass_lab'] : 0;
                                                $totalPaintinglabAmt += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                            }
                                        }
                                    @endphp
                                @endforeach
                            @endif
                        @endforeach
                        @php
                            $paintingLabourDepAmt = ((($totalPaintinglabAmt * $paintingMaterialDepPer) / 100) / 2);
                            $paintingLabourAfterDep = ($totalPaintinglabAmt - $paintingLabourDepAmt);
                            $imt23PaintingIMTDepAmt = ($paintingLabourAfterDep * $lossAssessment[0]['IMT23DepPer'] / 100);
                            //$totalPaintingAfterIMTDep = ($paintingLabourAfterDep + $imt23PaintingIMTDepAmt);

                            $totalSubEstimatedAmtTemp = $value != 0 ? number_format_custom(($totalEstlabAmt * $value / 100), 2, '.', '') : 0;
                            $totalSubDentingAmtTemp = ($value != 0 && ($totalAsslabAmt * $value / 100) > 0) ? number_format_custom(($totalAsslabAmt * $value / 100), 2, '.', '') : 0;
                            $totalSubPaintingAmtTemp = ($value != 0 && ($totalPaintingAfterIMTDep * $value / 100) > 0) ? number_format_custom(($totalPaintingAfterIMTDep * $value / 100), 2, '.', '') : 0;
                            $totalSubEstimatedAmt += $totalSubEstimatedAmtTemp;
                            $totalSubDentingAmt += $totalSubDentingAmtTemp;
                            $totalSubPaintingAmt += $totalSubPaintingAmtTemp;
                        @endphp

                        <tr>
                            <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $value }}%</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalSubEstimatedAmtTemp) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{!! number_format_custom($totalSubDentingAmtTemp) !!}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{!! number_format_custom($totalSubPaintingAmtTemp) !!}</td>
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
                    @php
                        $subTotalEstlabAmtTemp = (($lossAssessment[0]['total_labestAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100) > 0) ? number_format_custom(($lossAssessment[0]['total_labestAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100), 2, '.', '') : 0;
                        $subTotalEstlabAmt += $subTotalEstlabAmtTemp;
                        $subTotalAsslabAmtTemp = (($lossAssessment[0]['total_labassAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100) > 0) ? number_format_custom(($lossAssessment[0]['total_labassAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100), 2, '.', '') : 0;
                        $subTotalAsslabAmt += $subTotalAsslabAmtTemp;
                        $subTotalLabourAfterAmtTemp = (($totalPaintingAmtAfterIMT23Dep * $lossAssessment[0]['GSTLabourPer'] / 100) > 0) ? number_format_custom(($totalPaintingAmtAfterIMT23Dep * $lossAssessment[0]['GSTLabourPer'] / 100), 2, '.', '') : 0;
                        $subTotalLabourAfterAmt += $subTotalLabourAfterAmtTemp;

                        $totalSubEstimatedAmt += $subTotalEstlabAmtTemp;
                        $totalSubDentingAmt += $subTotalAsslabAmtTemp;
                        $totalSubPaintingAmt += $subTotalLabourAfterAmtTemp;
                    @endphp
                    <tr>
                        <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $lossAssessment[0]['GSTLabourPer'] }}%</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{!! number_format_custom($subTotalEstlabAmtTemp) !!}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{!! number_format_custom($subTotalAsslabAmtTemp) !!}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{!! number_format_custom($subTotalLabourAfterAmtTemp) !!}</td>
                    </tr>


                @elseif($lossAssessment[0]['GSTonEstimatedLab']=="Y" && $lossAssessment[0]['GstonAssessedLab']=="N")
                    @if($lossAssessment[0]['GstonAssessedLab']=="N")
                        @php
                            $subTotalAsslabAmtTemp = (($lossAssessment[0]['total_labassAmtWithoutGST'] * 0 / 100) > 0) ? number_format_custom(($lossAssessment[0]['total_labassAmtWithoutGST'] * 0 / 100), 2, '.', '') : 0;
                            $subTotalAsslabAmt += $subTotalAsslabAmtTemp;
                            $subTotalLabourAfterAmtTemp = (($totalPaintingAmtAfterIMT23Dep * 0 / 100) > 0) ? number_format_custom(($totalPaintingAmtAfterIMT23Dep * 0 / 100), 2, '.', '') : 0;
                            $subTotalLabourAfterAmt += $subTotalLabourAfterAmtTemp;
                            $totalSubDentingAmt += $subTotalAsslabAmtTemp;
                            $totalSubPaintingAmt += $subTotalLabourAfterAmtTemp;
                        @endphp
                        <tr>
                            <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST 0%</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">-</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($subTotalAsslabAmtTemp) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($subTotalLabourAfterAmtTemp) }}</td>
                        </tr>
                    @endif

                    @if($lossAssessment[0]['GSTonEstimatedLab']=="Y")
                        @php
                            $subTotalEstlabAmtTemp = (($lossAssessment[0]['total_labestAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100) > 0) ? number_format_custom(($lossAssessment[0]['total_labestAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100), 2, '.', '') : 0;
                            $subTotalEstlabAmt += $subTotalEstlabAmtTemp;
                            $totalSubEstimatedAmt += $subTotalEstlabAmtTemp;
                            //$subTotalAsslabAmt += ($lossAssessment[0]['total_labassAmtWithoutGST'] * 0 / 100);
                            //$subTotalLabourAfterAmt += floatval($totalPaintingAmtAfterIMT23Dep * 0 / 100);
                        @endphp
                        <tr>
                            <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $lossAssessment[0]['GSTLabourPer'] }}%</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($subTotalEstlabAmtTemp) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
                        </tr>
                    @endif
                @elseif($lossAssessment[0]['GSTonEstimatedLab']=="N" && $lossAssessment[0]['GstonAssessedLab']=="Y")
                    @if($lossAssessment[0]['GstonAssessedLab']=="Y")
                        @php
                            $subTotalAsslabAmtTemp = (($lossAssessment[0]['total_labassAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100) > 0) ? number_format_custom(($lossAssessment[0]['total_labassAmtWithoutGST'] * $lossAssessment[0]['GSTLabourPer'] / 100), 2, '.', '') : 0;
                            $subTotalAsslabAmt += $subTotalAsslabAmtTemp;
                            $subTotalLabourAfterAmtTemp = (($totalPaintingAmtAfterIMT23Dep * $lossAssessment[0]['GSTLabourPer'] / 100) > 0) ? number_format_custom(($totalPaintingAmtAfterIMT23Dep * $lossAssessment[0]['GSTLabourPer'] / 100), 2, '.', '') : 0;
                            $subTotalLabourAfterAmt += $subTotalLabourAfterAmtTemp;
                            $totalSubDentingAmt += $subTotalAsslabAmtTemp;
                            $totalSubPaintingAmt += $subTotalLabourAfterAmtTemp;
                        @endphp
                        <tr>
                            <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $lossAssessment[0]['GSTLabourPer'] }}%)</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">-</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($subTotalAsslabAmtTemp) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($subTotalLabourAfterAmtTemp) }}</td>
                        </tr>
                    @endif
                @elseif($lossAssessment[0]['GSTonEstimatedLab']=="N" && $lossAssessment[0]['GstonAssessedLab']=="N")
                    @php
                        $subTotalEstlabAmtTemp = (($lossAssessment[0]['total_labestAmtWithoutGST'] * 0 / 100) > 0) ? number_format_custom(($lossAssessment[0]['total_labestAmtWithoutGST'] * 0 / 100), 2, '.', '') : 0;
                        $subTotalEstlabAmt += $subTotalEstlabAmtTemp;
                        $subTotalAsslabAmtTemp = (($lossAssessment[0]['total_labassAmtWithoutGST'] * 0 / 100)) ? number_format_custom(($lossAssessment[0]['total_labassAmtWithoutGST'] * 0 / 100), 2, '.', '') : 0;
                        $subTotalAsslabAmt += $subTotalAsslabAmtTemp;
                        $subTotalLabourAfterAmtTemp = (($totalPaintingAmtAfterIMT23Dep * 0 / 100) > 0) ? number_format_custom(($totalPaintingAmtAfterIMT23Dep * 0 / 100), 2, '.', '') : 0;
                        $subTotalLabourAfterAmt += $subTotalLabourAfterAmtTemp;
                        $totalSubEstimatedAmt += $subTotalEstlabAmtTemp;
                        $totalSubDentingAmt += $subTotalAsslabAmtTemp;
                        $totalSubPaintingAmt += $subTotalLabourAfterAmtTemp;
                    @endphp
                    <tr>
                        <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST 0%)</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($subTotalEstlabAmtTemp) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($subTotalAsslabAmtTemp) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($subTotalLabourAfterAmtTemp) }}</td>
                    </tr>
                @endif
            @endif

            @php
                $totalEstimated = number_format_custom($subTotalEstlabAmt + (!empty($lossAssessment[0]['total_labestAmtWithoutGST']) ? $lossAssessment[0]['total_labestAmtWithoutGST'] : '0.00'), 2, '.', '');
                $totalOFDenting = number_format_custom($subTotalAsslabAmt + (!empty($lossAssessment[0]['total_labestAmtWithoutGST']) ? $lossAssessment[0]['total_labestAmtWithoutGST'] : '0.00'), 2, '.', '');
                $netLabourAmount = number_format_custom(($subTotalAsslabAmt + $lossAssessment[0]['total_labassAmtWithoutGST'] + $totalPaintingAmtAfterIMT23Dep + $subTotalLabourAfterAmt), 2, '.', '');
            @endphp
            <tr>
                <td align="right" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Total</td>
                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{ ($totalSubEstimatedAmt > 0) ? number_format_custom($totalSubEstimatedAmt) : '0.00' }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{ ($totalSubDentingAmt > 0) ? number_format_custom($totalSubDentingAmt) : '0.00' }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{ number_format_custom($totalSubPaintingAmt) }}</td>
{{--                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{ (($totalPaintingAmtAfterIMT23Dep + $subTotalLabourAfterAmt) > 0) ? number_format_custom(($totalPaintingAmtAfterIMT23Dep + $subTotalLabourAfterAmt), 2, '.', '') : '0.00' }}</td>--}}
            </tr>
            <tr>
                <td align="left" valign="top" colspan="{{$labcolspan}}" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Net Labour Amount (O/F & D/B + Painting Labour)</td>
                <td align="right" valign="top" colspan="3" style="padding: 0px 3px; font-weight: bold; border-top: 1px solid #000;">{{ ($netLabourAmount > 0) ? number_format_custom($netLabourAmount) : '0.00' }}</td>
            </tr>
            </tbody>
        </table>
        @if(!empty($IMTPaintingLabAssRemarksShow))
            <table width="100%" border="0" align="left" style="font-size: 12px; font-weight: bold; padding-top: 3px;">
                <tr>
                    <td>* The Star marks denotes against the IMT-21  Painting allowed.</td>
                </tr>
            </table>
        @endif
    @endif
@endif