<?php

namespace App\Http\Resources\employee;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Job;


class EmployeeResource extends JsonResource
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
            'user_id' => $this->user_id,
            'total_jobs' => Job::where('submitted_by', '=', $this->id)->count(),
            'completed_jobs' => Job::where('submitted_by', '=', $this->id)->where('submission_date', '!=', null)->count(),
            'type' => $this->type,
            'is_set_password' => $this->is_set_password,
            'role'=>'employee',
            'name' => $this->name,
            'email' => $this->email,
            'job_assigned_to_guest' => $this->job_assigned_to_guest,
            'is_guest_employee' => $this->is_guest_employee,
            'mobile_no' => $this->mobile_no,
            'amount_per_job' => $this->amount_per_job,
            'address' => $this->address,
            'created_by_admin' => $this->created_by_admin,
            'modified_by_admin' => $this->modified_by_admin,
            'status' => $this->status == "1" ? "Active" : "Inactive",
            'last_login_date' => $this->last_login_date,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
