<table width="100%" align="center" id="design">
      <tbody>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold; padding-top:10px;"><span style="text-decoration:underline;">PARTS TAX SUMMARY </span> </td>
        </tr>
      </tbody>
    </table>
    <table width="100%"  align="center" id="design" style="font-size: 14px;">
      <tbody>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Total Amt.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt. </td>
         
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amt. After Dep.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">GST</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">IGST</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
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
            if(empty($detail['quantities'])){
                switch ($detail['category'] && ($detail['imt_23']=="No" || $detail['imt_23'] == NULL)) {
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

      @if(isset($detail['quantities']))
          @foreach($detail['quantities'] as $partQuantity)
            @if($partQuantity['gst'] == $value)
                    @php
                        // Calculate totals for subparts with the same GST percentage
                        switch ($partQuantity['category'] && ($partQuantity['imt_23'] == null || $partQuantity['imt_23'] == "No")) {
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
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalPartsAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalDepAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalAmtAfterDep}}</td>
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalGSTOrIGSTAmtPer}}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalGSTOrIGSTAmtPer}}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalWithGSTorIGSTAmount}}</td>
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
          <td align="center" valign="top" style="padding: 0px 3px; ">{{ $lossAssessment[0]['GSTAssessedPartsPer'] }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalPartAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalDepAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalAfterDepAmount}}</td>
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalGSTOrIGSTAmtPer}}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalGSTOrIGSTAmtPer}}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalWithGSTorIGSTAmount}}</td>
        </tr>
   @elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0))
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">{{ $lossAssessment[0]['GSTAssessedPartsPer'] }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalPartAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalDepAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalAfterDepAmount}}</td>
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalGSTOrIGSTAmtPer}}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalGSTOrIGSTAmtPer}}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalWithGSTorIGSTAmount}}</td>
        </tr>
   @endif
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{$grandtotalPartAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{$grandtotalDepAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{$grandtotalAfterDepAmount}}</td>
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{$grandtotalGSTOrIGSTAmtPer}}</td>
        @else
            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
            @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{$grandtotalGSTOrIGSTAmtPer}}</td>
        @else
            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{$grandtotalWithGSTorIGSTAmount}}</td>
        </tr>
      </tbody>
    </table>
    @if($lossAssessment[0]['totalRubberIMTAmt'] != 0.00 || $lossAssessment[0]['totalMetalIMTAmt'] != 0.00)
    <table width="100%" align="center" id="design">
      <tbody>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">IMT PARTS</td>
        </tr>
      </tbody>
    </table>
    <table width="100%"  align="center" id="design" style="font-size: 14px;">
      <tbody>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Total Amt.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt. </td>
         
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amt. After Dep.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">GST</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">IGST</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
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
                switch ($detail['category'] && $detail['imt_23']=="Yes") {
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
            @endphp
        @endif

      @if(isset($detail['quantities']))
          @foreach($detail['quantities'] as $partQuantity)
            @if($partQuantity['gst'] == $value)
                    @php
                        // Calculate totals for subparts with the same GST percentage
                        switch ($partQuantity['category'] && $partQuantity['imt_23']=="Yes") {
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

    if($lossAssessment[0]['MutipleGSTonParts']==1){
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
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalPartsAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalDepAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalAmtAfterDep}}</td>
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalGSTOrIGSTAmtPer}}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalGSTOrIGSTAmtPer}}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalWithGSTorIGSTAmount}}</td>
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
            }elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] == 0)){
               $grandtotalGSTOrIGSTAmtPer = ((($totalAmtAfterDep) * $lossAssessment[0]['GSTAssessedPartsPer']) / 100);
            }
           $grandtotalWithGSTorIGSTAmount += ($totalAmtAfterDep + $totalGSTOrIGSTAmtPer);
        @endphp
    @endunless
    @endforeach 
    @if($lossAssessment[0]['MutipleGSTonParts']==0 && ($lossAssessment[0]['GSTAssessedPartsPer'] ==0 || $lossAssessment[0]['GSTAssessedPartsPer'] !=0))
      <tr>
          <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">{{ $lossAssessment[0]['GSTAssessedPartsPer'] }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalPartAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalDepAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalAfterDepAmount}}</td>
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalGSTOrIGSTAmtPer}}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalGSTOrIGSTAmtPer}}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalWithGSTorIGSTAmount}}</td>
        </tr>
     @elseif($lossAssessment[0]['MutipleGSTonParts']==1 && ($lossAssessment[0]['GSTAssessedPartsPer'] == 0))
           <tr>
          <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">{{ $lossAssessment[0]['GSTAssessedPartsPer'] }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalPartAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalDepAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalAfterDepAmount}}</td>
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalGSTOrIGSTAmtPer}}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalGSTOrIGSTAmtPer}}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$grandtotalWithGSTorIGSTAmount}}</td>
        </tr>
     @endif
     
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{$grandtotalPartAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{$grandtotalDepAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{$grandtotalAfterDepAmount}}</td>
        @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] !=1))
        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{$grandtotalGSTOrIGSTAmtPer}}</td>
        @else
            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
            @if(!empty($lossAssessment[0]['IGSTonPartsAndLab'] ==1))
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{$grandtotalGSTOrIGSTAmtPer}}</td>
        @else
            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{$grandtotalWithGSTorIGSTAmount}}</td>
        </tr>
      </tbody>
    </table>
 @endif