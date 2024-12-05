@php
    $totalAssFiberAmt = isset($cabinDetailsCalculation[1]['parts_total']['assessed']['fiber'])
        ? $cabinDetailsCalculation[1]['parts_total']['assessed']['fiber']
        : 0;
    $totalAssReconditionAmt = isset($cabinDetailsCalculation[1]['parts_total']['assessed']['recondition'])
        ? $cabinDetailsCalculation[1]['parts_total']['assessed']['recondition']
        : 0;
    $totalColSpan = 8;
    if ($totalAssFiberAmt > 0) {
        $totalColSpan++;
    }
    if ($totalAssReconditionAmt > 0) {
        $totalColSpan++;
    }
    $remarkShow = true;
    if ($totalAssReconditionAmt > 0 && $totalAssFiberAmt > 0) {
        $remarkShow = false;
        $totalColSpan = $totalColSpan - 1;
    }


@endphp

<table width="100%" align="center" id="design" cellpadding="0" cellspacing="0" border="0"
    style="padding-top:20px; border-top: 2px solid #333; border-bottom: 0px; border-right: 0px; border-left: 0px;">
    <tbody style="border-top: 2px solid #333; border-bottom: 0px; border-right: 0px; border-left: 0px;">
        <tr style="border-top: 2px solid #333; border-bottom: 0px; border-right: 0px; border-left: 0px;">
            <th align="left"
            valign="top" style="border-top:1px solid black;font-weight: bold; border-right: 0px; border-left: 0px;"
            colspan="{{ $totalColSpan }}"> Cabin : </th>
        </tr>
    </tbody>
