@if(isset($lossAssessment[0]['alldetails']))
    @php
        $allLabourCharges = json_decode($lossAssessment[0]['alldetails'], true);

        $labcolspan = 4; // Default colspan value
        $gstUniqueValue =[];
        $indexCounter = 0;
        $paintingindexCounter = 0;
        $imtindexCounter = 0;
    @endphp
    <table width="100%" align="center" id="design">
        <tbody>
        <tr>
            <td align="left" valign="top"
                style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">
                LABOUR TAX SUMMARY
            </td>
        </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
        <tr>
            <td align="center" valign="top"
                style="padding: 0px 3px; width: 17%; border-left: 1px solid #000; font-weight: bold;">Sr. No.
            </td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 18%; font-weight: bold;">Tax %</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>

            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">GST Amt.</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">IGST Amt</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amount with GST
            </td>
        </tr>
        @foreach($allLabourCharges as $index => $detail)
            @if($detail['est_lab'] != 0 || $detail['ass_lab'] != 0)
                @php
                    $gstUniqueValue[]= $detail['gst'];
                @endphp
            @endif
            @if(!empty($detail['quantities']))
                @foreach($detail['quantities'] as $quantityIndex => $quantity)
                    @if($quantity['est_lab'] != 0 || $quantity['ass_lab'] != 0)
                        @php
                            $gstUniqueValue[]= $quantity['gst'];
                        @endphp
                    @endif
                @endforeach

            @endif
        @endforeach

        @php
            $uniqueGstRates = []; // Initialize an array to store unique GST rates
            $subUniqueGstRates = array_values(array_unique($gstUniqueValue));
            $grandtotalPartAmount = 0;
            $grandtotalGSTOrIGSTAmtPer = 0;
            $grandtotalWithGSTorIGSTAmount = 0;
            $checkGST = true;
            if($lossAssessment[0]['MultipleGSTonLab'] == 0 && $lossAssessment[0]['GstonAssessedLab'] == 'Y' && $lossAssessment[0]['GSTLabourPer'] > 0) {
                $subUniqueGstRates = [$lossAssessment[0]['GSTLabourPer']];
                $checkGST = false;
            } elseif($lossAssessment[0]['MultipleGSTonLab'] == 0 && $lossAssessment[0]['GstonAssessedLab'] == 'N') {
                $subUniqueGstRates = [0];
                $checkGST = false;
            }
            sort($subUniqueGstRates);


        @endphp

        @foreach($subUniqueGstRates as $value)
            @unless(in_array($value, $uniqueGstRates))
                @php
                    $totalAsslabAmtArr = [];
                    $totalEstlabAmt = 0; // Initialize total estimated amount
                    $totalAsslabAmt = 0;
                    $totalPaintinglabAmt = 0;
                    $imt23PaintinglabTotalAmount=0;
                    $totalIMTLaboutAmt=0;
                    $indexCounter++;
                    $totalAssLabAmount =0;
                    $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                @endphp

                @foreach($allLabourCharges as $detail)
                    @if((($detail['gst'] == $value && !empty($checkGST)) || empty($checkGST)) && empty($detail['quantities']))
                        @php
                            $totalEstlabAmt += !empty($detail['est_lab']) ? $detail['est_lab'] : 0; // Sum up estimated amount
                            $totalAsslabAmt += !empty($detail['ass_lab']) ? $detail['ass_lab'] : 0;
                            $totalPaintinglabAmt += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                        @endphp
                    @endif

                    @if(isset($detail['quantities']) && !empty($detail['quantities']))
                        @foreach($detail['quantities'] as $partQuantity)
                            @if(($partQuantity['gst'] == $value && !empty($checkGST)) || empty($checkGST))
                                @php
                                    if(!empty($detail['ass_lab']) && $partQuantity['ass_lab'] > 0) {
                                        $totalAsslabAmtArr[] = $partQuantity['ass_lab'];
                                    }
                                    $totalEstlabAmt += !empty($partQuantity['est_lab']) ? $partQuantity['est_lab'] : 0; // Sum up estimated amount
                                    $totalAsslabAmt += !empty($partQuantity['ass_lab']) ? $partQuantity['ass_lab'] : 0;
                                    $totalPaintinglabAmt += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                @endphp
                            @endif
                        @endforeach
                    @endif
                @endforeach
                @php
                    $totalAssLabAmount = ($totalAsslabAmt);
                    $totalGSTOrIGSTAmtPer = ((($totalAssLabAmount) * $value) / 100);
                    $totalWithGSTorIGSTAmount = ($totalAssLabAmount + $totalGSTOrIGSTAmtPer);
                @endphp
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{$indexCounter}}</td>
                    <td align="center" valign="top" style="padding: 0px 3px; ">{{$value}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{{  number_format_custom($totalAssLabAmount ,2)}}</td>
                    @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer ,2)}}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                    @endif
                    @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer ,2)}}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                    @endif
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalWithGSTorIGSTAmount ,2)}}</td>
                </tr>

                @php
                    $grandtotalPartAmount += ($totalAsslabAmt);
                    $grandtotalGSTOrIGSTAmtPer += ((($totalAssLabAmount) * $value) / 100);
                    $grandtotalWithGSTorIGSTAmount += ($totalAssLabAmount + $totalGSTOrIGSTAmtPer);
                @endphp
            @endunless
        @endforeach


        <tr>
            <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;"
                colspan="2">Grand Total
            </td>
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalPartAmount),2) }}</td>
            @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalGSTOrIGSTAmtPer),2) }}</td>
            @else
                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
            @endif
            @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalGSTOrIGSTAmtPer),2) }}</td>
            @else
                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
            @endif
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalWithGSTorIGSTAmount),2) }}</td>
        </tr>
        </tbody>
    </table>

    <table width="100%" align="center" id="design">
        <tbody>
        <tr>
            <td align="left" valign="top"
                style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">
                PAINTING LABOUR TAX SUMMARY
            </td>
        </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
        <tr>
            <td align="center" valign="top"
                style="padding: 0px 3px; width: 10%; border-left: 1px solid #000; font-weight: bold;">Sr. No.
            </td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">Tax %</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 14%; font-weight: bold;">Dep. Amt.</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep.
            </td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 13%; font-weight: bold;">GST Amt.</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 13%; font-weight: bold;">IGST Amt.</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Total Amt.</td>
        </tr>
        @foreach($allLabourCharges as $index => $detail)
            @if($detail['painting_lab'] != 0)
                @php
                    $gstUniqueValue[] = $detail['gst'];
                @endphp
            @endif
            @if(!empty($detail['quantities']))
                @foreach($detail['quantities'] as $quantityIndex => $quantity)
                    @if($quantity['painting_lab'] != 0)
                        @php
                            $gstUniqueValue[]= $quantity['gst'];
                        @endphp
                    @endif
                @endforeach
            @endif
        @endforeach

        @php
            $uniqueGstRates = []; // Initialize an array to store unique GST rates
            $subUniqueGstRates = array_values(array_unique($gstUniqueValue));
            $grandtotalPaintingAmount= 0;
            $grandtotalPaintingDepAmount= 0;
            $grandtotalPaintingAfterDepAmount= 0;
            $grandtotalPaintingGSTOrIGSTAmtPer = 0;
            $grandtotalPaintingWithGSTorIGSTAmount = 0;
            $checkGST = true;
            if($lossAssessment[0]['MultipleGSTonLab'] == 0 && $lossAssessment[0]['GstonAssessedLab'] == 'Y' && $lossAssessment[0]['GSTLabourPer'] > 0) {
                $subUniqueGstRates = [$lossAssessment[0]['GSTLabourPer']];
                $checkGST = false;
            } elseif($lossAssessment[0]['MultipleGSTonLab'] == 0 && $lossAssessment[0]['GstonAssessedLab'] == 'N') {
                $subUniqueGstRates = [0];
                $checkGST = false;
            }
            sort($subUniqueGstRates);
        @endphp

        @foreach($subUniqueGstRates as $value)
            @unless(in_array($value, $uniqueGstRates))
                @php
                    $totalEstlabAmt = 0; // Initialize total estimated amount
                    $totalAsslabAmt = 0;
                    $totalPaintinglabAmt = 0;
                    $imt23PaintinglabTotalAmount=0;
                    $totalIMTLaboutAmt=0;
                    $paintingindexCounter++;
                    $totalPaitntingLabAmount =0;
                    $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                @endphp

                @foreach($allLabourCharges as $detail)

                    @if((($detail['gst'] == $value && !empty($checkGST)) || empty($checkGST)) && ($detail['imt_23'] == null || $detail['imt_23'] == "No" || empty($detail['imt_23']))) {{-- Check if GST rate matches --}}
                        @if(empty($detail['quantities']))
                            @php
                                $totalEstlabAmt += !empty($detail['est_lab']) ? $detail['est_lab'] : 0;
                                $totalAsslabAmt += !empty($detail['ass_lab']) ? $detail['ass_lab'] : 0;
                                $totalPaintinglabAmt += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                            @endphp
                        @endif
                    @endif

                    @if(isset($detail['quantities']))
                        @foreach($detail['quantities'] as $partQuantity)
                            @if((($partQuantity['gst'] == $value && !empty($checkGST)) || empty($checkGST)) && ($partQuantity['imt_23'] == null || $partQuantity['imt_23'] == "No" || empty($partQuantity['imt_23'])))
                                @php
                                    $totalEstlabAmt += !empty($partQuantity['est_lab']) ? $partQuantity['est_lab'] : 0; // Sum up estimated amount
                                    $totalAsslabAmt += !empty($partQuantity['ass_lab']) ? $partQuantity['ass_lab'] : 0;
                                    $totalPaintinglabAmt += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                @endphp
                            @endif
                        @endforeach
                    @endif
                @endforeach
                @php
                    $totalPaitntingLabAmount = ($totalPaintinglabAmt);
                    $totalDepPaintingAmt = (($totalPaitntingLabAmount * 25) / 100);
                    $totalDepHalfAmt = ($totalDepPaintingAmt/2);
                    if($lossAssessment[0]['IsZeroDep']==1){
                        $totalDepHalfAmt = 0.00;
                    }
                    $totalPaintingAmtAfterDep = ($totalPaitntingLabAmount - $totalDepHalfAmt);

                    $totalGSTOrIGSTAmtPer = ((($totalPaintingAmtAfterDep) * $value) / 100);

                    $totalWithGSTorIGSTAmount = ($totalPaintingAmtAfterDep + $totalGSTOrIGSTAmtPer);
                @endphp
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">{{ $paintingindexCounter}}</td>
                    <td align="center" valign="top" style="padding: 0px 3px;">{{$value}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalPaitntingLabAmount ,2)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalDepHalfAmt ,2)}}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalPaintingAmtAfterDep ,2)}}</td>
                    @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer ,2)}}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                    @endif
                    @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                        <td align="right" valign="top"
                            style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer ,2)}}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                    @endif
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalWithGSTorIGSTAmount ,2)}}</td>
                </tr>

                @php

                    $grandtotalPaintingAmount += ($totalPaintinglabAmt);
                    $grandtotalPaintingDepAmount  += ($totalDepHalfAmt);
                    $grandtotalPaintingAfterDepAmount  += ($totalPaitntingLabAmount - $totalDepHalfAmt);
                    $grandtotalPaintingGSTOrIGSTAmtPer += ((($totalPaintingAmtAfterDep) * $value) / 100);
                    $grandtotalPaintingWithGSTorIGSTAmount += ($totalPaintingAmtAfterDep + $totalGSTOrIGSTAmtPer);
                @endphp
            @endunless
        @endforeach
        <tr>
            <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;"
                colspan="2">Grand Total
            </td>
            <td align="right" valign="top"
                style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandtotalPaintingAmount ,2) }}</td>
            <td align="right" valign="top"
                style="padding: 0px 3px; font-weight: bold;">{{  number_format_custom($grandtotalPaintingDepAmount ,2)  }}</td>
            <td align="right" valign="top"
                style="padding: 0px 3px; font-weight: bold;">{{  number_format_custom($grandtotalPaintingAfterDepAmount ,2) }}</td>
            @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandtotalPaintingGSTOrIGSTAmtPer ,2) }}</td>
            @else
                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
            @endif
            @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandtotalPaintingGSTOrIGSTAmtPer ,2) }}</td>
            @else
                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
            @endif
            <td align="right" valign="top"
                style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandtotalPaintingWithGSTorIGSTAmount ,2) }}</td>
        </tr>
        </tbody>
    </table>


    @if($lossAssessment[0]['IMTPaintingLabAss'] != 0.00)
        <table width="100%" align="center" id="design">
            <tbody>
            <tr>
                <td align="left" valign="top"
                    style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">
                    IMT PAINTING LABOUR TAX SUMMARY
                </td>
            </tr>
            </tbody>
        </table>
        <table width="100%" align="center" id="design" style="font-size: 14px;">
            <tbody>
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; border-left: 1px solid #000; font-weight: bold;">Sr. No.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 11%; font-weight: bold;">Tax %</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Total Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 17%; font-weight: bold;">Amt. After Dep.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 17%; font-weight: bold;">IMT Dep.Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 13%; font-weight: bold;">GST Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 13%; font-weight: bold;">IGST Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 19%; font-weight: bold;">Total Amt.</td>
            </tr>
            @foreach($allLabourCharges as $index => $detail)
                @if($detail['painting_lab'] != 0)
                    @php
                        $gstUniqueValue[]= $detail['gst'];
                    @endphp
                @endif
                @if(!empty($detail['quantities']))
                    @foreach($detail['quantities'] as $quantityIndex => $quantity)
                        @if($quantity['painting_lab'] != 0)
                            @php
                                $gstUniqueValue[]= $quantity['gst'];
                            @endphp
                        @endif
                    @endforeach

                @endif
            @endforeach

            @php
                $uniqueGstRates = []; // Initialize an array to store unique GST rates
                $subUniqueGstRates = array_values(array_unique($gstUniqueValue));
                sort($subUniqueGstRates);
                $grandtotalPaintingAmount= 0;
                $grandtotalPaintingDepAmount= 0;
                $grandtotalPaintingAfterDepAmount= 0;
                $grandIMTDepAmount = 0;
                $grandPaintingAmtAfterIMTDep = 0;
                $grandtotalPaintingGSTOrIGSTAmtPer = 0;
                $grandtotalPaintingWithGSTorIGSTAmount = 0;
                $checkGST = true;
                if($lossAssessment[0]['MultipleGSTonLab'] == 0 && $lossAssessment[0]['GstonAssessedLab'] == 'Y' && $lossAssessment[0]['GSTLabourPer'] > 0) {
                    $subUniqueGstRates = [$lossAssessment[0]['GSTLabourPer']];
                    $checkGST = false;
                } elseif($lossAssessment[0]['MultipleGSTonLab'] == 0 && $lossAssessment[0]['GstonAssessedLab'] == 'N') {
                    $subUniqueGstRates = [0];
                    $checkGST = false;
                }
                sort($subUniqueGstRates);
            @endphp

            @foreach($subUniqueGstRates as $value)
                @unless(in_array($value, $uniqueGstRates))
                    @php
                        $totalEstlabAmt = 0; // Initialize total estimated amount
                        $totalAsslabAmt = 0;
                        $totalPaintinglabAmt = 0;
                        $imt23PaintinglabTotalAmount=0;
                        $totalIMTLaboutAmt=0;
                        $imtindexCounter++;
                        $totalPaitntingLabAmount =0;
                        $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                    @endphp

                    @foreach($allLabourCharges as $detail)
                        @if((($detail['gst'] == $value && !empty($checkGST)) || empty($checkGST)) && $detail['imt_23'] == "Yes") {{-- Check if GST rate matches --}}
                            @if(empty($detail['quantities']))
                                @php
                                    $totalEstlabAmt += !empty($detail['est_lab']) ? $detail['est_lab'] : 0; // Sum up estimated amount
                                    $totalAsslabAmt += !empty($detail['ass_lab']) ? $detail['ass_lab'] : 0;
                                    $totalPaintinglabAmt += !empty($detail['painting_lab']) ? $detail['painting_lab'] : 0;
                                @endphp
                            @endif
                        @endif

                        @if(isset($detail['quantities']))
                            @foreach($detail['quantities'] as $partQuantity)
                                @if((($partQuantity['gst'] == $value && !empty($checkGST)) || empty($checkGST)) && $partQuantity['imt_23'] == "Yes")
                                    @php
                                        $totalEstlabAmt += !empty($partQuantity['est_lab']) ? $partQuantity['est_lab'] : 0; // Sum up estimated amount
                                        $totalAsslabAmt += !empty($partQuantity['ass_lab']) ? $partQuantity['ass_lab'] : 0;
                                        $totalPaintinglabAmt += !empty($partQuantity['painting_lab']) ? $partQuantity['painting_lab'] : 0;
                                    @endphp
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    @php
                        $totalPaitntingLabAmount = ($totalPaintinglabAmt);
                        $totalDepPaintingAmt = (($totalPaitntingLabAmount * 25) / 100);
                        $totalDepHalfAmt = ($totalDepPaintingAmt/2);
                        if($lossAssessment[0]['IsZeroDep']==1){
                          $totalDepHalfAmt = 0.00;
                        }
                        $totalPaintingAmtAfterDep = ($totalPaitntingLabAmount - $totalDepHalfAmt);

                        $IMTDepAmount = ((($totalPaintingAmtAfterDep) * $lossAssessment[0]['IMT23DepPer']) / 100);

                        $paintingAmtAfterIMTDep = ($totalPaintingAmtAfterDep - $IMTDepAmount);

                        $totalGSTOrIGSTAmtPer = ((($paintingAmtAfterIMTDep) * $value) / 100);

                        $totalWithGSTorIGSTAmount = ($paintingAmtAfterIMTDep + $totalGSTOrIGSTAmtPer);
                    @endphp
                    <tr>
                        <td align="center" valign="top"
                            style="padding: 0px 3px; border-left: 1px solid #000;">{{ $imtindexCounter }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px;">{{$value}}</td>
                        <td align="right" valign="top"
                            style="padding: 0px 3px;">{{ number_format_custom($totalPaitntingLabAmount ,2)}}</td>
                        <td align="right" valign="top"
                            style="padding: 0px 3px;">{{ number_format_custom($totalDepHalfAmt ,2)}}</td>
                        <td align="right" valign="top"
                            style="padding: 0px 3px;">{{ number_format_custom($totalPaintingAmtAfterDep ,2)}}</td>
                        <td align="right" valign="top"
                            style="padding: 0px 3px;">{{ number_format_custom($IMTDepAmount ,2)}}</td>
                        <td align="right" valign="top"
                            style="padding: 0px 3px;">{{ number_format_custom($paintingAmtAfterIMTDep ,2)}}</td>
                        @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                            <td align="right" valign="top"
                                style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer ,2)}}</td>
                        @else
                            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                        @endif
                        @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                            <td align="right" valign="top"
                                style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer ,2)}}</td>
                        @else
                            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                        @endif
                        <td align="right" valign="top"
                            style="padding: 0px 3px;">{{ number_format_custom($totalWithGSTorIGSTAmount ,2)}}</td>
                    </tr>

                    @php

                        $grandtotalPaintingAmount += ($totalPaintinglabAmt);
                        $grandtotalPaintingDepAmount  += ($totalDepHalfAmt);
                        $grandtotalPaintingAfterDepAmount  += ($totalPaitntingLabAmount - $totalDepHalfAmt);
                        $grandIMTDepAmount = ((($totalPaintingAmtAfterDep) * $lossAssessment[0]['IMT23DepPer']) / 100);
                        $grandPaintingAmtAfterIMTDep = ($totalPaintingAmtAfterDep - $IMTDepAmount);

                        $grandtotalPaintingGSTOrIGSTAmtPer += ((($paintingAmtAfterIMTDep) * $value) / 100);
                        $grandtotalPaintingWithGSTorIGSTAmount += ($paintingAmtAfterIMTDep + $totalGSTOrIGSTAmtPer);
                    @endphp
                @endunless
            @endforeach
            <tr>
                <td align="center" valign="top"
                    style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total
                </td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalPaintingAmount) ,2)}}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{  number_format_custom(round($grandtotalPaintingDepAmount) ,2) }}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{  number_format_custom(round($grandtotalPaintingAfterDepAmount) ,2)}}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{  number_format_custom(round($grandIMTDepAmount) ,2)}}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{  number_format_custom(round($grandPaintingAmtAfterIMTDep) ,2)}}</td>
                @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                    <td align="right" valign="top"
                        style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalPaintingGSTOrIGSTAmtPer) ,2)}}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                    <td align="right" valign="top"
                        style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalPaintingGSTOrIGSTAmtPer) ,2)}}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalPaintingWithGSTorIGSTAmount) ,2)}}</td>
            </tr>
            </tbody>
        </table>
    @endif
@endif