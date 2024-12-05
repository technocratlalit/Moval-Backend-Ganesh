<?php

namespace App\Http\Resources\client;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientBrachResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'office_code' => $this->office_code,
            'office_name' => $this->office_name,
            'office_address' => $this->office_address,
            'gst_no' => $this->gst_no,
            'contact_detail' => $this->contact_detail,
            'manager_name' => $this->manager_name,
            'manager_email' => $this->manager_email,
            'manager_mobile_no' => $this->manager_mobile_no,
            'within_state' => $this->within_state,
            'mode_of_payment' => $this->mode_of_payment,
            'amount_per_job' => $this->amount_per_job,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
