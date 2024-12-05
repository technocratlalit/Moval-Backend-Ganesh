@php
    $totalMetalAmountWithGst = 0;
    $totalRubberPlasticAmountWithGst = 0;
    $totalGlassAmountWithGst = 0;
    $totalFibreAmountWithGst = 0;
    $totalEndorsementAmountWithGst = 0;
    if(isset($SummerAssessmentAmounts['Metal']) && !empty($SummerAssessmentAmounts['Metal'])) {
      foreach ($SummerAssessmentAmounts['Metal'] as $percent => $amt) {
        $totalMetalAmountWithGst += ($amt + number_format_custom(($amt * $percent / 100), 2));
      }
    }

    if(isset($SummerAssessmentAmounts['Rubber']) && !empty($SummerAssessmentAmounts['Rubber'])) {
      foreach ($SummerAssessmentAmounts['Rubber'] as $percent => $amt) {
        $totalRubberPlasticAmountWithGst += ($amt + number_format_custom(($amt * $percent / 100), 2));
      }
    }

    if(isset($SummerAssessmentAmounts['Glass']) && !empty($SummerAssessmentAmounts['Glass'])) {
      foreach ($SummerAssessmentAmounts['Glass'] as $percent => $amt) {
        $totalGlassAmountWithGst += ($amt + number_format_custom(($amt * $percent / 100), 2));
      }
    }

    if(isset($SummerAssessmentAmounts['Fibre']) && !empty($SummerAssessmentAmounts['Fibre'])) {
      foreach ($SummerAssessmentAmounts['Fibre'] as $percent => $amt) {
        $totalFibreAmountWithGst += ($amt + number_format_custom(($amt * $percent / 100), 2));
      }
    }
    if(isset($SummerAssessmentAmounts['Endorsement']) && !empty($SummerAssessmentAmounts['Endorsement'])) {
      foreach ($SummerAssessmentAmounts['Endorsement'] as $percent => $amt) {
        $totalEndorsementAmountWithGst += ($amt + number_format_custom(($amt * $percent / 100), 2));
      }
    }
    $TotalCostofPartsAmt = ($totalMetalAmountWithGst + $totalRubberPlasticAmountWithGst + $totalGlassAmountWithGst + $totalFibreAmountWithGst + $totalEndorsementAmountWithGst);
