<?php

namespace App\Http\Resources\adminms;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminmsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $letter_head_img = "";
        if (isset($this->letter_head_img) && $this->letter_head_img != "") {
           $letter_head_img =  asset('public/storage/' . $this->letter_head_img);
        }

        $letter_footer_img = "";
        if (isset($this->letter_footer_img) && $this->letter_footer_img != "") {
           $letter_footer_img =  asset('public/storage/' . $this->letter_footer_img);
        }

        $signature_img = "";
        if (isset($this->signature_img) && $this->signature_img != "") {
           $signature_img =  asset('public/storage/' . $this->signature_img);
        }


        return [
            'id' => $this->id,
            'created_by_name' =>adminname(($this->parent_id == 0) ? $this->id : $this->parent_id ),
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'mobile_no' => $this->mobile_no,
            'role' => $this->role,
            'branch_admin_id' => checkIsBranchAdminIsDisableORenable($this->branch_admin_id),
            'admin_branch_id' => $this->admin_branch_id,
            'branch_name' => getBranchbybranchid($this->admin_branch_id),
            'admin_branch_name' => getBranchbybranchid($this->admin_branch_id),
            'is_set_password' => $this->is_set_password,
            'status' => $this->status == '1' ? 'Active':'Inactive',
            'parent_id' => $this->parent_id,
            'can_download_report' => $this->can_download_report,
            'letter_head_img' => $letter_head_img,
            'letter_footer_img' => $letter_footer_img,
            'signature_img' => $signature_img,
            'reference_no_prefix' => $this->reference_no_prefix,
            'designation' => $this->designation,
            'membership_no' => $this->membership_no,
            'each_report_cost' => $this->each_report_cost,
            'number_of_photograph' => $this->number_of_photograph,
            'duraton_delete_photo' => $this->duraton_delete_photo,
            'report_no_start_from' => $this->report_no_start_from,
            'authorized_person_name' => $this->authorized_person_name,
            'branch_admin' => ($this->role == 'branch_admin') ? 1 : 0 ,
            "deleted_at" => (!empty($this->deleted_at)) ? 1 : 0,
            'billing_start_from' => (isset($this->billing_start_from) && $this->billing_start_from != "") ? $this->billing_start_from : "",
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'), 
        ];
    }
}