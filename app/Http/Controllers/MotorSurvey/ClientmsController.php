<?php

namespace App\Http\Controllers\MotorSurvey;

use Illuminate\Http\Request;
use App\Models\Clientms;
use App\Models\ClientBranch;
use App\Models\Employeems;
use App\Models\ClientBranchMs;
use App\Models\Job;
use App\Models\BranchContactPersonms;
use App\Models\Admin_ms;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use App\Http\Resources\clientms\ClientmsResource;
use App\Http\Resources\clientms\ClientmsResourceBranch;
use App\Http\Resources\client\ClientmsCollection;
use App\Jobs\SendMail;
use Illuminate\Support\Facades\Validator;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class ClientmsController extends BaseController
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


        $logged_in_id = Auth::user()->id;
        if ($request->user()->tokenCan('type:employee')) {
            $parent_admin_id = getAdminIdIfEmployeeLoggedIn1($logged_in_id);
        } else {
            $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);
        }
        $main_admin_id = getmainAdminIdIfAdminLoggedIn($parent_admin_id);


        $TBL_ADMIN = 'tbl_ms_admin';
        $TBL_CLIENT = "tbl_ms_clients";

        $allClients = Clientms::join($TBL_ADMIN . ' as created_by_admin', 'created_by_admin.id', '=', 'created_by')
            ->join($TBL_ADMIN . ' as modified_by_admin', 'modified_by_admin.id', '=', 'modified_by')
            ->orderBy('created_at', 'desc')
            // ->where($TBL_CLIENT.'.admin_id','=',$main_admin_id)
            ->select('created_by_admin.name as created_by_admin', 'modified_by_admin.name as modified_by_admin', $TBL_CLIENT . '.*');

        $reqeustType = $request->input('request_type');
        if ($reqeustType != null && $reqeustType == 'drop_down') {
            $allClients = $allClients->where($TBL_CLIENT . '.status', '=', '1');
        } else {
            $allClients = $allClients->where($TBL_CLIENT . '.status', '!=', '2');
        }


        $searchKeyword = "";
        if ($request->exists('search_keyword')) {
            $searchKeyword = $request->input('search_keyword');
            if ($searchKeyword != '') {
                $allClients = $allClients->where(function ($query) use ($TBL_CLIENT, $searchKeyword) {
                    $query->orWhere($TBL_CLIENT . '.name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere($TBL_CLIENT . '.email', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere($TBL_CLIENT . '.registered_mobile_no', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere($TBL_CLIENT . '.mobile_no', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere($TBL_CLIENT . '.address', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere($TBL_CLIENT . '.id', 'LIKE', '%' . $searchKeyword . '%');
                });
            }
        }

        if (Auth::user()->role == 'admin') {

            $wh2 = [$TBL_CLIENT . '.parent_admin_id' => $logged_in_id];

            $allClients = $allClients->where(function ($query) use ($wh2, $TBL_CLIENT, $logged_in_id) {
                $query->where($wh2)
                    ->orWhere(function ($query) use ($TBL_CLIENT, $logged_in_id) {
                        $query->where($TBL_CLIENT . '.parent_admin_id', '!=', $logged_in_id)
                            ->whereHas('admin', function ($query) use ($logged_in_id) {
                                $query->where('parent_id', $logged_in_id);
                            });
                    });
            });

        }
        //   else if (Auth::user()->role == 'branch_admin') {

        //     $logged_in_id = Auth::user()->parent_id;

        //     $wh2 = [$TBL_CLIENT . '.admin_branch_id' => $logged_in_id];

        //     $allClients = $allClients->Where($wh2);


        // }
        else {

            $main_admin_id = getmainAdminIdIfAdminLoggedIn($logged_in_id);
            $admin_branch_id = getBranchidbyAdminid($logged_in_id);
            $wh2 = [$TBL_CLIENT . '.admin_id' => $main_admin_id, $TBL_CLIENT . '.admin_branch_id' => $admin_branch_id];
            //  $allClients = $allClients->Where($wh2);
            $allClients = $allClients->where([$TBL_CLIENT . '.admin_id' => Auth::user()->id])->orWhere([$TBL_CLIENT . '.admin_branch_id' => $admin_branch_id]);
        }


        $allClients1['data'] = $request->exists('all')
            ? ClientmsResource::collection($allClients->get())
            : ClientmsResource::collection($allClients->paginate(10));

        if (!$request->exists('all')) {
            $alldata = $allClients->paginate(10);
            $allClients1['pagination'] =
                [
                    'total' => $alldata->total(),
                    'count' => $alldata->count(),
                    'per_page' => $alldata->perPage(),
                    'current_page' => $alldata->currentPage(),
                    'total_pages' => $alldata->lastPage(),
                ];
        }

        return $this->sendResponse($allClients1, 'client fetched.' . Auth::user()->role);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);


        $TBL_CLIENT_BRANCHES = "tbl_ms_client_branches";

        $fields = $request->validate([
            'client_name' => 'required|string',
            'contact_details' => 'required',
            'client_address' => 'required|string',
            'office_name' => 'required|string',
            'office_code' => 'required|string',
            'office_address' => 'required',
            'gst_no' => 'nullable|string',
            'contact_detail' => 'nullable|string',
            'admin_branch_id' => 'required|numeric',
            'manager_name' => 'nullable|string',
            'manager_email' => '',
            'manager_mobile_no' => 'nullable|string',
            'within_state' => 'required|string',
            'mode_of_payment' => 'required|string|in:Prepaid,Postpaid',
            'amount_per_job' => 'required|numeric|min:0',
        ]);
        $main_admin_id = getmainAdminIdIfAdminLoggedIn($logged_in_id);


        $client = Clientms::create([
            'client_name' => $fields['client_name'],
            'client_address' => $fields['client_address'],
            'contact_details' => $fields['contact_details'], // Client Contact Number
            'status' => '1',
            'parent_admin_id' => $parent_admin_id,
            'admin_id' => Auth::user()->id,
            'admin_branch_id' => $fields['admin_branch_id'],
            'created_by' => $logged_in_id,
            'modified_by' => $logged_in_id,
            'bank_id' => (isset($request->bank_id) && !empty($request->bank_id)) ? $request->bank_id : null
        ]);

        $cl_id = $client->id;

        $admin_branch_id = ClientBranchMs::create([
            'client_id' => $cl_id,
            'office_name' => $fields['office_name'],
            'office_code' => $fields['office_code'],
            'office_address' => $fields['office_address'],
            'gst_no' => $fields['gst_no'],
            'contact_detail' => $fields['contact_detail'], // Branch Contact No
            'manager_name' => $fields['manager_name'],
            'manager_email' => $fields['manager_email'],
            'manager_mobile_no' => $fields['manager_mobile_no'], // Manager Mobile No
            'within_state' => $fields['within_state'], // Manager Mobile No
            'mode_of_payment' => $fields['mode_of_payment'],
            'amount_per_job' => $fields['amount_per_job'],
            'parent_admin_id' => $parent_admin_id,
            'status' => '1',
        ]);


        $email = $fields['manager_email'];
        $maildata = [
            'subject' => 'Welcome to the Moval',
            'from_name' => config('app.from_name'),
            'fullName' => $fields['client_name'],
            'contactEmail' => config('app.from_email_address'),
            'mail_template' => "emails.client-welcome-email"
        ];

        SendMail::dispatch($email, $maildata)->onQueue('send-email');

        return $this->sendResponse(new ClientmsResource($client), "success", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);
        if (!$request->exists('all')) {
            $client = Clientms::where('id', '=', $id)->where('status', '!=', '2')->whereNull('deleted_at')->where('parent_admin_id', '=', $parent_admin_id)->first()->paginate(10);
        }
        if (!$request->exists('all')) {
            $data['pagination'] =
                [
                    'total' => $client->total(),
                    'count' => $client->count(),
                    'per_page' => $client->perPage(),
                    'current_page' => $client->currentPage(),
                    'total_pages' => $client->lastPage(),
                ];
        }
        $client = Clientms::where('id', '=', $id)->where('status', '!=', '2')->whereNull('deleted_at')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }
        $data['client'] = new ClientmsResource($client);

        return $this->sendResponse($data, 'Client fetched.');
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
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);

        //   $TBL_CLIENT   = config('global.client_table');
        if (!$parent_admin_id) {
            return $this->sendError('Unauthorised.', ['error' => 'Admin do not exists'], 401);
        }
        $client = Clientms::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }

        $update_by = Auth::user()->id;

        $fields = $request->validate([
            'client_name' => 'required|string',
            'client_address' => 'required|string',
            'contact_details' => 'required|string', // Client Contact Number
            'admin_branch_id' => 'required|numeric', // Client branch id

            //'contact_person_name' => 'required|string',
            //'email' => 'required|string|unique:'.$TBL_CLIENT.',email,'.$id,
            //'registered_mobile_no' => 'required|numeric|digits:10',
            // 'mode_of_payment' => 'required|string|in:Prepaid,Postpaid',
            // 'amount_per_job' => 'required|numeric|min:0',
        ]);

        $client->client_name = $fields['client_name'];
        $client->client_address = $fields['client_address'];
        $client->contact_details = $fields['contact_details'];
        $client->modified_by = $update_by;
        $client->admin_branch_id = $fields['admin_branch_id'];
        $client->bank_id = (isset($request->bank_id) && !empty($request->bank_id)) ? $request->bank_id : null;
        $client->update();

        return $this->sendResponse(new ClientmsResource($client), 'Client updated.', 200);
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
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);

        $client = Clientms::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }

        $clientCount = 0;
        // $clientCount += Job::where('requested_by','=', $id)->count();

        //   if ($clientCount > 0){
        //      return $this->sendError('Client account in use.');
        //  }

        $client->delete();

        return $this->sendResponse([], 'Client delete successfully.', 200);
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

        $validator = Validator::make(
            [
                'id' => $id,
                'status' => $status,
            ],
            [
                'id' => ['required'],
                'status' => [
                    'required', 'in:active,inactive'
                ],
            ]
        );


        if ($validator->fails()) {
            return $this->sendLaravelFormatError("The given data was invalid", $validator->errors());
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);

        $client = Clientms::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }

        $client->status = $status == 'active' ? '1' : '0';
        $client->update();
        return $this->sendResponse(new ClientmsResource($client), 'Status change successfully.', 200);
    }


    public function client_contact_store(Request $request)
    {


        $fields = $request->validate([
            'name' => 'required|string',
            'designation' => 'required|string',
            'email' => 'required|string',
            'mobile_no' => 'required|string',
            'landline_no' => 'required|string',
            'client_branch_id' => 'required|numeric',
        ]);

        $logged_in_id = Auth::user()->id;
        //   $parent_admin_id = getAdminIdIfEmployeeLoggedIn1($logged_in_id);


        $contact_data = array(
            'name' => $fields['name'],
            'designation' => $fields['designation'],
            'email' => $fields['email'],
            'mobile_no' => $fields['mobile_no'],
            'landline_no' => $fields['landline_no'],
            'client_branch_id' => $fields['client_branch_id'],
            'status' => 1,
            'created_by' => $logged_in_id,
            //  'parent_admin_id' =>$parent_admin_id,
            'modified_by' => $logged_in_id,
        );

        //  print_r($contact_data); die;

        $admin_branch_id = BranchContactPersonms::create($contact_data);

        if ($admin_branch_id) {
            return $this->sendResponse('Contact Branch Created.', ['result' => 1], 200);
        } else {
            return $this->sendError('Failed to Create.', ['error' => 'Failed to Create.'], 401);
        }
    }


    public function client_contact_update(Request $request, $id)
    {


        $fields = $request->validate([
            'name' => 'required|string',
            'designation' => 'required|string',
            'email' => 'required|string',
            'mobile_no' => 'required|string',
            'landline_no' => 'required|string',
            'client_branch_id' => 'required|numeric',
        ]);

        $logged_in_id = Auth::user()->id;
        //   $parent_admin_id = getAdminIdIfEmployeeLoggedIn1($logged_in_id);


        $contact_data = array(
            'name' => $fields['name'],
            'designation' => $fields['designation'],
            'email' => $fields['email'],
            'mobile_no' => $fields['mobile_no'],
            'landline_no' => $fields['landline_no'],
            'client_branch_id' => $fields['client_branch_id'],
            'status' => 1,
            'created_by' => $logged_in_id,
            //  'parent_admin_id' =>$parent_admin_id,
            'modified_by' => $logged_in_id,
        );

        //  print_r($contact_data); die;

        $admin_branch_id = BranchContactPersonms::where('id', $id)->update($contact_data);

        //  $admin_branch_id = BranchContactPersonms::create($contact_data);

        if ($admin_branch_id) {
            return $this->sendResponse('Contact Branch Updated.', ['result' => 1], 200);
        } else {
            return $this->sendError('Failed to Update.', ['error' => 'Failed to Update.'], 401);
        }
    }


    public function client_contact_delete(Request $request, $id)
    {
        BranchContactPersonms::where('id', $id)->delete();
        return $this->sendResponse('Contact Branch Deleted.', ['result' => 1], 200);
    }


    public function client_contact_getbyid(Request $request, $id)
    {

        $data = BranchContactPersonms::where('id', $id)->first();


        if (!$data) {
            return $this->sendResponse('error', "invalid contact id", 201);
        }
        $data['id'] = $id;

        return $this->sendResponse(new ClientmsResourceBranch($data), 'Contact List.', 200);

        // return $this->sendResponse('Contact Branch Feteched.', ['result'=>$data],200);
    }


    public function client_contact_getbybranchid(Request $request, $id)
    {

        $clientcontact = BranchContactPersonms::where('client_branch_id', $id)->get();

        if (count($clientcontact) == 0) {
            return $this->sendResponse('error', "invalid contact id", 201);
        }

        $i = 0;
        foreach ($clientcontact as $sop_master) {
            $contactP = ClientBranchMs::where('id', $sop_master->client_branch_id)->first();

            $data[$i] = [
                'id' => $sop_master->id,
                'client_branch_id' => $sop_master->client_branch_id,
                'branch_name' => $contactP['office_name'],
                'name' => $sop_master->name,
                'designation' => $sop_master->designation,
                'email' => $sop_master->email,
                'mobile_no' => $sop_master->mobile_no,
                'landline_no' => $sop_master->landline_no,
                'status' => $sop_master->status,
                'updated_at' => $sop_master->updated_at,
                'created_at' => $sop_master->created_at,
                'parent_admin_id' => $sop_master->parent_admin_id,
                'created_by' => $sop_master->created_by,
                'modified_by' => $sop_master->modified_by,
            ];
            $i++;
        }
        return $this->sendResponse($data, 'Branch Contact Person Fetched Here', 200);

    }


    public function client_contact_getall(Request $request)
    {

        $logged_in_id = Auth::user()->id;
        $page = ($request->exists('all') || (isset($request->page) && $request->page == 'all')) ? true : false;
        if (!empty($page)) {
            $contactperson = BranchContactPersonms::where('created_by', '=', $logged_in_id)->get();
        } else {
            $contactperson = BranchContactPersonms::paginate(10);
        }

        if (count($contactperson) == 0) {
            return $this->sendResponse('error', "invalid contact id", 201);
        } else {
            foreach ($contactperson as $i => $emp_details) {
                $data[$i] = [
                    'id' => $emp_details->id,
                    'client_branch_id' => $emp_details->client_branch_id,
                    'name' => $emp_details->name,
                    'designation' => $emp_details->designation,
                    'email' => $emp_details->email,
                    'mobile_no' => $emp_details->mobile_no,
                    'landline_no' => $emp_details->landline_no,
                    'status' => $emp_details->status,
                    'created_at' => $emp_details->created_at,
                ];
            }
            return $this->sendResponse($data, 'Contact person List Fetched', 200);
        }
    }
}
