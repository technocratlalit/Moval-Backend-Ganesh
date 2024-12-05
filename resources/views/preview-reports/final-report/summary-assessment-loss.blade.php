
@php
/*type 1:part 2:labour*/
$estPrtTotal=array_sum(array_column(array_filter(json_decode($estimates,true), function($item) {
    return $item['type'] == 1;
}), 'amount'));
$estLabourTotal=array_sum(array_column(array_filter(json_decode($estimates,true), function($item) {
    return $item['type'] == 2;
}), 'amount'));

@endphp
<table width="100%" align="center" id="design" style="padding-top:20px;">
      <tbody>
        <tr>
          <td align="left" valign="top" style="border-top: 2px solid #000; font-weight: bold; border-right:none; line-height:0px; padding: 3px 0px;">Summary of Estimate and Assessment of Loss</td>
        </tr>
      </tbody>
    </table>
    <table width="100%" align="center" id="design" style="font-size: 12px;">
      <tbody>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; width: 60%; font-weight: bold; border-left: 1px solid #000;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Estimated<br /><span style="font-weight: 400;">(Amt in ₹)</span></td>
          <td align="right" valign="top" style="padding: 0px 3px; width: 20%; font-weight: bold;">Assessed<br /><span style="font-weight: 400;">(Amt in ₹)</span></td>
        </tr>

        @foreach ( json_decode($estimates,true) as $key=>$details)
        @if($details['type'] == 1)
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">{{ $details["detail"] }}</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($details["amount"]) }}</td>
          <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
          @endif
        @endforeach
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Total Cost of Parts</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['totalestonlypart']) ? number_format_custom($lossAssessment[0]['totalestonlypart']) : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">Parts (Metal)</td>
          <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['partMetalAssamount']) ? number_format_custom($lossAssessment[0]['partMetalAssamount']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">Parts (Rub/Plast) </td>
          <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['partRubberAssamount']) ? number_format_custom($lossAssessment[0]['partRubberAssamount']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">Parts (Glass) </td>
          <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['partGlassAssamount']) ? number_format_custom($lossAssessment[0]['partGlassAssamount']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">Parts (Fibre) </td>
          <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['partFibreAssamount']) ? number_format_custom($lossAssessment[0]['partFibreAssamount']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">Total Cost of Endorsement Parts</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['totalendoresmentestonly']) ? $lossAssessment[0]['totalendoresmentestonly'] : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['totalendoresmentAss']) ? $lossAssessment[0]['totalendoresmentAss'] : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="font-weight: bold;padding: 0px 3px; border-left: 1px solid #000;">Total</td>
          <td align="right" valign="top" style="font-weight: bold; border-top: 1px solid #000; padding: 0px 3px;">{{  number_format_custom($estPrtTotal)  }}</td>
          <td align="right" valign="top" style="font-weight: bold; border-top: 1px solid #000; padding: 0px 3px;">{{ ($lossAssessment[0]['totalassparts'] > 0) ? number_format_custom($lossAssessment[0]['totalassparts']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">Total Cost of Recondition Parts</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['totalreconditionestonly']) ? number_format_custom($lossAssessment[0]['totalreconditionestonly']) : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['totalreconditionAss']) ? number_format_custom($lossAssessment[0]['totalreconditionAss']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;" >&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ ($lossAssessment[0]['totalEstWithReconditon'] > 0) ? number_format_custom($lossAssessment[0]['totalEstWithReconditon']) : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">{{ ($lossAssessment[0]['totalAssWithReconditon'] > 0) ? number_format_custom($lossAssessment[0]['totalAssWithReconditon']) : '0.00' }}</td>
      </tr>
      @foreach ( json_decode($estimates,true) as $key=>$details)
      @if($details['type'] == 2)
      <tr>
        <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">{{ $details["detail"] }}</td>
        <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($details["amount"]) }}</td>
        <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
      </tr>
        @endif
      @endforeach
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">Net Labour Charges</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ ($lossAssessment[0]['total_labourAmtWithGst'] > 0) ? number_format_custom($lossAssessment[0]['total_labourAmtWithGst']) : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px;">{{ ($lossAssessment[0]['netlabourAss'] > 0) ? number_format_custom($lossAssessment[0]['netlabourAss']) : '0.00'  }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">TOTAL</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; border-top: 2px solid #000;">{{$estLabourTotal }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; border-top: 2px solid #000;">{{ !empty($lossAssessment[0]['totalass']) ? number_format_custom($lossAssessment[0]['totalass']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">Less Imposed Clause (-)</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">{{ !empty($lossAssessment[0]['ImposedClause']) ? number_format_custom($lossAssessment[0]['ImposedClause']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">Less Compulsory Excess Clause (-)</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">{{ !empty($lossAssessment[0]['CompulsoryDeductable']) ? number_format_custom($lossAssessment[0]['CompulsoryDeductable']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">Less Voluntary Excess (-)</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">{{ !empty($lossAssessment[0]['less_voluntary_excess']) ? number_format_custom($lossAssessment[0]['less_voluntary_excess']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">Less Salvage / Scrap Value (-)</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">{{ !empty($lossAssessment[0]['SalvageAmt']) ? number_format_custom($lossAssessment[0]['SalvageAmt']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;"> Add Towing Charges (+)</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">{{ !empty($lossAssessment[0]['TowingCharges']) ? number_format_custom($lossAssessment[0]['TowingCharges']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">Additional Towing Charges (+)</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px; border-bottom: 1px solid #000;">{{ !empty($lossAssessment[0]['additional_towing']) ? number_format_custom($lossAssessment[0]['additional_towing']) : '0.00' }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">TOTAL</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['totalest'] > 0) ? number_format_custom($lossAssessment[0]['totalest']) : '0.00' }}</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ ($lossAssessment[0]['alltotalass'] > 0) ? number_format_custom($lossAssessment[0]['alltotalass']) : '0.00'  }}</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Net Liability on Repair Basis</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; border-bottom: 2px solid #000; padding: 0px 3px;">&nbsp;</td>
          <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; border-bottom: 2px solid #000; padding: 0px 3px;">{{ $estPrtTotal+$estLabourTotal }}</td>
        </tr>

      </tbody>
    </table>