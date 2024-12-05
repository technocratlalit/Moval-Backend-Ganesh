<?php

namespace App\Http\Resources\job;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\JobFile;
use App\Models\JobTransactionHistory;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $TBL_ADMIN      = config('global.admin_table');
        $TBL_JOB_TRANSACTION_HISTORY      = config('global.job_transaction_history');

        $transactionHistory =  JobTransactionHistory::join($TBL_ADMIN.' as admin', 'admin.id', '=', 'user_id')->where('job_id', '=', $this->id)->where($TBL_JOB_TRANSACTION_HISTORY.'.status', '=', 'pending')->where('user_type', '=', 'Admin')->select('admin.name as admin_name')->first();
        $assignedByName = "";
        if (!is_null($transactionHistory)){
            $assignedByName = $transactionHistory->admin_name;
        }

        $report_url = "";
        $draft_report_url = "";
        if ($this->job_status == "finalized"){
            $reportFileName = encryptString("job_finalize_report_".$this->id.".pdf");
            $report_url =  (config('app.imageurl')."pdf_report/".$reportFileName);
            $reportFileName = encryptString("job_finalize_report_draft_".$this->id.".pdf");
            $draft_report_url =  (config('app.imageurl')."pdf_report/".$reportFileName);
        }


        return [
            'id' => $this->id,
            'job_photos_count' => JobFile::where('job_id', '=', $this->id)->count(),
            'vehicle_reg_no' => $this->vehicle_reg_no,
            'owner_name' => $this->owner_name,
            'address' => $this->address,
            'inspection_place' => $this->inspection_place,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'contact_mobile_no' => $this->contact_mobile_no,
            'requested_by' => $this->requested_by,
            'approved_by' => $this->approved_by,
            'rejected_by' => $this->rejected_by,
            'submitted_by' => $this->submitted_by,
            'completed_by' => $this->completed_by,
            'assigned_by_name' => $assignedByName,
            'assigned_date'=>   $this->assigned_date,
            'requested_by_name' => $this->requested_by_name,
            'approved_by_name' => $this->approved_by_name,
            'rejected_by_name' => $this->rejected_by_name,
            'submitted_by_name' => $this->submitted_by_name,
            'completed_by_name' => $this->completed_by_name,
            'submission_date' => $this->submission_date,
            'approval_date' => $this->approval_date,
            'rejected_date' => $this->rejected_date,
            'completed_at' => $this->completed_at,
            'job_status' => $this->job_status,
            'finalize_report_url' => $report_url,
            'draft_report_url' => $draft_report_url,
            'is_offline' => $this->is_offline,
            'mail_sent_to_client' => $this->mail_sent_to_client,
            'mail_sent_to_employee' => $this->mail_sent_to_employee,
            'mail_sent_date' => $this->mail_sent_date,
            'mail_send_by' => $this->mail_send_by,
            'mail_send_by_name' => $this->mail_send_by_name,
            'payment_link_tracking_id' => $this->payment_link_tracking_id,
            'payment_link_send_date' => $this->payment_link_send_date,
            'payment_status' => $this->payment_status,
            'branch_name' => $this->branch_name,
            'branch_id' => $this->branch_id,
            'status' => $this->status == 1 ? 'Active' : 'Inactive',
            'images' => $this->images,
            'remark' => $this->remark,
            'job_detail' => $this->job_detail,
            'branch_list' => $this->branch_list,
            'contact_person_list' => $this->contact_person_list,
            'admin_remark' => $this->admin_remark,
            'mail_sent_cnt' => $this->mail_sent_cnt,
            'contact_person_name' => $this->contact_person_name,
            'contact_person_id' => $this->contact_person_id,
            'vehicle_owner_signature' => $this->vehicle_owner_signature,
            'is_outside_job' => $this->is_outside_job == 1 ? 'Yes' : 'No',
            'letter_head_img' => $this->letter_head_img,
            'letter_footer_img' => $this->letter_footer_img,
            'signature_img' => $this->signature_img,
            'job_report_no' => $this->job_report_no,
            'authorized_person_name' => $this->authorized_person_name,
            'job_distance_filter' => config('global.job_distance_filter'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
