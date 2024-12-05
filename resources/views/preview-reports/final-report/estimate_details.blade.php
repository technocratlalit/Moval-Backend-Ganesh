@php
    $estTab=json_decode($estimates,true);

    $total=array_sum(array_column($estTab, 'amount'));
@endphp
@if(count($estTab)>0)
<table id="detail" width="100%" style="border-top:1px solid #000;font-size:12px">
    <tbody>
        <tr>
            <td align="left" valign="top" style="padding-top: 3px; font-weight: bold;text-decoration: underline; " colspan="3">Estimate Details</td>
        </tr>
        <tr>
            <td align="left" valign="top" style="padding-top: 3px;" colspan="3">Details of Estimates submitted by the Repairer: </td>
        </tr>
        <tr>
            <th style="border:1px solid #000;width:10%;padding-top: 3px;font-weight: bold; ">Sr. No.</th>
            <th style="border:1px solid #000;padding-top: 3px;font-weight: bold; ">Estimate Description</th>
            <th style="border:1px solid #000;width:20%padding-top: 3px;font-weight: bold; ">Est. Amount</th>
        </tr>
@foreach ($estTab as $key=>$details)
    <tr>
        <td style="border:1px solid #000;text-align:center">{{ $key+1 }}</td>
        <td style="border:1px solid #000;">{{ $details["detail"] }}</td>
        <td style="border:1px solid #000;text-align:center">{{ number_format_custom($details["amount"]) }}</td>
    </tr>
@endforeach
<tr>
    <td colspan="2" style="text-align:right;border:1px solid #000;padding-right: 10px;font-weight: bold; text-decoration: underline;">Total</td>
    <td style="border:1px solid #000;text-align:center;font-weight: bold">{{ number_format_custom($total) }}</td>
</tr>
</tbody>
</table>
@endif