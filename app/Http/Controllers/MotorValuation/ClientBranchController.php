<?php

namespace App\Http\Controllers\MotorValuation;
use App\Models\Client;
use App\Models\ClientBranch;
use App\Models\Job;
use App\Http\Resources\client\ClientBrachResource;
use App\Http\Resources\client\ClientBrachCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
class ClientBranchController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
 

        if($request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $logged_in_id                  = Auth::user()->id;
        if($request->user()->tokenCan('type:employee')) {
            $parent_admin_id = getAdminIdIfEmployeeLoggedIn($logged_in_id);
        }else{
            $parent_admin_id = getAdminIdIfAdminLoggedIn($logged_in_id);
        }

        $fields = $request->validate([
            'client_id' => 'required|int',
        ]);

        $TBL_CLIENT_BRANCHES      = config('global.client_branch_table');
        $TBL_CLIENT               = config('global.client_table');

        $allClientsBranches = ClientBranch::join($TBL_CLIENT.' as client', 'client.id', '=', 'client_id')
                    ->orderBy('created_at', 'desc')
                    ->where($TBL_CLIENT_BRANCHES.'.client_id','=',  $fields['client_id'])
                    ->where($TBL_CLIENT_BRANCHES.'.parent_admin_id','=',$parent_admin_id)
                    ->select('client.name as client_name',$TBL_CLIENT_BRANCHES.'.*');

        $reqeustType = $request->input('request_type');
        if ($reqeustType != null && $reqeustType == 'drop_down') {
            $allClientsBranches =  $allClientsBranches->where($TBL_CLIENT_BRANCHES.'.status','=', '1');
        }else{
            $allClientsBranches =  $allClientsBranches->where($TBL_CLIENT_BRANCHES.'.status','!=', '2');
        }

        $searchKeyword = "";
        if ($request->exists('search_keyword')) {
            $searchKeyword = $request->input('search_keyword');
            if ($searchKeyword != '') {
                $allClientsBranches =  $allClientsBranches->where(function($query) use ($TBL_CLIENT_BRANCHES,$searchKeyword) {
                    $query->orWhere($TBL_CLIENT_BRANCHES.'.name','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_CLIENT_BRANCHES.'.email','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_CLIENT_BRANCHES.'.registered_mobile_no','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_CLIENT_BRANCHES.'.mobile_no','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_CLIENT_BRANCHES.'.address','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_CLIENT_BRANCHES.'.id','LIKE','%'.$searchKeyword.'%');
                });
            }
        }


        $allClientsBranches = $request->exists('all')
                ? ClientBrachResource::collection($allClientsBranches->get())
                : new ClientBrachCollection($allClientsBranches->paginate(20));

        return $this->sendResponse($allClientsBranches, 'Posts fetched.');

   
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

        $TBL_CLIENT_BRANCHES      = config('global.client_branch_table');

        // registered_mobile_no field will be considerd as landline number from Phase 2
        $fields = $request->validate([
            'name' => 'required|string',
            'client_id' => 'required|int',
            'address' => 'required|string',
            'mobile_no' => 'required|string', // Branch Contact No
            'contact_person_name' => 'required|string', // Manager Name
            'manager_email' => 'required|string',
            'registered_mobile_no' => 'required|numeric|digits:10',// Manager Mobile No
            'mode_of_payment' => 'required|string|in:Prepaid,Postpaid',
            'amount_per_job' => 'required|numeric|min:0',
        ]);


        $client = ClientBranch::create([
            'client_id' => $fields['client_id'],
            'name' => $fields['name'],
            'address' => $fields['address'],
            'mobile_no' => $fields['mobile_no'], // Branch Contact No
            'email' => "",
            'contact_person_name' => $fields['contact_person_name'], // Manager Name
            'manager_email' => $fields['manager_email'],
            'registered_mobile_no' => $fields['registered_mobile_no'], // Manager Mobile No
            'mode_of_payment' => $fields['mode_of_payment'],
            'amount_per_job' => $fields['amount_per_job'],
            'parent_admin_id' => $parent_admin_id,
            'status' => '1',

        ]);

        return $this->sendResponse(new ClientBrachResource($client),"success",201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        if(!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);



        $clientBranch = ClientBranch::where('id','=', $id)->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client Branch does not exist.');
        }
        return $this->sendResponse(new ClientBrachResource($clientBranch), 'Post fetched.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

        $TBL_CLIENT_BRANCHES      = config('global.client_branch_table');

        $clientBranch = ClientBranch::where('id','=', $id)->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client Branch does not exist.');
        }

        $fields = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'mobile_no' => 'required|string', // Branch Contact No

            'contact_person_name' => 'required|string', // Manager Name
            'registered_mobile_no' => 'required|numeric', // Manager Mobile Number
            'manager_email' => 'required|string',
            'mode_of_payment' => 'required|string|in:Prepaid,Postpaid',
            'amount_per_job' => 'required|numeric|min:0',
        ]);

        $clientBranch->name = $fields['name'];
        $clientBranch->address = $fields['address'];
        $clientBranch->mobile_no = $fields['mobile_no'];

        $clientBranch->registered_mobile_no = $fields['registered_mobile_no'];
        $clientBranch->contact_person_name = $fields['contact_person_name'];
        $clientBranch->manager_email = $fields['manager_email'];
        $clientBranch->mode_of_payment = $fields['mode_of_payment'];
        $clientBranch->amount_per_job = $fields['amount_per_job'];

        $clientBranch->update();

        return $this->sendResponse(new ClientBrachResource($clientBranch), 'Client branch updated.',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if(!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);



        $clientBranch = ClientBranch::where('id','=', $id)->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client branch does not exist.');
        }

        $clientBranchCount = 0;
        $clientBranchCount += Job::where('branch_id','=', $id)->count();

        if ($clientBranchCount > 0){
            return $this->sendError('Client Branch in use.');
        }

        $clientBranch->delete();

        return $this->sendResponse([], 'Client Branch deleted successfully.',200);
    }

    /**
     * Updat status of specified resource in storage.
     *
     * @param  int  $id
     * @param  String  $status
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id,$status)
    {
        if(!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);



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


        if($validator->fails()){
            return $this->sendLaravelFormatError("The given data was invalid",$validator->errors());
        }

        $clientBranch = ClientBranch::where('id','=', $id)->where('status','!=', '2')->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($clientBranch)) {
            return $this->sendError('Client Branch does not exist.');
        }

        $clientBranch->status = $status == 'active' ? '1'  : '0';
        $clientBranch->update();
        return $this->sendResponse(new ClientBrachResource($clientBranch), 'Status change successfully.',200);
    }

}
