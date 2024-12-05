@php
    //Labour Gst
    $labourGst = [];
    $labourConditionalGst = [];
    $labourConditionalGstWithName = [];
    $GSTonEstimatedLabMultiple = false;
    $GstonAssessedLabMultiple = false;
    if(isset($lossAssessment[0]['MultipleGSTonLab']) && !empty($lossAssessment[0]['MultipleGSTonLab'])) {
        if($lossAssessment[0]['GSTonEstimatedLab'] == 'Y') {
            $GSTonEstimatedLabMultiple = true;
            foreach ($uniqueLabourGstValue as $gst) {
                $labourConditionalGst[] = $gst;
                $labourConditionalGstWithName[$gst]['LabourGSTEstimatedPer'] = $gst;
            }
        } else {
            $labourConditionalGst[] = 0;
            $labourConditionalGstWithName[0]['LabourGSTEstimatedPer'] = 0;
        }

        if($lossAssessment[0]['GstonAssessedLab'] == 'Y') {
            $GstonAssessedLabMultiple = true;
            foreach ($uniqueLabourGstValue as $gst) {
                $labourConditionalGst[] = $gst;
                $labourConditionalGstWithName[$gst]['LabourGSTAssessedPPer'] = $gst;
            }
        } else {
            $labourConditionalGst[] = 0;
            $labourConditionalGstWithName[0]['LabourGSTAssessedPPer'] = 0;
        }
        $labourGst = $labourConditionalGst;
    }else {
        $conditionLabourEstGstValue = ($lossAssessment[0]['GSTonEstimatedLab'] == 'Y' && $lossAssessment[0]['GSTLabourPer'] > 0) ? $lossAssessment[0]['GSTLabourPer'] : 0;
        $conditionLabourAssGstValue = ($lossAssessment[0]['GstonAssessedLab'] == 'Y' && $lossAssessment[0]['GSTLabourPer'] > 0) ? $lossAssessment[0]['GSTLabourPer'] : 0;
        $labourConditionalGst[] = $conditionLabourEstGstValue;
        $labourConditionalGst[] = $conditionLabourAssGstValue;
        $labourConditionalGstWithName[$conditionLabourEstGstValue]['LabourGSTEstimatedPer'] = $conditionLabourEstGstValue;
        $labourConditionalGstWithName[$conditionLabourAssGstValue]['LabourGSTAssessedPPer'] = $conditionLabourAssGstValue;
        $labourGst = $labourConditionalGst;
    }
    $labourGst = !empty($labourGst) ? array_unique($labourGst) : [];
    sort($labourGst);
    $labourIndexCounter = 0;
