<?php

namespace App\Http\Controllers\MotorSurvey;

use Illuminate\Http\Request;
use App\Models\Employeems;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use App\Http\Resources\employee\EmployeeResource;
use App\Http\Resources\employee\EmployeeCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class EmployeemsController extends BaseController
{
    public function store(Request $request)
    {

          if(!$request->user()->tokenCan('type:admin')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
} 

        $TBL_EMPLOYEE   = 'tbl_ms_employee';
        $TBL_worksp = 'tbl_ms_workshop_contact_person';
        $TBL_MS_SUPERADMIN = 'tbl_ms_super_admin';
        $TBL_MS_ADMIN = 'tbl_ms_admin';

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn1($logged_in_id);

        $main_admin_id = getmainAdminIdIfAdminLoggedIn($logged_in_id);

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:' . $TBL_EMPLOYEE . ',email|unique:' . $TBL_MS_SUPERADMIN . ',email|unique:' . $TBL_MS_ADMIN . ',email|unique:' . $TBL_worksp . ',email',
            'address' => 'required|string',
            'mobile_no' => 'required|numeric|digits:10|unique:' . $TBL_EMPLOYEE . ',mobile_no',
            'user_id' => 'required|string|min:6|max:10|unique:' . $TBL_EMPLOYEE . ',user_id|unique:' . $TBL_worksp . ',username',
            'password' => 'required|string|confirmed|min:6|max:15',
            'amount_per_job' => 'required|numeric|between:1,99999.99',
            'admin_branch_id' =>  'required|numeric',
        ]);







        /*if(checkEmailExistOrNot($fields['email'])!=""){
 return $this->sendError('This Email Id is already used by the other user so please enter other email Id.',['error'=>'email already used'],422);
}*/



        $is_guest_employee = '0';
        if ($request->exists('is_guest_employee')) {
            $is_guest_employee = $request->input('is_guest_employee');
        }

        $employee = Employeems::create([
            'admin_id' => $main_admin_id,
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
            'admin_branch_id' =>  $fields['admin_branch_id']
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

        // print_r($maildata);
        Mail::to($email)->send(new WelcomeEmail($maildata));

        //  return $this->sendResponse(new EmployeeResource($employee),"success",201);
        return $this->sendResponse("sussessfully inserted", "success", 201);
    }

    public function update(Request $request, $id)
    {
        /* if(!$request->user()->tokenCan('type:admin')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}
*/

        $TBL_EMPLOYEE   = 'tbl_ms_employee';
        $TBL_worksp = 'tbl_ms_workshop_contact_person';
        $TBL_MS_SUPERADMIN = 'tbl_ms_super_admin';
        $TBL_MS_ADMIN = 'tbl_ms_admin';

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn1($logged_in_id);

        //$employee = Employeems::where('id','=', $id)->where('status','!=', '2')->where('parent_admin_id','=', $parent_admin_id)->first();
        $employee = Employeems::where('id', '=', $id)->first();

        //print_r(count($employee)); die('----------');
        if (!($employee)) {
            return $this->sendError('Employee does not exist.');
        }

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:' . $TBL_EMPLOYEE . ',email,' . $id . '|unique:' . $TBL_MS_SUPERADMIN . ',email|unique:' . $TBL_MS_ADMIN . ',email|unique:' . $TBL_worksp . ',email',
            'address' => 'required|string',
            'amount_per_job' => 'required|numeric|between:1,99999.99',
            'mobile_no' => 'required|numeric|digits:10|unique:' . $TBL_EMPLOYEE . ',mobile_no,' . $id,
            'admin_branch_id' =>  'required|numeric',
        ]);


        $employee->name = $fields['name'];
        $employee->email = $fields['email'];
        $employee->address = $fields['address'];
        $employee->amount_per_job = $fields['amount_per_job'];
        $employee->mobile_no = $fields['mobile_no'];
        $employee->modified_by = $logged_in_id;
        $employee->admin_branch_id = $fields['admin_branch_id'];;

        if ($request->exists('password')) {
            $password = $request->input('password');
            if ($password != "") {
                $fields = $request->validate([
                    'password' => 'required|string|min:6|max:15'
                ]);
                $employee->password = bcrypt($fields['password']);
            }
        }

        $employee->update();
        return $this->sendResponse("sussessfully updated", "success", 200);
        //  return $this->sendResponse(new EmployeeResource($employee), 'Employee updated.',200);
    }


    public function list(Request $request)
    {

        //created_by
        //$d=Employeems::where('id',$id)->delete();

        $logged_in_id       = Auth::user()->id;

        if (Auth::user()->role == 'admin') {
            //$wh2=['admin_id'=>$logged_in_id];
            if (!$request->exists("all")) {
                $employee = Employeems::where('admin_id', $logged_in_id)->orderBy('created_at', 'desc')->paginate(10);
            } else {
                $employee = Employeems::where('admin_id', $logged_in_id)->orderBy('created_at', 'desc')->get();
            }
        }else if (Auth::user()->role == 'branch_admin') { 

            $logged_in_id = Auth::user()->parent_id; 
            $admin_branch_id = Auth::user()->admin_branch_id; 
            
            if (!$request->exists("all")) {
                $employee = Employeems::where(['admin_branch_id' => $admin_branch_id,"admin_id" =>  $logged_in_id])->orderBy('created_at', 'desc')->paginate(10);
            } else {
                $employee = Employeems::where(['admin_branch_id' => $admin_branch_id,"admin_id" =>  $logged_in_id ])->orderBy('created_at', 'desc')->get();
            } 
            
       
            
        }
        else if (Auth::user()->role == 'sub_admin'){
            $admin_branch_id2 = getBranchidbyAdminid($logged_in_id);
            $logged_in_id = Auth::user()->parent_id; 
            $admin_branch_id =  Auth::user()->admin_branch_id; 
            // return ['admin_branch_id' => $admin_branch_id , 'logged_in_id' => $logged_in_id ,'admin_branch_id2' => $admin_branch_id2]; 
            if (!$request->exists("all")) {
                $employee = Employeems::where(['admin_branch_id' => $admin_branch_id])->orWhere(["parent_admin_id" =>  $logged_in_id])->orderBy('created_at', 'desc')->paginate(10);
            } else {
                $employee = Employeems::where(['admin_branch_id' => $admin_branch_id])->orWhere(["parent_admin_id" =>  $logged_in_id])->orderBy('created_at', 'desc')->get();
            }  
        } 
        // else {

        //     $main_admin_id = getmainAdminIdIfAdminLoggedIn($logged_in_id);
        //     $admin_branch_id = getBranchidbyAdminid($logged_in_id);

        //     if (!$request->exists("all")) {
        //         $employee = Employeems::where('admin_branch_id', $admin_branch_id)->orWhere('admin_id', $logged_in_id)->orderBy('created_at', 'desc')->paginate(10);
        //     } else {
        //         $employee = Employeems::where('admin_branch_id', $admin_branch_id)->orWhere('admin_id', $logged_in_id)->orderBy('created_at', 'desc')->get();
        //     }
        // }

        $i = 0; 
        if (!$request->exists("all")) {
            $data["pagination"] = [
                "total" => $employee->total(),
                "count" => $employee->count(),
                "per_page" => $employee->perPage(),
                "current_page" => $employee->currentPage(),
                "total_pages" => $employee->lastPage(),
            ];
        }

        foreach ($employee as $emp_details) {
            $branch_name = getBranchbybranchid($emp_details->admin_branch_id);

            if ($emp_details->status == 1) {
                $status = 'Active';
            } else {
                $status = 'Inactive';
            }

            $data['values'][$i] = [
                'id' => $emp_details->id,
                'user_id' => $emp_details->user_id,
                'type' => $emp_details->type,
                'password' => $emp_details->password,
                'name' => $emp_details->name,
                'email' => $emp_details->email,
                'mobile_no' => $emp_details->mobile_no,
                'address' => $emp_details->address,
                'amount_per_job' => $emp_details->amount_per_job,
                'firebase_token' => $emp_details->firebase_token,
                'device_id' => $emp_details->device_id,
                'status' => $status,
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
                "created_by_name" =>adminname($emp_details->created_by),
                'modified_by' => $emp_details->modified_by,
                'admin_branch_id' =>  $emp_details->admin_branch_id,
                'branch_name' => $branch_name,
            ];
            $i++;
        }

        return $this->sendResponse($data, 'Employee List Fetched'.Auth::user()->role, 200);
    }


    public function delemployee(Request $request, $id)
    {



        /* $logged_in_id       = Auth::user()->id;
$parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

if(!$request->user()->tokenCan('type:admin')) {
return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
}*/

        $d = Employeems::where('id', $id)->delete();

        if ($d == 1) {
            return $this->sendResponse('Employee deleted', "success", 200);
        } else {
            return $this->sendError('unable to delete', ['error' => 'invalid id'], 200);
        }
    }

    public function employeebyid(Request $request, $id)
    {
        $emp_details = Employeems::where('id', $id)->first();



        if (is_null($emp_details)) {
            return $this->sendError('Employee does not exist.');
        }

        $data = [
            'id' => $emp_details->id,
            'user_id' => $emp_details->user_id,
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
            'admin_branch_id' =>  $emp_details->admin_branch_id,
        ];
        if (!$request->exists('all')) {
            $emp_details = Employeems::where('id', $id)->paginate(10);
        }
        if (!$request->exists('all')) {
            $data['pagination'] =
                [
                    'total' => $emp_details->total(),
                    'count' => $emp_details->count(),
                    'per_page' => $emp_details->perPage(),
                    'current_page' => $emp_details->currentPage(),
                    'total_pages' => $emp_details->lastPage(),
                ];
        }

        return $this->sendResponse($data, 'Employee List Fetched', 200);
    }

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

        $logged_in_id       = Auth::user()->id;
        $parent_admin_id    = getAdminIdIfAdminLoggedIn1($logged_in_id);
        //    $employee = Employeems::where('id','=', $id)->where('status','!=', '2')->where('parent_admin_id','=', $parent_admin_id)->first();

        $employee = Employeems::where('id', '=', $id)->where('status', '!=', '2')->first();
        if (is_null($employee)) {
            return $this->sendError('Employee does not exist.');
        }
        $employee->status = $status == 'active' ? '1'  : '0';
        $employee->update();
        return $this->sendResponse(new EmployeeResource($employee), 'Status change successfully.', 200);
    }
}
