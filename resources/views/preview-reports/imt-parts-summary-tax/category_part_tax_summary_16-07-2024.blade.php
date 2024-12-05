<br/>

<table width="100%" align="center" id="design">
      <tbody>
        <tr>
          <td align="left" valign="top" style="font-weight: bold; border-right:none; border-bottom:none; padding: 3px 0px;"><span style="text-decoration:underline;">PARTS TAX SUMMARY</span> </td>
        </tr>

        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;">Metal</td>
        </tr>
      </tbody>  
    </table>
    <table width="100%"  align="center" id="design" style="font-size: 14px;">
      <tbody>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt. </td>
         
          <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">GST</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold;">IGST</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Amount</td>
        </tr>
    @php
      $alldetails=[];
      
      if(isset($lossAssessment[0]['alldetails']) && !empty($lossAssessment[0]['alldetails'])){
        $alldetails = json_decode($lossAssessment[0]['alldetails'], true); 
      } 
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
@foreach($subUniqueGstRates as $index=>$value)
  @unless(in_array($value, $uniqueGstRates))
    @php
        $totalMetalsAmt = 0;
        $totalPartMetalAmt = 0;
        $totalMetalPartsAmount = 0;
        $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
       @endphp

       @foreach($alldetails as $detail)
        @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
             @if(empty($detail['quantities'])){
                @php
                    // Sum up assessed amount for respective categories
                    if($detail['category']=="Metal" && ($detail['imt_23'] == null || $detail['imt_23'] == "No")) {
                         $totalMetalsAmt += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                    }
                @endphp
             @endif
        @endif

      @if(isset($detail['quantities']))
          @foreach($detail['quantities'] as $partQuantity)
            @if($partQuantity['gst'] == $value)
                    @php
                        // Calculate totals for subparts with the same GST percentage
                        if($partQuantity['category']=="Metal" && ($detail['imt_23'] == null || $detail['imt_23'] == "No")) {
                                $totalMetalsAmt += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                        }
                    @endphp
                @endif
            @endforeach
        @endif
      @endforeach

  @php 
    $totalMetalPartsAmount = ($totalMetalsAmt); 
    $totalDepMetalAmt = (($totalMetalsAmt * $lossAssessment[0]['MetalDepPer']) / 100);

    $totalDepAmount = ($totalDepMetalAmt);
    
    $totalAmtAfterDep = ($totalMetalPartsAmount - $totalDepAmount);
    
    
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
          <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{$index + 1 }}</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">{{$value}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalMetalPartsAmount,2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalDepAmount,2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalAmtAfterDep,2) }}</td>
        @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalGSTOrIGSTAmtPer,2) }}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalGSTOrIGSTAmtPer,2) }}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalWithGSTorIGSTAmount,2) }}</td>
        </tr>
  @endif
        @php 
          $grandtotalPartAmount += ($totalMetalsAmt);
          $grandtotalDepAmount  += ($totalDepMetalAmt);
          $grandtotalAfterDepAmount  += ($totalMetalPartsAmount - $totalDepAmount);
          $grandtotalGSTOrIGSTAmtPer += ((($totalAmtAfterDep) * $value) / 100);
          $grandtotalWithGSTorIGSTAmount += ($totalAmtAfterDep + $totalGSTOrIGSTAmtPer);
        @endphp
    @endunless
    @endforeach
    
    @if($lossAssessment[0]['MutipleGSTonParts']==0 || $lossAssessment[0]['GSTAssessedPartsPer'] ==0)
      <tr>
          <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">{{ $lossAssessment[0]['GSTAssessedPartsPer'] }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalMetalPartsAmount,2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalDepAmount,2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalAmtAfterDep,2) }}</td>
        @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalGSTOrIGSTAmtPer,2) }}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalGSTOrIGSTAmtPer,2) }}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalWithGSTorIGSTAmount,2) }}</td>
        </tr>
    @endif
 
    <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalPartAmount),2)}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalDepAmount),2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{ number_format(round($grandtotalAfterDepAmount),2) }}</td>
        @if(!empty($lossAssessment) && isset($lossAssessment[0]['IGSTonPartsAndLab']) && ($lossAssessment[0]['IGSTonPartsAndLab'] !== 1 || $lossAssessment[0]['IGSTonPartsAndLab'] === null))
        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalGSTOrIGSTAmtPer),2) }}</td>
        @else
            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
            @if(isset($lossAssessment[0]['IGSTonPartsAndLab']) && $lossAssessment[0]['IGSTonPartsAndLab'] ==1)
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalGSTOrIGSTAmtPer),2) }}</td>
        @else
            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalWithGSTorIGSTAmount),2) }}</td>
        </tr>
      </tbody>
    </table>


    <table width="100%" align="center" id="design">
      <tbody>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold; padding-top:5px;">Glass</td>
        </tr>
      </tbody>
    </table>
    <table width="100%"  align="center" id="design" style="font-size: 14px;">
      <tbody>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt. </td>
         
          <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep.</td>
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
    @endphp
