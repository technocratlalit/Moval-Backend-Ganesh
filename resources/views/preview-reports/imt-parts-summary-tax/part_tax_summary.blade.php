<table width="100%" align="center" id="design">
    <tbody>
        <tr>
            <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold; padding-top:10px;"><span style="text-decoration:underline;">PARTS TAX SUMMARY </span></td>
        </tr>
    </tbody>
</table>
<table width="100%" align="center" id="design" style="font-size: 14px;">
    <tbody>
        <tr>
            <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Total Amt.</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amt. After Dep.</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">GST</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">IGST</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
        </tr>

        @php
            $alldetails = (isset($lossAssessment[0]['alldetails']) && !empty($lossAssessment[0]['alldetails'])) ?  json_decode($lossAssessment[0]['alldetails'], true) : [];
            $uniqueGstRates = []; // Initialize an array to store unique GST rates
            $subUniqueGstRates = [];
            $uniqueGSTValues = [];

            // Loop through each item in $alldetails array
            foreach ($alldetails as $detail) {
                // Check if the current GST value exists in $uniqueGSTValues array
                if (!in_array($detail['gst'], $uniqueGSTValues)) {
                    // If not, add it to the $uniqueGSTValues array
                    $uniqueGSTValues[] = $detail['gst'];
                }

                if(isset($detail['quantities'])){
                    foreach($detail['quantities'] as $subpartValue){
                        if (!in_array($subpartValue['gst'], $uniqueGSTValues)) {
                          // If not, add it to the $uniqueGSTValues array
                          $uniqueGSTValues[] = $subpartValue['gst'];
                        }
                    }
                }
            }

            $subUniqueGstRates = array_values(array_unique($uniqueGSTValues));
            sort($subUniqueGstRates);
            $counter = count($subUniqueGstRates);

            $grandtotalPartAmount = 0;
            $grandtotalDepAmount = 0;
            $grandtotalAfterDepAmount = 0;
            $grandtotalGSTOrIGSTAmtPer = 0;
            $grandtotalWithGSTorIGSTAmount = 0;
        @endphp
        @foreach($subUniqueGstRates as $index => $value)
            @unless(in_array($value, $uniqueGstRates))
                @php
                    $totalGSTOrIGSTAmtPer = 0;
                    $totalMetalAmt = 0;
                    $totalRubberAmt = 0; // Initialize total assessed amount
                    $totalGlassAmt = 0;
                    $totalFibreAmt = 0;
                    $totalReconditionAmt = 0;

                    $totalPartEstAmt = 0;
                    $totalPartMetalAmt = 0;
                    $totalPartRubberAmt = 0;
                    $totalPartGlassAmt = 0;
                    $totalPartFibreAmt = 0;
                    $totalPartReconditionAmt = 0;

                    $totalMainPartsAmount =0 ;
                    $totalSubPartsAmount =0 ;
                    $totalPartsAmount = 0;
                    $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                @endphp

                @foreach($alldetails as $detail)
                    @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
                        @php
                            // Sum up assessed amount for respective categories
                            if(empty($detail['quantities'])){
                                if((empty($detail['imt_23']) || $detail['imt_23'] == "No" || is_null($detail['imt_23']))) {
                                    switch ($detail['category']) {
                                        case 'Metal':
                                            $totalMetalAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                            break;
                                        case 'Rubber':
                                            $totalRubberAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                            break;
                                        case 'Glass':
                                            $totalGlassAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                            break;
                                        case 'Fibre':
                                            $totalPartFibreAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                            break;
                                        case 'Recondition':
                                            $totalPartReconditionAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                            break;
                                    }
                                }
                            }
                        @endphp
                    @endif

                    @if(isset($detail['quantities']) && !empty($detail['quantities']))
                        @foreach($detail['quantities'] as $partQuantity)
                            @if($partQuantity['gst'] == $value)
                                @php
                                    if((empty($partQuantity['imt_23']) || $partQuantity['imt_23'] == "No" || is_null($partQuantity['imt_23']))) {
                                        // Calculate totals for subparts with the same GST percentage
                                        switch ($partQuantity['category']) {
                                            case 'Metal':
                                                $totalPartMetalAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                break;
                                            case 'Rubber':
                                                $totalPartRubberAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                break;
                                            case 'Glass':
                                                $totalPartGlassAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                break;
                                            case 'Fibre':
                                                $totalPartFibreAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                break;
                                            case 'Recondition':
                                            $totalPartReconditionAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                            break;
                                        }
                                    }
                                @endphp
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @php
                    $totalPartsAmount = ($totalMetalAmt + $totalPartMetalAmt + $totalRubberAmt + $totalPartRubberAmt + $totalGlassAmt + $totalPartGlassAmt + $totalPartFibreAmt + $totalPartReconditionAmt);
                    $totalDepMetalAmt = ((($totalMetalAmt + $totalPartMetalAmt) * $lossAssessment[0]['MetalDepPer']) / 100);
                    $totalDepRubberAmt = ((($totalRubberAmt + $totalPartRubberAmt) * $lossAssessment[0]['RubberDepPer']) / 100);
                    $totalDepGlassAmt = ((($totalGlassAmt + $totalPartGlassAmt) * $lossAssessment[0]['GlassDepPer']) / 100);
                    $totalDepFibreAmt = ((($totalPartFibreAmt) * $lossAssessment[0]['FibreDepPer']) / 100);

                    $totalDepAmount = ($totalDepMetalAmt + $totalDepRubberAmt + $totalDepGlassAmt + $totalDepFibreAmt);
                    $totalAmtAfterDep = ($totalPartsAmount - $totalDepAmount);

                    if($lossAssessment[0]['MutipleGSTonParts']==1 && $lossAssessment[0]['GSTAssessedPartsPer'] !=0){
                       $totalGSTOrIGSTAmtPer = ((($totalAmtAfterDep) * $value) / 100);
                    }

                    if($lossAssessment[0]['MutipleGSTonParts']==0 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=0)){
                       $totalGSTOrIGSTAmtPer = ((($totalAmtAfterDep) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                    }elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] == 0)){
                       $totalGSTOrIGSTAmtPer = ((($totalAmtAfterDep) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                    }
                    $totalWithGSTorIGSTAmount = ($totalAmtAfterDep + $totalGSTOrIGSTAmtPer);
                @endphp

                @if($lossAssessment[0]['MutipleGSTonParts']==1 && $lossAssessment[0]['GSTAssessedPartsPer'] !=0)
                    <tr>
                        <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{$index + 1 }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px; ">{{$value}}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($totalPartsAmount, 2)}}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($totalDepAmount, 2)}}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($totalAmtAfterDep, 2)}}</td>
                        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
                            <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($totalGSTOrIGSTAmtPer, 2)}}</td>
                        @else
                            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                        @endif
                        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
                            <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($totalGSTOrIGSTAmtPer, 2)}}</td>
                        @else
                            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                        @endif
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($totalWithGSTorIGSTAmount, 2)}}</td>
                    </tr>
                @endif

                @php
                    $grandtotalPartAmount += ($totalMetalAmt + $totalPartMetalAmt + $totalRubberAmt + $totalPartRubberAmt + $totalGlassAmt + $totalPartGlassAmt + $totalPartFibreAmt + $totalPartReconditionAmt);
                    $grandtotalDepAmount  += ($totalDepMetalAmt + $totalDepRubberAmt + $totalDepGlassAmt + $totalDepFibreAmt);
                    $grandtotalAfterDepAmount  += ($totalPartsAmount - $totalDepAmount);
                    if($lossAssessment[0]['MutipleGSTonParts']==1){
                        $grandtotalGSTOrIGSTAmtPer += ((($totalAmtAfterDep) * $value) / 100);
                    }
                    if($lossAssessment[0]['MutipleGSTonParts']==0 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=0)){
                        $grandtotalGSTOrIGSTAmtPer += ((($totalAmtAfterDep) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                    }elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0)){
                        $grandtotalGSTOrIGSTAmtPer = ((($totalAmtAfterDep) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                    }
                    $grandtotalWithGSTorIGSTAmount += ($totalAmtAfterDep + $totalGSTOrIGSTAmtPer);
                @endphp
            @endunless
        @endforeach

        @if($lossAssessment[0]['MutipleGSTonParts']==0 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=0))
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
                <td align="center" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($lossAssessment[0]['GSTAssessedPartsPer'], 2) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalPartAmount, 2)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalDepAmount, 2)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalAfterDepAmount, 2)}}</td>
                @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalGSTOrIGSTAmtPer, 2)}}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalGSTOrIGSTAmtPer, 2)}}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalWithGSTorIGSTAmount, 2)}}</td>
            </tr>
        @elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0))
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
                <td align="center" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($lossAssessment[0]['GSTAssessedPartsPer'], 2) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalPartAmount, 2)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalDepAmount, 2)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalAfterDepAmount, 2)}}</td>
                @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalGSTOrIGSTAmtPer, 2)}}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalGSTOrIGSTAmtPer, 2)}}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalWithGSTorIGSTAmount, 2)}}</td>
            </tr>
        @endif
        <tr>
            <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandtotalPartAmount, 2)}}</td>
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandtotalDepAmount, 2)}}</td>
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{number_format_custom($grandtotalAfterDepAmount, 2)}}</td>
            @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandtotalGSTOrIGSTAmtPer, 2)}}</td>
            @else
                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
            @endif
            @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandtotalGSTOrIGSTAmtPer, 2)}}</td>
            @else
                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
            @endif
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandtotalWithGSTorIGSTAmount, 2)}}</td>
        </tr>
    </tbody>
