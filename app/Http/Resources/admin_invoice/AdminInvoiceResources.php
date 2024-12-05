<?php

namespace App\Http\Resources\admin_invoice;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminInvoiceResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $reportFileName     =   encryptString('moval_invoice_'.$this->id.'.pdf');
        $report_url         =  (config('app.imageurl')."admin_invoice/".$reportFileName);

        return [
            'id' => $this->id,
            'invoice_date' => $this->invoice_date,
            'number_of_reports' => $this->number_of_reports,
            'report_cost' => $this->report_cost,
            'bill_amount' => $this->bill_amount,
            'paid_amount' => $this->paid_amount,
            'admin_name' => $this->admin_name,
            'admin_email' => $this->admin_email,
            'payment_status' => $this->payment_status,
            'payment_date' => $this->payment_date,
            'invoice_url' => $report_url,
            'last_date_of_payment' => $this->last_date_of_payment,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