@foreach($subUniqueGstRates as $index=>$value)
  @unless(in_array($value, $uniqueGstRates))
    @php
        $totalGlassAmount = 0;
        $totalGlassPartAmount =0;
        $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
       @endphp

       @foreach($alldetails as $detail)
        @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
            @php
                // Sum up assessed amount for respective categories
                if($detail['category']=="Glass" && ($detail['imt_23'] == null || $detail['imt_23'] == "No")) {
                     $totalGlassAmount += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                }
            @endphp
        @endif

      @if(isset($detail['quantities']))
          @foreach($detail['quantities'] as $partQuantity)
            @if($partQuantity['gst'] == $value)
                    @php
                        // Calculate totals for subparts with the same GST percentage
                        if($partQuantity['category']=="Glass" && ($detail['imt_23'] == null || $detail['imt_23'] == "No")) {
                                $totalGlassAmount += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                        }
                    @endphp
                @endif
            @endforeach
        @endif
      @endforeach

  @php 
    $totalGlassPartAmount = ($totalGlassAmount); 
    $totalDepGlassAmt = (($totalGlassAmount * $lossAssessment[0]['GlassDepPer']) / 100);

    $totalDepAmount = ($totalDepGlassAmt);
    
    $totalAmtAfterDep = ($totalGlassPartAmount - $totalDepAmount);
    
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
          <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{$index + 1 }}</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">{{$value}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalGlassPartAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalDepAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalAmtAfterDep}}</td>
        @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalGSTOrIGSTAmtPer}}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalGSTOrIGSTAmtPer}}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalWithGSTorIGSTAmount}}</td>
        </tr>
    @endif
        @php 
          $grandtotalPartAmount += ($totalGlassAmount);
          $grandtotalDepAmount  += ($totalDepGlassAmt);
          $grandtotalAfterDepAmount  += ($totalGlassPartAmount - $totalDepAmount);
          $grandtotalGSTOrIGSTAmtPer += ((($totalAmtAfterDep) * $value) / 100);
          $grandtotalWithGSTorIGSTAmount += ($totalAmtAfterDep + $totalGSTOrIGSTAmtPer);
        @endphp
    @endunless
    @endforeach 
    
    @if($lossAssessment[0]['MutipleGSTonParts']==0 || $lossAssessment[0]['GSTAssessedPartsPer'] ==0)
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">{{ $lossAssessment[0]['GSTAssessedPartsPer'] }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalGlassPartAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalDepAmount}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalAmtAfterDep}}</td>
        @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalGSTOrIGSTAmtPer}}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalGSTOrIGSTAmtPer}}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{$totalWithGSTorIGSTAmount}}</td>
        </tr>
    @endif
    
    <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalPartAmount),2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalDepAmount),2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{ number_format(round($grandtotalAfterDepAmount),2)}}</td>
        @if(isset($lossAssessment[0]['IGSTonPartsAndLab']) && ($lossAssessment[0]['IGSTonPartsAndLab'] !== 1 || $lossAssessment[0]['IGSTonPartsAndLab'] === null))
        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalGSTOrIGSTAmtPer),2) }}</td>
        @else
            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
         @if(isset($lossAssessment[0]['IGSTonPartsAndLab']) && $lossAssessment[0]['IGSTonPartsAndLab'] == 1)
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalGSTOrIGSTAmtPer),2) }}</td>
        @else
            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalWithGSTorIGSTAmount),2) }}</td>
        </tr>
      </tbody>
    </table>

    <table width="100%" align="center" id="design">
      <tbody>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">Rubber/Plastic</td>
        </tr>
      </tbody>
    </table>
    <table width="100%"  align="center" id="design" style="font-size: 14px;">
      <tbody>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt. </td>
         
          <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep.</td>
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
    @endphp
