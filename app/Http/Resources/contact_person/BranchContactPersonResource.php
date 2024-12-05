<?php

namespace App\Http\Resources\contact_person;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchContactPersonResource extends JsonResource
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
            'name' => $this->name,
            'branch_name' => $this->branch_name,
            'branch_id' => $this->branch_id,
            'designation' => $this->designation,
            'landline_no' => $this->landline_no,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'status' => $this->status == "1" ? "Active" : "Inactive",
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
