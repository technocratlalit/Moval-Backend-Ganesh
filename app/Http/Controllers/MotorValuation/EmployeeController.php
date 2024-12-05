<?php

namespace App\Http\Controllers\MotorValuation;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Workshop_contact_person;
use App\Models\Job;
use App\Models\Employeems;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use App\Http\Resources\employee\EmployeeResource;
use App\Http\Resources\employee\EmployeeCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail; 
class EmployeeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index(Request $request)
    {

        if(!$request->user()->tokenCan('type:admin')) {
           // return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);
        if($request->user()->tokenCan('type:employee')) {
            $parent_admin_id    = getAdminIdIfEmployeeLoggedIn($logged_in_id);
        }
        //echo $logged_in_id;
        //echo "<br>";
        //echo $parent_admin_id;

        $TBL_ADMIN      = config('global.admin_table');
        $TBL_EMPLOYEE   = config('global.employee_table');

        $allEmployees = Employee::join($TBL_ADMIN.' as created_by_admin', 'created_by_admin.id', '=', 'created_by')
                    ->join($TBL_ADMIN.' as modified_by_admin', 'modified_by_admin.id', '=', 'modified_by')
                    ->select('created_by_admin.name as created_by_admin','modified_by_admin.name as modified_by_admin',$TBL_EMPLOYEE.'.*');
        
        
        $allEmployees =  $allEmployees->where($TBL_EMPLOYEE.'.parent_admin_id','=', $parent_admin_id);
                    
        $reqeustType = $request->input('request_type');            
        
        if ($reqeustType != null && $reqeustType == 'assignEmployee') {
            $allEmployees =  $allEmployees->where($TBL_EMPLOYEE.'.job_assigned_to_guest','=', '0')->where($TBL_EMPLOYEE.'.status','=', '1')->orderBy('name', 'asc');
        }else{
            $allEmployees =  $allEmployees->where($TBL_EMPLOYEE.'.status','!=', '2')->orderBy('created_at', 'desc');

        }

 
        $searchKeyword = "";
        if ($request->exists('search_keyword')) {
            $searchKeyword = $request->input('search_keyword');
            if ($searchKeyword != '') {
                    $employees =  $allEmployees->where(function($query) use ($TBL_EMPLOYEE,$searchKeyword) {
                        $query->orWhere($TBL_EMPLOYEE.'.name','LIKE','%'.$searchKeyword.'%')
                            ->orWhere($TBL_EMPLOYEE.'.email','LIKE','%'.$searchKeyword.'%')
                            ->orWhere($TBL_EMPLOYEE.'.mobile_no','LIKE','%'.$searchKeyword.'%')
                            ->orWhere($TBL_EMPLOYEE.'.address','LIKE','%'.$searchKeyword.'%')
                            ->orWhere($TBL_EMPLOYEE.'.id','LIKE','%'.$searchKeyword.'%');
                    });
            }
        }

        $employees = $request->exists('all')
                ? EmployeeResource::collection($allEmployees->get())
                : new EmployeeCollection($allEmployees->paginate(20));



        return $this->sendResponse($employees, 'Posts fetched.');
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

       $TBL_SUPERADMIN='tbl_super_admin';
        $TBL_ADMIN ='tbl_admin';
        $TBL_EMPLOYEE   = config('global.employee_table');

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:'.$TBL_EMPLOYEE.',email|unique:'.$TBL_SUPERADMIN.',email|unique:'.$TBL_ADMIN.',email',
            'address' => 'required|string',
            'mobile_no' => 'required|numeric|digits:10|unique:'.$TBL_EMPLOYEE.',mobile_no',
            'user_id' => 'required|string|min:6|max:10|unique:'.$TBL_EMPLOYEE.',user_id',
            'password' => 'required|string|confirmed|min:6|max:15',
            'amount_per_job' => 'required|numeric|between:1,99999.99',
        ]);
        
        
        
        
           
    