@foreach($subUniqueGstRates as $index=>$value)
  @unless(in_array($value, $uniqueGstRates))
    @php
        $totalRubberAmount = 0;
        $totalRubberPartAmount =0;
        $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
       @endphp

       @foreach($alldetails as $detail)
        @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
              @if(empty($detail['quantities'])){
                    @php
                        // Sum up assessed amount for respective categories
                        if($detail['category']=="Rubber" && ($detail['imt_23'] == null || $detail['imt_23'] == "No")) {
                             $totalRubberAmount += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                        }
                    @endphp
               @endif
        @endif

      @if(isset($detail['quantities']))
          @foreach($detail['quantities'] as $partQuantity)
            @if($partQuantity['gst'] == $value)
                    @php
                        // Calculate totals for subparts with the same GST percentage
                        if($partQuantity['category']=="Rubber" && ($detail['imt_23'] == null || $detail['imt_23'] == "No")) {
                                $totalRubberAmount += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                        }
                    @endphp
                @endif
            @endforeach
        @endif
      @endforeach

  @php 
    $totalRubberPartAmount = ($totalRubberAmount); 
    $totalDepRubberAmt = (($totalRubberAmount * $lossAssessment[0]['RubberDepPer']) / 100);

    $totalDepAmount = ($totalDepRubberAmt);
    
    $totalAmtAfterDep = ($totalRubberPartAmount - $totalDepAmount);

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
          <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{$index + 1 }}</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">{{$value}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalRubberPartAmount,2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalDepAmount,2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalAmtAfterDep,2) }}</td>
        @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalGSTOrIGSTAmtPer,2) }}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalGSTOrIGSTAmtPer,2) }}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalWithGSTorIGSTAmount,2) }}</td>
        </tr>
  @endif
  
        @php 
          $grandtotalPartAmount += ($totalRubberAmount);
          $grandtotalDepAmount  += ($totalDepRubberAmt);
          $grandtotalAfterDepAmount  += ($totalRubberPartAmount - $totalDepAmount);
          $grandtotalGSTOrIGSTAmtPer += ((($totalAmtAfterDep) * $value) / 100);
          $grandtotalWithGSTorIGSTAmount += ($totalAmtAfterDep + $totalGSTOrIGSTAmtPer);
        @endphp
    @endunless
    @endforeach 
  @if($lossAssessment[0]['MutipleGSTonParts']==0 || $lossAssessment[0]['GSTAssessedPartsPer'] ==0)
    <tr>
          <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">{{ $lossAssessment[0]['GSTAssessedPartsPer'] }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalRubberPartAmount,2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalDepAmount,2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalAmtAfterDep,2) }}</td>
        @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalGSTOrIGSTAmtPer,2) }}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalGSTOrIGSTAmtPer,2) }}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalWithGSTorIGSTAmount,2) }}</td>
        </tr>
  @endif

    <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalPartAmount),2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalDepAmount),2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{ number_format(round($grandtotalAfterDepAmount),2) }}</td>
        @if(isset($lossAssessment[0]['IGSTonPartsAndLab']) && ($lossAssessment[0]['IGSTonPartsAndLab'] != 1 || $lossAssessment[0]['IGSTonPartsAndLab'] === null))
        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalGSTOrIGSTAmtPer),2)}}</td>
        @else
            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          @if(isset($lossAssessment[0]['IGSTonPartsAndLab']) && $lossAssessment[0]['IGSTonPartsAndLab'] == 1)

            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalGSTOrIGSTAmtPer),2)}}</td>
        @else
            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalWithGSTorIGSTAmount),2)}}</td>
        </tr>
      </tbody>
    </table>

    <table width="100%" align="center" id="design">
      <tbody>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border: none; border-bottom: 2px solid #000; font-weight: bold;padding-top:5px;">Fibre</td>
        </tr>
      </tbody>
    </table>
    <table width="100%"  align="center" id="design" style="font-size: 14px;">
      <tbody>
        <tr>
          <td align="center" valign="top" style="padding: 0px 3px; width: 10%; font-weight: bold; border-left: 1px solid #000;">Sr. No.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Tax %</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Total Amt.</td>
          <td align="center" valign="top" style="padding: 0px 3px; width: 15%; font-weight: bold;">Dep. Amt. </td>
         
          <td align="center" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Amt. After Dep.</td>
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
    @endphp
