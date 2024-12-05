<?php

namespace App\Http\Controllers\MotorSurvey;

use App\Http\Controllers\BaseController;
use App\Models\Clientms;
use App\Models\ClientBranchMs;
use App\Models\BranchResourcems;
use App\Models\Job;
use App\Http\Resources\client\ClientBrachResource;
use App\Http\Resources\client\ClientBranchResourcems;
use App\Http\Resources\client\ClientBrachCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ClientBranchMsController extends BaseController
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

        $fields = $request->validate([
            'client_id' => 'required|int',
        ]);

        $TBL_CLIENT_BRANCHES = "tbl_ms_client_branches";
        $TBL_CLIENT = "tbl_ms_clients";

        $main_admin_id = getmainAdminIdIfAdminLoggedIn($parent_admin_id);

        if ($request->user()->tokenCan('type:branch_contact')) {


            $allClientsBranches = ClientBranchMs::join($TBL_CLIENT . ' as client', 'client.id', '=', 'client_id')
                ->orderBy('created_at', 'desc')
                ->where($TBL_CLIENT_BRANCHES . '.client_id', '=', $fields['client_id'])
                ->select('client.client_name as client_name', $TBL_CLIENT_BRANCHES . '.*');

        } else {

            $allClientsBranches = ClientBranchMs::join($TBL_CLIENT . ' as client', 'client.id', '=', 'client_id')
                ->orderBy('created_at', 'desc')
                ->where($TBL_CLIENT_BRANCHES . '.client_id', '=', $fields['client_id'])
                ->select('client.client_name as client_name', $TBL_CLIENT_BRANCHES . '.*');
            // ->where($TBL_CLIENT_BRANCHES.'.parent_admin_id','=',$main_admin_id);
        }


        /* if($request->user()->tokenCan('type:admin'){
             $allClientsBranches
         } */
        // ->where($TBL_CLIENT_BRANCHES.'.parent_admin_id','=',$parent_admin_id)


        $reqeustType = $request->input('request_type');
        if ($reqeustType != null && $reqeustType == 'drop_down') {
            $allClientsBranches = $allClientsBranches->where($TBL_CLIENT_BRANCHES . '.status', '=', '1');
        } else {
            $allClientsBranches = $allClientsBranches->where($TBL_CLIENT_BRANCHES . '.status', '!=', '2');
        }

        $searchKeyword = "";
        if ($request->exists('search_keyword')) {
            $searchKeyword = $request->input('search_keyword');
            if ($searchKeyword != '') {
                $allClientsBranches = $allClientsBranches->where(function ($query) use ($TBL_CLIENT_BRANCHES, $searchKeyword) {
                    $query->orWhere($TBL_CLIENT_BRANCHES . '.name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere($TBL_CLIENT_BRANCHES . '.email', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere($TBL_CLIENT_BRANCHES . '.registered_mobile_no', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere($TBL_CLIENT_BRANCHES . '.mobile_no', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere($TBL_CLIENT_BRANCHES . '.address', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere($TBL_CLIENT_BRANCHES . '.id', 'LIKE', '%' . $searchKeyword . '%');
                });
            }
        }


        $allClientsBranches = $request->exists('all')
            ? ClientBranchResourcems::collection($allClientsBranches->get())
            : new ClientBrachCollection($allClientsBranches->paginate(10));

        /*  echo "<pre>";
          print_r($allClientsBranches); die;*/

        return $this->sendResponse($allClientsBranches, 'Clients Branch fetched.');


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        // registered_mobile_no field will be considerd as landline number from Phase 2
        $fields = $request->validate([
            'client_id' => 'required|int',
            'office_name' => 'required|string',
            'office_code' => 'required|string',
            'office_address' => 'required',
            'gst_no' => 'nullable|string',
            'contact_detail' => 'nullable|string',
            'manager_name' => 'nullable|string',
            'manager_email' => 'nullable|string',
            'manager_mobile_no' => 'nullable|string',
            'within_state' => 'required|string',
            'mode_of_payment' => 'required|string|in:Prepaid,Postpaid',
            'amount_per_job' => 'required|numeric|min:0',
        ]);


        $client = ClientBranchMs::create([
            'client_id' => $fields['client_id'],
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


        return $this->sendResponse(new ClientBrachResource($client), "success", 201);
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


        $clientBranch = ClientBranchMs::where('id', '=', $id)->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client Branch does not exist.');
        }
        return $this->sendResponse(new ClientBranchResourcems($clientBranch), 'Branch fetched.');
    }

    /**
     * Display the specified resource.
     *
     * @param string  Office_Code
     * @return \Illuminate\Http\Response
     */
    public function OfficebyCode(Request $request)
    {
        if (!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }

        $logged_in_id = Auth::user()->id;
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);


        $office_code = $request->Office_Code;

        $clientBranch = ClientBranchMs::where('office_code', '=', $office_code)->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client office does not belongs to you .');
        }


        $data = ['Office_Name' => $clientBranch->office_name,
            'Office_Addres' => $clientBranch->office_address];


        return $this->sendResponse($data, 'office detail fetched.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

        $TBL_CLIENT_BRANCHES = config('global.client_branch_table');

        $clientBranch = ClientBranchMs::where('id', '=', $id)->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client Branch does not exist.');
        }

        $fields = $request->validate([
            'client_id' => 'required|int',
            'office_name' => 'required|string',
            'office_code' => 'required|string',
            'office_address' => 'required',
            'gst_no' => 'nullable|string',
            'contact_detail' => 'nullable|string',
            'manager_name' => 'nullable|string',
            'manager_email' => 'nullable|string',
            'manager_mobile_no' => 'nullable|string',
            'within_state' => 'required|string',
            'mode_of_payment' => 'required|string|in:Prepaid,Postpaid',
            'amount_per_job' => 'required|numeric|min:0',
        ]);


        $clientBranch->client_id = $fields['client_id'];
        $clientBranch->office_name = $fields['office_name'];
        $clientBranch->office_code = $fields['office_code'];
        $clientBranch->office_address = $fields['office_address'];
        $clientBranch->gst_no = $fields['gst_no'];
        $clientBranch->contact_detail = $fields['contact_detail'];
        $clientBranch->manager_name = $fields['manager_name'];
        $clientBranch->manager_email = $fields['manager_email'];
        $clientBranch->manager_mobile_no = $fields['manager_mobile_no'];
        $clientBranch->within_state = $fields['within_state'];
        $clientBranch->mode_of_payment = $fields['mode_of_payment'];
        $clientBranch->amount_per_job = $fields['amount_per_job'];


        /*         $clientBranch->address = $fields['address'];
                $clientBranch->mobile_no = $fields['mobile_no'];

                $clientBranch->registered_mobile_no = $fields['registered_mobile_no'];
                $clientBranch->contact_person_name = $fields['contact_person_name'];
                $clientBranch->manager_email = $fields['manager_email'];
                $clientBranch->mode_of_payment = $fields['mode_of_payment'];
                $clientBranch->amount_per_job = $fields['amount_per_job'];*/

        $clientBranch->update();

        return $this->sendResponse(new ClientBrachResource($clientBranch), 'Client branch updated.', 200);
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


        $clientBranch = ClientBranchMs::where('id', '=', $id)->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client branch does not exist.');
        }

        $clientBranchCount = 0;
        $clientBranchCount += Job::where('admin_branch_id', '=', $id)->count();

        if ($clientBranchCount > 0) {
            return $this->sendError('Client Branch in use.');
        }

        $clientBranch->delete();

        return $this->sendResponse([], 'Client Branch deleted successfully.', 200);
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
        $parent_admin_id = getAdminIdIfAdminLoggedIn1($logged_in_id);


        $validator = Validator::make(
            [
                'id' => $id,
                'status' => $status,
            ],
            [
                'id' => ['required'],
                'status' => ['required', 'in:active,inactive'
                ],
            ]);


        if ($validator->fails()) {
            return $this->sendLaravelFormatError("The given data was invalid", $validator->errors());
        }

        $clientBranch = ClientBranchMs::where('id', '=', $id)->where('status', '!=', '2')->where('parent_admin_id', '=', $parent_admin_id)->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client Branch does not exist.');
        }

        $clientBranch->status = $status == 'active' ? '1' : '0';
        $clientBranch->update();
        return $this->sendResponse(new ClientBrachResource($clientBranch), 'Status change successfully.', 200);
    }

}