@endphp
<div class="bill">
      <div style="padding: 0px 3px;  font-weight: bold; border-top: 1px solid #000; margin-top: 20px; border-bottom: 1px solid #000;">
          SUMMARY OF ASSESSMENT</div>
      </div>
      <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
        <tbody>
          <tr>
            <td align="left" valign="top" style="width: 50%; padding: 0px 3px; font-weight: bold;">Particulars</td>
            <td align="right" valign="top" style="width: 25%; padding: 0px 3px; font-weight: bold;">Billed</td>
            <td align="right" valign="top" style="width: 25%; padding: 0px 3px; font-weight: bold;">Assessed</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px;">Parts (Metal)</td>
            <td align="right" valign="top" style="padding: 0px 3px;"> {{ number_format_custom($totalMetalAmountWithGst) }}</td>
            <td align="right" valign="top" style="padding: 0px 3px;"> {{ !empty($lossAssessment[0]['partMetalAssamount']) ? number_format_custom($lossAssessment[0]['partMetalAssamount']) : '0.00' }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px;">Parts (Rubber / Plastic)</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalRubberPlasticAmountWithGst) }}</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['partRubberAssamount']) ? number_format_custom($lossAssessment[0]['partRubberAssamount']) : '0.00' }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px;">Parts (Glass)</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalGlassAmountWithGst) }}</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['partGlassAssamount']) ? number_format_custom($lossAssessment[0]['partGlassAssamount']) : '0.00' }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px;">Parts (Fibre)</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalFibreAmountWithGst) }}</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['partFibreAssamount']) ? number_format_custom($lossAssessment[0]['partFibreAssamount']) : '0.00' }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px;">Total Cost of Endorsement Parts</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEndorsementAmountWithGst) }}</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['totalendoresmentAss']) ? number_format_custom($lossAssessment[0]['totalendoresmentAss']) : '0.00' }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px; font-weight: bold; border-top: 2px solid #000;">Total Cost of Parts</td>
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; border-top: 2px solid #000;">{!! number_format_custom($TotalCostofPartsAmt) !!}</td>
            @php
              $tt = ((isset($lossAssessment[0]['partMetalAssamount']) ? $lossAssessment[0]['partMetalAssamount'] : 0) + (isset($lossAssessment[0]['partRubberAssamount']) ? $lossAssessment[0]['partRubberAssamount'] : 0) + (isset($lossAssessment[0]['partGlassAssamount']) ? $lossAssessment[0]['partGlassAssamount'] : 0) + (isset($lossAssessment[0]['partFibreAssamount']) ? $lossAssessment[0]['partFibreAssamount'] : 0) + (isset($lossAssessment[0]['totalendoresmentAss']) ? $lossAssessment[0]['totalendoresmentAss'] : 0));
            @endphp
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; border-top: 2px solid #000;">{{ number_format_custom($tt) }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px;">Total Cost of Recondition Parts</td>
            <td align="right" valign="top" style="padding: 0px 3px;">0.00</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['totalreconditionAss']) ? number_format_custom($lossAssessment[0]['totalreconditionAss']) : '0.00' }}</td>
          </tr>
          <tr>
            @php
              //$NetLabourChargesAmt = ($totalLabBilledAmt + $subTotalbilledLabAmt + $totalLabBilledPaintingAmt + $subTotalBilledPaintingLabAmt);
              $NetLabourChargesAmt = ($totalLabBilledAmt + $subTotalbilledLabAmt + $PaintingLabourwithTaxes);
            @endphp
            <td align="left" valign="top" style="padding: 0px 3px;">Net Labour Charges</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($NetLabourChargesAmt, 2) }}</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom(((isset($lossAssessment[0]['totallabourass']) ? $lossAssessment[0]['totallabourass'] : 0) + (isset($lossAssessment[0]['paintinglabass']) ? $lossAssessment[0]['paintinglabass'] : 0) + (isset($lossAssessment[0]['IMTPaintingLabAss']) ? $lossAssessment[0]['IMTPaintingLabAss'] : 0))) }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px; font-weight: bold; border-top: 2px solid #000;">&nbsp;
            </td>
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; border-top: 2px solid #000;">{!! number_format_custom(($NetLabourChargesAmt + $TotalCostofPartsAmt), 2) !!}</td>
            <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; border-top: 2px solid #000;">{{ !empty($lossAssessment[0]['totalass']) ? number_format_custom($lossAssessment[0]['totalass']) : '0.00' }}
            </td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px;">Less Imposed Clause <span style="padding-left: 200px;">(-)</span></td>
            <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['ImposedClause']) ? number_format_custom($lossAssessment[0]['ImposedClause']) : '0.00' }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px;">Compulsory Deductible <span style="padding-left: 185px;">(-)</span></td>
            <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['CompulsoryDeductable']) ? number_format_custom($lossAssessment[0]['CompulsoryDeductable']) : '0.00' }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px;">Less Voluntary Excess <span style="padding-left: 185px;">(-)</span></td>
            <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['less_voluntary_excess']) ? number_format_custom($lossAssessment[0]['less_voluntary_excess']) : '0.00' }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px;">Less Salvage <span style="padding-left: 254px;">(-)</span></td>
            <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['SalvageAmt']) ? number_format_custom($lossAssessment[0]['SalvageAmt']) : '0.00' }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px;">Add Towing Charges <span
                style="padding-left: 200px;">(+)</span></td>
            <td align="right" valign="top" style="padding: 0px 3px;"></td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['TowingCharges']) ? number_format_custom($lossAssessment[0]['TowingCharges']) : '0.00' }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 0px 3px;">Add Additional Towing Charges <span
                      style="padding-left: 200px;">(+)</span></td>
            <td align="right" valign="top" style="padding: 0px 3px;"></td>
            <td align="right" valign="top" style="padding: 0px 3px;">{{ !empty($lossAssessment[0]['additional_towing']) ? number_format_custom($lossAssessment[0]['additional_towing']) : '0.00' }}</td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 50px 8px; font-weight: bold;">Net Loss </td>
            <td align="right" valign="top" style="padding: 50px 8px; font-weight: bold;">{!! number_format_custom(($NetLabourChargesAmt + $TotalCostofPartsAmt), 2) !!}</td>
            <td align="right" valign="top" style="padding: 50px 8px; font-weight: bold;">
            {{
                  number_format_custom(
                    round(
                        (
                            (isset($lossAssessment[0]['totalass']) ? $lossAssessment[0]['totalass'] : 0) -
                            (isset($lossAssessment[0]['ImposedClause']) ? $lossAssessment[0]['ImposedClause'] : 0) -
                            (isset($lossAssessment[0]['CompulsoryDeductable']) ? $lossAssessment[0]['CompulsoryDeductable'] : 0) -
                            (isset($lossAssessment[0]['less_voluntary_excess']) ? $lossAssessment[0]['less_voluntary_excess'] : 0) -
                            (isset($lossAssessment[0]['SalvageAmt']) ? $lossAssessment[0]['SalvageAmt'] : 0) +
                            (isset($lossAssessment[0]['TowingCharges']) ? $lossAssessment[0]['TowingCharges'] : 0) +
                            (isset($lossAssessment[0]['additional_towing']) ? $lossAssessment[0]['additional_towing'] : 0)
                        ), 
                        2
                    ),
                    2,
                    '.',
                    ''
                )
            }}

            </td>
          </tr>
        </tbody>
      </table>
    </div>