</table>
<table width="100%" align="center" id="design" style="font-size: 12px;">
    <tbody>
        <tr>
            <th align="center" valign="top" style="width:5%;padding: 0px 3px;  font-weight: bold; ">Sr. No.</th>
            <th align="left" valign="top" style="padding: 0px 3px;  font-weight: bold;">Description of Parts</th>
            <th align="center" valign="top" style="width:5%;padding: 0px 3px;  font-weight: bold;">GST<br/>%</th>
            <th align="center" valign="top" style="padding: 0px 3px;  font-weight: bold;">Est Amt.<br /><span style="font-weight: 400;"> (Amt in ₹)</span></th>
            <th align="center" valign="top" style="padding: 0px 3px;  font-weight: bold;">Metal<br /><span style="font-weight: 400;"> (Amt in ₹)</span></th>
            <th align="center" valign="top" style="padding: 0px 3px; font-weight: bold;">Rub/Plast<br /><span style="font-weight: 400;"> (Amt in ₹)</span></th>
            <th align="center" valign="top" style="padding: 0px 3px;  font-weight: bold;">Glass<br /><span style="font-weight: 400;"> (Amt in ₹)</span></th>
            @if ($totalAssFiberAmt > 0)
                <th align="center" valign="top" style="padding: 0px 3px;  font-weight: bold;">Fiber<br /><span style="font-weight: 400;"> (Amt in ₹)</span></th>
            @endif
            @if ($totalAssReconditionAmt > 0)
                <th align="center" valign="top" style="padding: 0px 3px;  font-weight: bold;">Recond<br /><span style="font-weight: 400;"> (Amt in ₹)</span></th>
            @endif
            @if (!empty($remarkShow))
                <th align="left" valign="middle" rowspan="2">Remark</th>
            @endif
        </tr>

        @if (!empty($cabinDetails))
            @php
                $cabinIndexCounter = 0;
            @endphp
            @foreach ($cabinDetails as $cabin)

                @if ($cabin['portion'] == 1)
                    @if (!empty($cabin['category']))
                        @php
                            $cabin_metal = 0;
                            $cabin_glass = 0;
                            $cabin_rubberPlastic = 0;
                            $cabin_fiber = 0;
                            $cabin_recondition = 0;

                               if($multiTaxes["set_mutilple_gst_on_parts"] == 0){
                                    $cabin['gst'] =$multiTaxes["gst_parts_ass_amt"];
                               }
                            if (empty($cabin['quantities'])) {
                                switch ($cabin['category']) {
                                    case 1: //1 = Metal
                                        $cabin_metal = $cabin['ass_amt'] > 0 ? $cabin['ass_amt'] : 0;
                                        break;
                                    case 2: //2=Glass
                                        $cabin_glass = $cabin['ass_amt'] > 0 ? $cabin['ass_amt'] : 0;
                                        break;
                                    case 3: //3= Rubber
                                        $cabin_rubberPlastic = $cabin['ass_amt'] > 0 ? $cabin['ass_amt'] : 0;
                                        break;
                                    case 4: //4 = Fiber
                                        $cabin_fiber = $cabin['ass_amt'] > 0 ? $cabin['ass_amt'] : 0;
                                        break;
                                    case 5: //5 = Recondition
                                        $cabin_recondition = $cabin['ass_amt'] > 0 ? $cabin['ass_amt'] : 0;
                                        break;
                                    default:
                                        break;
                                }
                            }
                            $supplementary = array_search('Supplementary', config('category'));

                        @endphp
                        @if ($cabin['category'] == $supplementary && !empty($cabin['description']))

                            @php
                                $partSupplementaryTitle = strtoupper($cabin['description']);
                                //  if(empty($cabin['quantities'])) {
                                //      continue;
                                //  }
                                $totalColForSup = $totalColSpan+1;
                            @endphp
                            @if (!empty($partSupplementaryTitle))
                                <tr>
                                    <td colspan="{{ $totalColForSup }}" align="left" valign="middle"
                                        style="padding: 3px 5px 3px 5px; font-weight: bold;">
                                        {{ $partSupplementaryTitle }}</td>
                                </tr>
                                @php
                                    $partSupplementaryTitle = null;
                                @endphp
                            @endif
                        @endif
                        <tr>
                            <td align="left" valign="top" style="padding: 0px 3px;">
                                {{ intval(++$cabinIndexCounter) }}</td>
                            <td align="left" valign="top" style="padding: 0px 3px;">{{ $cabin['description'] }}</td>
                            <td align="center" valign="top" style="padding: 0px 3px;">
                                {{ $cabin['gst'] > 0 ? number_format($cabin['gst']) : '0' }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">
                                {{ $cabin['est_amt'] > 0 ? number_format_custom($cabin['est_amt']) : '-' }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">
                                {{ $cabin_metal > 0 ? number_format_custom($cabin_metal) : '-' }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">
                                {{ $cabin_rubberPlastic > 0 ? number_format_custom($cabin_rubberPlastic) : '-' }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">
                                {{ $cabin_glass > 0 ? number_format_custom($cabin_glass) : '-' }}</td>
                            @if ($totalAssFiberAmt > 0)
                                <td align="right" valign="top" style="padding: 0px 3px;">
                                    {{ $cabin_fiber > 0 ? number_format_custom($cabin_fiber) : '-' }}</td>
                            @endif
                            @if ($totalAssReconditionAmt > 0)
                                <td align="right" valign="top" style="padding: 0px 3px;">
                                    {{ $cabin_recondition > 0 ? number_format_custom($cabin_recondition) : '-' }}
                                </td>
                            @endif
                            @if (!empty($remarkShow))
                                <td align="center" valign="top" style="padding: 0px 3px;">{!! !empty($cabin['remarks']) ? $cabin['remarks'] : '-' !!}</td>
                            @endif
                        </tr>
                    @endif
                    @if (!empty($cabin['quantities']))
                        @php
                            $cabinQuantityIndex = 0;
                        @endphp
                        @foreach ($cabin['quantities'] as $k => $sub_cabin)
                            @if (!empty($sub_cabin['category']))
                                @php
                                    $sub_cabin_metal = 0;
                                    $sub_cabin_glass = 0;
                                    $sub_cabin_rubberPlastic = 0;
                                    $sub_cabin_fiber = 0;
                                    $sub_cabin_recondition = 0;
                                    if($multiTaxes["set_mutilple_gst_on_parts"]==0){
                                $sub_cabin['gst'] =$multiTaxes["gst_parts_ass_amt"];
                               }
                                    switch (intval($sub_cabin['category'])) {
                                        case 1: //1 = Metal
                                            $sub_cabin_metal = $sub_cabin['ass_amt'] > 0 ? $sub_cabin['ass_amt'] : 0;
                                            break;
                                        case 2: //2=Glass
                                            $sub_cabin_glass = $sub_cabin['ass_amt'] > 0 ? $sub_cabin['ass_amt'] : 0;
                                            break;
                                        case 3: //3= Rubber
                                            $sub_cabin_rubberPlastic =
                                                $sub_cabin['ass_amt'] > 0 ? $sub_cabin['ass_amt'] : 0;
                                            break;
                                        case 4: //4 = Fiber
                                            $sub_cabin_fiber = $sub_cabin['ass_amt'] > 0 ? $sub_cabin['ass_amt'] : 0;
                                            break;
                                        case 5: //5 = Recondition
                                            $sub_cabin_recondition =
                                                $sub_cabin['ass_amt'] > 0 ? $sub_cabin['ass_amt'] : 0;
                                            break;
                                        default:
                                            break;
                                    }
                                @endphp
                                <tr>
                                    <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;">
                                        {!! $cabinIndexCounter . '.' . intval(++$cabinQuantityIndex) !!}</td>
                                    <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;">
                                        {{ $sub_cabin['description'] }}</td>
                                    <td align="center" valign="top" style="padding: 0px 3px;">
                                        {{ $sub_cabin['gst'] > 0 ? number_format($sub_cabin['gst']) : '0' }}</td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">
                                        {{ $sub_cabin['est_amt'] > 0 ? number_format_custom($sub_cabin['est_amt']) : '-' }}
                                    </td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">
                                        {{ $sub_cabin_metal > 0 ? number_format_custom($sub_cabin_metal) : '-' }}
                                    </td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">
                                        {{ $sub_cabin_rubberPlastic > 0 ? number_format_custom($sub_cabin_rubberPlastic) : '-' }}
                                    </td>
                                    <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">
                                        {{ $sub_cabin_glass > 0 ? number_format_custom($sub_cabin_glass) : '-' }}
                                    </td>
                                    @if ($totalAssFiberAmt > 0)
                                        <td align="right" valign="top"
                                            style="padding: 0px 3px; font-style: italic;">
                                            {{ $sub_cabin_fiber > 0 ? number_format_custom($sub_cabin_fiber) : '-' }}
                                        </td>
                                    @endif
                                    @if ($totalAssReconditionAmt > 0)
                                        <td align="right" valign="top"
                                            style="padding: 0px 3px; font-style: italic;">
                                            {{ $sub_cabin_recondition > 0 ? number_format_custom($sub_cabin_recondition) : '-' }}
                                        </td>
                                    @endif
                                    @if (!empty($remarkShow))
                                        <td align="center" valign="top"
                                            style="padding: 0px 3px; font-style: italic;">{!! !empty($sub_cabin['remarks']) ? $sub_cabin['remarks'] : '-' !!}</td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    @endif
                @endif
            @endforeach
            @php
                $MetalDepPer =
                    $lossAssessment[0]['MetalDepPer'] > 0 && empty($lossAssessment[0]['IsZeroDep'])
                        ? $lossAssessment[0]['MetalDepPer']
                        : '0';
                $RubberDepPer =
                    $lossAssessment[0]['RubberDepPer'] > 0 && empty($lossAssessment[0]['IsZeroDep'])
                        ? $lossAssessment[0]['RubberDepPer']
                        : '0';
                $GlassDepPer =
                    $lossAssessment[0]['GlassDepPer'] > 0 && empty($lossAssessment[0]['IsZeroDep'])
                        ? $lossAssessment[0]['GlassDepPer']
                        : '0';
                $FibreDepPer =
                    $lossAssessment[0]['FibreDepPer'] > 0 && empty($lossAssessment[0]['IsZeroDep'])
                        ? $lossAssessment[0]['FibreDepPer']
                        : '0';

                $total_est_amt = 0;
                $total_ass_metal = 0;
                $total_ass_glass = 0;
                $total_ass_rub_plast = 0;
                $total_ass_fiber = 0;
                $total_ass_recondition = 0;

                $total_ass_dep_metal = 0;
                $total_ass_dep_glass = 0;
                $total_ass_dep_rub_plast = 0;
                $total_ass_dep_fiber = 0;
                $total_ass_dep_recondition = 0;

                $total_ass_after_dep_metal = 0;
                $total_ass_after_dep_glass = 0;
                $total_ass_after_dep_rub_plast = 0;
                $total_ass_after_dep_fiber = 0;
                $total_ass_after_dep_recondition = 0;
            @endphp
            @if (isset($cabinDetailsCalculation[1]['parts_details_total']) &&
                    !empty($cabinDetailsCalculation[1]['parts_details_total']))
                @foreach ($cabinDetailsCalculation[1]['parts_details_total'] as $rate => $cabin_parts_gst)
                    @php
                        $gst_est_amt = 0;
                        if (isset($cabin_parts_gst['estimated']) && !empty($cabin_parts_gst['estimated'])) {
                            foreach ($cabin_parts_gst['estimated'] as $estimate_amt) {
                                if ($estimate_amt['total'] > 0) {
                                    $gst_est_amt += $estimate_amt['total'];
                                }
                            }
                        }
                        $gst_ass_metal = isset($cabin_parts_gst['assessed']['metal']['total'])
                            ? $cabin_parts_gst['assessed']['metal']['total']
                            : 0;
                        $gst_ass_glass = isset($cabin_parts_gst['assessed']['glass']['total'])
                            ? $cabin_parts_gst['assessed']['glass']['total']
                            : 0;
                        $gst_ass_rub_plast = isset($cabin_parts_gst['assessed']['rubberPlastic']['total'])
                            ? $cabin_parts_gst['assessed']['rubberPlastic']['total']
                            : 0;
                        $gst_ass_fiber = isset($cabin_parts_gst['assessed']['fiber']['total'])
                            ? $cabin_parts_gst['assessed']['fiber']['total']
                            : 0;
                        $gst_ass_recondition = isset($cabin_parts_gst['assessed']['recondition']['total'])
                            ? $cabin_parts_gst['assessed']['recondition']['total']
                            : 0;

                        $total_ass_dep_metal += isset($cabin_parts_gst['assessed']['metal']['dep'])
                            ? $cabin_parts_gst['assessed']['metal']['dep']
                            : 0;
                        $total_ass_dep_glass += isset($cabin_parts_gst['assessed']['glass']['dep'])
                            ? $cabin_parts_gst['assessed']['glass']['dep']
                            : 0;
                        $total_ass_dep_rub_plast += isset($cabin_parts_gst['assessed']['rubberPlastic']['dep'])
                            ? $cabin_parts_gst['assessed']['rubberPlastic']['dep']
                            : 0;
                        $total_ass_dep_fiber = isset($cabin_parts_gst['assessed']['fiber']['dep'])
                            ? $cabin_parts_gst['assessed']['fiber']['dep']
                            : 0;
                        $total_ass_dep_recondition += isset($cabin_parts_gst['assessed']['recondition']['dep'])
                            ? $cabin_parts_gst['assessed']['recondition']['dep']
                            : 0;

                        $total_ass_after_dep_metal += isset($cabin_parts_gst['assessed']['metal']['amt_after_dep'])
                            ? $cabin_parts_gst['assessed']['metal']['amt_after_dep']
                            : 0;
                        $total_ass_after_dep_glass += isset($cabin_parts_gst['assessed']['glass']['amt_after_dep'])
                            ? $cabin_parts_gst['assessed']['glass']['amt_after_dep']
                            : 0;
                        $total_ass_after_dep_rub_plast += isset(
                            $cabin_parts_gst['assessed']['rubberPlastic']['amt_after_dep'],
                        )
                            ? $cabin_parts_gst['assessed']['rubberPlastic']['amt_after_dep']
                            : 0;
                        $total_ass_after_dep_fiber += isset($cabin_parts_gst['assessed']['fiber']['amt_after_dep'])
                            ? $cabin_parts_gst['assessed']['fiber']['amt_after_dep']
                            : 0;
                        $total_ass_after_dep_recondition += isset(
                            $cabin_parts_gst['assessed']['recondition']['amt_after_dep'],
                        )
                            ? $cabin_parts_gst['assessed']['recondition']['amt_after_dep']
                            : 0;

                        $total_est_amt += $gst_est_amt;
                        $total_ass_metal += $gst_ass_metal;
                        $total_ass_glass += $gst_ass_glass;
                        $total_ass_rub_plast += $gst_ass_rub_plast;
                        $total_ass_fiber += $gst_ass_fiber;
                        $total_ass_recondition += $gst_ass_recondition;

                    @endphp
                    <tr>
                        <td align="left" valign="top" colspan="2"
                            style="padding: 0px 3px; border-left: 1px solid #000;" colspan="3">Total (Parts with
                            {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? 'GST' : 'IGST' }}
                            {{ $rate }}%)</td>
                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">
                            {{ number_format_custom($gst_est_amt) }}</td>

                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">
                            {{ number_format_custom($gst_ass_metal) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">
                            {{ number_format_custom($gst_ass_rub_plast) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">
                            {{ number_format_custom($gst_ass_glass) }}</td>
                        @if ($totalAssFiberAmt > 0)
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">
                                {{ number_format_custom($gst_ass_fiber) }}</td>
                        @endif
                        @if ($totalAssReconditionAmt > 0)
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">
                                {{ number_format_custom($gst_ass_recondition) }}</td>
                        @endif
                        @if (!empty($remarkShow))
                            <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                        @endif
                    </tr>
                @endforeach
            @endif
            <tr>
                <td align="right" valign="top" colspan="2"
                    style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;" colspan="3">Total
                </td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                    {{ number_format_custom($total_est_amt) }}</td>

                <td align="right" valign="top"
                    style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                    {{ number_format_custom($total_ass_metal) }}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                    {{ number_format_custom($total_ass_rub_plast) }}</td>
                <td align="right" valign="top"
                    style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                    {{ number_format_custom($total_ass_glass) }}</td>
                @if ($totalAssFiberAmt > 0)
                    <td align="right" valign="top"
                        style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                        {{ number_format_custom($total_ass_fiber) }}</td>
                @endif
                @if ($totalAssReconditionAmt > 0)
                    <td align="right" valign="top"
                        style="padding: 0px 3px; border-top: 1px solid #000; font-weight: bold;">
                        {{ number_format_custom($total_ass_recondition) }}</td>
                @endif
                @if (!empty($remarkShow))
                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                @endif
            </tr>
            <tr>
                <td align="left" valign="top" colspan="4"
                    style="padding: 0px 3px; border-left: 1px solid #000;"> Less Dep @ <span
                        style="font-weight: bold;">{{ $MetalDepPer }}%</span> on Metal, <span
                        style="font-weight: bold;">{{ $RubberDepPer }}%</span> on Rub/Plast, <span
                        style="font-weight: bold;">{{ $GlassDepPer }}%</span> on Glass
                    Parts{{ $lossAssessment[0]['totalFibreAmt'] > 0 && $FibreDepPer > 0 ? ', ' . $FibreDepPer . '% on Fibre Parts.' : '' }}
                </td>

                <td align="right" valign="top" style="padding: 0px 3px;">
                    {{ number_format_custom($total_ass_dep_metal) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px;">
                    {{ number_format_custom($total_ass_dep_rub_plast) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px;">
                    {{ number_format_custom($total_ass_dep_glass) }}</td>
                @if ($totalAssFiberAmt > 0)
                    <td align="right" valign="top" style="padding: 0px 3px;">
                        {{ number_format_custom($total_ass_dep_fiber) }}</td>
                @endif
                @if ($totalAssReconditionAmt > 0)
                    <td align="right" valign="top" style="padding: 0px 3px;">
                        {{ number_format_custom($total_ass_dep_recondition) }}</td>
                @endif
                @if (!empty($remarkShow))
                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                @endif
            </tr>
            <tr>
                <td align="right" valign="top" colspan="3"
                    style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                    {{ number_format_custom($total_est_amt) }}</td>

                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                    {{ number_format_custom($total_ass_after_dep_metal) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                    {{ number_format_custom($total_ass_after_dep_rub_plast) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                    {{ number_format_custom($total_ass_after_dep_glass) }}</td>
                @if ($totalAssFiberAmt > 0)
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                        {{ number_format_custom($total_ass_after_dep_fiber) }}</td>
                @endif
                @if ($totalAssReconditionAmt > 0)
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                        {{ number_format_custom($total_ass_after_dep_recondition) }}</td>
                @endif
                @if (!empty($remarkShow))
                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                @endif
            </tr>
            @php
                $totalEstAmtWithGst = 0;
                $totalAssMetalWithGst = 0;
                $totalAssGlassWithGst = 0;
                $totalAssRubPlastWithGst = 0;
                $totalAssFiberWithGst = 0;
                $totalAssReconditionWithGst = 0;
            @endphp
            @if (isset($cabinDetailsCalculation[1]['parts_details_total']) &&
                    !empty($cabinDetailsCalculation[1]['parts_details_total']))
                @foreach ($cabinDetailsCalculation[1]['parts_details_total'] as $gst => $cabin_parts_gst)
                {{-- @php //phpinfo();
                dump($cabinDetailsCalculation[1]);@endphp --}}
                    @php
                        $gst_est_amt_with_gst = 0;
                        $gst_est_amt_gst = 0;
                        if (isset($cabin_parts_gst['estimated']) && !empty($cabin_parts_gst['estimated'])) {
                            foreach ($cabin_parts_gst['estimated'] as $estimate_amt) {
                                if ($estimate_amt['gst_amount'] > 0) {
                                    $gst_est_amt_gst += $estimate_amt['gst_amount'];
                                }
                                if ($estimate_amt['amt_after_gst'] > 0) {
                                    $gst_est_amt_with_gst += $estimate_amt['amt_after_gst'];
                                }
                            }
                        }
                        $gst_ass_metal_gst = isset($cabin_parts_gst['assessed']['metal']['gst_amount'])
                            ? $cabin_parts_gst['assessed']['metal']['gst_amount']
                            : 0;
                        $gst_ass_metal_with_gst = isset($cabin_parts_gst['assessed']['metal']['amt_after_gst'])
                            ? $cabin_parts_gst['assessed']['metal']['amt_after_gst']
                            : 0;

                        $gst_ass_glass_gst = isset($cabin_parts_gst['assessed']['glass']['gst_amount'])
                            ? $cabin_parts_gst['assessed']['glass']['gst_amount']
                            : 0;
                        $gst_ass_glass_with_gst = isset($cabin_parts_gst['assessed']['glass']['amt_after_gst'])
                            ? $cabin_parts_gst['assessed']['glass']['amt_after_gst']
                            : 0;

                        $gst_ass_rub_plast_gst = isset($cabin_parts_gst['assessed']['rubberPlastic']['gst_amount'])
                            ? $cabin_parts_gst['assessed']['rubberPlastic']['gst_amount']
                            : 0;
                        $gst_ass_rub_plast_with_gst = isset(
                            $cabin_parts_gst['assessed']['rubberPlastic']['amt_after_gst'],
                        )
                            ? $cabin_parts_gst['assessed']['rubberPlastic']['amt_after_gst']
                            : 0;

                        $gst_ass_fiber_gst = isset($cabin_parts_gst['assessed']['fiber']['gst_amount'])
                            ? $cabin_parts_gst['assessed']['fiber']['gst_amount']
                            : 0;
                        $gst_ass_fiber_with_gst = isset($cabin_parts_gst['assessed']['fiber']['amt_after_gst'])
                            ? $cabin_parts_gst['assessed']['fiber']['amt_after_gst']
                            : 0;

                        $gst_ass_recondition_gst = isset($cabin_parts_gst['assessed']['recondition']['gst_amount'])
                            ? $cabin_parts_gst['assessed']['recondition']['gst_amount']
                            : 0;
                        $gst_ass_recondition_with_gst = isset(
                            $cabin_parts_gst['assessed']['recondition']['amt_after_gst'],
                        )
                            ? $cabin_parts_gst['assessed']['recondition']['amt_after_gst']
                            : 0;

                        $totalEstAmtWithGst += $gst_est_amt_with_gst;
                        $totalAssMetalWithGst += $gst_ass_metal_with_gst;
                        $totalAssGlassWithGst += $gst_ass_glass_with_gst;
                        $totalAssRubPlastWithGst += $gst_ass_rub_plast_with_gst;
                        $totalAssFiberWithGst += $gst_ass_fiber_with_gst;
                        $totalAssReconditionWithGst += $gst_ass_recondition_with_gst;

                    @endphp
                    <tr>
                        <td align="left" valign="top" colspan="3"
                            style="padding: 0px 3px; border-left: 1px solid #000;">Add {!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} @
                            {{ $gst }}%</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">
                            {{ number_format_custom($gst_est_amt_gst) }}</td>

                        <td align="right" valign="top" style="padding: 0px 3px;">
                            {{ number_format_custom($gst_ass_metal_gst) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">
                            {{ number_format_custom($gst_ass_rub_plast_gst) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px;">
                            {{ number_format_custom($gst_ass_glass_gst) }}</td>
                        @if ($totalAssFiberAmt > 0)
                            <td align="right" valign="top" style="padding: 0px 3px;">
                                {{ number_format_custom($gst_ass_fiber_gst) }}</td>
                        @endif
                        @if ($totalAssReconditionAmt > 0)
                            <td align="right" valign="top" style="padding: 0px 3px;">
                                {{ number_format_custom($gst_ass_recondition_gst) }}</td>
                        @endif
                        @if (!empty($remarkShow))
                            <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                        @endif
                    </tr>
                @endforeach
            @endif
            <tr>
                <td align="right" valign="top" colspan="3"
                    style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                    {{ number_format_custom($totalEstAmtWithGst) }}</td>

                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                    {{ number_format_custom($totalAssMetalWithGst) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                    {{ number_format_custom($totalAssRubPlastWithGst) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                    {{ number_format_custom($totalAssGlassWithGst) }}</td>
                @if ($totalAssFiberAmt > 0)
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                        {{ number_format_custom($totalAssFiberWithGst) }}</td>
                @endif
                @if ($totalAssReconditionAmt > 0)
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                        {{ number_format_custom($totalAssReconditionWithGst) }}</td>
                @endif
                @if (!empty($remarkShow))
                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                @endif
            </tr>
            @php
                $NetMaterialChargesForCabin =
                    $totalAssMetalWithGst +
                    $totalAssGlassWithGst +
                    $totalAssRubPlastWithGst +
                    $totalAssFiberWithGst +
                    $totalAssReconditionWithGst;

                $total_col_span = !empty($remarkShow) ? intval($totalColSpan - 3) : intval($totalColSpan - 2);

            @endphp

            <tr>
                <td align="right" valign="top" colspan="3"
                    style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Total</td>
                <td align="right" valign="top" colspan="{{ $total_col_span }}"
                    style="padding: 0px 3px; font-weight: bold;">
                    {{ number_format_custom($NetMaterialChargesForCabin) }}</td>
                @if (!empty($remarkShow))
                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                @endif
            </tr>
            <tr>
                <td align="right" valign="top" colspan="3"
                    style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Less Salvage (Cabin)</td>
                <td align="right" valign="top" colspan="{{ $total_col_span }}"
                    style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($less_cabin_salvage) }}</td>
                @if (!empty($remarkShow))
                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                @endif
            </tr>
            @php
                $NetMaterialChargesForCabin = $NetMaterialChargesForCabin - $less_cabin_salvage;
                $total_col_span_for_labour = !empty($remarkShow)
                    ? intval($total_col_span + 3)
                    : intval($total_col_span + 2);
                $labourDescColSpan = intval($total_col_span_for_labour - 4);

            @endphp
            <tr>
                <td align="right" valign="top" colspan="3"
                    style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Net Material Charges for
                    Cabin (A)</td>
                <td align="right" valign="top" colspan="{{ $total_col_span }}"
                    style="padding: 0px 3px; font-weight: bold;">
                    {{ number_format_custom($NetMaterialChargesForCabin) }}</td>
                @if (!empty($remarkShow))
                    <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                @endif
            </tr>
    </tbody>
</table>
<table  width="100%" align="center" id="design" style="font-size: 12px;"><tbody>
            @php
                $totalLabourEstAmtWithGst = 0;
                $totalLabourAssAmtWithGst = 0;
            @endphp
            @if (isset($cabinDetailsCalculation[1]['labour_total']['assessed']) &&
                    $cabinDetailsCalculation[1]['labour_total']['assessed'] > 0)
                <tr>
                    <td style="height: 2px;" colspan="{{ $total_col_span_for_labour }}"></td>
                </tr>
                <tr>
                    <th align="center" valign="top" style="width:7%;">Sr. No.</th>
                    <th align="center" valign="top" style="" colspan="{{ $labourDescColSpan }}">Description of Labour</th>
                    <th align="center" valign="top" style="width:5%">GST %</th>
                    <th align="right" valign="top" style="width:15%;padding: 2px 10px;">Est Amt.</th>
                    <th align="right" valign="top" style="width:15%;padding: 2px 10px;">Ass Amt.</th>
                </tr>
                @php
                    $cabinLabourIndexCounter = 0;
                @endphp
                @foreach ($cabinDetails as $cabin)
                    @if ($cabin['portion'] == 1)
                        @if ($cabin['est_lab'] > 0 || $cabin['ass_lab'] > 0)
                            @php
                                $ass_lab = empty($cabin['quantities']) && $cabin['ass_lab'] > 0 ? $cabin['ass_lab'] : 0;
                                if($multiTaxes["set_mutilple_gst_on_labour"]==0){
                                $cabin['gst'] =$multiTaxes["est_ass_gst_lab_per"];
                               }
                            @endphp
                            <tr>
                                <td align="left" valign="top" style="padding: 0px 3px;">
                                    {{ intval(++$cabinLabourIndexCounter) }}</td>
                                <td align="left" valign="top" style="padding: 0px 3px;"
                                    colspan="{{ $labourDescColSpan }}">{{ $cabin['description'] }}</td>
                                <td align="center" valign="top" style="padding: 0px 3px;">
                                    {{ number_format($cabin['gst']) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">
                                    {{ number_format_custom($cabin['est_lab']) }}</td>
                                <td align="right" valign="top" style="padding: 0px 3px;">
                                    {{ $ass_lab > 0 ? number_format_custom($ass_lab) : '-' }}</td>
                            </tr>
                        @endif
                        @if (!empty($cabin['quantities']))
                            @php
                                $cabinLabourQuantityIndex = 0;
                            @endphp
                            @foreach ($cabin['quantities'] as $sub_cabin)
                                @if ($sub_cabin['ass_lab'] > 0)
                                @php
                                     if($multiTaxes["set_mutilple_gst_on_labour"]==0){
                                $sub_cabin['gst'] =$multiTaxes["est_ass_gst_lab_per"];
                               }
                                @endphp
                                    <tr>
                                        <td align="left" valign="top"
                                            style="padding: 0px 3px; font-style: italic;">{!! $cabinLabourIndexCounter . '.' . intval(++$cabinLabourQuantityIndex) !!}</td>
                                        <td align="left" valign="top"
                                            style="padding: 0px 3px; font-style: italic;"
                                            colspan="{{ $labourDescColSpan }}">{{ $sub_cabin['description'] }}</td>
                                        <td align="center" valign="top"
                                            style="padding: 0px 3px; font-style: italic;">
                                            {{ $sub_cabin['gst'] > 0 ? number_format($sub_cabin['gst']) : '0' }}
                                        </td>
                                        <td align="right" valign="top"
                                            style="padding: 0px 3px; font-style: italic;">{{ '-' }}</td>
                                        <td align="right" valign="top"
                                            style="padding: 0px 3px; font-style: italic;">
                                            {{ $sub_cabin['ass_lab'] > 0 ? number_format_custom($sub_cabin['ass_lab']) : '-' }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach
                @if (isset($cabinDetailsCalculation[1]['labour_details_total']) &&
                        !empty($cabinDetailsCalculation[1]['labour_details_total']))
                    @php
                        $sub_total_ass_labour = 0;
                        $sub_total_est_labour = 0;
                    @endphp
                    @foreach ($cabinDetailsCalculation[1]['labour_details_total'] as $rate => $item)
                        @php
                            $gst_ass_labour =
                                isset($item['assessed']['total']) && $item['assessed']['total'] > 0
                                    ? $item['assessed']['total']
                                    : 0;
                            $gst_est_labour =
                                isset($item['estimated']['total']) && $item['estimated']['total'] > 0
                                    ? $item['estimated']['total']
                                    : 0;
                            $sub_total_ass_labour += $gst_ass_labour;
                            $sub_total_est_labour += $gst_est_labour;
                        @endphp
                        <tr>
                            <td align="left" valign="top" colspan="{{ intval($labourDescColSpan + 2) }}"
                                style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with
                                {{ $lossAssessment[0]['IGSTonPartsAndLab'] == 0 ? 'GST' : 'IGST' }}
                                {{ $rate }}%)</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">
                                {{ number_format_custom($gst_est_labour) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;">
                                {{ number_format_custom($gst_ass_labour) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td align="right" valign="top" colspan="{{ intval($labourDescColSpan + 2) }}"
                            style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                            {{ number_format_custom($sub_total_est_labour) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                            {{ number_format_custom($sub_total_ass_labour) }}</td>
                    </tr>
                    @foreach ($cabinDetailsCalculation[1]['labour_details_total'] as $gst => $labour_gst)
                        @php
                            $labour_est_amt_after_gst = isset($labour_gst['estimated']['amt_after_gst'])
                                ? $labour_gst['estimated']['amt_after_gst']
                                : 0;
                            $labour_ass_amt_after_gst = isset($labour_gst['assessed']['amt_after_gst'])
                                ? $labour_gst['assessed']['amt_after_gst']
                                : 0;

                            $labour_est_gst_amt = isset($labour_gst['estimated']['gst_amount'])
                                ? $labour_gst['estimated']['gst_amount']
                                : 0;
                            $labour_ass_gst_amt = isset($labour_gst['assessed']['gst_amount'])
                                ? $labour_gst['assessed']['gst_amount']
                                : 0;

                            $totalLabourEstAmtWithGst += $labour_est_amt_after_gst;
                            $totalLabourAssAmtWithGst += $labour_ass_amt_after_gst;

                        @endphp
                        <tr>
                            <td align="left" valign="top" colspan="{{ intval($labourDescColSpan + 2) }}"
                                style="padding: 0px 3px; border-left: 1px solid #000;">Add {!! !empty($lossAssessment[0]['IGSTonPartsAndLab']) ? 'IGST' : 'GST' !!} @
                                {{ $gst }}%</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">
                                {{ number_format_custom($labour_est_gst_amt) }}</td>
                            <td align="right" valign="top" style="padding: 0px 3px;">
                                {{ number_format_custom($labour_ass_gst_amt) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td align="right" valign="top" colspan="{{ intval($labourDescColSpan + 2) }}"
                            style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Subtotal</td>
                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                            {{ number_format_custom($totalLabourEstAmtWithGst) }}</td>
                        <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">
                            {{ number_format_custom($totalLabourAssAmtWithGst) }}</td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" colspan="{{ intval($labourDescColSpan + 2) }}"
                            style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Net Labour
                            Charges for Cabin (B)</td>
                        <td align="right" valign="top" colspan="2"
                            style="padding: 0px 3px; font-weight: bold;">
                            {{ number_format_custom($totalLabourAssAmtWithGst) }}</td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" colspan="{{ intval($labourDescColSpan + 2) }}"
                            style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Net Loss Assessed
                            for Cabin (A+B)</td>
                        <td align="right" valign="top" colspan="2"
                            style="padding: 0px 3px; font-weight: bold;">
                            {{ number_format_custom($totalLabourAssAmtWithGst + $NetMaterialChargesForCabin) }}</td>
                    </tr>
                @endif
            @else
                <tr>
                    <td align="right" valign="top" colspan="3"
                        style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Net Loss Assessed for
                        Cabin</td>
                    <td align="right" valign="top" colspan="{{ $total_col_span }}"
                        style="padding: 0px 3px; font-weight: bold;">
                        {{ number_format_custom($NetMaterialChargesForCabin) }}</td>
                    @if (!empty($remarkShow))
                        <td align="center" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                    @endif
                </tr>
            @endif
        @endif
    </tbody>
</table>