/*    die('22222222');
        
        
        
         if(checkEmailExistOrNotmv($fields['email'])!=""){
             
                    return response()->json(['errors' => $validator->errors()], 422);
 
 return $this->sendError('This Email Id is already used by the other user so please enter other email Id.',['error'=>'email already used'],422);
 
} */
        
        

        $is_guest_employee = '0';
        if ($request->exists('is_guest_employee')) {
            $is_guest_employee = $request->input('is_guest_employee');
        }
        $employee = Employee::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'user_id' => $fields['user_id'],
            'firebase_token' => '',
            'password' => bcrypt($fields['password']),
            'address' => $fields['address'],
            'mobile_no' => $fields['mobile_no'],
            'amount_per_job' => $fields['amount_per_job'],
            'status' => '1',
            'parent_admin_id' => $parent_admin_id,
            'is_guest_employee' => $is_guest_employee,
            'created_by' => $logged_in_id,
            'modified_by' => $logged_in_id,
        ]);

        $email = $fields['email'];
        $maildata = [
            'subject' => 'Welcome to the Moval',
            'from_name' => config('app.from_name'),
            'fullName' => $fields['name'],
            'userId' => $fields['user_id'],
            'password' => $fields['password'],
            'applink' => config('app.play_store_url'),
            'contactEmail' => config('app.from_email_address'),
            'mail_template' => "emails.employee-welcome-email"
        ];
        Mail::to($email)->send(new WelcomeEmail($maildata));

        return $this->sendResponse(new EmployeeResource($employee),"success",201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $logged_in_id       = Auth::user()->id;
        if($request->user()->tokenCan('type:employee')) {
            if ($logged_in_id != $id){
                return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
            }
        }else if($request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }


        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

        $employee = Employee::where('id','=', $id)->where('status','!=', '2')->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }
        return $this->sendResponse(new EmployeeResource($employee), 'Post fetched.');
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
        $TBL_SUPERADMIN='tbl_super_admin';
        $TBL_ADMIN ='tbl_admin';
        $TBL_EMPLOYEE   = config('global.employee_table');

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

        $employee = Employee::where('id','=', $id)->where('status','!=', '2')->where('parent_admin_id','=', $parent_admin_id)->first();

        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:'.$TBL_EMPLOYEE.',email,'.$id.'|unique:'.$TBL_SUPERADMIN.',email|unique:'.$TBL_ADMIN.',email',
            'address' => 'required|string',
            'amount_per_job' => 'required|numeric|between:1,99999.99',
            'mobile_no' => 'required|numeric|digits:10|unique:'.$TBL_EMPLOYEE.',mobile_no,'.$id,
        ]);

        $employee->name = $fields['name'];
        $employee->email = $fields['email'];
        $employee->address = $fields['address'];
        $employee->amount_per_job = $fields['amount_per_job'];
        $employee->mobile_no = $fields['mobile_no'];
        $employee->modified_by = $logged_in_id;

        if ($request->exists('password')) {
            $password = $request->input('password');
            if($password != ""){
                $fields = $request->validate([
                    'password' => 'required|string|min:6|max:15'
                ]);
                $employee->password = bcrypt($fields['password']);
            }
        }

        $employee->update();

        return $this->sendResponse(new EmployeeResource($employee), 'Employee updated.',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

        if(!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $employee = Employee::where('id','=', $id)->where('status','!=', '2')->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }

        $employeeCount = 0;
        $employeeCount += Job::where('submitted_by','=', $id)->where('parent_admin_id','=', $parent_admin_id)->count();

        if ($employeeCount > 0){
            return $this->sendError('Employee account in use.');
        }

        $employee->delete();

        return $this->sendResponse([], 'Employee delete successfully.',200);

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

        $employee = Employee::where('id','=', $id)->where('status','!=', '2')->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }

        $employee->status = $status == 'active' ? '1'  : '0';
        $employee->update();
        return $this->sendResponse(new EmployeeResource($employee), 'Status change successfully.',200);
    }

    /**
     * Set Password of specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setPassword(Request $request,$userid)
    {  
        $fields = $request->validate([
           'password' => 'required|confirmed|min:6|max:10'
        ]);
        $workShop="";

        if(isset($request->platform) && $request->platform=="ms"){
           
           $employee = Employeems::where('user_id','=', $userid)->where('status','=', '1')->first(); 
           if (is_null($employee)) {

            $employee = Workshop_contact_person::where('username', '=', $userid)->where('status', '=', '1')->first();
            $workShop= "WS";
        }
        }
        else{
          $employee = Employee::where('user_id','=', $userid)->where('status','=', '1')->first();   
        }
        
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }

        if ($employee->is_guest_employee == '1'){
            return $this->sendError("You can't perform this operation on guest employee");
        }

        if(Hash::check($fields['password'], $employee->password)) {
            return $this->sendError('Unauthorised.', ['error'=>'New password should be different from your current password'],400);
        }
        if($workShop=="WS"){
           $employee->otp = bcrypt($fields['password']);
        }else{
          $employee->password = bcrypt($fields['password']);
        }
        $employee->is_set_password = 'yes';
        $employee->update();

        return $this->sendResponse(($request->platform=='ms') ? $employee : new EmployeeResource($employee), 'Password set successfully.',200);
    }


    /**
     * Send forgot password request.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendForgotPasswordRequest(Request $request)
    {

        $fields = $request->validate([
            'user_id' => 'required|string',
            'platform' => 'required|string',
        ]);
        
        $employee = "";
        $userId = $fields['user_id'];
        
        if ($request->platform == "ms") {

            $employee = Employeems::where('user_id', '=', $userId)->where('status', '=', '1')->first();
            if (is_null($employee)) {

                $employee = Workshop_contact_person::where('username', '=', $userId)->where('status', '=', '1')->first();
            }
        } else if ($request->platform == "mv") {
            $employee = Employee::where('user_id', '=', $userId)->where('status', '=', '1')->first();
        }
        
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }
        
        if ($employee->is_guest_employee == '1') {
            return $this->sendError("You can't perform this operation on a guest employee");
        }
        
        if ($employee->status == '0') {
            return $this->sendError('Account is inactive.');
        }
        
        $digits = 4;
        $strOTP = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        
        $startTime = date('Y-m-d H:i:s');
        $expiryTime = date('Y-m-d H:i:s', strtotime('+15 minutes', strtotime($startTime)));
        
        $employee->otp_sent = $strOTP;
        $employee->expiry_time = $expiryTime;
        $employee->update();
        
        $email = $employee->email;
        $maildata = [
            'subject' => 'Moval - One Time Password',
            'from_name' => config('app.from_name'),
            'fullName' => $employee->name,
            'otp' => $strOTP,
            'contactEmail' => config('app.from_email_address'),
            'mail_template' => "emails.password-reset-otp-email",
        ];
        Mail::to($email)->send(new WelcomeEmail($maildata));
        
        if ($request->platform == "ms") {
            return $this->sendResponse($employee, 'Otp Sent Successfully.', 200);
        } else if ($request->platform == "mv") {
            return $this->sendResponse(new EmployeeResource($employee), 'Otp Sent Successfully.', 200);
        }
        
    }

    /**
     * Verify Otp.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyOTP(Request $request,$user_id)
    {

        $fields = $request->validate([
            'otp' => 'required|string'
        ]);
        
        $employee = Employee::where('user_id', '=', $user_id)->where('status', '=', '1')->first();
        
        // If Employee is not found, try searching in EmployeeOther
        $platform='';
        if (is_null($employee)) {
            $employee = Employeems::where('user_id', '=', $user_id)->where('status', '=', '1')->first();

            if (is_null($employee)) {
                $employee = Workshop_contact_person::where('username', '=', $user_id)->where('status', '=', '1')->first();
            }
            $platform = "ms";
        }
        
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }
        
        if ($employee->is_guest_employee == '1') {
            return $this->sendError("You can't perform this operation on a guest employee");
        }
        
        if ($employee->status == '0') {
            return $this->sendError('Account is inactive.');
        }
        
        $otp = $fields['otp'];
        
        $currentTime = strtotime(date('Y-m-d H:i:s'));
        $expiryTime = strtotime($employee->expiry_time);
        
        if ($employee->otp_sent != $otp) {
            return $this->sendError('Invalid OTP.');
        }
        
        if ($expiryTime < $currentTime) {
            return $this->sendError('OTP Expired.');
        }
        
        $token = $employee->createToken('moval-app-employee-token', ['type:employee'])->plainTextToken;
        
        $response = [
            'detail' => ($platform=='ms') ? $employee : new EmployeeResource($employee),
            'token' => $token
        ];
        
        return $this->sendResponse($response, 'OTP verified successfully.', 200);
        
    }

    /**
     * Change Password of specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {

        if(!$request->user()->tokenCan('type:employee')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $logged_in_id       = Auth::user()->id;

        $employee = Employee::where('id','=', $logged_in_id)->where('status','=', '1')->first();
        if (is_null($logged_in_id)) {
            return $this->sendError('Employee does not exist.');
        }

        if ($employee->is_guest_employee == '1'){
            return $this->sendError("You can't perform this operation on guest employee");
        }

        $fields = $request->validate([
            'old_password' => 'required|string|min:6|max:10',
            'password' => 'required|string|confirmed|min:6|max:10'
        ]);

        // Check password
        if(!Hash::check($fields['old_password'], $employee->password)) {
            return $this->sendError('Unauthorised.', ['error'=>'Incorrect Old Password'],401);
        }

        $employee->password = bcrypt($fields['password']);
        $employee->update();

        return $this->sendResponse(new EmployeeResource($employee), 'Password set successfully.',200);
    }

    /**
     * Updat Firebase token of specified resource in storage.
     *
     * @param  int  $id
     * @param  String  $status
     * @return \Illuminate\Http\Response
     */
    public function updateToken(Request $request)
    {
        if(!$request->user()->tokenCan('type:employee')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $authUser = Auth::user()->id;

        $fields = $request->validate([
            'firebase_token' => 'required|string',
        ]);

        $firebase_token = $fields['firebase_token'];

        $employee = Employee::where('id','=', $authUser)->where('status','!=', '2')->first();
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }

        $employee->firebase_token = $firebase_token;
        $employee->update();
        return $this->sendResponse(new EmployeeResource($employee), 'Firebase token updated successfully.',200);
    }

        /**
     * Updat Firebase token of specified resource in storage.
     *
     * @param  int  $id
     * @param  String  $status
     * @return \Illuminate\Http\Response
     */
    public function resetDeviceId(Request $request,$employeeId)
    {
        if(!$request->user()->tokenCan('type:admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $validator = Validator::make(['employeeId' => $employeeId,
            ],
            [
              'employeeId' => ['required']
            ]
        );


        if($validator->fails()){
            return $this->sendLaravelFormatError("The given data was invalid",$validator->errors());
        }

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);


        $employee = Employee::where('id','=', $employeeId)->where('status','!=', '2')->where('parent_admin_id','=', $parent_admin_id)->first();
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }

        $employee->device_id = "";
        $employee->update();
        return $this->sendResponse(new EmployeeResource($employee), 'Device Id reset successfully.',200);
    }
	
	
}
