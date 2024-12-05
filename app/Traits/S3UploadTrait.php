<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Inspection;
use App\Models\Admin_ms;
use App\Models\Branch;
use Illuminate\Support\Facades\Storage;

trait S3UploadTrait
{
    public function uploadToS3Old($fields, $filetype, $Filename)
    {
        $job = Inspection::where('id', $fields['inspection_id'])->first();
        $createdAt = Carbon::parse($job->created_at);
        $admin_id = $job->admin_id;
        $date = $createdAt->format('d-m-Y');
        $month = $createdAt->format('F');
        $year = $createdAt->format('Y');

        $adminName = Admin_ms::where('id', $admin_id)->first()->name;
        $branchName = Branch::where('id',$job->admin_branch_id)->first()->admin_branch_name;

        $JobId = $job->id;

        $S3Path = "";
        if ($filetype == 1 || $filetype == 3) {
            $vehicleImage = "Vehicle_Images";
            $S3Path = env('AWS_MAIN_BUCKET_URL', 'dev.moval.com/') . $adminName.'_'.$admin_id . '/' . $branchName . '/' . $year . '/' . $month . '/' . $date . '/' . $JobId . '/' . $vehicleImage . '/' . $Filename;
        } elseif($filetype == 2 || $filetype == 4){

            $vehicleDocument = "Vehicle_Documents";
            $S3Path = env('AWS_MAIN_BUCKET_URL', 'dev.moval.com/') . $adminName.'_'.$admin_id . '/' . $branchName . '/' . $year . '/' . $month . '/' . $date . '/' . $JobId . '/' . $vehicleDocument . '/' . $Filename;

        }else{
            $vehicleVideo = "Video";
            $S3Path = env('AWS_MAIN_BUCKET_URL', 'dev.moval.com/') . $adminName.'_'.$admin_id . '/' . $branchName . '/' . $year . '/' . $month . '/' . $date . '/' . $JobId . '/' . $vehicleVideo . '/' . $Filename; 
        }

        return $S3Path;
    }

    public function uploadToS3($fields, $filetype, $Filename)
    {
        $job = Inspection::where('id', $fields['inspection_id'])->first();
        $createdAt = Carbon::parse($job->created_at);
        $admin_id = $job->admin_id;
        $date = $createdAt->format('d-m-Y');
        $month = $createdAt->format('F');
        $year = $createdAt->format('Y');

        $adminName = Admin_ms::where('id', $admin_id)->first()->name;
        $branchName = Branch::where('id',$job->admin_branch_id)->first()->admin_branch_name;

        $JobId = $job->id;
        $path = 'dev.moval.com';
        if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'moval.techkrate.com') {
            $path = 'moval.com';
        }
        $vehicleType = "Video";
        if ($filetype == 1 || $filetype == 3) {
            $vehicleType = "Vehicle_Images";
        } elseif($filetype == 2 || $filetype == 4){
            $vehicleType = "Vehicle_Documents";
        }
        $S3Path = "$path/$adminName"."_"."$admin_id/$branchName/$year/$month/$date/$JobId/$vehicleType/$Filename";
        return $S3Path;
    }
}
