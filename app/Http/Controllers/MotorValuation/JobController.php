<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobFile;
use App\Models\JobDetail;
use App\Models\vehicle\VehicleBody;
use App\Models\vehicle\VehicleColors;
use App\Models\Employee;
use App\Models\vehicle\VehicleClass;
use App\Models\vehicle\VehicleVarient;
use App\Models\vehicle\VehicleMakers;
use App\Models\vehicle\VehicleIssuingAuthority;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\job\JobResource;
use App\Http\Resources\job\JobDetailResource;
use App\Http\Resources\job\JobCollection;
use Illuminate\Support\Facades\Validator;
use PDF;
use App\Models\Client;
use App\Models\ClientBranch;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\BranchContactPerson;
use App\Models\Admin;
use App\Models\AppSetting;
use App\Models\PaymentHistory;
use App\Http\Resources\job\JobFileResouce;
use App\Mail\WelcomeEmail;
use App\Models\AdminSettings;
use Illuminate\Support\Facades\Mail;
use App\Models\JobTransactionHistory;
use Razorpay\Api\Api;
use App\Http\Controllers\BaseController;
use App\Jobs\SendMail;

class JobController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $fields = $request->validate([
            'status' => 'required|string|in:created,pending,approved,rejected,finalized,submitted'
        ]);

        $TBL_ADMIN = config('global.admin_table');
        $TBL_JOB = config('global.job_table');
        $TBL_EMPLOYEE = config('global.employee_table');
        $TBL_CLIENT = config('global.client_table');
        $TBL_CLIENT_BRANCHES = config('global.client_branch_table');
        $arrStatus = array($fields['status']);

        $created_by_id = Auth::user()->id;


        if ($request->user()->tokenCan('type:employee')) {
            if ($fields['status'] == 'pending') {
                $arrStatus[] = 'rejected';
            } else if ($fields['status'] == 'approved') {
                $arrStatus[] = 'finalized';
            }
            $parent_admin_id = getAdminIdIfEmployeeLoggedIn($created_by_id);
        } else {
            $parent_admin_id = getAdminIdIfAdminLoggedIn($created_by_id);
        }


        $allJobs = Job::select([
            $TBL_JOB . '.*',
            'requested_by_client.name as requested_by_name',
            'branch.name as branch_name',
            'approved_by_admin.name as approved_by_name',
            'mail_send_by_admin.name as mail_send_by_name',
            'rejected_by_admin.name as rejected_by_name',
            'completed_by_admin.name as completed_by_name',
            'submitted_by_employee.name as submitted_by_name',
        ])
            ->leftJoin($TBL_ADMIN . ' as approved_by_admin', function ($join) {
                $join->on('approved_by_admin.id', '=', 'approved_by');
            })
            ->leftJoin($TBL_ADMIN . ' as mail_send_by_admin', function ($join) {
                $join->on('mail_send_by_admin.id', '=', 'mail_send_by');
            })
            ->join($TBL_CLIENT . ' as requested_by_client', 'requested_by_client.id', '=', 'requested_by')
            ->join($TBL_CLIENT_BRANCHES . ' as branch', 'branch.id', '=', 'branch_id')
            ->leftJoin($TBL_ADMIN . ' as rejected_by_admin', function ($join) {
                $join->on('rejected_by_admin.id', '=', 'rejected_by');
            })
            ->leftJoin($TBL_ADMIN . ' as completed_by_admin', function ($join) {
                $join->on('completed_by_admin.id', '=', 'completed_by');
            })
            ->leftJoin($TBL_EMPLOYEE . ' as submitted_by_employee', function ($join) {
                $join->on('submitted_by_employee.id', '=', 'submitted_by');
            })
            ->where($TBL_JOB . '.status', '!=', '2')
            ->where($TBL_JOB . '.parent_admin_id', '=', $parent_admin_id)
            ->whereIn('job_status', $arrStatus);


        if (Auth::user()->parent_id != "0") {
            if (!$request->user()->tokenCan('type:employee')) {
                $allJobs->where($TBL_JOB . '.created_by_id', '=', Auth::user()->id);
            }

        }

        $searchForEmployeeId = $request->input('employee_id');
        if ($searchForEmployeeId != null & $searchForEmployeeId != '') {
            $allJobs = $allJobs->where($TBL_JOB . '.submitted_by', '=', $searchForEmployeeId);
        }

        $searchForClientId = $request->input('client_id');
        if ($searchForClientId != null && $searchForClientId != '') {
            $allJobs = $allJobs->where($TBL_JOB . '.requested_by', '=', $searchForClientId);
        }

        $is_offline = $request->input('is_offline');
        if ($is_offline != null && $is_offline != '') {
            if ($is_offline == 'yes') {
                $allJobs = $allJobs->where($TBL_JOB . '.is_offline', '=', 'yes');
            }
        }

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($fromDate != null && $fromDate != '' && $toDate != null && $toDate != '') {
            if ($fields['status'] == 'pending') {
                $allJobs = $allJobs->whereDate($TBL_JOB . '.assigned_date', '>=', $fromDate)
                    ->whereDate($TBL_JOB . '.assigned_date', '<=', $toDate);
                // $allJobs =  $allJobs->where(function($query) use ($TBL_JOB,$fromDate,$toDate) {
                //     $query =  $query->orWhere(function($query1) use ($TBL_JOB,$fromDate,$toDate) {
                //         $query1 =  $query1->whereDate($TBL_JOB.'.rejected_date','>=',$fromDate)
                //         ->whereDate($TBL_JOB.'.rejected_date','<=',$toDate);
                //     });
                //     $query->orWhere(function($query1) use ($TBL_JOB,$fromDate,$toDate) {
                //         $query1 =  $query1->whereDate($TBL_JOB.'.assigned_date','>=',$fromDate)
                //         ->whereDate($TBL_JOB.'.assigned_date','<=',$toDate);
                //     });
                // });

            } else if ($fields['status'] == 'approved') {
                $allJobs = $allJobs->whereDate($TBL_JOB . '.approval_date', '>=', $fromDate)
                    ->whereDate($TBL_JOB . '.approval_date', '<=', $toDate);
            } else if ($fields['status'] == 'rejected') {
                $allJobs = $allJobs->whereDate($TBL_JOB . '.rejected_date', '>=', $fromDate)
                    ->whereDate($TBL_JOB . '.rejected_date', '<=', $toDate);
            } else if ($fields['status'] == 'submitted') {
                $allJobs = $allJobs->whereDate($TBL_JOB . '.submission_date', '>=', $fromDate)
                    ->whereDate($TBL_JOB . '.submission_date', '<=', $toDate);
            } else if ($fields['status'] == 'finalized') {
                $allJobs = $allJobs->whereDate($TBL_JOB . '.completed_at', '>=', $fromDate)
                    ->whereDate($TBL_JOB . '.completed_at', '<=', $toDate);
            } else {
                $allJobs = $allJobs->whereDate($TBL_JOB . '.updated_at', '>=', $fromDate)
                    ->whereDate($TBL_JOB . '.updated_at', '<=', $toDate);
            }
        }

        if ($request->user()->tokenCan('type:employee')) {
            $allJobs = $allJobs->where($TBL_JOB . '.submitted_by', '=', Auth::user()->id);

            $is_offline = $request->input('is_offline');
            if ($is_offline != null && $is_offline != '') {
                if ($is_offline == 'yes') {
                    $allJobs = $allJobs->where($TBL_JOB . '.is_offline', '=', 'yes');
                }
            }
        }


        $searchKeyword = $request->input('search_keyword');
        if ($searchKeyword != null && $searchKeyword != '') {
            $allJobs = $allJobs->where(function ($query) use ($TBL_JOB, $searchKeyword) {
                $query->orWhere('requested_by_client.name', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('approved_by_admin.name', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('branch.name', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('rejected_by_admin.name', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('submitted_by_employee.name', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('completed_by_admin.name', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere($TBL_JOB . '.id', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere($TBL_JOB . '.owner_name', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere($TBL_JOB . '.contact_mobile_no', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere($TBL_JOB . '.address', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere($TBL_JOB . '.vehicle_reg_no', 'LIKE', '%' . $searchKeyword . '%');
            });
        }

        //echo $allJobs->toSql();

        if ($fields['status'] == 'pending') {
            $allJobs = $allJobs->orderBy('assigned_date', 'desc');
        } else if ($fields['status'] == 'approved') {
            $allJobs = $allJobs->orderBy('approval_date', 'desc');
        } else if ($fields['status'] == 'rejected') {
            $allJobs = $allJobs->orderBy('rejected_date', 'desc');
        } else if ($fields['status'] == 'submitted') {
            $allJobs = $allJobs->orderBy('submission_date', 'desc');
        } else if ($fields['status'] == 'finalized') {
            $allJobs = $allJobs->orderBy('completed_at', 'desc');
        } else {
            $allJobs = $allJobs->orderBy('created_at', 'desc');
        }


        $jobsList = $request->exists('all')
            ? JobResource::collection($allJobs->get())
            : new JobCollection($allJobs->paginate(20));
        return $this->sendResponse($jobsList, 'Posts fetched.');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $created_by = 'Admin';
        $submitted_by = 0;
        $jobStatus = 'created';
        $logged_in_id = Auth::user()->id;
        $assigned_date = NULL;

        if ($request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        if ($request->user()->tokenCan('type:employee')) {
            $created_by = 'Employee';
            $submitted_by = $logged_in_id;
            $jobStatus = 'pending';
            $assigned_date = date('Y-m-d H:i:s');

            $parent_admin_id = getAdminIdIfEmployeeLoggedIn($logged_in_id);

            $employee = Employee::where('id', '=', $logged_in_id)->where('status', '=', '1')->where('parent_admin_id', '=', $parent_admin_id)->first();

            if (is_null($employee)) {
                return $this->sendError('Employee does not exist.');
            }

            if ($employee->is_guest_employee == '1') {
                return $this->sendError("You can't perform this operation as guest employee");
            }

        } else {
            $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);
        }

        $isOffline = $request->input('is_offline');
        if ($isOffline == null || $isOffline == '' || $isOffline == 'no') {
            $isOffline = 'no';
        } else {
            $isOffline = 'yes';
        }

        $fields = $request->validate([
            'vehicle_reg_no' => 'required|string',
            'owner_name' => 'required|string',
            'address' => 'required|string',
            'contact_mobile_no' => 'required|numeric|digits:10',
            'requested_by' => 'required|int',
            'branch_id' => 'required|int',
            'contact_person_id' => 'required|int',
            'inspection_place' => 'required|string',
        ]);

        $client = Client::where('id', '=', $fields['requested_by'])->where('status', '!=', '2')->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }

        $clientBranch = ClientBranch::where('id', '=', $fields['branch_id'])->where('client_id', '=', $fields['requested_by'])->where('status', '!=', '2')->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client Branch does not exist.');
        }

        $contactPerson = BranchContactPerson::where('id', '=', $fields['contact_person_id'])->where('branch_id', '=', $fields['branch_id'])->where('status', '!=', '2')->first();


        if (is_null($contactPerson)) {
            return $this->sendError('Contact Person does not exist.');
        }

        $job = Job::create([
            'vehicle_reg_no' => $fields['vehicle_reg_no'],
            'owner_name' => $fields['owner_name'],
            'address' => $fields['address'],
            'contact_mobile_no' => $fields['contact_mobile_no'],
            'requested_by' => $fields['requested_by'],
            'branch_id' => $fields['branch_id'],
            'remark' => '',
            'is_offline' => $isOffline,
            'job_status' => $jobStatus,
            'created_by' => $created_by,
            'created_by_id' => $logged_in_id,
            'submitted_by' => $submitted_by,
            'assigned_date' => $assigned_date,
            'inspection_place' => $fields['inspection_place'],
            'parent_admin_id' => $parent_admin_id,
            'contact_person_id' => $fields['contact_person_id'],
        ]);

        JobTransactionHistory::create([
            'job_id' => $job->id,
            'status' => $jobStatus,
            'user_type' => $created_by,
            'user_id' => $parent_admin_id,
            'on_date_time' => date('Y-m-d H:i:s'),
        ]);

        return $this->sendResponse(new JobResource($job), "success", 200);

    }

    /**
     * Update resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateJobBasicInfo(Request $request, $id)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);


        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }

        $arrJobsUpdateStatus = array("pending", 'created');

        if (!in_array($job->job_status, $arrJobsUpdateStatus)) {
            return $this->sendError('Bad Request');
        }

        $fields = $request->validate([
            'vehicle_reg_no' => 'required|string',
            'owner_name' => 'required|string',
            'address' => 'required|string',
            'contact_mobile_no' => 'required|numeric|digits:10',
            'requested_by' => 'required|int',
            'branch_id' => 'required|int',
            'contact_person_id' => 'required|int',
            'inspection_place' => 'required|string',
        ]);

        $client = Client::where('id', '=', $fields['requested_by'])->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }

        $clientBranch = ClientBranch::where('id', '=', $fields['branch_id'])->where('client_id', '=', $fields['requested_by'])->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client Branch does not exist.');
        }

        $contactPerson = BranchContactPerson::where('id', '=', $fields['contact_person_id'])->where('branch_id', '=', $fields['branch_id'])->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($contactPerson)) {
            return $this->sendError('Contact Person does not exist.');
        }


        $submitted_by = $request->input('submitted_by');
        if ($submitted_by != null) {
            $validator = Validator::make($request->all(), [
                'submitted_by' => 'required|int',
            ]);

            if ($validator->fails()) {
                return $this->sendLaravelFormatError("The given data was invalid", $validator->errors());
            }
        }

        if ($job->job_status == 'pending' && $submitted_by != null && $submitted_by != 0) {
            $oldEmployee = Employee::where('id', '=', $job->submitted_by)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
            if (is_null($oldEmployee)) {
                return $this->sendError('Something went wrong.');
            }

            $employee = Employee::where('id', '=', $submitted_by)->where('status', '=', '1')->where('parent_admin_id', '=', $parent_admin_id)->first();
            if (is_null($employee)) {
                return $this->sendError('Employee does not exist.');
            }

            $oldEmployee->job_assigned_to_guest = 0;
            $oldEmployee->update();

            if ($employee->is_guest_employee == '1') {
                $employee->job_assigned_to_guest = $id;
                $employee->update();
            }

            $job->submitted_by = $submitted_by;
            $job->assigned_date = date('Y-m-d H:i:s');
        }


        $job->vehicle_reg_no = $fields['vehicle_reg_no'];
        $job->owner_name = $fields['owner_name'];
        $job->address = $fields['address'];
        $job->contact_mobile_no = $fields['contact_mobile_no'];
        $job->requested_by = $fields['requested_by'];
        $job->branch_id = $fields['branch_id'];
        $job->inspection_place = $fields['inspection_place'];
        $job->contact_person_id = $fields['contact_person_id'];

        $job->update();

        return $this->sendResponse(new JobResource($job), "success", 200);

    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $TBL_ADMIN = config('global.admin_table');
        $TBL_JOB = config('global.job_table');
        $TBL_EMPLOYEE = config('global.employee_table');
        $TBL_CLIENT = config('global.client_table');
        $TBL_CLIENT_BRANCHES = config('global.client_branch_table');
        $TBL_BRANCH_CONTACT_PERSON = config('global.branch_contact_person_table');

        if ($request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        if ($request->user()->tokenCan('type:employee')) {
            $parent_admin_id = getAdminIdIfEmployeeLoggedIn($logged_in_id);
        } else {
            $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);
        }

        $job = Job::select([
            $TBL_JOB . '.*',
            'requested_by_client.name as requested_by_name',
            'branch.name as branch_name',
            'contact_person.name as contact_person_name',
            'approved_by_admin.name as approved_by_name',
            'mail_send_by_admin.name as mail_send_by_name',
            'rejected_by_admin.name as rejected_by_name',
            'completed_by_admin.name as completed_by_name',
            'submitted_by_employee.name as submitted_by_name',
        ])
            ->leftJoin($TBL_ADMIN . ' as mail_send_by_admin', function ($join) {
                $join->on('mail_send_by_admin.id', '=', 'mail_send_by');
            })
            ->leftJoin($TBL_ADMIN . ' as approved_by_admin', function ($join) {
                $join->on('approved_by_admin.id', '=', 'approved_by');
            })
            ->join($TBL_CLIENT . ' as requested_by_client', 'requested_by_client.id', '=', 'requested_by')
            ->join($TBL_CLIENT_BRANCHES . ' as branch', 'branch.id', '=', 'branch_id')
            ->join($TBL_BRANCH_CONTACT_PERSON . ' as contact_person', 'contact_person.id', '=', 'contact_person_id')
            ->leftJoin($TBL_ADMIN . ' as rejected_by_admin', function ($join) {
                $join->on('rejected_by_admin.id', '=', 'rejected_by');
            })
            ->leftJoin($TBL_ADMIN . ' as completed_by_admin', function ($join) {
                $join->on('completed_by_admin.id', '=', 'completed_by');
            })
            ->leftJoin($TBL_EMPLOYEE . ' as submitted_by_employee', function ($join) {
                $join->on('submitted_by_employee.id', '=', 'submitted_by');
            })
            ->where($TBL_JOB . '.id', '=', $id)
            ->where($TBL_JOB . '.parent_admin_id', '=', $parent_admin_id)
            ->where($TBL_JOB . '.status', '!=', '2')->first();

        //$job = Job::where('id','=', $id)->where('status','!=', '2')->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }
        $job->images = JobFileResouce::collection(JobFile::where('job_id', $id)->get());
        $job->job_detail = JobDetail::where('job_id', $id)->first();
        $job->branch_list = ClientBranch::where('client_id', '=', $job->requested_by)
            ->where('status', '=', '1')->where('parent_admin_id', '=', $parent_admin_id)->select('name', 'id')->get();
        $job->contact_person_list = BranchContactPerson::where('branch_id', '=', $job->branch_id)
            ->where('status', '=', '1')->where('parent_admin_id', '=', $parent_admin_id)->select('name', 'id')->get();

        return $this->sendResponse(new JobResource($job), 'Post fetched.');
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
        if (!$request->user()->tokenCan('type:employee')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;


        $parent_admin_id = getAdminIdIfEmployeeLoggedIn($logged_in_id);

        $employee = Employee::where('id', '=', $logged_in_id)->where('status', '=', '1')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }

        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }

        $arrJobsUpdateStatus = array("pending", 'submitted', 'rejected');

        if (!in_array($job->job_status, $arrJobsUpdateStatus)) {
            return $this->sendError('Bad Request');
        }

        $validator = Validator::make($request->all(), [
            'detail_type' => 'required|string|in:Vehicle Detail,Technical Features',
        ]);

        if ($validator->fails()) {
            return $this->sendLaravelFormatError("The given data was invalid", $validator->errors());
        }
        $requestType = $request->input('detail_type');

        if ($requestType == "Vehicle Detail") {
            return $this->updateJobVehicleDetail($id, $request);
        } else {
            /*$jobDetail = JobDetail::where('job_id',$id)->first();
            if (is_null($jobDetail)) {
                return $this->sendError('Bad Request');
            }*/

            return $this->updateJobVehicleTechnicalFeature($id, $request, $job);
        }

    }

    function updateJobVehicleTechnicalFeature($id, $request, $job)
    {

        $employee = Employee::where('id', '=', $job->submitted_by)->where('status', '=', '1')->first();
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }

        $fields = $request->validate([
            'engine_transmission' => 'required|string|in:0,1',
            'electrical_gadgets' => 'required|string|in:0,1',
            'right_side' => 'required|string|in:0,1',
            'left_body' => 'required|string|in:0,1',
            'front_body' => 'required|string|in:0,1',
            'back_body' => 'required|string|in:0,1',
            'load_body' => 'required|string|in:0,1',
            'all_glass_condition' => 'required|string|in:0,1',
            'cabin_condition' => 'required|string|in:0,1',
            'head_lamp' => 'required|string|in:0,1',
            'tyres_condition' => 'required|string|in:0,1',
            'maintenance' => 'required|string|in:0,1',
            'other_damages' => 'required|string',
            'vehicle_owner_signature' => 'required|string',
        ]);

        $arrJobDetail = [
            'engine_transmission' => $fields['engine_transmission'],
            'electrical_gadgets' => $fields['electrical_gadgets'],
            'right_side' => $fields['right_side'],
            'left_body' => $fields['left_body'],
            'front_body' => $fields['front_body'],
            'back_body' => $fields['back_body'],
            'load_body' => $fields['load_body'],
            'all_glass_condition' => $fields['all_glass_condition'],
            'cabin_condition' => $fields['cabin_condition'],
            'head_lamp' => $fields['head_lamp'],
            'tyres_condition' => $fields['tyres_condition'],
            'maintenance' => $fields['maintenance'],
            'other_damages' => $fields['other_damages'],
            'job_id' => $id,
        ];

        $jobDetail = JobDetail::where('job_id', $id)->first();
        if (is_null($jobDetail)) {
            $jobDetail = JobDetail::create($arrJobDetail);
        } else {
            $jobDetail = JobDetail::where('job_id', $id)->update($arrJobDetail);
        }

        $jobDetail = JobDetail::where('job_id', $id)->first();

        $update_by = Auth::user()->id;

        $job->vehicle_owner_signature = $fields['vehicle_owner_signature'];

        if ($job->job_status != 'submitted') {
            $job->submission_date = date('Y-m-d H:i:s');
            $job->job_status = 'submitted';

            JobTransactionHistory::create([
                'job_id' => $job->id,
                'status' => "submitted",
                'user_type' => 'Employee',
                'user_id' => $update_by,
                'on_date_time' => date('Y-m-d H:i:s'),
            ]);
            sendJobNotificationToEmployee($employee->firebase_token, $id, "job_submitted_by_employee");

            if ($employee->is_guest_employee == '1') {
                $employee->status = '2';
                $employee->update();
            }

        }

        $job->update();

        return $this->sendResponse(new JobDetailResource($jobDetail), 'Post fetched.');
    }

    function updateJobVehicleDetail($id, $request)
    {

        $arrValidation = [
            'vehicle_class' => 'required|string',
            'registration_date' => 'required|string|date_format:Y-m-d',
            'type_of_body' => 'required|string',
            'manufactoring_year' => 'required|integer',
            'maker' => 'required|string',
            'model' => 'required|string',
            'chassis_no' => 'required|string',
            'engine_no' => 'required|string',
            'rc_status' => 'required|integer|in:0,1',
            'seating_capacity' => 'required|integer',
            'issuing_authority' => 'required|string',
            'fuel_type' => 'required|string',
            'color' => 'required|string',
            'odometer_reading' => 'required|string',
            'fitness_valid_upto' => 'required|string|date_format:Y-m-d',
            'laden_weight' => 'required|string',
            'unladen_weight' => 'required|string',
            'requested_value' => 'required|numeric|min:3|max:99999999',
        ];

        $arrFieldWithValue = array();
        $arrFieldWithValidation = array();


        foreach ($arrValidation as $fieldName => $validation) {
            $fieldValue = $request->input($fieldName);
            if ($fieldValue != null & $fieldValue != '') {
                $arrFieldWithValue[$fieldName] = $fieldValue;
                $arrFieldWithValidation[$fieldName] = $validation;
            }
        }

        $validator = Validator::make(
            $arrFieldWithValue,
            $arrFieldWithValidation
        );

        if (count($arrFieldWithValue) > 0 && $validator->fails()) {
            return $this->sendLaravelFormatError("The given data was invalid", $validator->errors());
        }

        $arrJobDetail = array();
        foreach ($arrFieldWithValue as $fieldName => $fieldValue) {
            $arrJobDetail[$fieldName] = $fieldValue;
        }


        $arrJobDetail['job_id'] = $id;

        // $arrJobDetail = [
        //     'vehicle_class' => $fields['vehicle_class'],
        //     'registration_date' => $fields['registration_date'],
        //     'type_of_body' => $fields['type_of_body'],
        //     'manufactoring_year' => $fields['manufactoring_year'],
        //     'maker' => $fields['maker'],
        //     'model' => $fields['model'],
        //     'chassis_no' => $fields['chassis_no'],
        //     'engine_no' => $fields['engine_no'],
        //     'rc_status' => $fields['rc_status'],
        //     'seating_capacity' => $fields['seating_capacity'],
        //     'issuing_authority' => $fields['issuing_authority'],
        //     'fuel_type' => $fields['fuel_type'],
        //     'color' => $fields['color'],
        //     'odometer_reading' => $fields['odometer_reading'],
        //     'fitness_valid_upto' => $fields['fitness_valid_upto'],
        //     'laden_weight' => $fields['laden_weight'],
        //     'unladen_weight' => $fields['unladen_weight'],
        //     'requested_value' => $fields['requested_value'],
        //     'job_id' => $id,
        //     'other_damages' => ''
        // ];

        $jobDetail = JobDetail::where('job_id', $id)->first();
        /*govind*/
        $logged_in_id = Auth::user()->id;
        //$parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

        if ($request->user()->tokenCan('type:employee')) {
            $parent_admin_id = getAdminIdIfEmployeeLoggedIn($logged_in_id);
        } else {
            $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);
        }

        /*govind*/

        if (is_null($jobDetail)) {
            $jobDetail = JobDetail::create($arrJobDetail);
        } else {
            $jobDetail = JobDetail::where('job_id', $id)->update($arrJobDetail);
        }

        if (isset($arrJobDetail['issuing_authority'])) {
            $issuingAuthority = VehicleIssuingAuthority::where('name', '=', $arrJobDetail['issuing_authority'])->where('created_by_id', '=', $parent_admin_id)->first();
            if (is_null($issuingAuthority)) {
                $issuingAuthority = VehicleIssuingAuthority::create([
                    'name' => $arrJobDetail['issuing_authority'],
                    'created_by_id' => $parent_admin_id,
                ]);
            }
        }


        if (isset($arrJobDetail['type_of_body'])) {
            $vehicleBody = VehicleBody::where('name', '=', $arrJobDetail['type_of_body'])->where('created_by_id', '=', $parent_admin_id)->first();
            if (is_null($vehicleBody)) {
                $vehicleBody = VehicleBody::create([
                    'name' => $arrJobDetail['type_of_body'],
                    'created_by_id' => $parent_admin_id,
                ]);
            }
        }
        if (isset($arrJobDetail['vehicle_class'])) {
            $vehicleClass = VehicleClass::where('name', '=', $arrJobDetail['vehicle_class'])->where('created_by_id', '=', $parent_admin_id)->first();
            if (is_null($vehicleClass)) {
                $vehicleClass = VehicleClass::create([
                    'name' => $arrJobDetail['vehicle_class'],
                    'created_by_id' => $parent_admin_id,
                ]);
            }
        }
        if (isset($arrJobDetail['color'])) {
            $vehicleColor = VehicleColors::where('name', '=', $arrJobDetail['color'])->where('created_by_id', '=', $parent_admin_id)->first();
            if (is_null($vehicleColor)) {
                $vehicleColor = VehicleColors::create([
                    'name' => $arrJobDetail['color'],
                    'created_by_id' => $parent_admin_id,
                ]);
            }
        }

        if (isset($arrJobDetail['maker'])) {
            $vehicleMaker = VehicleMakers::where('name', '=', $arrJobDetail['maker'])->where('created_by_id', '=', $parent_admin_id)->first();
            if (is_null($vehicleMaker)) {
                $vehicleMaker = VehicleMakers::create([
                    'name' => $arrJobDetail['maker'],
                    'created_by_id' => $parent_admin_id,
                ]);
            }

            if (isset($arrJobDetail['model'])) {
                $seating_capacity = 0;
                if (isset($arrJobDetail['seating_capacity'])) {
                    $seating_capacity = $arrJobDetail['seating_capacity'];
                }
                $vehicleVarientCount = VehicleVarient::where('name', '=', $arrJobDetail['model'])->where('maker_id', '=', $vehicleMaker->id)->where('created_by_id', '=', $parent_admin_id)->count();
                if ($vehicleVarientCount == 0) {
                    VehicleVarient::create([
                        'name' => $arrJobDetail['model'],
                        'maker_id' => $vehicleMaker->id,
                        'created_by_id' => $parent_admin_id,
                        'seats' => $seating_capacity,
                    ]);
                }
            }
        }

        $jobDetail = JobDetail::where('job_id', $id)->first();

        return $this->sendResponse(new JobDetailResource($jobDetail), 'Post fetched.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);

        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }

        if ($job->submitted_by == null || $job->submitted_by == 0) {
            $job->delete();
            return $this->sendResponse([], 'Job delete successfully.', 200);
        }

        $employee = Employee::where('id', '=', $job->submitted_by)->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($employee)) {
            return $this->sendError('Bad Request');
        }


        if (($employee->is_guest_employee && $job->job_status == 'submitted')) {
            $job->status = '2';
            $job->update();
            return $this->sendResponse([], 'Job deleted successfully.', 200);
        } else {
            return $this->sendError('Bad Request');
        }
    }

    /**
     * Upload Image or Video of specified resource in storage.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function uploadJobFile(Request $request, $id)
    {
        if (!$request->user()->tokenCan('type:employee')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $TBL_JOB_FILES_TEMP = config('global.job_files_table_temp');

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfEmployeeLoggedIn($logged_in_id);

        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:png,jpg,jpeg,mp4,mov|max:102400',
            'type' => 'required|string|in:Chassis Number,Front View,Rear View,Right Side,Left Side,Odometer,Other,Video,Vehicle Owner Image'
        ]);

        if ($validator->fails()) {
            return $this->sendLaravelFormatError("The given data was invalid", $validator->errors());
        }

        $imageType = $request->input('type');

        if ($imageType == 'Vehicle Owner Image') {
            if ($file = $request->file('file')) {
                $path = $file->store('job_files', 'public');
                return $this->sendResponse(array('name' => $path), 'File uploaded successfully.');
            }
            return $this->sendError('File not found');
        } else {
            $arrJobsUpdateStatus = array("pending", "rejected");

            if (!in_array($job->job_status, $arrJobsUpdateStatus)) {
                return $this->sendError('Bad Request');
            }
        }


        $images = DB::table($TBL_JOB_FILES_TEMP)->where([
            ['type', '=', $request->input('type')],
            ['job_id', '=', $id],
        ])->get();

        $imageType = $request->input('type');

        if ($file = $request->file('file')) {
            $path = $file->store('job_files', 'public');
            $name = $file->getClientOriginalName();

            if (count($images) > 0 && $imageType != 'Other') {
                foreach ($images as $row) {
                    $imagePath = public_path('storage/' . $row->name);
                    if (File::exists($imagePath)) {
                        unlink($imagePath);
                    }

                }

                DB::table($TBL_JOB_FILES_TEMP)
                    ->where([
                        ['type', '=', $request->input('type')],
                        ['job_id', '=', $id],
                    ])
                    ->update(
                        [
                            'job_id' => $id,
                            'type' => $request->input('type'),
                            'name' => $path
                        ]
                    );
            } else {
                DB::table($TBL_JOB_FILES_TEMP)->insert(
                    [
                        'job_id' => $id,
                        'type' => $request->input('type'),
                        'name' => $path
                    ]
                );
            }


            return $this->sendResponse(array('name' => $path), 'File uploaded successfully.');

        }
    }


    /**
     * Submit job images and video of specified resource in storage.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function submitJobImageAndVideo(Request $request, $id)
    {

        if (!$request->user()->tokenCan('type:employee')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfEmployeeLoggedIn($logged_in_id);

        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }

        $fields = $request->validate([
            'remark' => 'required|string',
            'other_images' => 'required|string',
            //'inspection_place' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_outside_job' => 'required|string|in:Yes,No',
        ]);

        $TBL_JOB_FILES_TEMP = config('global.job_files_table_temp');


        $arrJobsUpdateStatus = array("pending", "rejected");

        if (!in_array($job->job_status, $arrJobsUpdateStatus)) {
            return $this->sendError('Bad Request');
        }

        $arrImageType = array("Chassis Number", "Front View", "Rear View", "Right Side", "Left Side", "Odometer");
        $imagesCount = JobFile::where([
            ['job_id', '=', $id],
        ])->whereIn('type', $arrImageType)->count();
        if ($imagesCount == 0) {
            $imagesCount = DB::table($TBL_JOB_FILES_TEMP)->where([
                ['job_id', '=', $id],
            ])->whereIn('type', $arrImageType)->count();
            if ($imagesCount < count($arrImageType)) {
                return $this->sendError('Upload all compulsory images');
            }
        }

        $images = DB::table($TBL_JOB_FILES_TEMP)->where([
            ['job_id', '=', $id],
        ])->get();

        $otherImages = json_decode($fields['other_images']);
        $is_outside_job = $fields['is_outside_job'];
        $latitude = $fields['latitude'];
        $longitude = $fields['longitude'];

        JobFile::where([
            ['job_id', '=', $id],
            ['type', '=', 'Other'],
        ])->whereNotIn('name', $otherImages)->delete();

        foreach ($images as $row) {
            $jobImages = JobFile::where([
                ['job_id', '=', $id],
                ['type', '=', $row->type],
            ])->get();

            if (count($jobImages) > 0 && $row->type != 'Other') {
                JobFile::where([
                    ['type', '=', $row->type],
                    ['job_id', '=', $row->job_id],
                ])->update([
                    'name' => $row->name,
                ]);
            } else {
                if ($row->type == 'Other' && !in_array($row->name, $otherImages)) {
                    continue;
                }
                JobFile::create([
                    'name' => $row->name,
                    'type' => $row->type,
                    'job_id' => $row->job_id,
                ]);
            }
        }

        DB::table($TBL_JOB_FILES_TEMP)->where('job_id', $id)->delete();

        $remark = $fields['remark'];

        $job->remark = $remark;
        $job->latitude = $latitude;
        $job->longitude = $longitude;
        $job->is_outside_job = $is_outside_job == "Yes" ? '1' : '0';
        $job->update();
        $employee = Employee::where('id', '=', $logged_in_id)->where('status', '=', '1')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }
        return $this->sendResponse(new JobResource($job), 'Images submitted successfully.', 200);
    }

    /**
     * Updat status of specified resource in storage.
     *
     * @param int $id
     * @param String $status
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id, $status)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);

        $validator = Validator::make([
            'id' => $id,
            'status' => $status,
        ],[
            'id' => ['required'],
            'status' => ['required', 'in:approved,rejected']
        ]);

        if ($validator->fails()) {
            return $this->sendLaravelFormatError("The given data was invalid", $validator->errors());
        }

        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }

        if ($status == 'approved' || $status == 'rejected') {
            if ($job->job_status != 'submitted') {
                return $this->sendError('Job not submitted.');
            }
        } else {
            if ($job->job_status != 'approved') {
                return $this->sendError('Job not approved.');
            }
        }

        if ($status == 'approved') {
            $job->approval_date = date('Y-m-d H:i:s');
            $job->approved_by = $logged_in_id;
        } else if ($status == 'rejected') {
            $job->rejected_date = date('Y-m-d H:i:s');
            $job->rejected_by = $logged_in_id;
            $job->is_outside_job = '0';
        } else if ($status == 'finalized') {
            $job->completed_at = date('Y-m-d H:i:s');
            $job->completed_by = $logged_in_id;
        }

        $adminRemark = '';
        $adminRemark = $request->input('remark');
        if ($adminRemark != null) {
            $job->admin_remark = $adminRemark;
        }


        $job->job_status = $status;
        $job->update();

        JobTransactionHistory::create([
            'job_id' => $job->id,
            'status' => $status,
            'user_type' => 'Admin',
            'user_id' => $logged_in_id,
            'on_date_time' => date('Y-m-d H:i:s'),
        ]);

        return $this->sendResponse(new JobResource($job), 'Status change successfully.', 200);
    }

    /**
     * Get Vechile Details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVehicleDetail(Request $request)
    {
        $logged_in_id = Auth::user()->id;
        /*govind*/
        if ($request->user()->tokenCan('type:employee')) {
            $parent_admin_id = getAdminIdIfEmployeeLoggedIn($logged_in_id);
        } else {
            $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);
        }
        //$parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

        $arrVehicleDetail = array();
        $arrVehicleDetail["vehicle_colors"] = VehicleColors::where('created_by_id', '=', $parent_admin_id)->get();
        $arrVehicleDetail["vehicle_class"] = VehicleClass::where('created_by_id', '=', $parent_admin_id)->get();
        $arrVehicleDetail["vehicle_body_type"] = VehicleBody::where('created_by_id', '=', $parent_admin_id)->get();

        $arrVehicleDetail["vehicle_makers"] = VehicleMakers::where('created_by_id', '=', $parent_admin_id)->get();
        $arrVehicleDetail["vehicle_issue_authority"] = VehicleIssuingAuthority::where('created_by_id', '=', $parent_admin_id)->get();

        return $this->sendResponse($arrVehicleDetail, 'Posts fetched.');

    }

    /**
     * Get Vechile Details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getModelsByMaker(Request $request, $id)
    {
        $arrVehicleDetail = array();
        $arrVehicleDetail["vehicle_variants"] = VehicleVarient::where('maker_id', '=', $id)->get();
        return $this->sendResponse($arrVehicleDetail, 'Posts fetched.');

    }

    /**
     * Assigned Employee to Job.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function assignEmployeeToJob(Request $request, $id)
    {

        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);


        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }

        $fields = $request->validate([
            'employee_id' => 'required|int'
        ]);

        $employeeId = $fields['employee_id'];

        $employee = Employee::where('id', '=', $employeeId)->where('status', '=', '1')->first();
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }

        if ($job->submission_date != null || !empty($job->submission_date)) {
            return $this->sendError('This job is already submitted.');
        }


        $job->submitted_by = $employeeId;
        $job->assigned_date = date('Y-m-d H:i:s');
        $job->job_status = 'pending';
        $job->update();

        if ($employee->is_guest_employee == '1') {
            $employee->job_assigned_to_guest = $id;
            $employee->update();
        }

        JobTransactionHistory::create([
            'job_id' => $job->id,
            'status' => 'pending',
            'user_type' => 'Admin',
            'user_id' => $logged_in_id,
            'on_date_time' => date('Y-m-d H:i:s'),
        ]);

        sendJobNotificationToEmployee($employee->firebase_token, $id, "job_assigned_to_employee");
        return $this->sendResponse(new JobResource($job), 'Employee assigned successfully.', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateJob(Request $request, $id)
    {

        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);

        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }


        $jobDetail = JobDetail::where('job_id', $id)->first();
        if (is_null($jobDetail)) {
            return $this->sendError('Bad Request');
        }

        $arrJobsUpdateStatus = array("approved", 'rejected');

        if (!in_array($job->job_status, $arrJobsUpdateStatus)) {
            return $this->sendError('Bad Request');
        }

        $fields = $request->validate([
            'vehicle_reg_no' => 'required|string',
            'owner_name' => 'required|string',
            'address' => 'required|string',
            'contact_mobile_no' => 'required|numeric|digits:10',
            'requested_by' => 'required|int',
            'vehicle_class' => 'required|string',
            'registration_date' => 'required|string|date_format:Y-m-d',
            'type_of_body' => 'required|string',
            'manufactoring_year' => 'required|int',
            'maker' => 'required|string',
            'model' => 'required|string',
            'chassis_no' => 'required|string',
            'engine_no' => 'required|string',
            'rc_status' => 'required|int|in:0,1',
            'seating_capacity' => 'required|int',
            'issuing_authority' => 'required|string',
            'fuel_type' => 'required|string',
            'color' => 'required|string',
            'odometer_reading' => 'required|string',
            'fitness_valid_upto' => 'required|string|date_format:Y-m-d',
            'laden_weight' => 'required|string',
            'unladen_weight' => 'required|string',
            'requested_value' => 'required|numeric',
            'engine_transmission' => 'required|string|in:0,1',
            'electrical_gadgets' => 'required|string|in:0,1',
            'right_side' => 'required|string|in:0,1',
            'left_body' => 'required|string|in:0,1',
            'front_body' => 'required|string|in:0,1',
            'back_body' => 'required|string|in:0,1',
            'load_body' => 'required|string|in:0,1',
            'all_glass_condition' => 'required|string|in:0,1',
            'cabin_condition' => 'required|string|in:0,1',
            'head_lamp' => 'required|string|in:0,1',
            'tyres_condition' => 'required|string|in:0,1',
            'maintenance' => 'required|string|in:0,1',
            'other_damages' => 'required|string',
            'branch_id' => 'required|int',
            'inspection_place' => 'required|string',
        ]);

        $client = Client::where('id', '=', $fields['requested_by'])->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }

        $clientBranch = ClientBranch::where('id', '=', $fields['branch_id'])->where('client_id', '=', $fields['requested_by'])->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client Branch does not exist.');
        }

        $arrJobDetail = [
            'vehicle_class' => $fields['vehicle_class'],
            'registration_date' => $fields['registration_date'],
            'type_of_body' => $fields['type_of_body'],
            'manufactoring_year' => $fields['manufactoring_year'],
            'maker' => $fields['maker'],
            'model' => $fields['model'],
            'chassis_no' => $fields['chassis_no'],
            'engine_no' => $fields['engine_no'],
            'rc_status' => $fields['rc_status'],
            'seating_capacity' => $fields['seating_capacity'],
            'issuing_authority' => $fields['issuing_authority'],
            'fuel_type' => $fields['fuel_type'],
            'color' => $fields['color'],
            'odometer_reading' => $fields['odometer_reading'],
            'fitness_valid_upto' => $fields['fitness_valid_upto'],
            'laden_weight' => $fields['laden_weight'],
            'unladen_weight' => $fields['unladen_weight'],
            'requested_value' => $fields['requested_value'],
            'other_damages' => $fields['other_damages'],
            'engine_transmission' => $fields['engine_transmission'],
            'electrical_gadgets' => $fields['electrical_gadgets'],
            'right_side' => $fields['right_side'],
            'left_body' => $fields['left_body'],
            'front_body' => $fields['front_body'],
            'back_body' => $fields['back_body'],
            'load_body' => $fields['load_body'],
            'all_glass_condition' => $fields['all_glass_condition'],
            'cabin_condition' => $fields['cabin_condition'],
            'head_lamp' => $fields['head_lamp'],
            'tyres_condition' => $fields['tyres_condition'],
            'maintenance' => $fields['maintenance'],
        ];

        $job->vehicle_reg_no = $fields['vehicle_reg_no'];
        $job->owner_name = $fields['owner_name'];
        $job->address = $fields['address'];
        $job->contact_mobile_no = $fields['contact_mobile_no'];
        $job->requested_by = $fields['requested_by'];
        $job->branch_id = $fields['branch_id'];
        $job->inspection_place = $fields['inspection_place'];
        $job->update();

        $job_images = $request->input('job_images');
        if ($job_images != null & $job_images != '') {
            $job_images = json_decode($job_images);
            foreach ($job_images as $row) {
                JobFile::where([
                    ['type', '=', $row->type],
                    ['job_id', '=', $row->job_id],
                    ['id', '=', $row->id],
                ])->update([
                    'name' => $row->name,
                ]);
            }
        }


        /*govind*/
        $logged_in_id = Auth::user()->id;
        if ($request->user()->tokenCan('type:employee')) {
            $parent_admin_id = getAdminIdIfEmployeeLoggedIn($logged_in_id);
        } else {
            $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);
        }
        if (isset($arrJobDetail['issuing_authority'])) {
            $issuingAuthority = VehicleIssuingAuthority::where('name', '=', $arrJobDetail['issuing_authority'])->where('created_by_id', '=', $parent_admin_id)->first();
            if (is_null($issuingAuthority)) {
                $issuingAuthority = VehicleIssuingAuthority::create([
                    'name' => $arrJobDetail['issuing_authority'],
                    'created_by_id' => $parent_admin_id,
                ]);
            }
        }

        if (isset($arrJobDetail['maker'])) {
            $vehicleMaker = VehicleMakers::where('name', '=', $arrJobDetail['maker'])->where('created_by_id', '=', $parent_admin_id)->first();
            if (is_null($vehicleMaker)) {
                $vehicleMaker = VehicleMakers::create([
                    'name' => $arrJobDetail['maker'],
                    'created_by_id' => $parent_admin_id,
                ]);
            }

            if (isset($arrJobDetail['model'])) {
                $seating_capacity = 0;
                if (isset($arrJobDetail['seating_capacity'])) {
                    $seating_capacity = $arrJobDetail['seating_capacity'];
                }
                $vehicleVarientCount = VehicleVarient::where('name', '=', $arrJobDetail['model'])->where('maker_id', '=', $vehicleMaker->id)->where('created_by_id', '=', $parent_admin_id)->count();
                if ($vehicleVarientCount == 0) {
                    VehicleVarient::create([
                        'name' => $arrJobDetail['model'],
                        'maker_id' => $vehicleMaker->id,
                        'created_by_id' => $parent_admin_id,
                        'seats' => $seating_capacity,
                    ]);
                }
            }
        }
        if (isset($arrJobDetail['type_of_body'])) {
            $vehicleBody = VehicleBody::where('name', '=', $arrJobDetail['type_of_body'])->where('created_by_id', '=', $parent_admin_id)->first();
            if (is_null($vehicleBody)) {
                $vehicleBody = VehicleBody::create([
                    'name' => $arrJobDetail['type_of_body'],
                    'created_by_id' => $parent_admin_id,
                ]);
            }
        }
        if (isset($arrJobDetail['vehicle_class'])) {
            $vehicleClass = VehicleClass::where('name', '=', $arrJobDetail['vehicle_class'])->where('created_by_id', '=', $parent_admin_id)->first();
            if (is_null($vehicleClass)) {
                $vehicleClass = VehicleClass::create([
                    'name' => $arrJobDetail['vehicle_class'],
                    'created_by_id' => $parent_admin_id,
                ]);
            }
        }
        if (isset($arrJobDetail['color'])) {
            $vehicleColor = VehicleColors::where('name', '=', $arrJobDetail['color'])->where('created_by_id', '=', $parent_admin_id)->first();
            if (is_null($vehicleColor)) {
                $vehicleColor = VehicleColors::create([
                    'name' => $arrJobDetail['color'],
                    'created_by_id' => $parent_admin_id,
                ]);
            }
        }

        /*govind*/


        $jobDetail = JobDetail::where('job_id', $id)->update($arrJobDetail);

        $jobDetail = new JobDetailResource(JobDetail::where('job_id', $id)->first());

        $job->images = JobFile::where('job_id', $id)->get();
        $job->job_detail = $jobDetail;

        return $this->sendResponse(new JobResource($job), 'Modifications
        saved successfully.');

    }


    /**
     * Gerenarte Report of specific resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function finalizeReport(Request $request, $id)
    {


        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);


        $admin = Admin::where('id', '=', $parent_admin_id)->where('status', '!=', '2')->first();
        if (is_null($admin)) {
            return $this->sendError('Admin does not exist.');
        }

        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }


        if ($job->job_status != 'approved') {
            //return $this->sendError('Bad request.');
        }

        $letter_head_img = "";
        if (isset($admin->letter_head_img) && $admin->letter_head_img != "") {
            $letter_head_img = config('app.imageurl') . "" . $admin->letter_head_img;
        }

        $letter_footer_img = "";
        if (isset($admin->letter_footer_img) && $admin->letter_footer_img != "") {
            $letter_footer_img = config('app.imageurl') . "" . $admin->letter_footer_img;
        }

        $signature_img = "";
        if (isset($admin->signature_img) && $admin->signature_img != "") {
            $signature_img = config('app.imageurl') . "" . $admin->signature_img;
        }

        $photos_delete_date = date('Y-m-d', strtotime(date('Y-m-d') . " +" . $admin->duraton_delete_photo . " months"));

        $report_no_start_from = $admin->report_no_start_from;

        $jobForReportNo = Job::where('parent_admin_id', '=', $parent_admin_id)->orderBy('job_report_no', 'desc')->first();
        if (isset($jobForReportNo)) {
            $report_no_start_from = $jobForReportNo->job_report_no + 1;
        }

        $job->completed_at = date('Y-m-d H:i:s');
        $job->completed_by = $logged_in_id;
        $job->job_status = "finalized";
        $job->job_report_no = $report_no_start_from;
        $job->photos_delete_date = $photos_delete_date;

        $job->update();

        $job->letter_head_img = $letter_head_img;
        $job->letter_footer_img = $letter_footer_img;
        $job->signature_img = $signature_img;
        $job->reference_no_prefix = $admin->reference_no_prefix;
        $job->authorized_person_name = $admin->authorized_person_name;
        $job->designation = $admin->designation;


        JobTransactionHistory::create([
            'job_id' => $job->id,
            'status' => 'finalized',
            'user_type' => 'Admin',
            'user_id' => $logged_in_id,
            'on_date_time' => date('Y-m-d H:i:s'),
        ]);

        $job->jobdetail = JobDetail::where('job_id', $id)->first();
        $job->images = JobFileResouce::collection(JobFile::where('job_id', $id)->get());
        $job->client = Client::where('id', $job->requested_by)->where('parent_admin_id', '=', $parent_admin_id)->first();
        $job->other_images = JobFileResouce::collection(JobFile::where('job_id', $id)->where('type', 'Other')->get());

        $job->contact_person = BranchContactPerson::where('id', '=', $job->contact_person_id)->first();


        view()->share('invoice', $job);

        $newFilename = 'job_finalize_report_' . $id . '.pdf';
        $newFilenamedraft = 'job_finalize_report_draft_' . $id . '.pdf';

        PDF::loadView('invoice', array('job' => $job, "draft" => false), [], [
            'watermark' => "CONFIDENTIAL",
            'show_watermark' => true,
            'margin_header' => 0,
            'watermark_text_alpha' => 0.03
        ])->save('storage/app/public/pdf_report/' . $newFilename);

        PDF::loadView('invoice', array('job' => $job, "draft" => true), [], [
            'watermark' => "DRAFT",
            'show_watermark' => true,
            'margin_header' => 0,
            'watermark_text_alpha' => 0.03
        ])->save('storage/app/public/pdf_report/' . $newFilenamedraft);

        return $this->sendResponse(new JobResource($job), 'Report generated successfully.', 200);

    }


    /**
     * Reactive of specified resource in storage.
     *
     * @param int $id
     * @param String $status
     * @return \Illuminate\Http\Response
     */
    public function reactiveJob(Request $request, $id)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }
        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);

        $validator = Validator::make(
            [
                'id' => $id,
            ],
            [
                'id' => ['required']
            ]
        );

        if ($validator->fails()) {
            return $this->sendLaravelFormatError("The given data was invalid", $validator->errors());
        }
        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }
        if ($job->job_status != 'finalized') {
            return $this->sendError('Bad request.');
        }

        $job->job_status = 'approved';
        $job->update();
        JobTransactionHistory::create([
            'job_id' => $job->id,
            'status' => 'approved',
            'user_type' => 'Admin',
            'user_id' => $logged_in_id,
            'on_date_time' => date('Y-m-d H:i:s'),
        ]);
        return $this->sendResponse(new JobResource($job), 'Status change successfully.', 200);
    }

    /**
     * Send finalize job report in mail
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function sendReportInEmail(Request $request)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);


        $fields = $request->validate([
            'job_id' => 'required|int',
        ]);

        $id = $fields['job_id'];

        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }

        if ($job->job_status != 'finalized') {
            return $this->sendError('Bad request.');
        }

        $client = Client::where('id', '=', $job->requested_by)->where('parent_admin_id', '=', $parent_admin_id)->where('status', '!=', '2')->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }

        $clientBranch = ClientBranch::where('id', '=', $job->branch_id)->where('parent_admin_id', '=', $parent_admin_id)->where('status', '!=', '2')->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client Branch does not exist.');
        }


        $employee = Employee::where('id', '=', $job->submitted_by)->where('parent_admin_id', '=', $parent_admin_id)->where('status', '!=', '2')->first();
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }

        $superAdmin = Admin::where('id', '=', $parent_admin_id)->where('status', '!=', '2')->first();
        if (is_null($superAdmin)) {
            return $this->sendError('Admin does not exist.');
        }


        $admin = Admin::where('id', '=', $logged_in_id)->where('status', '!=', '2')->first();
        if (is_null($admin)) {
            return $this->sendError('Admin does not exist.');
        }

        $contact_person_list = BranchContactPerson::where('id', '=', $job->contact_person_id)->where('parent_admin_id', '=', $parent_admin_id)->get();

        $arrEmails = array();
        foreach ($contact_person_list as $contactPerson) {
            $strEmail = $contactPerson->email;
            $arrTempEmails = explode(",", $strEmail);
            foreach ($arrTempEmails as $mailObj) {
                if (trim($mailObj) != "") {
                    $arrEmails[] = trim($mailObj);
                }
            }
        }

        $path = storage_path('app/public/pdf_report/job_finalize_report_' . $id . '.pdf');

        $subject = "Motor Valuation report of Vehicle Regn no. " . $job->vehicle_reg_no . " against Job No. " . $id;

        $primaryEmail = "";

        if ($employee->email != "") {

            if ($primaryEmail == "") {
                $primaryEmail = $employee->email;
            } else {
                $arrEmails[] = $employee->email;
            }


        }

        if ($clientBranch->email != "") {

            if ($primaryEmail == "") {
                $primaryEmail = $clientBranch->email;
            } else {
                $arrEmails[] = $clientBranch->email;
            }

        }

        if ($primaryEmail == "") {
            $primaryEmail = $arrEmails[0];
        }

        // Mail Send To Client
        $maildata = [
            'todayDate' => date("F j, Y"),
            'subject' => $subject,
            'reportUrl' => $path,
            'from_name' => config('app.from_name'),
            'fullName' => $clientBranch->contact_person_name,
            'vehicleRegnNo' => $job->vehicle_reg_no,
            'jobNo' => $id,
            'superAdminName' => $superAdmin->name,
            'adminEmail' => $admin->email,
            'contactEmail' => config('app.technical_support_email'),
            'mail_template' => "emails.job-report-email"
        ];

        $job->mail_sent_to_client = '1';
        $job->mail_sent_to_employee = '1';
        $job->mail_sent_date = date('Y-m-d H:i:s');
        $job->mail_send_by = $logged_in_id;
        $job->mail_sent_cnt = $job->mail_sent_cnt + 1;
        $job->update();
        SendMail::dispatch($primaryEmail, $maildata, $arrEmails)->onQueue('send-email')->onConnection('database');
        return $this->sendResponse(array(), 'Mail sent successfully.', 200);

    }

    /**
     * Send Payment Link
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function sendPaymentLink(Request $request)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);


        $fields = $request->validate([
            'job_id' => 'required|int',
        ]);

        $id = $fields['job_id'];

        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }

        $client = Client::where('id', '=', $job->requested_by)->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }

        $clientBranch = ClientBranch::where('id', '=', $job->branch_id)->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client Branch does not exist.');
        }

        if ($clientBranch->mode_of_payment == "Prepaid") {
            if ($job->job_status == 'created') {
                return $this->sendError('Bad request.');
            }
        } else {
            if ($job->job_status != 'finalized') {
                return $this->sendError('Bad request.');
            }
        }

        $settings = AdminSettings::where('type', '=', 'razorpay')->where('amdin_id', '=', $parent_admin_id)->first();
        if (is_null($settings)) {
            return $this->sendError('Something went wrong');
        }
        $razorPaymentSettings = json_decode($settings->message);

        $api = new Api($razorPaymentSettings->apikey, $razorPaymentSettings->secret);

        $host = request()->headers->get('origin');
        $CALL_BACK_URL = $host . config('global.razor_payment_thankyou_page') . "job";

        $expiryDate = date('Y-m-d H:i:s');
        $expiryDate = strtotime($expiryDate . " + 1 day");
        $paymentReferenceId = generateRandomString();
        $amountToPay = $clientBranch->amount_per_job * 100;
        $description = "Payment for Job Id #" . $job->id;

        $response = $api->paymentLink->create(
            array(
                'amount' => $amountToPay,
                'currency' => 'INR',
                'accept_partial' => false,
                'description' => $description,
                'reference_id' => $paymentReferenceId,
                'expire_by' => $expiryDate,
                'customer' => array(
                    'name' => $client->name . ' (' . $clientBranch->name . ')',
                    'email' => $clientBranch->email,
                    'contact' => '+91' . $clientBranch->mobile_no),
                'notify' => array('sms' => true, 'email' => true),
                'reminder_enable' => true,
                'notes' =>
                    array('job_id' => $job->id, 'client_id' => $client->id, 'branch_id' => $clientBranch->id),
                'callback_url' => $CALL_BACK_URL,
                'callback_method' => 'get',
                'options' => array('checkout' => array('name' => 'Moval'))
            ));
        if (!is_null($response)) {
            if (!isset($response->id)) {
                return $this->sendError('Unable to generate payment link.');
            }

            $paymentHistory = PaymentHistory::create([
                'job_id' => $job->id,
                'parent_admin_id' => $parent_admin_id,
                'client_id' => $client->id,
                'branch_id' => $clientBranch->id,
                'payment_link_reference_id' => $response->reference_id,
                'payment_link_id' => $response->id,
                'payment_link' => $response->short_url,
            ]);

            $job->payment_link_tracking_id = $paymentHistory->id;
            $job->payment_link_send_date = date('Y-m-d H:i:s');
            $job->update();
        }

        return $this->sendResponse(array(), 'Payment link sent successfully.', 200);

    }

    /**
     *  Verify Payment
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function verifyPayment(Request $request)
    {
        $fields = $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_payment_link_id' => 'required|string',
            'razorpay_payment_link_reference_id' => 'required|string',
            'razorpay_payment_link_status' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $paymentHistory = PaymentHistory::where('payment_link_id', '=', $fields['razorpay_payment_link_id'])
            ->where('payment_link_reference_id', '=', $fields['razorpay_payment_link_reference_id'])->first();
        if (is_null($paymentHistory)) {
            return $this->sendError('Bad Request');
        }

        $settings = AdminSettings::where('type', '=', 'razorpay')->where('amdin_id', '=', $paymentHistory->parent_admin_id)->first();
        if (is_null($settings)) {
            return $this->sendError('Something went wrong');
        }

        $razorPaymentSettings = json_decode($settings->message);
        $api = new Api($razorPaymentSettings->apikey, $razorPaymentSettings->secret);
        $attributes = array('razorpay_payment_link_id' => $fields['razorpay_payment_link_id'],
            'razorpay_payment_link_reference_id' => $fields['razorpay_payment_link_reference_id'],
            'razorpay_payment_link_status' => $fields['razorpay_payment_link_status'],
            'razorpay_payment_id' => $fields['razorpay_payment_id'],
            'razorpay_signature' => $fields['razorpay_signature']);

        try {
            $api->utility->verifyPaymentSignature($attributes);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        $paymentHistory->callback_signature = $fields['razorpay_signature'];
        $paymentHistory->link_status = $fields['razorpay_payment_link_status'];
        $paymentHistory->payment_id = $fields['razorpay_payment_id'];
        $paymentHistory->update();

        return $this->sendResponse(array(), 'Verified.', 200);
    }

    /**
     *  Job Payment Webhook
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateJobPaymentStatus(Request $request)
    {

        $payload = $request->all();

        if ((!isset($payload['entity'])) || $payload['entity'] != 'event') {
            //Unknown webhook as currrently configured to support only events
            return $this->sendError("Unknown request");
        }

        $paymentStatus = '';
        if ($payload['event'] == "payment_link.paid") {
            $paymentStatus = 'completed';
        } else if ($payload['event'] == "payment_link.cancelled") {
            $paymentStatus = 'rejected';
        } else if ($payload['event'] == "payment_link.expired") {
            $paymentStatus = 'expired';
        }

        if ($paymentStatus != '') {
            $paymentLinkId = $payload['payload']['payment_link']['entity']['id'];

            $paymentHistory = PaymentHistory::where('payment_link_id', '=', $paymentLinkId)->first();
            if (is_null($paymentHistory)) {
                return $this->sendError('No records found for payment');
            }

            $settings = AdminSettings::where('type', '=', 'razorpay')->where('amdin_id', '=', $paymentHistory->parent_admin_id)->first();
            if (is_null($settings)) {
                return $this->sendError('Something went wrong');
            }

            $razorPaymentSettings = json_decode($settings->message);
            $api = new Api($razorPaymentSettings->apikey, $razorPaymentSettings->secret);
            $webhookSecret = $razorPaymentSettings->job_payment_webhook_secret;
            $webhookSignature = $request->header('X-Razorpay-Signature');


            try {
                $api->utility->verifyWebhookSignature($payload, $webhookSignature, $webhookSecret);
            } catch (\Exception $e) {
                //return $this->sendError($e->getMessage());
            }

            $paymentHistory->payment_status = $paymentStatus;

            if ($paymentStatus == 'completed') {
                $orderId = $payload['payload']['order']['entity']['id'];
                $paymentLinkId = $payload['payload']['payment_link']['entity']['id'];
                $paymentHistory->order_id = $orderId;
                $paymentHistory->payment_id = $paymentLinkId;
                $paymentHistory->link_status = "paid";

                $job = Job::where('id', '=', $paymentHistory->job_id)->first();
                if (!is_null($job)) {
                    $job->payment_status = 'completed';
                    $job->update();
                }
            }
            $paymentHistory->update();

            return $this->sendResponse(array(), 'Success.', 200);
        } else {
            return $this->sendError("Invalid payment status");
        }

    }


    /**
     * Updat status of specified resource in storage.
     *
     * @param int $id
     * @param String $status
     * @return \Illuminate\Http\Response
     */
    public function updateJobRemark(Request $request, $id)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);


        $fields = $request->validate([
            'remark' => 'required|string',
        ]);

        $adminRemark = $fields['remark'];

        $validator = Validator::make(
            [
                'id' => $id,
            ],
            [
                'id' => ['required', 'int']
            ]);


        if ($validator->fails()) {
            return $this->sendLaravelFormatError("The given data was invalid", $validator->errors());
        }

        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }

        if ($job->job_status == 'approved' || $job->job_status == 'rejected') {
            $job->admin_remark = $adminRemark;
            $job->update();
            return $this->sendResponse(new JobResource($job), 'Remark updated.', 200);
        } else {
            return $this->sendError('Bad Request.');
        }
    }

    /**
     * Upload Image or Video of specified resource in storage.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateJobImage(Request $request, $id)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);


        $job = Job::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($job)) {
            return $this->sendError('Job does not exist.');
        }

        $arrJobsUpdateStatus = array("approved");

        if (!in_array($job->job_status, $arrJobsUpdateStatus)) {
            return $this->sendError('Bad Request');
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:png,jpg,jpeg,mp4,mov|max:10240',
            'type' => 'required|string|in:Chassis Number,Front View,Rear View,Right Side,Left Side,Odometer,Other',
            'image_id' => 'required|int'
        ]);

        if ($validator->fails()) {
            return $this->sendLaravelFormatError("The given data was invalid", $validator->errors());
        }

        $imageId = $request->input('image_id');
        $imageType = $request->input('type');


        $jobFile = JobFile::where('id', '=', $imageId)->where('job_id', '=', $id)->where('type', '=', $imageType)->first();
        if (is_null($jobFile)) {
            return $this->sendError('Job File does not exist.');
        }


        if ($file = $request->file('file')) {
            $path = $file->store('job_files', 'public');
            $name = $file->getClientOriginalName();

            $imagePath = public_path('storage/' . $jobFile->name);
            if (File::exists($imagePath)) {
                unlink($imagePath);
            }

            $jobFile->name = $path;
            $jobFile->update();

            return $this->sendResponse(array('name' => $path), 'File uploaded successfully.');

        }
    }

}
