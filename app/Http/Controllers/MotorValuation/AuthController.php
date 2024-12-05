<?php

namespace App\Http\Controllers\MotorValuation;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Admin_ms;
use App\Models\Employee;
use App\Models\Employeems;
use App\Http\Resources\admin\AdminResource;
use App\Http\Resources\employee\EmployeeResource;
use App\Models\SuperAdmin;
use App\Models\SuperAdmin_ms;
use App\Http\Resources\super_admin\SuperAdminResource;
use App\Models\AdminInvoice;
use App\Http\Resources\branch\BranchResource;
use App\Models\Branch;
use App\Models\Msbranchcontact;
use App\Models\Job;

class AuthController extends BaseController
{

    public function superAdminLogin(Request $request)
    {


        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'platform' => 'required'
        ]);


        if ($fields['platform'] == 1) {

            $admin = SuperAdmin::where('email', $fields['email'])->where('status', '!=', '2')->first();

            if (!$admin || !Hash::check($fields['password'], $admin->password)) {
                return $this->sendError('Unauthorised.', ['error' => 'Bad Credentials'], 401);
            }

            if ($admin->status == '0') {
                return $this->sendError('Unauthorised.', ['error' => 'Account Inactive'], 401);
            }

            // Check password


            $token = $admin->createToken('moval-app-super-admin-token', ['type:super_admin'])->plainTextToken;
            $response = [
                'detail' => new SuperAdminResource($admin),
                'token' => $token
            ];
            return $this->sendResponse($response, 'Login successfully.');
        } else if ($fields['platform'] == 2) {

            $admin = SuperAdmin_ms::where('email', $fields['email'])->where('status', '!=', '2')->first();
            // return "Test" .  env('DB_DATABASE');
            if (!$admin || !Hash::check($fields['password'], $admin->password)) {
                return $this->sendError('Unauthorised.', ['error' => 'Bad Credentials asd'], 401);
            }

            if ($admin->status == '0') {
                return $this->sendError('Unauthorised.', ['error' => 'Account Inactive'], 401);
            }

            // Check password


            $token = $admin->createToken('moval-app-super-admin-token', ['type:super_admin'])->plainTextToken;
            $response = [
                'detail' => new SuperAdminResource($admin),
                'token' => $token
            ];
            return $this->sendResponse($response, 'Login successfully.');
        } else {
            return $this->sendError('Platfrom Not Matching.', ['error' => 'Enter Correct Platform'], 401);
        }
    }

    public function adminLogin(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'platform' => 'required',
        ]);


        $superAdminEmail = config('global.super_admin_email');

        if ($superAdminEmail == $fields['email']) {
            return $this->superAdminLogin($request);
        }


        if ($fields['platform'] == 1) {

            // Check email
            $admin = Admin::where('email', $fields['email'])->where('status', '!=', '2')->first();

            if (!$admin || !Hash::check($fields['password'], $admin->password)) {
                return $this->sendError('Unauthorised.', ['error' => 'Bad Credentials'], 401);
            }

            if ($admin->status == '0') {
                return $this->sendError('Unauthorised.', ['error' => 'Account Inactive'], 401);
            }

            $token = $admin->createToken('moval-app-admin-token', ['type:admin'])->plainTextToken;
            $response = [
                'detail' => new AdminResource($admin),
                'token' => $token,
                'module' => 'Motor valuation'
            ];
        }


        if ($fields['platform'] == 2) {

            $admin = Admin_ms::where('email', $fields['email'])->where('status', '!=', '2')->first();

            // Check if admin exists and parent_id is not 0
            if ($admin && $admin->parent_id != 0) {
                // Retrieve branch information
                $branch = Branch::find($admin->admin_branch_id);

                if (!$branch) {
                    return $this->sendError('Unauthorised.', ['error' => 'Your Branch does not exist. Please contact the administrator.'], 401);
                }
            }

            // Check password
            if (!$admin || !Hash::check($fields['password'], $admin->password)) {
                return $this->sendError('Unauthorised.', ['error' => 'Bad Credentials'], 401);
            }

            if ($admin->status == '0') {
                return $this->sendError('Unauthorised.', ['error' => 'Account Inactive'], 401);
            }

            $token = $admin->createToken('moval-app-admin-token', ['type:admin'])->plainTextToken;
            $response = [
                'detail' => new AdminResource($admin),
                'token' => $token,
                'module' => 'Motor Survey',
                'role' => $admin['role']
            ];


            if ($admin->parent_id != 0) {
                $branch_id = $admin->admin_branch_id;

                $all = Branch::where('id', $branch_id)->first();
                //  print_r($data['sop_master']); die;
                if (!$all) {
                    $response['branch_detail'] = "Invalid branch assigned";
                } else {
                    $response['branch_detail'] = [
                        'id' => $all->id,
                        'admin_branch_name' => $all->admin_branch_name,
                        'email' => $all->email,
                        'address' => $all->address,
                        'mobile_no' => $all->mobile_no,
                        'contact_person' => $all->contact_person,
                        'admin_id' => $all->admin_id,
                        'created_by' => $all->created_by,
                        'created_at' => $all->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => ($all->updated_at) ? $all->updated_at->format('Y-m-d H:i:s') : "",
                    ];
                }
            }
        }
        return $this->sendResponse($response, 'Login successfully.');
    }

    public function employeeLogin(Request $request)
    {

        $fields = $request->validate([
            'user_id' => 'required|string',
            'password' => 'required|string',
            'deviceId' => 'required|string'
        ]);

        // Check email
        $employee = Employee::where('user_id', $fields['user_id'])->where('status', '!=', '2')->first();

        // Check password
        if (!$employee || !Hash::check($fields['password'], $employee->password)) {
            return $this->sendError('Unauthorised.', ['error' => 'Bad Credentials'], 401);
        }

        if ($employee->status == '0') {
            return $this->sendError('Unauthorised.', ['error' => 'Account Inactive'], 401);
        }

        /*if ($employee->device_id != null && $employee->device_id != '' && $employee->device_id != $fields['deviceId']){
  return $this->sendError('Unauthorised.', ['error'=>'Invalid device'],401);
  }*/

        $TBL_ADMIN = config('global.admin_table');
        $admin = Admin::select([$TBL_ADMIN . '.number_of_photograph'])
            ->where($TBL_ADMIN . '.id', $employee->parent_admin_id)
            ->where($TBL_ADMIN . '.status', '!=', '2')->first();

        if (is_null($admin)) {
            return $this->sendError('Admin does not exist.');
        }


        $TBL_ADMIN_INVOICE = config('global.admin_invoice_table');

        $adminInvoiceList = AdminInvoice::where($TBL_ADMIN_INVOICE . '.admin_id', '=', $employee->parent_admin_id)
            ->where($TBL_ADMIN_INVOICE . '.payment_status', '=', 'pending')
            ->where($TBL_ADMIN_INVOICE . '.bill_amount', '!=', 0)
            ->orderBy('created_at', 'desc')->get();

        $is_bill_due = 0;
        $isShowMessageToSubAdmin = 0;
        $totalAmountDue = 0;
        $lastDateOfPayment = "";
        if ($adminInvoiceList->count() > 0) {
            $is_bill_due = 1;
            $lastAdminInvoice = $adminInvoiceList[0];
            $todayDate = date("Y-m-d H:i:s");
            $invoiceGenerateDate = $lastAdminInvoice->created_at;
            $lastDateOfPayment = $lastAdminInvoice->last_date_of_payment;

            if (strtotime($lastDateOfPayment) < strtotime($todayDate)) {
                return $this->sendError('Unauthorised.', ['error' => 'Subscription expired'], 401);
            }

            $totalAmountDue = $lastAdminInvoice->bill_amount;
            // Declare and define two dates
            $date1 = strtotime($lastDateOfPayment);
            $date2 = strtotime($todayDate);

            // Formulate the Difference between two dates
            $diff = abs($date2 - $date1);
            $days = floor(($diff) / (60 * 60 * 24));

            if ($days <= 2) {
                $isShowMessageToSubAdmin = 1;
            }
        }


        $token = $employee->createToken('moval-app-employee-token', ['type:employee'])->plainTextToken;
        $response = [
            'detail' => new EmployeeResource($employee),
            'is_bill_due' => $is_bill_due,
            'total_amount_due' => $totalAmountDue,
            'last_date_of_payment' => $lastDateOfPayment,
            'is_show_message_to_employee' => $isShowMessageToSubAdmin,
            'number_of_photograph' => $admin->number_of_photograph,
            'token' => $token
        ];

        $firebase_token = '';
        if ($request->exists('firebase_token')) {
            $firebase_token = $request->input('firebase_token');
        }


        $employee->last_login_date = date('Y-m-d H:i:s');
        $employee->firebase_token = $firebase_token;
        $employee->device_id = $fields['deviceId'];
        $employee->update();
        return $this->sendResponse($response, 'Login successfully.');
    }

    public function adminLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return [
            'message' => 'Logged out'
        ];
    }


    public function empadminLogin(Request $request)
    {

        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'platform' => 'required',
        ]);


        $superAdminEmail = config('global.super_admin_email');

        if ($superAdminEmail == $fields['email']) {
            return $this->superAdminLogin($request);
        }


        if ($fields['platform'] == 1) {

            // Check email
            $admin = Admin::where('email', $fields['email'])->where('status', '!=', '2')->first();

            if (!$admin || !Hash::check($fields['password'], $admin->password)) {
                return $this->employeeloginapp_mv($fields['email'], $fields['password'], $fields['platform']);
            }

            if ($admin->status == '0') {
                return $this->sendError('Unauthorised.', ['error' => 'Account Inactive'], 401);
            }

            $token = $admin->createToken('moval-app-admin-token', ['type:admin'])->plainTextToken;


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


            $response['detail'] = [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'address' => $admin->address,
                'mobile_no' => $admin->mobile_no,
                'is_set_password' => $admin->is_set_password,
                'status' => $admin->status == '1' ? 'Active' : 'Inactive',
                'parent_id' => $admin->parent_id,
                'can_download_report' => $admin->can_download_report,
                'letter_head_img' => $letter_head_img,
                'letter_footer_img' => $letter_footer_img,
                'signature_img' => $signature_img,
                'reference_no_prefix' => $admin->reference_no_prefix,
                'designation' => $admin->designation,
                'membership_no' => $admin->membership_no,
                'each_report_cost' => $admin->each_report_cost,
                'number_of_photograph' => $admin->number_of_photograph,
                'duraton_delete_photo' => $admin->duraton_delete_photo,
                'report_no_start_from' => $admin->report_no_start_from,
                'authorized_person_name' => $admin->authorized_person_name,
                'billing_start_from' => (isset($admin->billing_start_from) && $admin->billing_start_from != "") ? $admin->billing_start_from : "",
                'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $admin->updated_at->format('Y-m-d H:i:s'),
                'token' => $token,
                'module' => 'Motor valuation',
                'user_id' => "",
                'total_jobs' => "",
                'completed_jobs' => "",
                'type' => "",
                'role' => 'admin',
                'job_assigned_to_guest' => "",
                'is_guest_employee' => "",
                'amount_per_job' => "",
                'created_by_admin' => "",
                'modified_by_admin' => "",
                'last_login_date' => "",
                'is_bill_due' => "",
                'total_amount_due' => "",
                'last_date_of_payment' => "",
                'is_show_message_to_employee' => "",


            ];
        }


        if ($fields['platform'] == 2) {

            // Check email
            $admin = Admin_ms::where('email', $fields['email'])->where('status', '!=', '2')->first();

            if (!$admin || !Hash::check($fields['password'], $admin->password)) {

                return $this->employeeloginapp_ms($fields['email'], $fields['password'], $fields['platform']);
            }

            if ($admin->status == '0') {
                return $this->sendError('Unauthorised.', ['error' => 'Account Inactive'], 401);
            }

            $token = $admin->createToken('moval-app-admin-token', ['type:admin'])->plainTextToken;


            $letter_head_img = "";
            if (isset($admin->letter_head_img) && $admin->letter_head_img != "") {
                $letter_head_img = config('app.imageurl') . "" . $admin->letter_head_img;
            }

            $letter_footer_img = "";
            if (isset($admin->letter_footer_img) && $admin->letter_footer_img != "") {
                $letter_footer_img = config('app.imageurl') . "" . $admin->letter_footer_img;
            }

            $signature_img = "";
            if (isset($this->signature_img) && $this->signature_img != "") {
                $signature_img = config('app.imageurl') . "" . $admin->signature_img;
            }


            $response['detail'] = [
                'id' => $admin->id,
                'user_id' => "",
                'type' => "",
                'name' => $admin->name,
                'email' => $admin->email,
                'address' => $admin->address,
                'mobile_no' => $admin->mobile_no,
                'role' => $admin->role,
                'branch_id' => $admin->branch_id,
                'branch_name' => getBranchbybranchid($admin->branch_id),
                'is_set_password' => $admin->is_set_password,
                'status' => $admin->status == '1' ? 'Active' : 'Inactive',
                'parent_id' => $admin->parent_id,
                'can_download_report' => $admin->can_download_report,
                'letter_head_img' => $letter_head_img,
                'letter_footer_img' => $letter_footer_img,
                'signature_img' => $signature_img,
                'reference_no_prefix' => $admin->reference_no_prefix,
                'designation' => $admin->designation,
                'membership_no' => $admin->membership_no,
                'each_report_cost' => $admin->each_report_cost,
                'number_of_photograph' => $admin->number_of_photograph,
                'duraton_delete_photo' => $admin->duraton_delete_photo,
                'report_no_start_from' => $admin->report_no_start_from,
                'authorized_person_name' => $admin->authorized_person_name,
                'branch_admin' => $admin->branch_admin,
                'billing_start_from' => (isset($admin->billing_start_from) && $admin->billing_start_from != "") ? $admin->billing_start_from : "",
                'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $admin->updated_at->format('Y-m-d H:i:s'),
                'token' => $token,
                'module' => 'Motor Survey',
                'role' => $admin->role,
                'amount_per_job' => "",
                'address' => "",
                'branch_id' => "",
            ];


            if ($admin->parent_id != 0) {
                $branch_id = $admin->branch_id;

                $all = Branch::where('id', $branch_id)->first();
                //  print_r($data['sop_master']); die;
                if (!$all) {
                    $response['branch_detail'] = "Invalid branch assigned";
                } else {

                    $response['branch_detail'] = [
                        'id' => $all->id,
                        'branch_name' => $all->branch_name,
                        'email' => $all->email,
                        'address' => $all->address,
                        'mobile_no' => $all->mobile_no,
                        'contact_person' => $all->contact_person,
                        'admin_id' => $all->admin_id,
                        'created_by' => $all->created_by,
                        'created_at' => $all->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => ($all->updated_at) ? $all->updated_at->format('Y-m-d H:i:s') : "",
                    ];
                }
            }
        }
        return $this->sendResponse($response, 'Login successfully.');
    }


    public function employeeloginapp_ms($email, $password, $platform)
    {

        $fields['user_id'] = $email;
        $fields['password'] = $password;

        // Check email
        $employee = Employeems::where('user_id', $fields['user_id'])->where('status', '!=', '2')->first();

        if (!$employee || !Hash::check($fields['password'], $employee->password)) {

            return $this->worksop_contact_ms($email, $password, $platform);
        }

        if ($employee->status == '0') {
            return $this->sendError('Unauthorised.', ['error' => 'Account Inactive'], 401);
        }


        $TBL_ADMIN_INVOICE = config('global.admin_invoice_table');

        $adminInvoiceList = AdminInvoice::where($TBL_ADMIN_INVOICE . '.admin_id', '=', $employee->parent_admin_id)
            ->where($TBL_ADMIN_INVOICE . '.payment_status', '=', 'pending')
            ->where($TBL_ADMIN_INVOICE . '.bill_amount', '!=', 0)
            ->orderBy('created_at', 'desc')->get();

        $is_bill_due = 0;
        $isShowMessageToSubAdmin = 0;
        $totalAmountDue = 0;
        $lastDateOfPayment = "";


        $token = $employee->createToken('moval-app-employee-token', ['type:employee'])->plainTextToken;


        //'detail' => '',
        $response['detail'] = [
            'id' => $employee->id,
            'user_id' => $employee->user_id,
            'type' => $employee->type,
            'is_set_password' => $employee->is_set_password,
            'role' => "employee",
            'name' => $employee->name,
            'email' => $employee->email,
            'mobile_no' => $employee->mobile_no,
            'amount_per_job' => $employee->amount_per_job,
            'address' => $employee->address,
            'branch_id' => $employee->branch_id,
            'status' => $employee->status == '1' ? 'Active' : 'Inactive',
            'parent_id' => $employee->parent_admin_id,
            'can_download_report' => "",
            'letter_head_img' => "",
            'letter_footer_img' => "",
            'signature_img' => "",
            'reference_no_prefix' => "",
            'designation' => "",
            'membership_no' => "",
            'each_report_cost' => "",
            'number_of_photograph' => "",
            'duraton_delete_photo' => "",
            'report_no_start_from' => "",
            'authorized_person_name' => "",
            'billing_start_from' => "",
            'created_at' => $employee->created_at,
            'updated_at' => $employee->updated_at,
            'is_bill_due' => $is_bill_due,
            'total_amount_due' => $totalAmountDue,
            'module' => 'Motor Surevey ',
            'last_date_of_payment' => $lastDateOfPayment,
            'is_show_message_to_employee' => $isShowMessageToSubAdmin,
            'number_of_photograph' => "",
            'token' => $token
        ];

        /*$firebase_token = '';
  if ($request->firebase_token) {
  $firebase_token = $request->input('firebase_token');
  }
  */

        $firebase_token = "";
        $deviceId = '';

        $employee->last_login_date = date('Y-m-d H:i:s');
        $employee->firebase_token = $firebase_token;
        $employee->device_id = $deviceId;
        $employee->update();
        return $this->sendResponse($response, 'Login successfully.');
    }


    public function employeeloginapp_mv($email, $password, $platform)
    {

        $fields['user_id'] = $email;
        $fields['password'] = $password;

        // Check email
        $employee = Employee::where('user_id', $fields['user_id'])->where('status', '!=', '2')->first();

        // Check password
        if (!$employee || !Hash::check($fields['password'], $employee->password)) {
            return $this->sendError('Unauthorised.', ['error' => 'Bad Credentials'], 401);
        }

        if ($employee->status == '0') {
            return $this->sendError('Unauthorised.', ['error' => 'Account Inactive'], 401);
        }

        /*if ($employee->device_id != null && $employee->device_id != '' && $employee->device_id != $fields['deviceId']){
  return $this->sendError('Unauthorised.', ['error'=>'Invalid device'],401);
  }*/


        $is_bill_due = 0;
        $isShowMessageToSubAdmin = 0;
        $totalAmountDue = 0;
        $lastDateOfPayment = "";


        $token = $employee->createToken('moval-app-employee-token', ['type:employee'])->plainTextToken;


        $response['detail'] = [
            'id' => $employee->id,
            'user_id' => $employee->user_id,
            'total_jobs' => Job::where('submitted_by', '=', $employee->id)->count(),
            'completed_jobs' => Job::where('submitted_by', '=', $employee->id)->where('submission_date', '!=', null)->count(),
            'type' => $employee->type,
            'is_set_password' => $employee->is_set_password,
            'role' => 'employee',
            'name' => $employee->name,
            'email' => $employee->email,
            'job_assigned_to_guest' => $employee->job_assigned_to_guest,
            'is_guest_employee' => $employee->is_guest_employee,
            'mobile_no' => $employee->mobile_no,
            'amount_per_job' => $employee->amount_per_job,
            'address' => $employee->address,
            'created_by_admin' => $employee->created_by_admin,
            'modified_by_admin' => $employee->modified_by_admin,
            'status' => $employee->status == "1" ? "Active" : "Inactive",
            'last_login_date' => $employee->last_login_date,
            'created_at' => $employee->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $employee->updated_at->format('Y-m-d H:i:s'),
            'is_bill_due' => $is_bill_due,
            'role' => 'employee',
            'module' => 'Motor Valuation',
            'total_amount_due' => $totalAmountDue,
            'last_date_of_payment' => $lastDateOfPayment,
            'is_show_message_to_employee' => $isShowMessageToSubAdmin,
            'number_of_photograph' => "",
            'token' => $token,
            'parent_id' => "",
            'can_download_report' => "",
            'letter_head_img' => "",
            'letter_footer_img' => "",
            'signature_img' => "",
            'reference_no_prefix' => "",
            'designation' => "",
            'membership_no' => "",
            'each_report_cost' => "",
            'duraton_delete_photo' => "",
            'report_no_start_from' => "",
            'authorized_person_name' => "",
            'billing_start_from' => ""
        ];


        $firebase_token = '';
        if ($employee->exists('firebase_token')) {
            $firebase_token = $employee->firebase_token;
        }


        $employee->last_login_date = date('Y-m-d H:i:s');
        $employee->firebase_token = $firebase_token;
        $employee->device_id = '';
        $employee->update();
        return $this->sendResponse($response, 'Login successfully.');
    }


    public function worksop_contact_ms($email, $password, $platform)
    {


        // Check email

        $contactperson = Msbranchcontact::where('username', $email)->first();


        if (!$contactperson || !Hash::check($password, $contactperson->otp)) {
            return $this->sendError('Unauthorised.', ['error' => 'Bad Credentials'], 401);
        }


        $is_bill_due = 0;
        $isShowMessageToSubAdmin = 0;
        $totalAmountDue = 0;
        $lastDateOfPayment = "";


        $token = $contactperson->createToken('moval-app-branch_contact-token', ['type:branch_contact'])->plainTextToken;


        //'detail' => '',
        $response['detail'] = [
            'id' => $contactperson->id,
            'user_id' => $contactperson->username,
            'type' => "",
            'is_set_password' => "",
            'role' => "Branch Contact",
            'name' => $contactperson->name,
            'email' => $contactperson->email,
            'is_set_password' => $contactperson->is_set_password,
            'mobile_no' => $contactperson->mobile_no,
            'amount_per_job' => "",
            'status' => "",
            'parent_id' => "",
            'address' => "",
            'branch_id' => "",
            'can_download_report' => "",
            'letter_head_img' => "",
            'letter_footer_img' => "",
            'signature_img' => "",
            'reference_no_prefix' => "",
            'designation' => "",
            'membership_no' => "",
            'each_report_cost' => "",
            'number_of_photograph' => "",
            'duraton_delete_photo' => "",
            'report_no_start_from' => "",
            'authorized_person_name' => "",
            'billing_start_from' => "",
            'created_at' => $contactperson->created_at,
            'updated_at' => $contactperson->updated_at,
            'is_bill_due' => $is_bill_due,
            'total_amount_due' => $totalAmountDue,
            'module' => 'Motor Surevey ',
            'last_date_of_payment' => $lastDateOfPayment,
            'is_show_message_to_employee' => $isShowMessageToSubAdmin,
            'number_of_photograph' => "",
            'token' => $token
        ];

        /*$firebase_token = '';
  if ($request->firebase_token) {
  $firebase_token = $request->input('firebase_token');
  }
  */

        $firebase_token = "";
        $deviceId = '';

        //$employee->last_login_date = date('Y-m-d H:i:s');
        //$employee->firebase_token =  $firebase_token;
        //$employee->device_id =  $deviceId;
        //$employee->update();
        return $this->sendResponse($response, 'Login successfully.');
    }
}
