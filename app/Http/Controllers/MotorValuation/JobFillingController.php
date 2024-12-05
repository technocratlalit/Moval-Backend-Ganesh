<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use App\Models\BranchContactPerson;
use App\Models\ClientBranch;
use App\Models\Branch;
use App\Models\Employeems;
use App\Models\Admin_ms;
use App\Models\Clientms;
use App\Models\Job;
use App\Models\{Jobms, Inspection, InspectionLinks, InspectionVehicleDetail};
use App\Models\Joblink;
use App\Models\Manuallupload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use File;
use App\Models\SopMaster;
use App\Models\Workshop;
use App\Http\Resources\sop\SopResource;
use App\Http\Resources\sop\SopCollection;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Storage;
use App\Traits\S3UploadTrait;


class JobFillingController extends BaseController
{
    use S3UploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function json_validator($data)
    {
        if (!empty($data)) {
            return is_string($data) &&
            is_array(json_decode($data, true)) ? true : false;
        }
        return false;
    }


    public function store(Request $request)
    {
        $response = [];
        $TBL_job = 'tbl_ms_inspection_details';
        $fields = $request->validate([
            'vehichle_images_field_post' => '',
            'document_images_field_post' => '',
            'custom_vehichle_images_field_post' => '',
            'custom_document_images_field_post' => '',
            'inspection_id' => 'required|exists:' . $TBL_job . ',id',
            'sop_id' => 'required',
        ]);

        $job = Inspection::where('id', $fields['inspection_id'])->first();

        if ($job->Job_Route_To == 2) {
            $created_by = 'Survey Link';
            $role = 'Customer';
            $logged_in_id = 'Link Uploaded';
        } else {
            $created_by = Auth::user()->id;
            $role = Auth::user()->role;
        }
        $callFrom = $request->route()->getPrefix();
        $data['submitted_by'] = $created_by;
        $data['submitted_by_role'] = $role;
        $job->update($data);
        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            if (!$file->isValid()) {
                throw new \Exception('Invalid file.');
            }

            // Generate a unique file name
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filetype = 5;


            $S3Path = $this->uploadToS3($fields, $filetype, $fileName);
            if ($fileName && $file->isValid()) {
                Storage::disk('s3')->put($S3Path, file_get_contents($file));
            }
            $job_data = array('video_file' => $fileName, 'job_remark' => $request->job_remark);
            $admin = Inspection::where('id', $fields['inspection_id'])->update($job_data);
            $response['video_file'][] = $admin;
        }

        $job_data = array('job_remark' => $request->job_remark);
        Inspection::where('id', $fields['inspection_id'])->update($job_data);
        //creating new folder
        $path = public_path() . '/job_files/' . $fields['inspection_id'];
        File::makeDirectory($path, $mode = 0777, true, true);

        Manuallupload::where('inspection_id', $fields['inspection_id'])->delete();
        $appType = $request->app_type;

