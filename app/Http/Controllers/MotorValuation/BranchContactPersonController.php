<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use App\Models\BranchContactPerson;
use App\Models\ClientBranch;
use App\Models\Job;
use App\Http\Resources\contact_person\BranchContactPersonResource;
use App\Http\Resources\contact_person\BranchContactPersonResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
class BranchContactPersonController extends BaseController
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

        $fields = $request->validate([
            'branch_id' => 'required|int',
        ]);

        $created_by_id                  = Auth::user()->id;
        if($request->user()->tokenCan('type:employee')) {
            $parent_admin_id = getAdminIdIfEmployeeLoggedIn($created_by_id);
        }else{
            $parent_admin_id = getAdminIdIfAdminLoggedIn($created_by_id);
        }

        $TBL_CLIENT_BRANCHES            = config('global.client_branch_table');
        $TBL_BRANCH_CONTACT_PERSON      = config('global.branch_contact_person_table');

        $allClientsBranches = BranchContactPerson::join($TBL_CLIENT_BRANCHES.' as branch', 'branch.id', '=', 'branch_id')
                    ->orderBy('created_at', 'desc')
                    ->where($TBL_BRANCH_CONTACT_PERSON.'.branch_id','=',  $fields['branch_id'])
                    ->where($TBL_BRANCH_CONTACT_PERSON.'.parent_admin_id','=',$parent_admin_id)
                    ->select('branch.name as branch_name',$TBL_BRANCH_CONTACT_PERSON.'.*');

        $searchKeyword = "";
        if ($request->exists('search_keyword')) {
            $searchKeyword = $request->input('search_keyword');
            if ($searchKeyword != '') {
                $allClientsBranches =  $allClientsBranches->where(function($query) use ($TBL_BRANCH_CONTACT_PERSON,$searchKeyword) {
                    $query->orWhere($TBL_BRANCH_CONTACT_PERSON.'.name','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_BRANCH_CONTACT_PERSON.'.email','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_BRANCH_CONTACT_PERSON.'.designation','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_BRANCH_CONTACT_PERSON.'.mobile_no','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_BRANCH_CONTACT_PERSON.'.landline_no','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_BRANCH_CONTACT_PERSON.'.id','LIKE','%'.$searchKeyword.'%');
                });
            }
        }


        $allClientsBranches = $request->exists('all')
                ? BranchContactPersonResource::collection($allClientsBranches->get())
                : new BranchContactPersonResourceCollection($allClientsBranches->paginate(20));

        return $this->sendResponse($allClientsBranches, 'Posts fetched.');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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


        $TBL_BRANCH_CONTACT_PERSON      = config('global.branch_contact_person_table');

        $fields = $request->validate([
            'name' => 'required|string',
            'branch_id' => 'required|int',
            'email' => 'required|string',
            'designation' => 'required|string',
            'mobile_no' => 'required|numeric|digits:10',
            'landline_no' => 'required',
        ]);

        $contact_person = BranchContactPerson::create([
            'name' => $fields['name'],
            'branch_id' => $fields['branch_id'],
            'email' => $fields['email'],
            'designation' => $fields['designation'],
            'mobile_no' => $fields['mobile_no'],
            'landline_no' => $fields['landline_no'],
            'parent_admin_id' => $parent_admin_id,
            'created_by' => $logged_in_id,
            'modified_by' => $logged_in_id,
            'status' => '1',
        ]);

        return $this->sendResponse(new BranchContactPersonResource($contact_person),"success",201);
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


        $contact_person = BranchContactPerson::where('id','=', $id)->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($contact_person)) {
            return $this->sendError('Contact person does not exist.');
        }
        return $this->sendResponse(new BranchContactPersonResource($contact_person), 'Post fetched.');
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

        $contactPerson = BranchContactPerson::where('id','=', $id)->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($contactPerson)) {
            return $this->sendError('Contact person does not exist.');
        }

        $TBL_BRANCH_CONTACT_PERSON      = config('global.branch_contact_person_table');

        $fields = $request->validate([
            'name' => 'required|string',
            'branch_id' => 'required|int',
            'email' => 'required|string',
            'designation' => 'required|string',
            'mobile_no' => 'required|numeric|digits:10',
            'landline_no' => 'required',
        ]);

        $contactPerson->name = $fields['name'];
        $contactPerson->branch_id = $fields['branch_id'];
        $contactPerson->email = $fields['email'];
        $contactPerson->designation = $fields['designation'];
        $contactPerson->mobile_no = $fields['mobile_no'];
        $contactPerson->landline_no = $fields['landline_no'];
        $contactPerson->modified_by = $logged_in_id;

        $contactPerson->update();

        return $this->sendResponse(new BranchContactPersonResource($contactPerson), 'Contact person updated.',200);
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

        $contactPerson = BranchContactPerson::where('id','=', $id)->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($contactPerson)) {
            return $this->sendError('Contact person does not exist.');
        }

        $contactPersonCount = 0;
        $contactPersonCount += Job::where('contact_person_id','=', $id)->count();

        if ($contactPersonCount > 0){
            return $this->sendError('Contact person account in use.');
        }

        $contactPerson->delete();

        return $this->sendResponse([], 'Contact person deleted successfully.',200);

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

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

        $contactPerson = BranchContactPerson::where('id','=', $id)->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($contactPerson)) {
            return $this->sendError('Contact person does not exist.');
        }

        $contactPerson->status = $status == 'active' ? '1'  : '0';
        $contactPerson->update();
        return $this->sendResponse(new BranchContactPersonResource($contactPerson), 'Status change successfully.',200);
    }

}