@foreach($subUniqueGstRates as $index=>$value)
  @unless(in_array($value, $uniqueGstRates))
    @php
        $totalFibreAmount = 0;
        $totalFibrePartAmount =0;
        $uniqueGstRates[] = $value; // Add the current GST rate to the list of unique rates
       @endphp

       @foreach($alldetails as $detail)
        @if($detail['gst'] == $value) {{-- Check if GST rate matches --}}
          @if(empty($detail['quantities'])){
                @php
                    // Sum up assessed amount for respective categories
                    if($detail['category']=="Fibre" && ($detail['imt_23'] == null || $detail['imt_23'] == "No")) {
                         $totalFibreAmount += !empty($detail['ass_amt']) ? $detail['ass_amt'] : 0;
                    }
                @endphp
            @endif
        @endif

      @if(isset($detail['quantities']))
          @foreach($detail['quantities'] as $partQuantity)
            @if($partQuantity['gst'] == $value)
                    @php
                        // Calculate totals for subparts with the same GST percentage
                        if($partQuantity['category']=="Fibre" && ($detail['imt_23'] == null || $detail['imt_23'] == "No")) {
                                $totalFibreAmount += !empty($partQuantity['ass_amt']) ? $partQuantity['ass_amt'] : 0;
                        }
                    @endphp
                @endif
            @endforeach
        @endif
      @endforeach

  @php 
    $totalFibrePartAmount = ($totalFibreAmount); 
    $totalDepFibreAmt = (($totalFibreAmount * $lossAssessment[0]['FibreDepPer']) / 100);

    $totalDepAmount = ($totalDepFibreAmt);
    
    $totalAmtAfterDep = ($totalFibrePartAmount - $totalDepAmount);

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
          <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">{{$index + 1 }}</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">{{$value}}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalFibrePartAmount,2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalDepAmount,2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalAmtAfterDep,2) }}</td>
        @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalGSTOrIGSTAmtPer,2) }}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalGSTOrIGSTAmtPer,2) }}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalWithGSTorIGSTAmount,2) }}</td>
        </tr>
  @endif
        @php 
          $grandtotalPartAmount += ($totalFibreAmount);
          $grandtotalDepAmount  += ($totalDepFibreAmt);
          $grandtotalAfterDepAmount  += ($totalFibrePartAmount - $totalDepAmount);
          $grandtotalGSTOrIGSTAmtPer += ((($totalAmtAfterDep) * $value) / 100);
          $grandtotalWithGSTorIGSTAmount += ($totalAmtAfterDep + $totalGSTOrIGSTAmtPer);
        @endphp
    @endunless
    @endforeach 
    
  @if($lossAssessment[0]['MutipleGSTonParts']==0 || $lossAssessment[0]['GSTAssessedPartsPer'] ==0)
    <tr>
          <td align="center" valign="top" style="padding: 0px 3px;  border-left: 1px solid #000;">1</td>
          <td align="center" valign="top" style="padding: 0px 3px; ">{{ $lossAssessment[0]['GSTAssessedPartsPer'] }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalFibrePartAmount,2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalDepAmount,2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalAmtAfterDep,2) }}</td>
        @if(($lossAssessment[0]['IGSTonPartsAndLab'] !=1 || $lossAssessment[0]['IGSTonPartsAndLab']==null))
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalGSTOrIGSTAmtPer,2) }}</td>
        @else
        <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
        @if($lossAssessment[0]['IGSTonPartsAndLab'] ==1)
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalGSTOrIGSTAmtPer,2) }}</td>
        @else
         <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; ">{{ number_format($totalWithGSTorIGSTAmount,2) }}</td>
        </tr>
  @endif
    <tr>
          <td align="center" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="2">Grand Total</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalPartAmount), 2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalDepAmount), 2) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; ">{{ number_format(round($grandtotalAfterDepAmount), 2)}}</td>
        @if(isset($lossAssessment[0]['IGSTonPartsAndLab']) && ($lossAssessment[0]['IGSTonPartsAndLab'] != 1 || $lossAssessment[0]['IGSTonPartsAndLab'] === null))
        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalGSTOrIGSTAmtPer), 2)}}</td>
        @else
            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
            @if(isset($lossAssessment[0]['IGSTonPartsAndLab']) && $lossAssessment[0]['IGSTonPartsAndLab'] ==1)
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalGSTOrIGSTAmtPer), 2) }}</td>
        @else
            <td align="right" valign="top" style="padding: 0px 3px; ">0.00</td>
        @endif
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format(round($grandtotalWithGSTorIGSTAmount), 2) }}</td>
        </tr>
      </tbody>
    </table>