@if(!empty($lossAssessment) && ($lossAssessment[0]['display_gst_summary_part_category_wise']==1))
    @php

        $alldetails = json_decode($lossAssessment[0]['alldetails'], true);

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
        $totalGSTOrIGSTAmtPer = 0;
        $totalWithGSTorIGSTAmount =0;

        $checkGstCondition = true;
        if($lossAssessment[0]['MutipleGSTonParts'] == 0) {
            $subUniqueGstRates = ($lossAssessment[0]['GSTAssessedPartsPer'] > 0) ? [$lossAssessment[0]['GSTAssessedPartsPer']] : [0];
            $checkGstCondition = false;
        }
    @endphp
    @if($lossAssessment[0]['totalRubberIMTAmt'] != 0.00)
        <table width="100%" align="center" id="design">
            <tbody>
            <tr>
                <td align="left" valign="top"
                    style=" padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">
                    IMT Rubber
                </td>
            </tr>
            </tbody>
        </table>
        <table width="100%" align="center" id="design" style="font-size: 14px;">
            <tbody>
            <tr>
                <td align="center" valign="top"
                    style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>

                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After
                    Dep.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">GST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">IGST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
            </tr>
            @foreach($subUniqueGstRates as $index=>$value)
                @unless(in_array($value, $uniqueGstRates))
                    @php
                        $totalIMTRubberAmount = 0;
                        $totalIMTRubberPartAmount =0;
                        $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                    @endphp

                    @foreach($alldetails as $detail)

                        @if($detail['gst'] == $value || empty($checkGstCondition)) {{-- Check if GST rate matches --}}
                        @php
                            // Sum up assessed amount for respective categories
                            if($detail['category']=="Rubber" && $detail['imt_23'] =="Yes") {
                                 $totalIMTRubberAmount += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                            }
                        @endphp
                        @endif

                        @if(isset($detail['quantities']))
                            @foreach($detail['quantities'] as $partQuantity)
                                @if($partQuantity['gst'] == $value || empty($checkGstCondition))
                                    @php
                                        // Calculate totals for subparts with the same GST percentage
                                        if($partQuantity['category']=="Rubber" && $partQuantity['imt_23'] =="Yes") {
                                                $totalIMTRubberAmount += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                        }
                                    @endphp
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @php
                        $totalIMTRubberPartAmount = ($totalIMTRubberAmount);
                        $totalDepIMTRubberAmt = (($totalIMTRubberAmount * $lossAssessment[0]['RubberDepPer']) / 100);

                        $totalDepAmount = ($totalDepIMTRubberAmt);

                        $totalAmtAfterDep = ($totalIMTRubberPartAmount - $totalDepAmount);

                        if($lossAssessment[0]['MutipleGSTonParts']==1){
                           $totalGSTOrIGSTAmtPer = ((($totalAmtAfterDep) * $value) / 100);
                        }

                        if($lossAssessment[0]['MutipleGSTonParts']==0 || $lossAssessment[0]['GSTAssessedPartsPer'] ==0){
                           $totalGSTOrIGSTAmtPer = ((($totalAmtAfterDep) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                        }

                        $totalWithGSTorIGSTAmount = ($totalAmtAfterDep + $totalGSTOrIGSTAmtPer);
                    @endphp
                    @if($lossAssessment[0]['MutipleGSTonParts']==1 && $lossAssessment[0]['GSTAssessedPartsPer'] !=0)
                        <tr>
                            <td align="center" valign="top"
                                style="padding: 0px 3px;  border-left: 1px solid #000;">{{$index + 1 }}</td>
                            <td align="center" valign="top" style="padding: 0px 3px; ">{{$value}}</td>
                            <td align="right" valign="top"
                                style="padding: 0px 3px; ">{{ number_format_custom($totalIMTRubberPartAmount ,2, '.', '') }}</td>
                            <td align="right" valign="top"
                                style="padding: 0px 3px; ">{{ number_format_custom($totalDepAmount ,2, '.', '') }}</td>
                            <td align="right" valign="top"
                                style="padding: 0px 3px; ">{{ number_format_custom($totalAmtAfterDep ,2, '.', '') }}</td>
                            @if(empty($lossAssessment[0]['IGSTonPartsAndLab']))
                                <td align="right" valign="top"
                                    style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer ,2, '.', '') }}</td>
                            @else
                                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                            @endif
                            @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                                <td align="right" valign="top"
                                    style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer ,2, '.', '') }}</td>
                            @else
                                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                            @endif
                            <td align="right" valign="top"
                                style="padding: 0px 3px; ">{{ number_format_custom($totalWithGSTorIGSTAmount ,2, '.', '') }}</td>
                        </tr>
                    @endif
                    @php
                        $grandtotalDepAmount  += ($totalDepIMTRubberAmt);
                        $grandtotalAfterDepAmount  += ($totalIMTRubberPartAmount - $totalDepAmount);

                          if($lossAssessment[0]['MutipleGSTonParts']==1){
                             $grandtotalGSTOrIGSTAmtPer += ((($grandtotalAfterDepAmount) * $value) / 100);
                          }

                          if($lossAssessment[0]['MutipleGSTonParts']==0 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=0)){
                             $grandtotalGSTOrIGSTAmtPer = ((($grandtotalAfterDepAmount) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                          }

                        $grandtotalWithGSTorIGSTAmount += ($grandtotalAfterDepAmount + $grandtotalGSTOrIGSTAmtPer);
                    @endphp
                @endunless
            @endforeach
            @if($lossAssessment[0]['MutipleGSTonParts']==0 || $lossAssessment[0]['GSTAssessedPartsPer'] ==0)
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
                    <td align="center" valign="top"
                        style="padding: 0px 3px; ">{{ $lossAssessment[0]['GSTAssessedPartsPer'] }}</td>
                    <td align="right" valign="top"
                        style="padding: 0px 3px; ">{{ number_format_custom($grandtotalPartAmount ,2, '.', '') }}</td>
                    <td align="right" valign="top"
                        style="padding: 0px 3px; ">{{ number_format_custom($grandtotalDepAmount ,2, '.', '') }}</td>
                    <td align="right" valign="top"
                        style="padding: 0px 3px; ">{{ number_format_custom($grandtotalAfterDepAmount ,2, '.', '') }}</td>
                    @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                        <td align="right" valign="top"
                            style="padding: 0px 3px; ">{{ number_format_custom($grandtotalGSTOrIGSTAmtPer ,2, '.', '') }}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                    @endif
                    @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                        <td align="right" valign="top"
                            style="padding: 0px 3px; ">{{ number_format_custom($grandtotalGSTOrIGSTAmtPer ,2, '.', '') }}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                    @endif
                    <td align="right" valign="top"
                        style="padding: 0px 3px; ">{{ number_format_custom($totalWithGSTorIGSTAmount ,2, '.', '') }}</td>
                </tr>
            @endif
            <tr>
                <td align="center" valign="top"
                    style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total
                </td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalPartAmount),2, '.', '') }}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalDepAmount),2, '.', '')}}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold; ">{{ number_format_custom(round($grandtotalAfterDepAmount),2, '.', '') }}</td>
                @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                    <td align="right" valign="top"
                        style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalGSTOrIGSTAmtPer),2, '.', '')}}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                    <td align="right" valign="top"
                        style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalGSTOrIGSTAmtPer),2) }}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalWithGSTorIGSTAmount),2) }}</td>
            </tr>
            </tbody>
        </table>
    @endif
    @if($lossAssessment[0]['totalMetalIMTAmt'] != 0.00)
        <table width="100%" align="center" id="design">
            <tbody>
            <tr>
                <td align="left" valign="top"
                    style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold; padding-top:5px;">
                    IMT Metal
                </td>
            </tr>
            </tbody>
        </table>
        <table width="100%" align="center" id="design" style="font-size: 14px;">
            <tbody>
            <tr>
                <td align="center" valign="top"
                    style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>

                <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After
                    Dep.
                </td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">GST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">IGST</td>
                <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
            </tr>
            @php
                $uniqueGstRates = []; // Initialize an array to store unique GST rates
                $subUniqueGstRates = [];
                $subUniqueGstRates = array_values(array_unique($uniqueGSTValues));
                sort($subUniqueGstRates);
                $counter = count($subUniqueGstRates);
                $grandtotalPartAmount = 0;
                $grandtotalDepAmount = 0;
                $grandtotalAfterDepAmount = 0;
                $grandtotalGSTOrIGSTAmtPer = 0;
                $grandtotalWithGSTorIGSTAmount = 0;

                $checkGstCondition = true;
                if($lossAssessment[0]['MutipleGSTonParts'] == 0) {
                    $subUniqueGstRates = ($lossAssessment[0]['GSTAssessedPartsPer'] > 0) ? [$lossAssessment[0]['GSTAssessedPartsPer']] : [0];
                    $checkGstCondition = false;
                }
            @endphp
            @foreach($subUniqueGstRates as $index=>$value)
                @unless(in_array($value, $uniqueGstRates))
                    @php
                        $totalIMTMetalAmount = 0;
                        $totalIMTMetalPartAmount =0;
                        $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                    @endphp

                    @foreach($alldetails as $detail)
                        @if($detail['gst'] == $value || empty($checkGstCondition)) {{-- Check if GST rate matches --}}
                        @php
                            // Sum up assessed amount for respective categories
                            if($detail['category']=="Metal" && $detail['imt_23'] =="Yes") {
                                 $totalIMTMetalAmount += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                            }
                        @endphp
                        @endif

                        @if(isset($detail['quantities']))
                            @foreach($detail['quantities'] as $partQuantity)
                                @if($partQuantity['gst'] == $value || empty($checkGstCondition))
                                    @php
                                        // Calculate totals for subparts with the same GST percentage
                                        if($partQuantity['category']=="Metal" && $detail['imt_23'] =="Yes") {
                                                $totalIMTMetalAmount += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                        }
                                    @endphp
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    @php
                        $totalIMTMetalPartAmount = ($totalIMTMetalAmount);
                        $totalDepIMTMetalAmt = (($totalIMTMetalAmount * $lossAssessment[0]['MetalDepPer']) / 100);

                        $totalDepAmount = ($totalDepIMTMetalAmt);

                        $totalAmtAfterDep = ($totalIMTMetalPartAmount - $totalDepAmount);

                        if($lossAssessment[0]['MutipleGSTonParts']==1){
                           $totalGSTOrIGSTAmtPer = ((($totalAmtAfterDep) * $value) / 100);
                        }

                        if($lossAssessment[0]['MutipleGSTonParts']==0 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=0)){
                           $totalGSTOrIGSTAmtPer = ((($totalAmtAfterDep) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                        }

                        $totalWithGSTorIGSTAmount = ($totalAmtAfterDep + $totalGSTOrIGSTAmtPer);
                    @endphp
                    @if($lossAssessment[0]['MutipleGSTonParts']==1 && $lossAssessment[0]['GSTAssessedPartsPer'] !=0)
                        <tr>
                            <td align="center" valign="top"
                                style="padding: 0px 3px;  border-left: 1px solid #000;">{{$index + 1 }}</td>
                            <td align="center" valign="top" style="padding: 0px 3px; ">{{$value}}</td>
                            <td align="right" valign="top"
                                style="padding: 0px 3px; ">{{ number_format_custom($totalIMTMetalPartAmount,2) }}</td>
                            <td align="right" valign="top"
                                style="padding: 0px 3px; ">{{ number_format_custom($totalDepAmount, 2)}}</td>
                            <td align="right" valign="top"
                                style="padding: 0px 3px; ">{{ number_format_custom($totalAmtAfterDep, 2)}}</td>
                            @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                                <td align="right" valign="top"
                                    style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer, 2)}}</td>
                            @else
                                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                            @endif
                            @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                                <td align="right" valign="top"
                                    style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer, 2)}}</td>
                            @else
                                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                            @endif
                            <td align="right" valign="top"
                                style="padding: 0px 3px; ">{{ number_format_custom($totalWithGSTorIGSTAmount, 2)}}</td>
                        </tr>
                    @endif
                    @php
                        $grandtotalPartAmount += ($totalIMTMetalAmount);
                        $grandtotalDepAmount  += ($totalDepIMTMetalAmt);
                        $grandtotalAfterDepAmount  += ($totalIMTMetalPartAmount - $totalDepAmount);

                          if($lossAssessment[0]['MutipleGSTonParts']==1){
                             $grandtotalGSTOrIGSTAmtPer += ((($totalAmtAfterDep) * $value) / 100);
                          }

                          if($lossAssessment[0]['MutipleGSTonParts']==0 || $lossAssessment[0]['GSTAssessedPartsPer'] ==0){
                             $grandtotalGSTOrIGSTAmtPer = ((($totalAmtAfterDep) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                          }

                        $grandtotalWithGSTorIGSTAmount += ($totalAmtAfterDep + $totalGSTOrIGSTAmtPer);
                    @endphp
                @endunless
            @endforeach

            @if($lossAssessment[0]['MutipleGSTonParts']==0 || $lossAssessment[0]['GSTAssessedPartsPer'] ==0)
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
                    <td align="center" valign="top"
                        style="padding: 0px 3px; ">{{ $lossAssessment[0]['GSTAssessedPartsPer'] }}</td>
                    <td align="right" valign="top"
                        style="padding: 0px 3px; ">{{ number_format_custom($grandtotalPartAmount,2) }}</td>
                    <td align="right" valign="top"
                        style="padding: 0px 3px; ">{{ number_format_custom($grandtotalDepAmount, 2)}}</td>
                    <td align="right" valign="top"
                        style="padding: 0px 3px; ">{{ number_format_custom($grandtotalAfterDepAmount, 2)}}</td>
                    @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                        <td align="right" valign="top"
                            style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer, 2)}}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                    @endif
                    @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                        <td align="right" valign="top"
                            style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer, 2)}}</td>
                    @else
                        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                    @endif
                    <td align="right" valign="top"
                        style="padding: 0px 3px; ">{{ number_format_custom($totalWithGSTorIGSTAmount, 2)}}</td>
                </tr>
            @endif

            <tr>
                <td align="center" valign="top"
                    style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total
                </td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalPartAmount),2) }}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalDepAmount),2) }}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold; ">{{ number_format_custom(round($grandtotalAfterDepAmount),2) }}</td>
                @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                    <td align="right" valign="top"
                        style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalGSTOrIGSTAmtPer),2) }}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                    <td align="right" valign="top"
                        style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalGSTOrIGSTAmtPer),2) }}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalWithGSTorIGSTAmount),2) }}</td>
            </tr>
            </tbody>
        </table>
    @endif
    <table width="100%" align="center" id="design">
        <tbody>
        <tr>
            <td align="left" valign="top"
                style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">
                Recondition
            </td>
        </tr>
        </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 14px;">
        <tbody>
        <tr>
            <td align="center" valign="top"
                style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.
            </td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt.</td>

            <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep.
            </td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">GST</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">IGST</td>
            <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
        </tr>
        @php
            $uniqueGstRates = []; // Initialize an array to store unique GST rates
            $subUniqueGstRates = [];
            $uniqueGSTValues = [];

            $subUniqueGstRates = array_values(array_unique($uniqueGSTValues));
            sort($subUniqueGstRates);
            $counter = count($subUniqueGstRates);
            $totalRecondtionPartAmount =0;
            $grandtotalPartAmount = 0;
            $grandtotalDepAmount = 0;
            $grandtotalAfterDepAmount = 0;
            $grandtotalGSTOrIGSTAmtPer = 0;
            $grandtotalWithGSTorIGSTAmount = 0;
            $totalGSTOrIGSTAmtPer =0;
            $totalWithGSTorIGSTAmount =0;
        @endphp
        @foreach($subUniqueGstRates as $index=>$value)
            @unless(in_array($value, $uniqueGstRates))
                @php
                    $totalRecondtionAmount = 0;
                    $totalRecondtionPartAmount =0;
                    $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
                @endphp

                @foreach($alldetails as $detail)
                    @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
                    @php
                        // Sum up assessed amount for respective categories
                        if($detail['category']=="Recondition") {
                             $totalRecondtionAmount += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                        }
                    @endphp
                    @endif

                    @if(isset($detail['quantities']))
                        @foreach($detail['quantities'] as $partQuantity)
                            @if($partQuantity['gst'] == $value)
                                @php
                                    // Calculate totals for subparts with the same GST percentage
                                    if($partQuantity['category']=="Recondition") {
                                            $totalRecondtionAmount += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                                    }
                                @endphp
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @php
                    $totalGSTOrIGSTAmtPer = 0;
                    $totalWithGSTorIGSTAmount =0;

                    $totalRecondtionPartAmount = ($totalRecondtionAmount);
                    $totalDepRecondtionAmt = (($totalRecondtionAmount * 0) / 100);

                    $totalDepAmount = ($totalDepRecondtionAmt);

                    $totalAmtAfterDep = ($totalRecondtionPartAmount - $totalDepAmount);

                    if($lossAssessment[0]['MutipleGSTonParts']==1){
                       $totalGSTOrIGSTAmtPer = ((($totalAmtAfterDep) * $value) / 100);
                    }

                    if($lossAssessment[0]['MutipleGSTonParts']==0 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=0)){
                       $totalGSTOrIGSTAmtPer = ((($totalAmtAfterDep) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                    }

                    $totalWithGSTorIGSTAmount = ($totalAmtAfterDep + $totalGSTOrIGSTAmtPer);
                @endphp
                @if($lossAssessment[0]['MutipleGSTonParts']==1 && $lossAssessment[0]['GSTAssessedPartsPer'] !=0)
                    <tr>
                        <td align="center" valign="top"
                            style="padding: 0px 3px;  border-left: 1px solid #000;">{{$index + 1 }}</td>
                        <td align="center" valign="top" style="padding: 0px 3px; ">{{$value}}</td>
                        <td align="right" valign="top"
                            style="padding: 0px 3px; ">{{ number_format_custom($totalRecondtionPartAmount,2) }}</td>
                        <td align="right" valign="top"
                            style="padding: 0px 3px; ">{{ number_format_custom($totalDepAmount,2) }}</td>
                        <td align="right" valign="top"
                            style="padding: 0px 3px; ">{{ number_format_custom($totalAmtAfterDep,2) }}</td>
                        @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                            <td align="right" valign="top"
                                style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer,2) }}</td>
                        @else
                            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                        @endif
                        @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                            <td align="right" valign="top"
                                style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer,2) }}</td>
                        @else
                            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                        @endif
                        <td align="right" valign="top"
                            style="padding: 0px 3px; ">{{ number_format_custom($totalWithGSTorIGSTAmount,2) }}</td>
                    </tr>
                @endif
                @php
                    $grandtotalPartAmount += ($totalRecondtionAmount);
                    $grandtotalDepAmount  += ($totalDepRecondtionAmt);
                    $grandtotalAfterDepAmount  += ($totalRecondtionPartAmount - $totalDepAmount);

                      if($lossAssessment[0]['MutipleGSTonParts']==1){
                         $grandtotalGSTOrIGSTAmtPer += ((($grandtotalAfterDepAmount) * $value) / 100);
                      }

                      if($lossAssessment[0]['MutipleGSTonParts']==0 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=0)){
                         $grandtotalGSTOrIGSTAmtPer = ((($grandtotalAfterDepAmount) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
                      }
                    $grandtotalWithGSTorIGSTAmount += ($grandtotalAfterDepAmount + $totalGSTOrIGSTAmtPer);
                @endphp
            @endunless
        @endforeach

        @if($lossAssessment[0]['MutipleGSTonParts']==0 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=0))
            <tr>
                <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
                <td align="center" valign="top"
                    style="padding: 0px 3px; ">{{ $lossAssessment[0]['GSTAssessedPartsPer'] }}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; ">{{ number_format_custom($grandtotalPartAmount,2) }}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; ">{{ number_format_custom($grandtotalDepAmount,2) }}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; ">{{ number_format_custom($grandtotalAfterDepAmount,2) }}</td>
                @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                    <td align="right" valign="top"
                        style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer,2) }}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                    <td align="right" valign="top"
                        style="padding: 0px 3px; ">{{ number_format_custom($totalGSTOrIGSTAmtPer,2) }}</td>
                @else
                    <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
                @endif
                <td align="right" valign="top"
                    style="padding: 0px 3px; ">{{ number_format_custom($totalWithGSTorIGSTAmount,2) }}</td>
            </tr>
        @endif
        <tr>
            <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;"
                colspan="2">Grand Total
            </td>
            <td align="right" valign="top"
                style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalPartAmount),2) }}</td>
            <td align="right" valign="top"
                style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalDepAmount),2) }}</td>
            <td align="right" valign="top"
                style="padding: 0px 3px; font-weight: bold; ">{{ number_format_custom(round($grandtotalAfterDepAmount),2) }}</td>
            @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalGSTOrIGSTAmtPer),2)  }}</td>
            @else
                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
            @endif
            @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
                <td align="right" valign="top"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalGSTOrIGSTAmtPer),2)  }}</td>
            @else
                <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
            @endif
            <td align="right" valign="top"
                style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom(round($grandtotalWithGSTorIGSTAmount),2)  }}</td>
        </tr>
        </tbody>
    </table>
@endif