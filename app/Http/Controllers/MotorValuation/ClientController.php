<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientBranch;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use App\Http\Resources\client\ClientResource;
use App\Http\Resources\client\ClientCollection;
use App\Jobs\SendMail;
use Illuminate\Support\Facades\Validator;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class ClientController extends BaseController
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

        $TBL_ADMIN      = config('global.admin_table');
        $TBL_CLIENT     = config('global.client_table');

        $allClients = Client::join($TBL_ADMIN.' as created_by_admin', 'created_by_admin.id', '=', 'created_by')
                    ->join($TBL_ADMIN.' as modified_by_admin', 'modified_by_admin.id', '=', 'modified_by')
                    ->orderBy('created_at', 'desc')
                    ->where($TBL_CLIENT.'.parent_admin_id','=',$parent_admin_id)
                    ->select('created_by_admin.name as created_by_admin','modified_by_admin.name as modified_by_admin',$TBL_CLIENT.'.*');

        $reqeustType = $request->input('request_type');
        if ($reqeustType != null && $reqeustType == 'drop_down') {
            $allClients =  $allClients->where($TBL_CLIENT.'.status','=', '1');
        }else{
            $allClients =  $allClients->where($TBL_CLIENT.'.status','!=', '2');
        }

        $searchKeyword = "";
        if ($request->exists('search_keyword')) {
            $searchKeyword = $request->input('search_keyword');
            if ($searchKeyword != '') {
                $allClients =  $allClients->where(function($query) use ($TBL_CLIENT,$searchKeyword) {
                    $query->orWhere($TBL_CLIENT.'.name','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_CLIENT.'.email','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_CLIENT.'.registered_mobile_no','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_CLIENT.'.mobile_no','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_CLIENT.'.address','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_CLIENT.'.id','LIKE','%'.$searchKeyword.'%');
                });
            }
        }

        $allClients = $request->exists('all')
                ? ClientResource::collection($allClients->get())
                : new ClientCollection($allClients->paginate(20));

        return $this->sendResponse($allClients, 'Posts fetched.');

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

        $TBL_CLIENT   = config('global.client_table');

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

        $fields = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'mobile_no' => 'required', // Client contact Number

            'branch_name' => 'required|string',
            'branch_address' => 'required|string',
            'branch_contact_no' => 'required|string',
            'contact_person_name' => 'required|string', // Manager Name
            'manager_email' => 'required|string',
            'registered_mobile_no' => 'required|numeric|digits:10', // Manager Mobile Number
            'mode_of_payment' => 'required|string|in:Prepaid,Postpaid',
            'amount_per_job' => 'required|numeric|min:0',
        ]);

        $client = Client::create([
            'name' => $fields['name'],
            'address' => $fields['address'],
            'mobile_no' => $fields['mobile_no'], // Client Contact Number
            'mode_of_payment' => "Prepaid",
            'amount_per_job' => 0,

            'email' => "",
            'contact_person_name' => "",
            'registered_mobile_no' => "",

            'status' => '1',
            'parent_admin_id' => $parent_admin_id,
            'created_by' => $logged_in_id,
            'modified_by' => $logged_in_id,
        ]);

        ClientBranch::create([
            'client_id' => $client->id,
            'name' => $fields['branch_name'],
            'address' => $fields['branch_address'],
            'mobile_no' => $fields['branch_contact_no'], // Branch Contact Number

            'contact_person_name' => $fields['contact_person_name'], // Manager Name
            'manager_email' => $fields['manager_email'],
            'registered_mobile_no' => $fields['registered_mobile_no'], // Manager Mobile Number
            'mode_of_payment' => $fields['mode_of_payment'],
            'parent_admin_id' => $parent_admin_id,
            'amount_per_job' => $fields['amount_per_job'],
            'status' => '1',

        ]);

        $email = $fields['manager_email'];
        $maildata = [
            'subject' => 'Welcome to the Moval',
            'from_name' => config('app.from_name'),
            'fullName' => $fields['name'],
            'contactEmail' => config('app.from_email_address'),
            'mail_template' => "emails.client-welcome-email"
        ];
        SendMail::dispatch($email, $maildata)->onQueue('send-email')->onConnection('database');
        // Mail::to($email)->send(new WelcomeEmail($maildata));

        return $this->sendResponse(new ClientResource($client),"success",201);

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

        $client = Client::where('id','=', $id)->where('status','!=', '2')->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }
        return $this->sendResponse(new ClientResource($client), 'Post fetched.');
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

        $TBL_CLIENT   = config('global.client_table');

        $client = Client::where('id','=', $id)->where('status','!=', '2')->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }

        $update_by = Auth::user()->id;

        $fields = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'mobile_no' => 'required|string', // Client Contact Number

            //'contact_person_name' => 'required|string',
            //'email' => 'required|string|unique:'.$TBL_CLIENT.',email,'.$id,
            //'registered_mobile_no' => 'required|numeric|digits:10',
            // 'mode_of_payment' => 'required|string|in:Prepaid,Postpaid',
            // 'amount_per_job' => 'required|numeric|min:0',
        ]);

        $client->name = $fields['name'];
        $client->address = $fields['address'];
        $client->mobile_no = $fields['mobile_no'];
        $client->modified_by = $update_by;

        $client->update();

        return $this->sendResponse(new ClientResource($client), 'Client updated.',200);

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

        $client = Client::where('id','=', $id)->where('status','!=', '2')->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }

        $clientCount = 0;
        $clientCount += Job::where('requested_by','=', $id)->count();

        if ($clientCount > 0){
            return $this->sendError('Client account in use.');
        }

        $client->delete();

        return $this->sendResponse([], 'Client delete successfully.',200);

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

        $client = Client::where('id','=', $id)->where('status','!=', '2')->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($client)) {
            return $this->sendError('Client does not exist.');
        }

        $client->status = $status == 'active' ? '1'  : '0';
        $client->update();
        return $this->sendResponse(new ClientResource($client), 'Status change successfully.',200);
    }

}
