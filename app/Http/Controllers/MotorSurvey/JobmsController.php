<?php

namespace App\Http\Controllers\MotorSurvey;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\jobms\JobmsResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Inspection;
use App\Models\Admin_ms;
use App\Models\Joblink;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use App\Models\SopMaster;
use App\Models\Clientms;
use App\Models\InspectionLinks;
use PDF;

class JobmsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = ($request->exists('all') || (isset($request->page) && $request->page == 'all')) ? true : false;
        $created_by = Auth::user()->id;
        $search_keyword = (isset($request->search_keyword) && !empty($request->search_keyword)) ? $request->search_keyword : null;
        $search_type = (isset($request->search_type) && !empty($request->search_type)) ? $request->search_type : null;
        $from_date = (isset($request->from_date) && !empty($request->from_date)) ? date('Y-m-d', strtotime($request->from_date)) : null;
        $to_date = (isset($request->to_date) && !empty($request->to_date)) ? date('Y-m-d', strtotime($request->to_date)) : null;

        $job_status = $request->status;
        $where = ['job_status' => $job_status];

        $admin_branch_id = [];
        if(!empty($search_type) && $search_type == 'office_branch_wise' && !empty($search_keyword)) {
            $admin_branch_id = [$search_keyword];
        } else {
            if (Auth::user()->role == 'admin') {
                $admin_branch_id = Auth::user()->branchAdminChildren->pluck('id')->toArray();
            } else if (Auth::user()->role == 'branch_admin') {
                $single_admin_branch_id = getBranchidbyAdminid($created_by);
                $admin_branch_id = [$single_admin_branch_id];
            } else {
                $single_admin_branch_id = getBranchidbyAdminid($created_by);
                $admin_branch_id = [$single_admin_branch_id];
            }
        }
        $jobms = Inspection::with('get_workshop_branch__details', 'getClient')
            ->where(function($q) use($admin_branch_id) {
                $q->WhereIn('admin_branch_id', $admin_branch_id);
            })
            ->where($where)
            ->where(function($sub_query) use($from_date, $to_date) {
                if(!empty($from_date) && !empty($to_date)) {
                    $sub_query->whereBetween('created_at', [$from_date, $to_date]);
                }
            })
            ->where(function($sub_query1) use($search_type, $search_keyword) {
                if(!empty($search_type) && !empty($search_keyword)) {
                    switch ($search_type) {
                        case 'report_no':
                            $sub_query1->where('Reference_No', 'LIKE', "%$search_keyword%");
                            break;
                        case 'claim_no':
                            $sub_query1->where('claim_no', 'LIKE', "%$search_keyword%");
                            break;
                        case 'policy_no':
                            $sub_query1->where('policy_no', 'LIKE', "%$search_keyword%");
                            break;
                        case 'registration_no':
                            $sub_query1->where('vehicle_reg_no', 'LIKE', "%$search_keyword%");
                            break;
                        case 'insured_name':
                            $sub_query1->where('insured_name', 'LIKE', "%$search_keyword%");
                            break;
                        case 'Job_Route_To':
                            $sub_query1->where('Job_Route_To', $search_keyword);
                            break;
                        default: break;
                    }
                }
            })
            ->orderBy('id', 'desc');
        if(!empty($search_type) && $search_type == 'workshop_name' && $search_keyword) {
            $jobms = $jobms->whereRelation('get_workshop_branch__details', 'workshop_branch_name', 'LIKE', "%$search_keyword%");
        }
        if(!empty($search_type) && $search_type == 'insurer_name' && $search_keyword) {
            $jobms = $jobms->whereRelation('getClient', 'client_name', 'LIKE', "%$search_keyword%");
        }

        $allAdmins1['values'] = !empty($page) ? JobmsResource::collection($jobms->get()) : JobmsResource::collection($jobms->paginate(10));
        if (empty($page)) {
            $alldata = $jobms->paginate(10);
            $allAdmins1['pagination'] = [
                'total' => $alldata->total(),
                'count' => $alldata->count(),
                'per_page' => $alldata->perPage(),
                'current_page' => $alldata->currentPage(),
                'total_pages' => $alldata->lastPage(),
            ];
        }

        return $this->sendResponse($allAdmins1, 'Jobs fetched.' . Auth::user()->id . Auth::user()->role);
    }

    /* respone for mobile app */

    public function jobmsApp(Request $request)
    {
        $TBL_ADMIN = "tbl_ms_admin";
        $created_by = Auth::user()->id;
        $job_status = $request->status;
        $role = $request->role;

        if ($job_status == "submitted" || $job_status == "approved") {
            if ($role == 'admin') {
                $wh2 = ['admin_id' => $created_by, 'job_status' => $job_status];
                $jobms = Inspection::with('get_workshop_details', 'get_workshop_branch__details')->where($wh2)->orderBy('id', 'desc');
            } else if ($role == 'sub_admin' || $role == 'branch_admin') {

                $main_admin_id = getmainAdminIdIfAdminLoggedIn($created_by);
                $admin_branch_id = getBranchidbyAdminid($created_by);
                $wh2 = ['admin_id' => $main_admin_id, 'branch_name' => $admin_branch_id, 'job_status' => $job_status];
                $jobms = Inspection::with('get_workshop_details', 'get_workshop_branch__details')->where($wh2)->orderBy('id', 'desc');
            } else if ($role == 'Branch Contact') {
                $wh2 = ['jobassignedto_workshopEmpid' => $created_by, 'job_status' => $job_status];
                $jobms = Inspection::with('get_workshop_details', 'get_workshop_branch__details')->where($wh2)->orderBy('id', 'desc');
            } else {
                $wh2 = ['jobjssignedto_surveyorEmpId' => $created_by, 'job_status' => $job_status];
                $jobms = Inspection::with('get_workshop_details', 'get_workshop_branch__details')->where($wh2)->orderBy('id', 'desc');
            }
        } else {

            if ($role == 'Branch Contact') {
                $wh2 = ['jobassignedto_workshopEmpid' => $created_by, 'job_status' => $job_status];
                $jobms = Inspection::with('get_workshop_details', 'get_workshop_branch__details')->where($wh2)->orderBy('id', 'desc');
            } else {
                $wh2 = ['jobjssignedto_surveyorEmpId' => $created_by, 'job_status' => $job_status];
                $jobms = Inspection::with('get_workshop_details', 'get_workshop_branch__details')->where($wh2)->orderBy('id', 'desc');
            }
        }

        $allAdmins1['values'] = $request->exists('all') ? JobmsResource::collection($jobms->get()) : JobmsResource::collection($jobms->paginate(10));
        if (!$request->exists('all')) {
            $alldata = $jobms->paginate(10);
            $allAdmins1['pagination'] =
                [
                    'total' => $alldata->total(),
                    'count' => $alldata->count(),
                    'per_page' => $alldata->perPage(),
                    'current_page' => $alldata->currentPage(),
                    'total_pages' => $alldata->lastPage(),
                ];
        }
        return $this->sendResponse($allAdmins1, 'Jobs fetched.');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $TBL_ADMIN = "tbl_ms_admin";
        $created_by = Auth::user()->id;

        $main_admin_id = getmainAdminIdIfAdminLoggedIn($created_by);


        $arrFieldsToBeValidate = [
            'claim_type' => 'required|numeric|in:1,2',
            'Job_Route_To' => 'required|numeric|in:1,2,3,4',
            'vehicle_reg_no' => 'required|string',
            'insured_name' => 'required|string',
            'place_survey' => 'required|string',
            'workshop_id' => 'required|numeric',
            'workshop_branch_id' => 'required|numeric',
            'contact_person' => '',
            'contact_no' => '',
            'client_id' => 'required|numeric',
            'client_branch_id' => 'required|numeric',
            'date_of_appointment' => 'required|date_format:Y-m-d H:i',
            'sop_id' => 'required',
            //  'admin_branch_id' => 'required|numeric',
            'admin_branch_id' => 'required|numeric',
            'created_by' => 'required|numeric',
        ];

        $fields = $request->validate($arrFieldsToBeValidate);


        if ($fields['Job_Route_To'] == 3) {
            $job_status = "submitted";
            $assigned_by = $created_by;
            $assigned_on = date('Y-m-d h:i:s');
        } else if ($fields['Job_Route_To'] == 1) {
            $job_status = "created";
            $assigned_by = null;
            $assigned_on = null;
        } else {
            $job_status = "pending";
            $assigned_by = $created_by;
            $assigned_on = date('Y-m-d h:i:s');
        }

        if ($request->upload_type) {
            $upload_type = $request->upload_type;
        } else {
            $upload_type = '0';
        }

        $user = Auth::user();

        if ($user->role == 'sub_admin') {
            // Retrieve the parent (branch_Admin) information
            $branchAdmin = $user->parent;
            if ($branchAdmin) {
                // Retrieve the admin_id of the branch_admin
                $adminId = $branchAdmin = $user->parent;

                if ($adminId->role == "branch_admin") {
                    $adminId = $adminId->parent_id;
                } else {
                    $adminId = $adminId->id;
                }

                // Now $adminId contains the admin_id
                // Use $adminId as needed
                //dd($adminId);
            } else {
                $adminId = $user->parent_id;
                // Handle the case where branch_Admin is not found
            }
        } else if ($user->role == 'branch_admin') {
            $adminId = $user->parent_id;
        } else {
            $adminId = $user->id;
        }

        //motarsurevey 2 : pending
        $arrFieldsToBeAdd = [
            'claim_type' => $fields['claim_type'],
            'vehicle_reg_no' => $fields['vehicle_reg_no'],
            'Job_Route_To' => $fields['Job_Route_To'],
            'insured_name' => $fields['insured_name'],
            'place_survey' => $fields['place_survey'],
            'workshop_id' => $fields['workshop_id'],
            "workshop_branch_id" => $fields['workshop_branch_id'],
            'contact_person' => $fields['contact_person'],
            'contact_no' => $fields['contact_no'],
            'client_id' => $fields['client_id'],
            'client_branch_id' => $fields['client_branch_id'],
            'date_of_appointment' => $fields['date_of_appointment'],
            'sop_id' => $fields['sop_id'],
            'branch_name' => $fields['admin_branch_id'],
            'admin_branch_id' => $fields['admin_branch_id'],
            'admin_id' => $adminId,
            'created_by' => Auth::user()->id,
            'job_status' => $job_status,
            'upload_type' => $upload_type,
            'assigned_by' => $assigned_by,
            'assigned_on' => $assigned_on,
        ];


        $jobms = Inspection::create($arrFieldsToBeAdd);


        return $this->sendResponse(new JobmsResource($jobms), "success", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //die("===========");
        $created_by = Auth::user()->id;

        $main_admin_id = getmainAdminIdIfAdminLoggedIn($created_by);

        //$wh=['id'=>$id,'admin_id'=>$main_admin_id];
        $wh = ['id' => $id];

        $jobms = Inspection::where($wh)->first();
        // if (Auth::user()->role == 'admin') {
        //     //  $wh2=['id'=>$id,'admin_id'=>$created_by];
        //     $wh2 = ['id' => $id, 'admin_id' => $created_by];
        //     $jobms = Inspection::where($wh)->first();
        // } else {

        //     $jobms = Inspection::where($wh)->first();
        // }
        if (is_null($jobms)) {
            return $this->sendError('Job does not exist.');
        }
        return $this->sendResponse(new JobmsResource($jobms), 'job fetched.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $TBL_ADMIN = "tbl_ms_admin";

        $update_by = Auth::user()->id;
        /*
            if($request->user()->tokenCan('type:employee')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
            }else if($request->user()->tokenCan('type:admin')) {
            $loggedInAdmin = Admin_ms::where('id','=', $update_by)->where('status','!=', '2')->first();
            if (is_null($loggedInAdmin)) {
            return $this->sendError('Admin does not exist.');
            }
            if ($loggedInAdmin->parent_id != 0){
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
            }
        }*/

        $jobms = Inspection::where('id', $id)->first();
        if (is_null($jobms)) {
            return $this->sendError('Job does not exist.');
        }

        $arrFieldsToBeValidate = [
            'claim_type' => 'required|numeric|in:1,2',
            'vehicle_reg_no' => 'required|string',
            'Job_Route_To' => 'required|numeric|in:1,2,3',
            'insured_name' => 'required|string',
            'place_survey' => 'required|string',
            'sop_id' => 'required',
            'contact_person' => '',
            'workshop_id' => 'required|numeric',
            'workshop_branch_id' => 'required|numeric',
            'contact_no' => '',
            'client_id' => 'required|numeric',
            'client_branch_id' => 'required|numeric',
            'date_of_appointment' => 'required|date_format:Y-m-d H:i',

            // 'admin_branch_id' => 'required|numeric',
            'admin_branch_id' => 'required|string',
            'created_by' => 'required|numeric',
        ];

        $fields = $request->validate($arrFieldsToBeValidate);


        /*Mobile App
-----> M S L --> status pending
-----> Manual Uploal --> status submitted

Motar S L 
---> App status created*/


        //1 mobile , 2 motar survey link , 3 manuall upload


        $jobcurrent_route = $jobms->Job_Route_To;
        $jobcurrent_status = $jobms->job_status;

        $job_status = $jobcurrent_status;


        //jobassignedto_workshopEmpid,jobjssignedto_surveyorEmpId

        if ($jobcurrent_route == 1) {

            if ($fields['Job_Route_To'] == 2) {
                $job_status = "pending";
                $jobms->jobassignedto_workshopEmpid = null;
                $jobms->jobjssignedto_surveyorEmpId = null;
                $jobms->assigned_on = date("Y-m-d H:i:s");
                $jobms->assigned_by = $update_by;
            }


            if ($fields['Job_Route_To'] == 3) {
                $job_status = "submitted";
                $jobms->jobassignedto_workshopEmpid = null;
                $jobms->jobjssignedto_surveyorEmpId = null;
                $jobms->assigned_on = date("Y-m-d H:i:s");
            }
        } else if ($jobcurrent_route == 2) {
            if ($fields['Job_Route_To'] == 1) {
                $job_status = "created";
            }

            if ($fields['Job_Route_To'] == 3) {
                $job_status = "submitted";
                $jobms->jobassignedto_workshopEmpid = null;
                $jobms->jobjssignedto_surveyorEmpId = null;
            }
        }

        if ($request->upload_type) {
            $upload_type = $request->upload_type;
        } else {
            $upload_type = '0';
        }

        $jobms->claim_type = $fields['claim_type'];
        $jobms->contact_person = $fields['contact_person'];
        $jobms->Job_Route_To = $fields['Job_Route_To'];
        $jobms->vehicle_reg_no = $fields['vehicle_reg_no'];
        $jobms->insured_name = $fields['insured_name'];
        $jobms->place_survey = $fields['place_survey'];
        $jobms->workshop_id = $fields['workshop_id'];
        $jobms->workshop_branch_id = $fields['workshop_branch_id'];
        //$jobms->contact_id = $fields['contact_person_id'];
        $jobms->contact_no = $fields['contact_no'];
        $jobms->client_id = $fields['client_id'];
        $jobms->client_branch_id = $fields['client_branch_id'];
        $jobms->date_of_appointment = $fields['date_of_appointment'];
        $jobms->admin_branch_id = $fields['admin_branch_id'];
        $jobms->sop_id = $fields['sop_id'];

        $jobms->branch_name = $fields['admin_branch_id'];
        $jobms->updated_by = $fields['created_by'];
        $jobms->job_status = $job_status;
        $jobms->upload_type = $upload_type;
        $jobms->update();

        return $this->sendResponse(new JobmsResource($jobms), 'job updated.', 200);
    }

    /** 
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $update_by = Auth::user()->id;

        $wh = ['id' => $id];   //,'created_by'=>$update_by

        $jobms = Inspection::where($wh)->first();

        if (is_null($jobms)) {
            return $this->sendError('Job  does not exist.Or not belong to you');
        }

        $jobms->delete();


        return $this->sendResponse([], 'job delete successfully.', 200);
    }


    public function assign(Request $request)
    {

        $fields1 = $request->validate(['user_role' => 'nullable|string']);
        if (!isset($field1['user_role'])) {
            $field1['user_role'] = null;
        }

        $created_by = Auth::user()->id;
        $jobjssignedto_surveyorEmpId = $request->jobjssignedto_surveyorEmpId;
        $jobassignedto_workshopEmpid = $request->jobassignedto_workshopEmpid;
        $job_id = $request->job_id;

        $wh = ['id' => $job_id];
        $jobms = Inspection::where($wh)->first();

        if (is_null($jobms)) {
            return $this->sendError('Job  does not exist.Or not belong to you');
        } else {

            $data = array(
                'assigned_by' => $created_by,
                'jobjssignedto_surveyorEmpId' => $jobjssignedto_surveyorEmpId,
                'jobassignedto_workshopEmpid' => $jobassignedto_workshopEmpid,
                'user_role' => $fields1['user_role'],
                'job_status' => 'pending',
                'assigned_on' => date("Y-m-d H:i:s")
            );

            $admin = Inspection::where('id', $job_id)->update($data);

            return $this->sendResponse($admin, 'Successfully Assigned Job.', 200);
        }
    }


    public function linkcreate(Request $request, $id)
    {

        $created_by = Auth::user()->id;
        $main_admin_id = getmainAdminIdIfAdminLoggedIn($created_by);
        $wh = ['id' => $id];
        // $wh = ['id' => $id, 'admin_id' => $main_admin_id];


        $jobms = Inspection::where($wh)->first();


        if (is_null($jobms)) {
            return $this->sendError('Job  does not exist.Or not belong to you');
        } else {


            $jobid_enc = md5($id);
            $currentDateTime = date('Y-m-d H:i:s');
            $timestamp = strtotime($currentDateTime);
            $newTimestamp = $timestamp + (24 * 60 * 60);
            $expDateTime = date('Y-m-d H:i:s', $newTimestamp);

            $linkdata = array(
                'inspection_id' => $id,
                'encoded_job_id' => $jobid_enc,
                'link_createdate' => date('Y-m-d H:i:s'),
                'link_expdate' => $expDateTime,
                'status' => 0,
                'link_createdby' => $created_by,
            );


            $link_where = ['inspection_id' => $id];
            $link_details = Joblink::where($link_where)
                //  ->whereDate('link_expdate', '<', date('Y-m-d H:i:s'))
                ->first();

            // print_r($link_details); die('xxxxxxxxxxx');

            if (is_null($link_details)) {
                $client = Joblink::create($linkdata);
            } else {
                $admin = Joblink::where('inspection_id', $id)->update($linkdata);
            }


            $job_data = Inspection::where('id', $id)->first();
            $data_response = array(
                'encripted_jobid' => $jobid_enc,
                'inspection_id' => $id,
                'vehicle_reg_no' => $job_data->vehicle_reg_no,
                'insured_name' => $job_data->insured_name,
                'place_survey' => $job_data->place_survey,
                'contact_no' => $job_data->contact_no,
                'contact_no' => $job_data->contact_no,
                'upload_type' => $job_data->upload_type,
                'Job_Route_To' => $job_data->Job_Route_To,
            );

            return $this->sendResponse($data_response, 'Job Url Created Successfully', 200);
        }
    }

    public function linkget(Request $request, $id)
    {


        $link_where = ['encoded_job_id' => $id];

        $link_details = InspectionLinks::where($link_where)->first();


        if (is_null($link_details)) {
            return $this->sendError('Job  does not exist.Or not belong to you');
        } else {
            // return $link_details;  
            $status = $link_details['status'];
            if ($status == 1) {
                return $this->sendError('Images and Document were already Submitted for this job.');
            } else {
                $exp_data = $link_details['link_expdate'];
                if ($exp_data < date('Y-m-d H:i:s')) {
                    return $this->sendError('Job  Link Expired Generate Other link');
                } else {

                    $job_data = Inspection::where('id', $link_details['inspection_id'])->first();


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
                            // $json_array_str = '[{"form_field_label": "awdawd"}, {"form_field_label": "awdawdwad"}]';

                            // Parse the JSON array
                            $json_array = json_decode($json_array_str, true);

                            // Check if decoding was successful
                            if ($json_array === null) {
                                // echo "Error decoding JSON\n";
                            } else {
                                $i = 0;
                                // Loop through the objects in the array
                                foreach ($json_array as $item) {


                                    $data1[$i] = ['name' => $item["form_field_label"], 'path' => 'assets/img/no-image.jpg'];

                                    // $form_field_label = $item["form_field_label"];
                                    // $data1[$i] = [
                                    //     $form_field_label => 'assets/img/no-image.jpg'
                                    // ];
                                    $i++;
                                }
                            }

                            $vehicle_imagedata = json_encode($data1);
                            $vehicle_data1 = $sop_master->document_image_field_label;
                            $correctedInput1 = preg_replace('/(\w+):/', '"$1":', $vehicle_data1);
                            $json_array_str1 = str_replace('\\', '', $correctedInput1);
                            // $json_array_str = '[{"form_field_label": "awdawd"}, {"form_field_label": "awdawdwad"}]';
                            //echo $json_array_str1; die;
                            // Parse the JSON array
                            $data2 = '';
                            $json_array1 = json_decode($json_array_str1, true);
                            // Check if decoding was successful
                            if ($json_array1 === null) {
                                // echo "Error decoding JSON\n";
                            } else {
                                $i = 0;
                                $data2 = [];
                                // Loop through the objects in the array
                                foreach ($json_array1 as $item) {

                                    $data2[$i] = ['name' => $item["form_document_label"], 'path' => 'assets/img/no-image.jpg'];
                                    // print_r($item); die;
                                    // $form_field_label = $item["form_document_label"];
                                    // $data2[$i] = [
                                    //     $form_field_label => 'assets/img/no-image.jpg'
                                    // ];
                                    $i++;
                                }
                            }

                            if (is_null($data2)) {
                                $vehicle_docdata = '';
                            } else {
                                $vehicle_docdata = json_encode($data2);
                            }

                            //client_id se client name 

                            $clientid = $job_data->client_id;
                            $client = Clientms::where('id', $clientid)->first();

                            $client_name = $client->name;


                            $data = [
                                'inspection_id' => $link_details['inspection_id'],
                                'sop_id' => $sop_id,
                                'sop_name' => $sop_master->sop_name,
                                'admin_branch_id' => $sop_master->id,
                                'vehicle_reg_no' => $job_data->vehicle_reg_no,
                                'insured_name' => $job_data->insured_name,
                                'client_name' => $client_name,
                                'contact_no' => $job_data->contact_no,
                                'place_survey' => $job_data->place_survey,
                                'is_location_allowed' => $sop_master->is_location_allowed,
                                'upload_type' => $job_data->upload_type,
                                'vehichle_images_field_label' => $vehicle_imagedata,
                                'document_image_field_label' => $vehicle_docdata,
                                'video_file' => $job_data->video_file,
                                'job_remark' => $job_data->job_remark,
                                'can_record_video' => can_record_video($job_data->sop_id),
                                'created_at' => $sop_master->created_at->format('Y-m-d H:i:s'),
                                'updated_at' => ($sop_master->updated_at) ? $sop_master->updated_at->format('Y-m-d H:i:s') : "",
                            ];
                            $i++;
                        }

                        return $this->sendResponse($data, 'sop fetched', 200);
                    }
                    //return $this->sendResponse('Job File Uploaded.', ['result'=>$data],200);
                }
            }
        }
    }
}