</table>
@if($lossAssessment[0]['totalRubberIMTAmt'] != 0.00 || $lossAssessment[0]['totalMetalIMTAmt'] != 0.00)
    <table width="100%" align="center" id="design">
        <tbody>
            <tr><td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">IMT PARTS</td></tr>
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
            @php
                $uniqueGstRates = []; // Initialize an array to store unique GST rates
                $subUniqueGstRates = [];
                $subUniqueGstRates = array_values(array_unique($subPartUniqueValue));
                sort($subUniqueGstRates);
                $counter = count($subUniqueGstRates);
                $grandtotalPartAmount = 0;
                $grandtotalDepAmount = 0;
                $grandtotalAfterDepAmount = 0;
                $grandtotalGSTOrIGSTAmtPer = 0;
                $grandtotalWithGSTorIGSTAmount = 0;
                $grandTotalIMTDesp = 0;
                $grandTotalIMTAfterDesp = 0;
            @endphp
            @foreach($subUniqueGstRates as $index=>$value)
                @unless(in_array($value, $uniqueGstRates))
                    @php
                        $totalGSTOrIGSTAmtPer = 0;
                        $totalMetalAmt = 0;
                        $totalRubberAmt = 0; // Initialize total assessed amount
                        $totalGlassAmt = 0;
                        $totalFibreAmt = 0;
                        $totalReconditionAmt = 0;

                        $totalPartEstAmt = 0;
                        $totalPartMetalAmt = 0;
                        $totalPartRubberAmt = 0;
                        $totalPartGlassAmt = 0;
                        $totalPartFibreAmt = 0;
                        $totalPartReconditionAmt = 0;

                        $totalMainPartsAmount =0 ;
                        $totalSubPartsAmount =0 ;
                        $totalPartsAmount = 0;
                        $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                    @endphp

                    @foreach($alldetails as $detail)
                        @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
                            @php
                                // Sum up assessed amount for respective categories
                                if(empty($detail['quantities']) && !empty($detail['imt_23']) && $detail['imt_23'] == "Yes") {
                                    switch ($detail['category']) {
                                        case 'Metal':
                                            $totalMetalAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                            break;
                                        case 'Rubber':
                                            $totalRubberAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                            break;
                                        case 'Glass':
                                            $totalGlassAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                                            break;
                                        case 'Fibre':
                                                $totalPartFibreAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                break;
                                        case 'Recondition':
                                            $totalPartReconditionAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                            break;
                                    }
                                }
                            @endphp
                        @endif

                        @if(isset($detail['quantities']) && !empty($detail['quantities']))
                            @foreach($detail['quantities'] as $partQuantity)
                                @if($partQuantity['gst'] == $value)
                                    @php
                                        // Calculate totals for subparts with the same GST percentage
                                        if(!empty($partQuantity['imt_23']) && $partQuantity['imt_23']=="Yes") {
                                            switch ($partQuantity['category']) {
                                                case 'Metal':
                                                    $totalPartMetalAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                    break;
                                                case 'Rubber':
                                                    $totalPartRubberAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                    break;
                                                case 'Glass':
                                                    $totalPartGlassAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                    break;
                                                case 'Fibre':
                                                    $totalPartFibreAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                    break;
                                                case 'Recondition':
                                                    $totalPartReconditionAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                                break;
                                            }
                                        }
                                    @endphp
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @php
                        $totalMetalGST = $totalMetalAmt + $totalPartMetalAmt;
                        $totalRubberGST = $totalRubberAmt + $totalPartRubberAmt;
                        $totalGlassGST = $totalGlassAmt + $totalPartGlassAmt;
                        $totalFibreGST = $totalPartFibreAmt;
                        $totalRecondtionGST = $totalReconditionAmt + $totalPartReconditionAmt;

                        $depAmtMetal = 0;
                        $totalMetalAmtAfterDep = 0;
                        $depAmtIMTMetalAfterIMTDep = 0;
                        $totalIMTMetalAmtAfterIMTDep = 0;
                        if(!empty($totalMetalGST) && $totalMetalGST > 0){
                            $depAmtMetal = (($totalMetalGST * $lossAssessment[0]['MetalDepPer']) / 100);
                            $totalMetalAmtAfterDep = ($totalMetalGST - $depAmtMetal);
                            $depAmtIMTMetalAfterIMTDep = (($totalMetalAmtAfterDep * $lossAssessment[0]['IMT23DepPer']) / 100);
                            $totalIMTMetalAmtAfterIMTDep = ($totalMetalAmtAfterDep - $depAmtIMTMetalAfterIMTDep);
                        }

                        $depAmtRubber = 0;
                        $totalRubberAmtAfterDep = 0;
                        $depAmtIMTRubberAfterIMTDep = 0;
                        $totalIMTRubberAmtAfterIMTDep = 0;
                        if(!empty($totalRubberGST) && $totalRubberGST > 0){
                            $depAmtRubber = (($totalRubberGST * $lossAssessment[0]['RubberDepPer']) / 100);
                            $totalRubberAmtAfterDep = ($totalRubberGST - $depAmtRubber);
                            $depAmtIMTRubberAfterIMTDep = (($totalRubberAmtAfterDep * $lossAssessment[0]['IMT23DepPer']) / 100);
                            $totalIMTRubberAmtAfterIMTDep = ($totalRubberAmtAfterDep - $depAmtIMTRubberAfterIMTDep);
                        }

                        $depAmtFibre = 0;
                        $totalFibreAmtAfterDep = 0;;
                        $depAmtIMTFibreAfterIMTDep = 0;
                        $totalIMTFibreAmtAfterIMTDep = 0;
                        if(!empty($totalFibreGST) && $totalFibreGST > 0){
                            $depAmtFibre = (($totalFibreGST * $lossAssessment[0]['FibreDepPer']) / 100);
                            $totalFibreAmtAfterDep = ($totalFibreGST - $depAmtFibre);
                            $depAmtIMTFibreAfterIMTDep = (($totalFibreAmtAfterDep * $lossAssessment[0]['IMT23DepPer']) / 100);
                            $totalIMTFibreAmtAfterIMTDep = ($totalFibreAmtAfterDep - $depAmtIMTFibreAfterIMTDep);
                        }

                        $depAmtGlass = 0;
                        $totalGlassAmtAfterDep = 0;
                        $depAmtIMTGlassAfterIMTDep = 0;
                        $totalIMTGlassAmtAfterIMTDep = 0;
                        if(!empty($totalGlassGST) && $totalGlassGST > 0){
                            $depAmtGlass = (($totalGlassGST * $lossAssessment[0]['GlassDepPer']) / 100);
                            $totalGlassAmtAfterDep = ($totalGlassGST - $depAmtGlass);
                            $depAmtIMTGlassAfterIMTDep = (($totalGlassAmtAfterDep * $lossAssessment[0]['IMT23DepPer']) / 100);
                            $totalIMTGlassAmtAfterIMTDep = ($totalGlassAmtAfterDep - $depAmtIMTGlassAfterIMTDep);
                        }
                        $totalPartsAmount = ($totalMetalGST + $totalRubberGST + + $totalGlassGST+ $totalFibreGST + $totalRecondtionGST);
                        $totalDepAmount = ($depAmtMetal + $depAmtRubber + $depAmtFibre + $depAmtGlass);
                        //$grandtotalDepAmount += $totalDepAmount;
                        $totalAmtAfterDep = ($totalPartsAmount - $totalDepAmount);

                        $totalIMTDesp = ($depAmtIMTMetalAfterIMTDep + $depAmtIMTRubberAfterIMTDep + $depAmtIMTFibreAfterIMTDep);
                        $totalIMTAfterDesp = ($totalAmtAfterDep - $totalIMTDesp);
                        $grandTotalIMTAfterDesp += $totalIMTAfterDesp;
                        $grandTotalIMTDesp += $totalIMTDesp;

                        if($lossAssessment[0]['MutipleGSTonParts'] == 1){
                           $totalGSTOrIGSTAmtPer = ((($totalAmtAfterDep - $totalIMTDesp) * $value) / 100);
                        }

                        if($lossAssessment[0]['MutipleGSTonParts']==0 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=0)){
                           $totalGSTOrIGSTAmtPer = ((($totalAmtAfterDep - $totalIMTDesp) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                        }elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] == 0)){
                           $totalGSTOrIGSTAmtPer = ((($totalAmtAfterDep - $totalIMTDesp) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                        }
                        $totalWithGSTorIGSTAmount = ($totalAmtAfterDep + $totalGSTOrIGSTAmtPer) - $totalIMTDesp;
                        $grandtotalWithGSTorIGSTAmount += $totalWithGSTorIGSTAmount;
                    @endphp

                    @if($lossAssessment[0]['MutipleGSTonParts']==1 && $lossAssessment[0]['GSTAssessedPartsPer'] !=0)
                        <tr>
                            <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{$index + 1 }}</td>
                            <td align="center" valign="top" style="padding: 0px 3px; ">{{ $value }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalPartsAmount, 2) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalDepAmount, 2) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalAmtAfterDep, 2) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalIMTDesp, 2) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalIMTAfterDesp, 2) }}</td>
                            @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
                                <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer, 2) }}</td>
                            @else
                                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                            @endif
                            @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
                                <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer, 2) }}</td>
                            @else
                                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                            @endif
                            <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($totalWithGSTorIGSTAmount, 2) }}</td>
                        </tr>
                    @endif
                    @php
                        $grandtotalPartAmount += ($totalMetalAmt + $totalPartMetalAmt + $totalRubberAmt + $totalPartRubberAmt + $totalGlassAmt + $totalPartGlassAmt + $totalPartFibreAmt + $totalPartReconditionAmt);
                        //$grandtotalDepAmount  += ($totalDepMetalAmt + $totalDepRubberAmt + $totalDepGlassAmt + $totalDepFibreAmt);
                        $grandtotalDepAmount  += $totalDepAmount;
                        $grandtotalAfterDepAmount  += ($totalPartsAmount - $totalDepAmount);
                        if($lossAssessment[0]['MutipleGSTonParts']==1){
                            $grandtotalGSTOrIGSTAmtPer += ((($totalAmtAfterDep - $totalIMTDesp) * $value) / 100);
                        }
                        if($lossAssessment[0]['MutipleGSTonParts']==0 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=0)){
                            $grandtotalGSTOrIGSTAmtPer += ((($totalAmtAfterDep - $totalIMTDesp) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                        }elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] == 0)){
                            $grandtotalGSTOrIGSTAmtPer = ((($totalAmtAfterDep - $totalIMTDesp) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                        }
                    @endphp
                @endunless
            @endforeach
            @if($lossAssessment[0]['MutipleGSTonParts']==0 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=0))
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
                    <td align="center" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($lossAssessment[0]['GSTAssessedPartsPer'], 2) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($grandtotalPartAmount, 2) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($grandtotalDepAmount, 2) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($grandtotalAfterDepAmount, 2) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($grandTotalIMTDesp, 2) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($grandTotalIMTAfterDesp, 2) }}</td>
                    @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalGSTOrIGSTAmtPer, 2)}}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                    @endif
                    @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalGSTOrIGSTAmtPer, 2)}}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                    @endif
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalWithGSTorIGSTAmount, 2)}}</td>
                </tr>
            @elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] == 0))
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
                    <td align="center" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($lossAssessment[0]['GSTAssessedPartsPer'], 2) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($grandtotalPartAmount, 2) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($grandtotalDepAmount, 2) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($grandtotalAfterDepAmount, 2) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($grandTotalIMTDesp, 2) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format_custom($grandTotalIMTAfterDesp, 2) }}</td>
                    @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalGSTOrIGSTAmtPer, 2)}}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                    @endif
                    @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
                        <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalGSTOrIGSTAmtPer, 2)}}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                    @endif
                    <td align="right" valign="top" style="padding: 0px 3px; ">{{number_format_custom($grandtotalWithGSTorIGSTAmount, 2)}}</td>
                </tr>
            @endif
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandtotalPartAmount, 2)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandtotalDepAmount, 2)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandtotalAfterDepAmount, 2)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalIMTDesp, 2)}}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($grandTotalIMTAfterDesp, 2)}}</td>
                @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandtotalGSTOrIGSTAmtPer, 2)}}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandtotalGSTOrIGSTAmtPer, 2)}}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{number_format_custom($grandtotalWithGSTorIGSTAmount, 2)}}</td>
            </tr>
        </tbody>
    </table>
@endif