        if (isset($fields['vehichle_images_field_post'])) {
            $type = 'vehichle_images_field_post';
            $data = (!empty($fields[$type]) && !is_array($fields[$type])) ? json_decode($fields[$type], true) : $fields[$type];
            $response[] = $this->UploadDocument($data, $fields, $type, 1, $appType);
        }
        if (isset($fields['document_images_field_post'])) {
            $type = 'document_images_field_post';
            $data = (!empty($fields[$type]) && !is_array($fields[$type])) ? json_decode($fields[$type], true) : $fields[$type];
            $response[] = $this->UploadDocument($data, $fields, $type, 2, $appType);
        }
        if (isset($fields['custom_vehichle_images_field_post'])) {
            $type = 'custom_vehichle_images_field_post';
            $data = (!empty($fields[$type]) && !is_array($fields[$type])) ? json_decode($fields[$type], true) : $fields[$type];
            $response[] = $this->UploadDocument($data, $fields, $type, 3, $appType);
        }
        if (isset($fields['custom_document_images_field_post'])) {
            $type = 'custom_document_images_field_post';
            $data = (!empty($fields[$type]) && !is_array($fields[$type])) ? json_decode($fields[$type], true) : $fields[$type];
            $response[] = $this->UploadDocument($data, $fields, $type, 4, $appType);
        }
        return $this->sendResponse($response, 'Job File Uploaded.', 200);
    }

    private function UploadDocument($data, $fields, $type, $filetype, $appType = NULL)
    {
        if (request()->is('api/job-upload-link')) {
            $logged_in_id = 'Link Uploaded';
        } else {
            $logged_in_id = Auth::user()->id;
        }

        $result = [];
        if (!empty($data)) {
            foreach ($data as $document) {
                $sopLabel = $document['name'];
                $url = $document['path'];
                if (isset($document['photosheet_selected'])) {
                    $photosheet = $document['photosheet_selected'];
                } else {
                    $photosheet = 0;
                }
                $path = parse_url($url, PHP_URL_PATH);
                $tempName = basename($path);
                $Filename = (!empty($tempName)) ? $fields['inspection_id'] . '_original_' . rand() . '.png' : '';
                $ai_box_coordinate = (isset($document['ai_box_coordinate'])) ? $document['ai_box_coordinate'] : [];
                $final_box_coordinate = (isset($document['final_box_coordinate'])) ? $document['final_box_coordinate'] : [];
                if ((strpos($tempName, 'temp_') !== false)) {
                    $S3Path = $this->uploadToS3($fields, $filetype, $Filename);
                    if (file_exists(public_path('temp/' . $tempName))) {
                        $s3file = Storage::disk('s3')->put($S3Path, file_get_contents(public_path('temp/' . $tempName)));
                        $s3fileurl = Storage::disk('s3')->url($S3Path);
                    }
                } else if ((strpos($document['path'], '_original_') !== false)) {
                    $Filename = basename($document['path']);
                }

                $vahicle_data = array(
                    'inspection_id' => $fields['inspection_id'],
                    'sop_id' => $fields['sop_id'],
                    'sop_label' => $sopLabel,
                    'ai_box_coordinate' => json_encode($ai_box_coordinate),
                    'final_box_coordinate' => json_encode($final_box_coordinate),
                    'original_file_name' => $Filename,
                    'photosheet_selected' => $photosheet,
                    'uploaded_by' => $logged_in_id,
                    'file_type' => $filetype
                );
                $uploaded = Manuallupload::create($vahicle_data);
                $result[$type][] = $uploaded;
            }
        }

        if ($appType === NULL) {
            $job_data = array('job_status' => 'submitted');
            $admin = Inspection::where('id', $fields['inspection_id'])->update($job_data);
        }

        if (request()->is('api/job-upload-link')) {
            InspectionLinks::where('inspection_id', $fields['inspection_id'])->update(['status' => 1, 'submitted_date' => date('Y-m-d H:i:s')]);
        }
        return $result;
    }


    public function UploadSignture(Request $request)
    {
        $TBL_job = 'tbl_ms_inspection_details';
        $fields = $request->validate([
            'inspection_id' => 'required|exists:' . $TBL_job . ',id',
        ]);
        $path = parse_url($request->signature, PHP_URL_PATH);
        $lastValue = basename($path);
        $filename_new = $fields['inspection_id'] . '_original_' . rand() . '.png';

        $filetype = 1;
        $S3Path = $this->uploadToS3($fields, $filetype, $filename_new);

        //$path = 'inspeaction_files/' . $fields['inspection_id'] . '/' . $filename_new;

        Storage::disk('s3')->put($S3Path, file_get_contents(public_path('temp/' . $lastValue)));
        // File::copy(public_path('temp/' . $lastValue), public_path('job_files/' . $fields['inspection_id'] . '/' . $filename_new));
        $job_data = array('job_status' => 'submitted', 'signature_image' => $filename_new);
        $admin = Inspection::where('id', $fields['inspection_id'])->update($job_data);
        // echo $request->signature; die;
        return $this->sendResponse('Job Signature Uploaded.', [], 200);
    }


    public function get_job_files(Request $request)
    {
        $fields = $request->validate([
            'inspection_id' => 'required',
        ]);

        $inspection_id = $fields['inspection_id'];

        $callFrom = $request->route()->getPrefix();

        $jobList = Manuallupload::where('inspection_id', '=', $fields['inspection_id'])->orderBy('id', 'desc')->get();
        if ($jobList->count() > 0) {

            $post_type1 = '';
            $post_type2 = '';
            $post_type3 = '';
            $post_type4 = '';

            $jobList_type1 = Manuallupload::where('inspection_id', '=', $fields['inspection_id'])->where('file_type', '=', 1)->get();
            if (count($jobList_type1) > 0) {
                $i = 0;
                foreach ($jobList_type1 as $key => $sop_master) {

                    $original_file_path = "";
                    if (isset($sop_master->original_file_name) && $sop_master->original_file_name != "") {
                        if ((strpos($sop_master->original_file_name, 'no-image.jpg') !== false)) {
                            echo "If";
                            $original_file_path = $sop_master->original_file_name;
                        } else {
                            $S3Path = $this->uploadToS3($fields, $sop_master->file_type, $sop_master->original_file_name);
                            $original_file_path = 'https://' .getAwsBaseUrl() . '/' . $S3Path;
                        }
                    }

                    $newArray = ['name' => $sop_master->sop_label, 'path' => (!empty($sop_master->original_file_name)) ? $original_file_path : 'assets/img/no-image.jpg', 'ai_box_coordinate' => $sop_master->ai_box_coordinate, 'photosheet_selected' => $sop_master->photosheet_selected];
                    if (!empty($callFrom) && $callFrom == 'api/mobile-app') {
                        $newArray = ['name' => $sop_master->sop_label, 'path' => $original_file_path, 'ai_box_coordinate' => $sop_master->ai_box_coordinate];
                        $datatpe1[$i] = $newArray;
                    } else {
                        $datatpe1[$i] = $newArray;
                    }
                    $i++;
                }
                $post_type1 = json_encode($datatpe1, JSON_UNESCAPED_SLASHES);
            }


            $jobList_type2 = Manuallupload::where('inspection_id', '=', $fields['inspection_id'])->where('file_type', '=', 2)->get();
            if (count($jobList_type2) > 0) {
                $i = 0;
                foreach ($jobList_type2 as $sop_master) {
                    $original_file_path = "";
                    if (isset($sop_master->original_file_name) && $sop_master->original_file_name != "") {

                        if ((strpos($sop_master->original_file_name, 'no-image.jpg') !== false)) {
                            $original_file_path = $sop_master->original_file_name;
                        } else {
                            $S3Path = $this->uploadToS3($fields, $sop_master->file_type, $sop_master->original_file_name);
                            $original_file_path = (!empty($sop_master->original_file_name)) ? 'https://' .getAwsBaseUrl() . '/' . $S3Path : 'assets/img/no-image.jpg';
                        }
                    }
                    $newArray = ['name' => $sop_master->sop_label, 'path' => (!empty($sop_master->original_file_name)) ? $original_file_path : 'assets/img/no-image.jpg', 'ai_box_coordinate' => $sop_master->ai_box_coordinate, 'photosheet_selected' => $sop_master->photosheet_selected];
                    $datatpe2[$i] = $newArray;
                    $i++;
                }
                $post_type2 = json_encode($datatpe2, JSON_UNESCAPED_SLASHES);
            }

            $jobList_type3 = Manuallupload::where('inspection_id', '=', $fields['inspection_id'])->where('file_type', '=', 3)->get();
            if (count($jobList_type3) > 0) {
                $i = 0;
                foreach ($jobList_type3 as $sop_master) {

                    $original_file_path = "";
                    if (isset($sop_master->original_file_name) && $sop_master->original_file_name != "") {
                        if ((strpos($sop_master->original_file_name, 'no-image.jpg') !== false)) {
                            $original_file_path = $sop_master->original_file_name;
                        } else {
                            $S3Path = $this->uploadToS3($fields, $sop_master->file_type, $sop_master->original_file_name);
                            $original_file_path = 'https://' .getAwsBaseUrl() . '/' . $S3Path;
                        }
                    }
                    $newArray = ['name' => $sop_master->sop_label, 'path' => (!empty($sop_master->original_file_name)) ? $original_file_path : 'assets/img/no-image.jpg', 'ai_box_coordinate' => $sop_master->ai_box_coordinate, 'photosheet_selected' => $sop_master->photosheet_selected];
                    if (!empty($callFrom) && $callFrom == 'api/mobile-app') {
                        $newArray = [
                            'name' => $sop_master->sop_label,
                            'path' => $original_file_path,
                            'ai_box_coordinate' => $sop_master->ai_box_coordinate
                        ];
                        $datatpe3[$i] = $newArray;
                    } else {
                        $datatpe3[$i] = $newArray;
                    }
                    $i++;
                }

                $post_type3 = json_encode($datatpe3, JSON_UNESCAPED_SLASHES);
            }


            $jobList_type4 = Manuallupload::where('inspection_id', '=', $fields['inspection_id'])->where('file_type', '=', 4)->get();
            if (count($jobList_type4) > 0) {
                $i = 0;
                foreach ($jobList_type4 as $sop_master) {
                    $original_file_path = "";
                    if (isset($sop_master->original_file_name) && $sop_master->original_file_name != "") {

                        if ((strpos($sop_master->original_file_name, 'no-image.jpg') !== false)) {
                            $original_file_path = $sop_master->original_file_name;
                        } else {
                            $S3Path = $this->uploadToS3($fields, $sop_master->file_type, $sop_master->original_file_name);
                            $original_file_path = 'https://' .getAwsBaseUrl() . '/' . $S3Path;
                        }
                    }

                    $newArray = ['name' => $sop_master->sop_label, 'path' => (!empty($sop_master->original_file_name)) ? $original_file_path : 'assets/img/no-image.jpg', 'ai_box_coordinate' => $sop_master->ai_box_coordinate, 'photosheet_selected' => $sop_master->photosheet_selected];
                    if (!empty($callFrom) && $callFrom == 'api/mobile-app') {
                        $datatpe4[$i] = $newArray;
                    } else {
                        $datatpe4[$i] = $newArray;
                    }

                    $i++;
                }
                $post_type4 = json_encode($datatpe4, JSON_UNESCAPED_SLASHES);
            }

            $job_data = Inspection::where('id', $fields['inspection_id'])->first();

            $file_type = 5;

            $S3Path = $this->uploadToS3($fields, $file_type, $sop_master->signature_image);
            $original_file_path_sign = (!empty($sop_master->signature_image)) ? 'https://' .getAwsBaseUrl() . '/' . $S3Path . '/' . $sop_master->signature_image : 'assets/img/no-image.jpg';
            $S3Path = $this->uploadToS3($fields, $file_type, $job_data->video_file);
            $videourl = "";
            if ($job_data->video_file) {
                $videourl = 'https://' .getAwsBaseUrl() . '/' . $S3Path;
            }

            if (!$job_data->job_remark) {
                $job_data->job_remark = "";
            }

            $jobdata['values'] = [
                'id' => $job_data->id,
                'vehicle_reg_no' => $job_data->vehicle_reg_no,
                'insured_name' => $job_data->insured_name,
                'place_survey' => $job_data->place_survey,
                'contact_no' => $job_data->contact_no,
                'upload_type' => $job_data->upload_type,
                'video_file' => $videourl,
                'job_remark' => $job_data->job_remark,
                'job_status' => $job_data->job_status,
                'signature' => $original_file_path_sign,
                'vehichle_images_field_label' => $post_type1,
                'document_image_field_label' => $post_type2,
                'custom_vehichle_images_field_label' => $post_type3,
                'custom_document_image_field_label' => $post_type4,
                'can_record_video' => can_record_video($job_data->sop_id),
            ];


            return $this->sendResponse($jobdata, [], 200);
        } else {

            $job_data = Inspection::where('id', $fields['inspection_id'])->first();
            $sop_id = $job_data->sop_id;
            //echo $sop_id; die;
            $data_sop = SopMaster::where('id', $sop_id)->get();
            if (count($data_sop) == 0) {
                return $this->sendResponse('error', "invalid job id", 200);
            } else {
                $i = 0;
                foreach ($data_sop as $sop_master) {
                    $vehicle_data = $sop_master->vehichle_images_field_label;
                    $correctedInput = preg_replace('/(\w+):/', '"$1":', $vehicle_data);
                    $json_array_str = str_replace('\\', '', $correctedInput);

                    // Parse the JSON array
                    $json_array = json_decode($json_array_str, true);

                    // Check if decoding was successful
                    if ($json_array === null) {
                        //  echo "Error decoding JSON\n";
                    } else {
                        $i = 0;
                        // Loop through the objects in the array
                        foreach ($json_array as $item) {
                            $newArray = ['name' => $item["form_field_label"], 'path' => 'assets/img/no-image.jpg'];
                            $form_field_label = $item["form_field_label"];

                            $data1[$i] = $newArray;
                            // $data1[$i] = [
                            //   $form_field_label => 'assets/img/no-image.jpg'
                            // ];
                            $i++;
                        }
                    }

                    $vehicle_imagedata = json_encode($data1, JSON_UNESCAPED_SLASHES);


                    $data2 = [];

                    $vehicle_data1 = $sop_master->document_image_field_label;
                    $correctedInput1 = preg_replace('/(\w+):/', '"$1":', $vehicle_data1);
                    $json_array_str1 = str_replace('\\', '', $correctedInput1);
                    // $json_array_str = '[{"form_field_label": "awdawd"}, {"form_field_label": "awdawdwad"}]';
                    //echo $json_array_str1; die;
                    // Parse the JSON array
                    $json_array1 = json_decode($json_array_str1, true);
                    // Check if decoding was successful
                    if ($json_array1 === null) {
                        // echo "Error decoding JSON\n";
                    } else {
                        $i = 0;
                        $data2 = [];
                        // Loop through the objects in the array
                        foreach ($json_array1 as $item) {
                            // print_r($item); die;
                            $newArray = ['name' => $item["form_document_label"], 'path' => 'assets/img/no-image.jpg'];
                            // $form_field_label = $item["form_document_label"];

                            $data2[$i] = $newArray;
                            // $data2[$i] = [
                            //   $form_field_label => 'assets/img/no-image.jpg'
                            // ];
                            $i++;
                        }
                    }


                    $vehicle_docdata = json_encode($data2, JSON_UNESCAPED_SLASHES);

                    $job_data = Inspection::where('id', $fields['inspection_id'])->first();

                    $S3Path = $this->uploadToS3($fields, $sop_master->file_type, $job_data->video_file);
                    $videourl = "";
                    if ($job_data->video_file) {
                        $videourl = 'https://' .getAwsBaseUrl() . '/' . $S3Path;
                        // $videourl = $url = url('/') . '/public/job_files/' . $inspection_id . '/' . $job_data->video_file;
                    }
                    $data['values'] = [
                        'id' => $sop_master->id,
                        'sop_name' => $sop_master->sop_name,
                        //'branch_id' => $sop_master->branch_id,
                        //'admin_id' => $sop_master->admin_id,
                        //'can_record_video' => $sop_master->can_record_video,
                        'vehicle_reg_no' => $job_data->vehicle_reg_no,
                        'insured_name' => $job_data->insured_name,
                        'place_survey' => $job_data->place_survey,
                        'contact_no' => $job_data->contact_no,
                        'upload_type' => $job_data->upload_type,
                        'video_file' => $videourl,
                        'job_remark' => $job_data->job_remark,
                        'job_status' => $job_data->job_status,
                        'vehichle_images_field_label' => $vehicle_imagedata,
                        'document_image_field_label' => $vehicle_docdata,
                        'can_record_video' => can_record_video($job_data->sop_id),
                        'is_location_allowed' => $sop_master->is_location_allowed,
                        'created_at' => $sop_master->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => ($sop_master->updated_at) ? $sop_master->updated_at->format('Y-m-d H:i:s') : "",
                    ];
                    $i++;
                }

                return $this->sendResponse($data, 'sop fetched', 200);
            }
            //return $this->sendResponse('Job File Uploaded.', ['result'=>$data],200);
        }

        //print_r($jobList);

    }


    public function sop_branchid(Request $request, $id)
    {

        $allAdmins = SopMaster::where('admin_branch_id', $id)->get();
        if (count($allAdmins) == 0) {
            $data = [];
            return $this->sendResponse($data, 'Data Not Available Against Branch Id', 200);
        } else {
            $i = 0;
            foreach ($allAdmins as $sop_master) {
                $branch_name = getBranchbybranchid($sop_master->branchadmin_branch_id_id);

                $data[$i] = [
                    'id' => $sop_master->id,
                    'sop_name' => $sop_master->sop_name,
                    'branch_id' => $sop_master->admin_branch_id,
                    'branch_name' => $branch_name,
                    'admin_id' => $sop_master->admin_id,
                    'can_record_video' => $sop_master->can_record_video,
                    'vehichle_images_field_label' => json_decode($sop_master->vehichle_images_field_label),
                    'document_image_field_label' => json_decode($sop_master->document_image_field_label),
                    'created_at' => $sop_master->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => ($sop_master->updated_at) ? $sop_master->updated_at->format('Y-m-d H:i:s') : "",
                ];
                $i++;
            }

            return $this->sendResponse($data, 'sop fetched', 200);
        }
        //return $this->sendResponse('Sop List Fetched.', ['result'=>$data],200);
    }


    public function workshop_branchid(Request $request, $id)
    {
//        $admin_id = Admin_ms::find($id);
        $Workshop = Workshop::where(['admin_branch_id' => $id])->whereNull('deleted_at')->get();
        if (count($Workshop) == 0) {
            $data = [];
            return $this->sendResponse($data, 'Data Not Available Against Branch Id', 200);
        } else {
            foreach ($Workshop as $i => $sop_master) {
                $branch_name = getBranchbybranchid($sop_master->admin_branch_id);
                $data[$i] = [
                    'id' => $sop_master->id,
                    'workshop_name' => $sop_master->workshop_name,
                    'branch_name' => $branch_name,
                    'address' => $sop_master->address,
                    'gst_no' => $sop_master->gst_no,
                    'contact_detail' => $sop_master->contact_detail,
                    'workshop_type' => $sop_master->is_local_workshop,
                    'admin_id' => $sop_master->admin_id,
                    'created_at' => $sop_master->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => ($sop_master->updated_at) ? $sop_master->updated_at->format('Y-m-d H:i:s') : "",
                ];
            }
            return $this->sendResponse($data, 'Workshop Fetched', 200);
        }
    }


    public function client_branchid(Request $request, $id)
    {

        $clients = Clientms::where('admin_branch_id', $id)->get();
        if (count($clients) == 0) {
            $data = [];
            return $this->sendResponse($data, 'Data Not Available Against Branch Id', 200);
        } else {

            foreach ($clients as $i => $sop_master) {
                $branch_name = getBranchbybranchid($sop_master->admin_branch_id);

                $data[$i] = [
                    'id' => $sop_master->id,
                    'client_name' => $sop_master->client_name,
                    'client_address' => $sop_master->client_address,
                    'contact_details' => $sop_master->contact_details,
                    'status' => $sop_master->status,
                    'branch_name' => $branch_name,
                ];
            }


            return $this->sendResponse($data, 'Client Fetched', 200);
            //return $this->sendResponse('Workshop List Fetched.', ['result'=>$data],200);
        }
    }


    public function employee_branchid(Request $request, $id)
    {

        $employee = Employeems::where('admin_branch_id', $id)->get();

        if (count($employee) == 0) {
            $data = [];

            return $this->sendResponse($data, 'Data Not Available Against Branch Id', 200);

            //return $this->sendResponse('',"invalid branch id",201);
        } else {

            $i = 0;
            foreach ($employee as $emp_details) {
                $branch_name = getBranchbybranchid($emp_details->admin_branch_id);
                $data[$i] = [
                    'id' => $emp_details->id,
                    'user_id' => $emp_details->user_id,
                    'branch_name' => $branch_name,
                    'type' => $emp_details->type,
                    'password' => $emp_details->password,
                    'name' => $emp_details->name,
                    'email' => $emp_details->email,
                    'mobile_no' => $emp_details->mobile_no,
                    'address' => $emp_details->address,
                    'amount_per_job' => $emp_details->amount_per_job,
                    'firebase_token' => $emp_details->firebase_token,
                    'device_id' => $emp_details->device_id,
                    'status' => $emp_details->status,
                    'is_set_password' => $emp_details->is_set_password,
                    'is_guest_employee' => $emp_details->is_guest_employee,
                    'job_assigned_to_guest' => $emp_details->job_assigned_to_guest,
                    'created_at' => $emp_details->created_at,
                    'updated_at' => $emp_details->updated_at,
                    'last_login_date' => $emp_details->last_login_date,
                    'otp_sent' => $emp_details->otp_sent,
                    'expiry_time' => $emp_details->id,
                    'parent_admin_id' => $emp_details->expiry_time,
                    'created_by' => $emp_details->created_by,
                    'modified_by' => $emp_details->modified_by,
                    'branch_id' => $emp_details->admin_branch_id,
                ];
                $i++;
            }

            return $this->sendResponse($data, 'Employee List Fetched', 200);
        }
    }


    public function admin_branchid(Request $request, $id)
    {

        $data = Admin_ms::where('admin_branch_id', $id)->get();

        if (count($data) == 0) {
            $data = [];

            return $this->sendResponse($data, 'Data Not Available Against Branch Id', 200);

            //return $this->sendResponse('',"invalid branch id",201);
        } else
            return $this->sendResponse('Admin List Fetched.', ['result' => $data], 200);
    }


    public function subadmin_branchid(Request $request, $id)
    {

        $subadmin = Admin_ms::where('admin_branch_id', $id)->where('role', 'sub_admin')->get();

        if (count($subadmin) == 0) {
            $data = [];

            return $this->sendResponse($data, 'Data Not Available Against Branch Id', 200);

            //return $this->sendResponse('',"invalid branch id",201);
        } else {

            $i = 0;
            foreach ($subadmin as $emp_details) {

                $branch_name = getBranchbybranchid($emp_details->admin_branch_id);


                $data[$i] = [
                    'id' => $emp_details->id,
                    'user_id' => $emp_details->user_id,
                    'branch_name' => $branch_name,
                    'type' => $emp_details->type,
                    'password' => $emp_details->password,
                    'name' => $emp_details->name,
                    'email' => $emp_details->email,
                    'mobile_no' => $emp_details->mobile_no,
                    'address' => $emp_details->address,
                    'amount_per_job' => $emp_details->amount_per_job,
                    'firebase_token' => $emp_details->firebase_token,
                    'device_id' => $emp_details->device_id,
                    'status' => $emp_details->status,
                    'is_set_password' => $emp_details->is_set_password,
                    'is_guest_employee' => $emp_details->is_guest_employee,
                    'job_assigned_to_guest' => $emp_details->job_assigned_to_guest,
                    'created_at' => $emp_details->created_at,
                    'updated_at' => $emp_details->updated_at,
                    'last_login_date' => $emp_details->last_login_date,
                    'otp_sent' => $emp_details->otp_sent,
                    'expiry_time' => $emp_details->id,
                    'parent_admin_id' => $emp_details->expiry_time,
                    'created_by' => $emp_details->created_by,
                    'modified_by' => $emp_details->modified_by,
                    'branch_id' => $emp_details->admin_branch_id,
                    'role' => $emp_details->role,
                ];
                $i++;
            }

            return $this->sendResponse($data, 'Sub Admin List Fetched', 200);
        }
    }


    public function store_link(Request $request)
    {
        // var_dump($request->all());
        $response = [];

        $fields = $request->validate([
            'vehichle_images_field_post' => '',
            'document_images_field_post' => '',
            'custom_vehichle_images_field_post' => '',
            'custom_document_images_field_post' => '',
            'inspection_id' => 'required',
            'sop_id' => 'required',
        ]);

        if (isset($request->video_file)) {


            $file = $request->file('video_file');
            $path = 'inspeaction_files/' . $fields['inspection_id'] . '/';
            $result = UploadFilesToS3('s3', $path, $file);

            $filename_new = $file->getClientOriginalName();
            $job_data = array('video_file' => $filename_new, 'job_remark' => $request->job_remark);
            $admin = Inspection::where('id', $fields['inspection_id'])->update($job_data);
        }


        $job_data = array('job_remark' => $request->job_remark);

        $admin = Inspection::where('id', $fields['inspection_id'])->update($job_data);

        //job_id delete Manuallupload
        $ManualFileUpload = Manuallupload::where('inspection_id', $fields['inspection_id'])->first();
        //creating new folder
        $path = public_path() . '/job_files/' . $fields['inspection_id'];


        // File::makeDirectory($path, $mode = 0777, true, true);
        //creating new folder end

        if (isset($fields['vehichle_images_field_post'])) {

            $data = $fields['vehichle_images_field_post'];

            if ($this->json_validator($data)) {
                $data = json_decode($data, true);
            }

            foreach ($data as $da) {
                // $vehicle_image_data=json_decode($da);
                $vehicle_image_data = $da;
                foreach ($vehicle_image_data as $key => $value) {


                    $logged_in_id = 'Link Uploaded';
                    $filename_new = $fields['inspection_id'] . '_original_' . rand() . '.png';

                    if ((strpos($value, 'temp_') !== false) || (strpos($value, 'no-image.jpg') !== false)) {

                        if ((strpos($value, 'no-image.jpg') !== false)) {
                            $filename_new = $value;
                        }

                        $vahicle_data = array(
                            'inspection_id' => $fields['inspection_id'],
                            'sop_id' => $fields['sop_id'],
                            'sop_label' => $key,
                            'original_file_name' => $filename_new,
                            'uploaded_by' => $logged_in_id,
                            'file_type' => 1,
                        );

                        $file_type = $vahicle_data['file_type'];
                        $path = parse_url($value, PHP_URL_PATH);
                        $lastValue = basename($path);

                        if ((strpos($value, 'temp_') !== false)) {

                            //$path = 'inspeaction_files/' . $fields['inspection_id'] . '/' . $filename_new;

                            $S3Path = $this->uploadToS3($fields, $file_type, $filename_new);

                            $job = Inspection::where('id', $fields['inspection_id'])->first();
                            $createdAt = Carbon::parse($job->created_at);
                            $admin_id = $job->admin_id;
                            $date = $createdAt->format('d-m-Y');
                            $month = $createdAt->format('F');
                            $year = $createdAt->format('Y');

                            $adminName = Admin_ms::where('id', $admin_id)->first()->name;
                            $branchName = Branch::where('admin_id', $admin_id)->first()->admin_branch_name;

                            $vehRegisNo = $job->vehicle_reg_no;

                            $storagePath3 = "";
                            if ($filetype == 1 || $filetype == 3) {
                                $vehicleImage = "Vehicle_Images";
                                $storagePath3 =getAwsBaseUrl() . $adminName . '/' . $branchName . '/' . $year . '/' . $month . '/' . $date . '/' . $vehRegisNo . '/' . $vehicleImage;
                            }

                            if (!empty($ManualFileUpload->original_file_name) && Storage::disk('s3')->exists($storagePath3 . '/' . $ManualFileUpload->original_file_name)) {
                                Storage::disk('s3')->delete($storagePath3 . '/' . $ManualFileUpload->original_file_name);
                                Manuallupload::where('inspection_id', $fields['inspection_id'])->delete();
                            }


                            Storage::disk('s3')->put($S3Path, file_get_contents(public_path('temp/' . $lastValue)));


                            // $vahicle_data['original_file_name'] =  $result['store_image_name'];
                            // File::copy(public_path('temp/' . $lastValue), public_path('job_files/' . $fields['inspection_id'] . '/' . $filename_new));
                        }
                        $client = Manuallupload::create($vahicle_data);
                        $response['vehichle_images_field_post'][] = $client;
                    }
                }
            }
        }


        //uploading vehicle image end

        //uploading document image
        if (isset($fields['document_images_field_post'])) {

            $data = $fields['document_images_field_post'];

            if ($this->json_validator($data)) {
                $data = json_decode($data, true);
            }


            foreach ($data as $da) {


                $document_image_data = $da;

                foreach ($document_image_data as $key => $value) {

                    $logged_in_id = 'Link Uploaded';

                    $filename_new = $fields['inspection_id'] . '_original_' . rand() . '.png';


                    if ((strpos($value, 'temp_') !== false) || (strpos($value, 'no-image.jpg') !== false)) {

                        if ((strpos($value, 'no-image.jpg') !== false)) {
                            $filename_new = $value;
                        }
                        $vahicle_data = array(
                            'inspection_id' => $fields['inspection_id'],
                            'sop_id' => $fields['sop_id'],
                            'sop_label' => $key,
                            'original_file_name' => $filename_new,
                            'uploaded_by' => $logged_in_id,
                            'file_type' => 2,
                        );

                        $path = parse_url($value, PHP_URL_PATH);
                        $lastValue = basename($path);
                        $file_type = $vahicle_data['file_type'];
                        //$filename_new=time();
                        if ((strpos($value, 'temp_') !== false)) {

                            //$path = 'inspeaction_files/' . $fields['inspection_id'] . '/' . $filename_new;

                            $path = $this->uploadToS3($fields, $file_type, $filename_new);

                            Storage::disk('s3')->put($path, file_get_contents(public_path('temp/' . $lastValue)));

                            // File::copy(public_path('temp/' . $lastValue), public_path('job_files/' . $fields['inspection_id'] . '/' . $filename_new));
                        }

                        $client = Manuallupload::create($vahicle_data);
                        $response['document_images_field_post'][] = $client;
                    }
                }
            }
        }

        //uploading v--ehicle document end


        //uploading vehicle image custom

        if (isset($fields['custom_vehichle_images_field_post'])) {

            $data = $fields['custom_vehichle_images_field_post'];
            if ($this->json_validator($data)) {
                $data = json_decode($data, true);
            }

            foreach ($data as $da) {

                $custom_images_field_post = $da;

                foreach ($custom_images_field_post as $key => $value) {

                    $logged_in_id = 'Link Uploaded';

                    $filename_new = $fields['inspection_id'] . '_original_' . rand() . '.png';

                    if ((strpos($value, 'temp_') !== false) || (strpos($value, 'no-image.jpg') !== false)) {

                        if ((strpos($value, 'no-image.jpg') !== false)) {
                            $filename_new = $value;
                        }

                        $vahicle_data = array(
                            'inspection_id' => $fields['inspection_id'],
                            'sop_id' => $fields['sop_id'],
                            'sop_label' => $key,
                            'original_file_name' => $filename_new,
                            'uploaded_by' => $logged_in_id,
                            'file_type' => 3,
                        );

                        $file_type3 = $vahicle_data['file_type'];
                        $path = parse_url($value, PHP_URL_PATH);
                        $lastValue = basename($path);

                        //$filename_new=time();
                        if ((strpos($value, 'temp_') !== false)) {

                            $path = $this->uploadToS3($fields, $file_type3, $filename_new);

                            //$path = 'inspeaction_files/' . $fields['inspection_id'] . '/' . $filename_new;
                            Storage::disk('s3')->put($path, file_get_contents(public_path('temp/' . $lastValue)));
                            // File::copy(public_path('temp/' . $lastValue), public_path('job_files/' . $fields['inspection_id'] . '/' . $filename_new));


                            // File::copy(public_path('temp/' . $lastValue), public_path('job_files/' . $fields['inspection_id'] . '/' . $filename_new));
                        }

                        $client = Manuallupload::create($vahicle_data);
                        $response['custom_vehichle_images_field_post'][] = $client;
                    }
                }
            }
        }

        //uploading  vehicle image custom end


        //uploading  vehicle document custom

        if (isset($fields['custom_document_images_field_post'])) {

            $data = $fields['custom_document_images_field_post'];
            if ($this->json_validator($data)) {
                $data = json_decode($data, true);
            }

            foreach ($data as $da) {

                $custom_document_images_field_post = $da;

                foreach ($custom_document_images_field_post as $key => $value) {

                    $logged_in_id = 'Link Uploaded';

                    $filename_new = $fields['inspection_id'] . '_original_' . rand() . '.png';
                    if ((strpos($value, 'temp_') !== false) || (strpos($value, 'no-image.jpg') !== false)) {

                        if ((strpos($value, 'no-image.jpg') !== false)) {
                            $filename_new = $value;
                        }
                        $vahicle_data = array(
                            'inspection_id' => $fields['inspection_id'],
                            'sop_id' => $fields['sop_id'],
                            'sop_label' => $key,
                            'original_file_name' => $filename_new,
                            'uploaded_by' => $logged_in_id,
                            'file_type' => 4,
                        );


                        $path = parse_url($value, PHP_URL_PATH);
                        $lastValue = basename($path);
                        $file_type4 = $vahicle_data['file_type'];
                        //$filename_new=time();
                        if ((strpos($value, 'temp_') !== false)) {

                            $path = $this->uploadToS3($fields, $file_type4, $filename_new);
                            //$path = 'inspeaction_files/' . $fields['inspection_id'] . '/' . $filename_new;
                            Storage::disk('s3')->put($path, file_get_contents(public_path('temp/' . $lastValue)));

                            // File::copy(public_path('temp/' . $lastValue), public_path('job_files/' . $fields['inspection_id'] . '/' . $filename_new));
                        }

                        $client = Manuallupload::create($vahicle_data);
                        $response['custom_document_images_field_post'][] = $client;
                    }
                }
            }
        }


        // if (isset($request->video_file)) {

        //   $path_v = parse_url($request->video_file, PHP_URL_PATH);
        //   $lastValue_v = basename($path_v);

        //   $filename_new = $fields['inspection_id'] . '_original_' . rand() . '.mp4';
        //   if ((strpos($lastValue_v, 'temp_') !== false)) {

        //     $path = 'inspeaction_files/' . $fields['inspection_id'] . '/' . $filename_new;
        //     Storage::disk('s3')->put($path, file_get_contents(public_path('temp/' . $lastValue)));
        //     //  File::copy(public_path('temp/' . $lastValue_v), public_path('job_files/' . $fields['inspection_id'] . '/' . $filename_new));
        //   }

        //   $job_data = array('video_file' => $filename_new, 'job_remark' => $request->job_remark);

        //   $admin = Inspection::where('id', $fields['inspection_id'])->update($job_data);
        // }

        $job_data = array('job_status' => 'submitted');
        $admin = Inspection::where('id', $fields['inspection_id'])->update($job_data);

        $linkdata = array(
            'status' => 1,
            'submitted_date' => date('Y-m-d H:i:s')
        );

        $admin = Joblink::where('inspection_id', $fields['inspection_id'])->update($linkdata);

        $response['jobLine_data'][] = $admin;

        return $this->sendResponse($response, 'Job File Uploaded.', 200);
    }


    public function getimage(Request $req)
    {
        $path = $req->path;

        $image = file_get_contents($path); // Retrieve the image data
        $base64 = base64_encode($image); // Convert the image data to base64 encoding

        $data['baseimg'] = $base64;
        return $this->sendResponse($data, 'fetched successfully', 200);
    }
}