@endphp
<table width="100%" id="design" cellpadding="0" cellspacing="0" border="0" align="center" style="padding-top:20px;">
    <tbody>
        <tr><td align="left" valign="top" style="border-top: 2px solid #000; font-weight: bold; border-right:none; line-height:0px; padding: 3px 0px;">LABOUR CHARGES</td> </tr>
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

        @foreach($alldetails as $labour)
            @if(($labour['est_lab'] > 0 && $labour['ass_lab'] > 0) || $labour['painting_lab'] > 0)
                <tr>
                    <td align="center" valign="top" style="padding: 0px 3px; border-left: 1px solid #000;">{{ ++$labourIndexCounter }}</td>
                    <td align="left" valign="top" style="padding: 0px 3px;">{!! ($labour['imt_23'] == "Yes") ? '<strong>*</strong>' : ''!!} {{ $labour['description'] }}</td>
                    @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                        <td align="left" valign="top" style="padding: 0px 3px;">{{ (!empty($labour['sac']) && $labour['sac'] > 0) ? $labour['sac'] : '-' }}</td>
                    @endif
                    <td align="left" valign="top" style="padding: 0px 3px;">{!! !empty($labour['remarks']) ? $labour['remarks'] : '-' !!}</td>
                    <td align="center" valign="top" style="padding: 0px 3px;">{!! !empty($labour['gst']) ? $labour['gst'] : 0 !!}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{!! (!empty($labour['est_lab']) && $labour['est_lab'] > 0) ? number_format_custom($labour['est_lab']) : '-' !!}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{!! (empty($labour['quantities']) && $labour['ass_lab'] > 0) ? number_format_custom($labour['ass_lab']) : '-' !!}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{!! (empty($labour['quantities']) && $labour['painting_lab'] > 0) ? number_format_custom($labour['painting_lab']) : '-' !!}</td>
                </tr>
            @endif
            @if(!empty($labour['quantities']))
                @php
                    $quantityLabourIndex = 0;
                @endphp
                @foreach($labour['quantities'] as $quantityLabour)
                    @if(($quantityLabour['est_lab'] > 0 && $quantityLabour['ass_lab'] > 0) || $quantityLabour['painting_lab'] > 0)
                        <tr>
                            <td align="center" valign="top" style="padding: 0px 3px 0px 13px; font-style: italic; border-left: 1px solid #000;">{!! $labourIndexCounter.'.'.intval(++$quantityLabourIndex) !!}</td>
                            <td align="left" valign="top" style="padding: 0px 3px; font-style: italic;">{!! ($quantityLabour['imt_23'] == "Yes") ? '<strong>*</strong>' : ''!!} {{ $quantityLabour['description'] }}</td>
                            @if(isset($lossAssessment[0]['display_hsn']) && $lossAssessment[0]['display_hsn'] == 1)
                                <td align="left" valign="top" style="padding: 0px 3px;">{{ (!empty($quantityLabour['sac']) && $quantityLabour['sac'] > 0) ? $quantityLabour['sac'] : '-' }}</td>
                            @endif
                            <td align="left" valign="top" style="padding: 0px 3px;">{!! !empty($quantityLabour['remarks']) ? $quantityLabour['remarks'] : '-' !!}</td>
                            <td align="center" valign="top" style="padding: 0px 3px;">{!! !empty($quantityLabour['gst']) ? $quantityLabour['gst'] : 0 !!}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{!! ($quantityLabour['est_lab'] > 0) ? number_format_custom($quantityLabour['est_lab']) : '-' !!}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{!!  ($quantityLabour['ass_lab'] > 0) ? number_format_custom($quantityLabour['ass_lab']) : '-' !!}</td>
                            <td align="right" valign="top" style="padding: 0px 3px; font-style: italic;">{!! ($quantityLabour['painting_lab'] > 0) ? number_format_custom($quantityLabour['painting_lab']) : '-' !!}</td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if(!empty($labourGst))
            @php
                $gstLabourIndexWiseAmt = [];
                //Sub Total Counting Variable Start
                $SubTotalEstimatedLabourAmt = 0;
                $SubTotalAssLabourAmt = 0;
                $SubTotalPaintingAmt = 0;
                $SubTotalPaintingAmtImt = 0;

            @endphp
            @foreach($labourGst as $gst)
                @php
                    $totalEstimatedLabourAmt = 0;
                    $totalAssLabourAmt = 0;
                    $totalPaintingAmt = 0;
                    $totalPaintingAmtImt = 0;
                @endphp
                @foreach($alldetails as $labour)
                    @php
                        if(($labour['est_lab'] > 0 && $labour['ass_lab'] > 0) || $labour['painting_lab'] > 0) {
                            if((isset($labourConditionalGstWithName[$labour['gst']]['LabourGSTEstimatedPer']) && $gst == $labour['gst']) || (isset($labourConditionalGstWithName[$gst]['LabourGSTEstimatedPer']) && empty($GSTonEstimatedLabMultiple))) {
                                $totalEstimatedLabourAmt += ($labour['est_lab'] > 0) ? $labour['est_lab'] : 0;
                            }
                            if((isset($labourConditionalGstWithName[$labour['gst']]['LabourGSTAssessedPPer']) && $gst == $labour['gst']) || (isset($labourConditionalGstWithName[$gst]['LabourGSTAssessedPPer']) && empty($GstonAssessedLabMultiple))) {
                                $totalAssLabourAmt += ($labour['ass_lab'] > 0) ? $labour['ass_lab'] : 0;
                                if($labour['imt_23'] == "Yes") {
                                    $totalPaintingAmtImt += ($labour['painting_lab'] > 0) ? $labour['painting_lab'] : 0;
                                } else {
                                    $totalPaintingAmt += ($labour['painting_lab'] > 0) ? $labour['painting_lab'] : 0;
                                }
                            }
                        }

                        if(!empty($labour['quantities'])) {
                            foreach ($labour['quantities'] as $quantities) {
                                if(($quantities['est_lab'] > 0 && $quantities['ass_lab'] > 0) || $quantities['painting_lab'] > 0) {
                                    if((isset($labourConditionalGstWithName[$quantities['gst']]['LabourGSTEstimatedPer']) && $gst == $quantities['gst']) || (isset($labourConditionalGstWithName[$gst]['LabourGSTEstimatedPer']) && empty($GSTonEstimatedLabMultiple))) {
                                        $totalEstimatedLabourAmt += ($quantities['est_lab'] > 0) ? $quantities['est_lab'] : 0;
                                    }
                                    if((isset($labourConditionalGstWithName[$quantities['gst']]['LabourGSTAssessedPPer']) && $gst == $quantities['gst']) || (isset($labourConditionalGstWithName[$gst]['LabourGSTAssessedPPer']) && empty($GstonAssessedLabMultiple))) {
                                        $totalAssLabourAmt += ($quantities['ass_lab'] > 0) ? $quantities['ass_lab'] : 0;
                                        if($quantities['imt_23'] == "Yes") {
                                            $totalPaintingAmtImt += ($quantities['painting_lab'] > 0) ? $quantities['painting_lab'] : 0;
                                        } else {
                                            $totalPaintingAmt += ($quantities['painting_lab'] > 0) ? $quantities['painting_lab'] : 0;
                                        }
                                    }
                                }
                            }
                        }
                    @endphp
                @endforeach
                @php
                    $SubTotalEstimatedLabourAmt += $totalEstimatedLabourAmt;
                    $SubTotalAssLabourAmt += $totalAssLabourAmt;
                    $SubTotalPaintingAmt += $totalPaintingAmt;
                    $SubTotalPaintingAmtImt += $totalPaintingAmtImt;
                    $gstLabourIndexWiseAmt[$gst]['ess_lab_amt'] = $totalEstimatedLabourAmt;
                    $gstLabourIndexWiseAmt[$gst]['ass_lab_amt'] = $totalAssLabourAmt;
                    $gstLabourIndexWiseAmt[$gst]['ass_painting_amt'] = $totalPaintingAmt;
                    $gstLabourIndexWiseAmt[$gst]['ass_painting_imt_amt'] = $totalPaintingAmtImt;

                    $labour_category_tax_summery_details['labour'][$gst]['total'] = $totalAssLabourAmt;
                    $labour_category_tax_summery_details['painting_labour'][$gst]['total'] = $totalPaintingAmt;
                    $labour_category_tax_summery_details['imt_painting_labour'][$gst]['total'] = $totalPaintingAmtImt;
                @endphp
                <tr>
                    <td align="left" valign="top" colspan="4" style="padding: 0px 3px; border-left: 1px solid #000;">Total (Labour with GST {{$gst}}%)</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalEstimatedLabourAmt) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalAssLabourAmt) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalPaintingAmt + $totalPaintingAmtImt) }}</td>
                </tr>
            @endforeach
            @php
                $imtNoteShowPainting = false;
                $totalPaintingWithImtPaint = ($SubTotalPaintingAmt + $SubTotalPaintingAmtImt);
                $less50DepPainting = 0;
                $less50DepPaintingImt = 0;
                if(empty($lossAssessment[0]['IsZeroDep']) && $lossAssessment[0]['IsZeroDep']==0) {
                    $less50DepPainting = ((($SubTotalPaintingAmt * 25) / 100) / 2);
                    $less50DepPaintingImt = ((($SubTotalPaintingAmtImt * 25) / 100) / 2);
                }
                $totalPaintingLess = ($less50DepPainting + $less50DepPaintingImt);
                $totalLessPaintingWithImtPaint = ($totalPaintingWithImtPaint - $totalPaintingLess);
            @endphp
            <tr>
                <td align="right" valign="top" colspan="4" style="padding: 0px 3px; font-weight: bold;  border-left: 1px solid #000;">Total</td>
                <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($SubTotalEstimatedLabourAmt) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($SubTotalAssLabourAmt) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($totalPaintingWithImtPaint) }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" colspan="4" style="padding: 0px 3px; border-left: 1px solid #000;">Less <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['IsZeroDep']==1) ? 0 : 50 }}%</span> on <span style="font-weight: bold;">25%</span> of Painting Material of <span style=" font-weight: bold;">Rs.{{ number_format_custom($totalPaintingWithImtPaint) }}</span></td>
                <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($totalPaintingLess) }}</td>
            </tr>
            <tr>
                <td align="right" valign="top" colspan="4" style="padding: 0px 3px; font-weight: bold;  border-left: 1px solid #000;">Sub Total</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($SubTotalEstimatedLabourAmt) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold;">{{ number_format_custom($SubTotalAssLabourAmt) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($totalLessPaintingWithImtPaint) }}</td>
            </tr>
            @if($SubTotalPaintingAmtImt > 0)
                @php
                    $totalIMTPaintingAmtAfterDep = ($SubTotalPaintingAmtImt - $less50DepPaintingImt);
                    $addLessPaintingOnImt = (($totalIMTPaintingAmtAfterDep * $lossAssessment[0]['IMT23DepPer']) / 100);
                @endphp
                <tr>
                    <td align="left" valign="top" colspan="4" style="padding: 0px 3px; border-left: 1px solid #000;">Less Addl. Deduction <span style="font-weight: bold;">{{ !empty($lossAssessment[0]['IMT23DepPer']) ? number_format_custom($lossAssessment[0]['IMT23DepPer'], 2) : '0' }}% </span> on IMT Labour of <span style=" font-weight: bold;">Rs.{{ number_format_custom($totalIMTPaintingAmtAfterDep)}}</span>
                    <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">&nbsp;</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addLessPaintingOnImt)}}</td>
                </tr>
                <tr>
                    <td align="right" valign="top" colspan="4" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Sub Total</td>
                    <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($SubTotalEstimatedLabourAmt) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;font-weight: bold;">{{ number_format_custom($SubTotalAssLabourAmt) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px; font-weight: bold; border-top: 1px solid #000;">{{ number_format_custom($totalLessPaintingWithImtPaint - $addLessPaintingOnImt) }}</td>
                </tr>
            @endif

            @php
                $grandTotalEstimatedLabourAmt = 0;
                $grandTotalAssLabourAmt = 0;
                $grandTotalPaintingAmt = 0;
            @endphp
            @foreach($labourGst as $gst)
                @php
                    $totalEstLabAmt = $gstLabourIndexWiseAmt[$gst]['ess_lab_amt'];
                    $totalAssLabAmt = $gstLabourIndexWiseAmt[$gst]['ass_lab_amt'];
                    $totalPaintingAmt = $gstLabourIndexWiseAmt[$gst]['ass_painting_amt'];
                    $totalPaintingImtAmt = $gstLabourIndexWiseAmt[$gst]['ass_painting_imt_amt'];

                    $totalPaintingLess = 0;
                    $totalPaintingImtLess = 0;
                    if(empty($lossAssessment[0]['IsZeroDep']) && $lossAssessment[0]['IsZeroDep']==0) {
                        $totalPaintingLess = ((($totalPaintingAmt * 25) / 100) / 2);
                        $totalPaintingImtLess = ((($totalPaintingImtAmt * 25) / 100) / 2);
                    }
                    $totalPaintingAmt -= $totalPaintingLess;
                    $totalPaintingImtAmt -= $totalPaintingImtLess;
                    $addLessOnImt = 0;
                    if($totalPaintingImtAmt > 0) {
                        if(empty($imtNoteShowPainting)) {
                            $imtNoteShowPainting = true;
                        }
                        $addLessOnImt = (($totalPaintingImtAmt * $lossAssessment[0]['IMT23DepPer']) / 100);
                        $totalPaintingImtAmt -= $addLessOnImt;
                    }
                    $subTotalPaintingAmt = ($totalPaintingAmt + $totalPaintingImtAmt);

                    $addingEstLabGst = (($totalEstLabAmt * $gst) / 100);
                    $addingAssLabGst = (($totalAssLabAmt * $gst) / 100);
                    $addingPaintingGst = (($subTotalPaintingAmt * $gst) / 100);
                    $grandTotalEstimatedLabourAmt += ($totalEstLabAmt + $addingEstLabGst);
                    $grandTotalAssLabourAmt += ($totalAssLabAmt + $addingAssLabGst);
                    $grandTotalPaintingAmt += ($subTotalPaintingAmt + $addingPaintingGst);

                    $labour_category_tax_summery_details['labour'][$gst]['gst'] = $addingAssLabGst;
                    $labour_category_tax_summery_details['painting_labour'][$gst]['less'] = $totalPaintingLess;
                    $labour_category_tax_summery_details['imt_painting_labour'][$gst]['less'] = $totalPaintingImtLess;
                    $labour_category_tax_summery_details['imt_painting_labour'][$gst]['add_imt_less'] = $addLessOnImt;
                @endphp
                <tr>
                    <td align="left" valign="top" colspan="4" style="padding: 0px 3px; border-left: 1px solid #000;">Add GST {{ $gst }}%</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addingEstLabGst) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addingAssLabGst) }}</td>
                    <td align="right" valign="top" style="padding: 0px 3px;">{{ number_format_custom($addingPaintingGst) }}</td>
                </tr>
            @endforeach
            <tr>
                <td align="right" valign="top" colspan="4" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Total</td>
                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{ number_format_custom($grandTotalEstimatedLabourAmt) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{ number_format_custom($grandTotalAssLabourAmt) }}</td>
                <td align="right" valign="top" style="padding: 0px 3px; border-top: 1px solid #000;font-weight: bold;">{{ number_format_custom($grandTotalPaintingAmt) }}</td>
            </tr>
            <tr>
                <td align="left" valign="top" colspan="4" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">Net Labour Amount (O/F & D/B + Painting Labour)</td>
                <td align="right" valign="top" colspan="3" style="padding: 0px 3px; font-weight: bold; border-top: 1px solid #000;">{{ number_format_custom($grandTotalAssLabourAmt + $grandTotalPaintingAmt) }}</td>
            </tr>
            @if(!empty($imtNoteShowPainting))
                <tr>
                    <td align="left" valign="top" colspan="7" style="padding: 0px 3px; font-weight: bold; border-left: 1px solid #000;">* The Star marks denotes against the IMT-21  Painting allowed.</td>
                </tr>
            @endif
        @endif
    </tbody>
</table>