<?php

namespace App\Http\Controllers\MotorValuation;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Http\Resources\admin\AdminResource;
use App\Http\Resources\admin_invoice\AdminInvoiceResources;
use App\Http\Resources\admin_invoice\AdminInvoiceResourcesCollection;
use App\Http\Resources\admin\AdminCollection;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendMail;
use App\Models\Admin;
use App\Models\ImportantUpdates;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Client;
use App\Models\Clientms;
use App\Models\Employeems;
use App\Models\Admin_ms;
use App\Mail\WelcomeEmail;
use App\Models\AdminInvoice;
use App\Models\BranchContactPerson;
use App\Models\JobDetail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\JobFile;
use App\Models\AdminPaymentHistory;
use App\Models\AdminSettings;
use App\Models\AppSetting;
use App\Models\ClientBranch;
use App\Models\JobTransactionHistory;
use App\Models\PaymentHistory;
use App\Models\SuperAdmin;
use App\Models\SuperAdmin_ms;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Traits\AdminUploadTrait;
use Razorpay\Api\Api;
use PDF; 

class AdminController extends BaseController
{
  use AdminUploadTrait;
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {


    if ($request->platform == 1) {

      $TBL_ADMIN      = config('global.admin_table');
      $created_by     = Auth::user()->id;

      if ($request->user()->tokenCan('type:employee')) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      } else if ($request->user()->tokenCan('type:admin')) {
        $loggedInAdmin = Admin::where('id', '=', $created_by)->where('status', '!=', '2')->first();
        if (is_null($loggedInAdmin)) {
          return $this->sendError('Admin does not exist.');
        }
        if ($loggedInAdmin->parent_id != 0) {
          return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }
      }

      if ($request->user()->tokenCan('type:super_admin')) {
        $allAdmins = Admin::select([$TBL_ADMIN . '.*'])
          ->where($TBL_ADMIN . '.parent_id', '0')
          ->where($TBL_ADMIN . '.status', '!=', '2')
          ->orderBy('created_at', 'desc');
      } else {
        $allAdmins = Admin::join($TBL_ADMIN . ' as created_by_admin', 'created_by_admin.id', '=', $TBL_ADMIN . '.parent_id')
          ->where($TBL_ADMIN . '.parent_id', $created_by)
          ->where($TBL_ADMIN . '.status', '!=', '2')
          ->orderBy('created_at', 'desc')
          ->select('created_by_admin.name as created_by', $TBL_ADMIN . '.*');
      }


      $searchKeyword = "";
      if ($request->exists('search_keyword')) {
        $searchKeyword = $request->input('search_keyword');
        if ($searchKeyword != '') {
          $allAdmins =  $allAdmins->where(function ($query) use ($TBL_ADMIN, $searchKeyword) {
            $query->orWhere($TBL_ADMIN . '.name', 'LIKE', '%' . $searchKeyword . '%')
              ->orWhere($TBL_ADMIN . '.email', 'LIKE', '%' . $searchKeyword . '%')
              ->orWhere($TBL_ADMIN . '.address', 'LIKE', '%' . $searchKeyword . '%')
              ->orWhere($TBL_ADMIN . '.id', 'LIKE', '%' . $searchKeyword . '%');
          });
        }
      }

      $allAdmins = $request->exists('all')
        ? AdminResource::collection($allAdmins->get())
        : new AdminCollection($allAdmins->paginate(20));
      return $this->sendResponse($allAdmins, 'Posts fetched.');
    } else if ($request->platform == 2) {


      $TBL_ADMIN      = 'tbl_ms_admin';
      $created_by     = Auth::user()->id;

      if ($request->user()->tokenCan('type:employee')) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      } else if ($request->user()->tokenCan('type:admin')) {
        $loggedInAdmin = Admin_ms::where('id', '=', $created_by)->where('status', '!=', '2')->first();
        if (is_null($loggedInAdmin)) {
          return $this->sendError('Admin does not exist.');
        }
        if ($loggedInAdmin->parent_id != 0) {
          // return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }
      }

      if ($request->user()->tokenCan('type:super_admin')) {
        $allAdmins = Admin_ms::select([$TBL_ADMIN . '.*'])
          ->where($TBL_ADMIN . '.parent_id', '0')
          ->where($TBL_ADMIN . '.status', '!=', '2')
          ->orderBy('created_at', 'desc');
      } else {
        $allAdmins = Admin_ms::join($TBL_ADMIN . ' as created_by_admin', 'created_by_admin.id', '=', $TBL_ADMIN . '.parent_id')
          ->where($TBL_ADMIN . '.parent_id', $created_by)
          ->where($TBL_ADMIN . '.status', '!=', '2')
          ->orderBy('created_at', 'desc')
          ->select('created_by_admin.name as created_by', $TBL_ADMIN . '.*');
      }


      $searchKeyword = "";
      if ($request->exists('search_keyword')) {
        $searchKeyword = $request->input('search_keyword');
        if ($searchKeyword != '') {
          $allAdmins =  $allAdmins->where(function ($query) use ($TBL_ADMIN, $searchKeyword) {
            $query->orWhere($TBL_ADMIN . '.name', 'LIKE', '%' . $searchKeyword . '%')
              ->orWhere($TBL_ADMIN . '.email', 'LIKE', '%' . $searchKeyword . '%')
              ->orWhere($TBL_ADMIN . '.address', 'LIKE', '%' . $searchKeyword . '%')
              ->orWhere($TBL_ADMIN . '.id', 'LIKE', '%' . $searchKeyword . '%');
          });
        }
      }

      $allAdmins = $request->exists('all')
        ? AdminResource::collection($allAdmins->get())
        : new AdminCollection($allAdmins->paginate(20));
      return $this->sendResponse($allAdmins, 'Posts fetched.');
    } else {
      return $this->sendError('Platfrom Not Matching.', ['error' => 'Enter Correct Platform'], 401);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {


    $TBL_ADMIN   = config('global.admin_table');

    $created_by = Auth::user()->id;

    $duraton_delete_photo = "0";

    if ($request->user()->tokenCan('type:employee')) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    } else if ($request->user()->tokenCan('type:admin')) {
      $loggedInAdmin = Admin::where('id', '=', $created_by)->where('status', '!=', '2')->first();
      if (is_null($loggedInAdmin)) {
        return $this->sendError('Admin does not exist.');
      }

      if ($loggedInAdmin->parent_id != 0) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      }

      $duraton_delete_photo = $loggedInAdmin->duraton_delete_photo;
    }



    $arrFieldsToBeValidate = [
      'name' => 'required|string',
      'email' => 'required|string',
      'address' => 'required|string',
      'mobile_no' => 'required|numeric|digits:10',
      'password' => 'required|string|min:6|max:15',
      'status' => 'required|string|in:active,inactive',
    ];

    if ($request->user()->tokenCan('type:admin')) {
      $arrFieldsToBeValidate["can_download_report"] = 'required|string|in:0,1';
    }

    $letter_head_img = "";
    $letter_footer_img = "";
    $signature_img = "";

    if ($request->user()->tokenCan('type:super_admin')) {
      $arrFieldsToBeValidate["letter_head_img"] = 'required|mimes:png,jpg,jpeg,mp4,mov|max:10240';
      //$arrFieldsToBeValidate["letter_footer_img"] = 'required|mimes:png,jpg,jpeg,mp4,mov|max:10240';
      $arrFieldsToBeValidate["designation"] = 'required|string';
      $arrFieldsToBeValidate["membership_no"] = 'required|string';
      $arrFieldsToBeValidate["each_report_cost"] = 'required|numeric';
      $arrFieldsToBeValidate["number_of_photograph"] = 'required|numeric';
      $arrFieldsToBeValidate["duraton_delete_photo"] = 'required|numeric';
      $arrFieldsToBeValidate["reference_no_prefix"] = 'required|string';
      $arrFieldsToBeValidate["report_no_start_from"] = 'required|numeric';
      $arrFieldsToBeValidate["billing_start_from"] = 'required|date_format:Y-m-d';
      $arrFieldsToBeValidate["authorized_person_name"] = 'required|string';
      // $arrFieldsToBeValidate["role"] = 'required|string';
    }

    $fields = $request->validate($arrFieldsToBeValidate);

    if ($request->platform == 2) {

      $TBL_MS_SUPERADMIN = 'tbl_ms_super_admin';
      $TBL_MS_ADMIN = 'tbl_ms_admin';
      $TBL_MS_WK = 'tbl_ms_workshop_contact_person';
      $TBL_MS_EMPLOYEE = 'tbl_ms_employee';



      $fields1 = $request->validate(['email' => 'required|string|unique:' . $TBL_MS_SUPERADMIN . ',email|unique:' . $TBL_MS_ADMIN . ',email|unique:' . $TBL_MS_WK . ',email|unique:' . $TBL_MS_EMPLOYEE . ',email']);






      /* if(checkEmailExistOrNot($fields['email'])!=""){
 return $this->sendError('This Email Id is already used by the other user so please enter other email Id.',['error'=>'email already used'],422);
}*/
    } else {


      $TBL_SUPERADMIN = 'tbl_super_admin';
      $TBL_ADMIN = 'tbl_admin';
      $TBL_EMPLOYEE = 'tbl_employee';


      $fields1 = $request->validate(['email' => 'required|string|unique:' . $TBL_SUPERADMIN . ',email|unique:' . $TBL_ADMIN . ',email|unique:' . $TBL_EMPLOYEE . ',email']);



      /* if(checkEmailExistOrNotmv($fields['email'])!=""){ 
 return $this->sendError('This Email Id is already used by the other user so please enter other email Id.',['error'=>'email already used'],422);
}   
*/
    }


    $arrFieldsToBeAdd = [
      'name' => $fields['name'],
      'email' => $fields['email'],
      'parent_id' => 0,
      'password' => bcrypt($fields['password']),
      'address' => $fields['address'],
      'mobile_no' => $fields['mobile_no'],
      'is_set_password' => 'no',
      'status' => ($fields['status'] == "active" ? '1' : '0')
    ];

    if ($request->user()->tokenCan('type:admin')) {
      $arrFieldsToBeAdd["can_download_report"] = $fields['can_download_report'];
      $arrFieldsToBeAdd["parent_id"] = $created_by;
    }

    if ($request->user()->tokenCan('type:super_admin')) {

      if ($request->hasFile('letter_head_img')) {
        $letter_head_img = $this->storeImage($request->file('letter_head_img'), 'admin_document/letter_head_img');
      }

      if ($request->hasFile('letter_footer_img')) {
        $letter_footer_img = $this->storeImage($request->file('letter_footer_img'), 'admin_document/letter_footer_img');
      }
      //$letter_footer_img = $this->storeImage($request->file('letter_footer_img'), 'admin_document/letter_footer_img');
      if ($request->hasFile('signature_img')) {
        $signature_img = $this->storeImage($request->file('signature_img'), 'admin_document/signature_img');
      }
      // if ($file = $request->file('letter_head_img')) {
      //   $result = UploadFilesToS3('s3', 'pdf_resources/', $file);
      //   $letter_head_img =   $result['store_image_name']; 
      //   // $letter_head_img = $file->store('pdf_resources', 'public');


      // } else {
      //   return $this->sendError('Letter head image not found.');
      // }

      // if ($file = $request->file('letter_footer_img')) {
      //   $result = UploadFilesToS3('s3', 'pdf_resources/', $file);
      //   $letter_footer_img =   $result['store_image_name']; 


      //   // $letter_footer_img = $file->store('pdf_resources', 'public');
      // }

      // if ($file = $request->file('signature_img')) {
      //   $result = UploadFilesToS3('s3', 'pdf_resources/', $file);
      //   $signature_img =   $result['store_image_name']; 


      //   // $signature_img = $file->store('pdf_resources', 'public');
      // }

      $arrFieldsToBeAdd["letter_head_img"] = $letter_head_img ?? null;
      $arrFieldsToBeAdd["letter_footer_img"] = $letter_footer_img ?? null;
      $arrFieldsToBeAdd["signature_img"] = $signature_img ?? null;
      $arrFieldsToBeAdd["designation"] = $fields['designation'];
      $arrFieldsToBeAdd["membership_no"] = $fields['membership_no'];
      $arrFieldsToBeAdd["each_report_cost"] = $fields['each_report_cost'];
      $arrFieldsToBeAdd["number_of_photograph"] = $fields['number_of_photograph'];
      $arrFieldsToBeAdd["duraton_delete_photo"] = $fields['duraton_delete_photo'];
      $arrFieldsToBeAdd["reference_no_prefix"] = $fields['reference_no_prefix'];
      $arrFieldsToBeAdd["report_no_start_from"] = $fields['report_no_start_from'];
      $arrFieldsToBeAdd["billing_start_from"] = $fields['billing_start_from'];
      $arrFieldsToBeAdd["authorized_person_name"] = $fields['authorized_person_name'];
      $arrFieldsToBeAdd["next_billing_date"] = date('Y-m-d', strtotime($fields['billing_start_from'] . " +1 month"));
      //$arrFieldsToBeAdd["next_billing_date"] = date('Y-m-d', strtotime($fields['billing_start_from'] . " +5 days"));
      $duraton_delete_photo = $fields['duraton_delete_photo'];
      $arrFieldsToBeAdd['role'] = "admin";
    }

    // print_r($arrFieldsToBeAdd); die;


    if ($request->platform == 1) {
      $admin = Admin::create($arrFieldsToBeAdd);
    } else {
      $admin = Admin_ms::create($arrFieldsToBeAdd);
    }



    /*$admin = Admin::create($arrFieldsToBeAdd);
Admin_ms::create($arrFieldsToBeAdd);
*/

    //new mail code
  $email = $fields['email'];
  $maildata = [
  'subject' => 'Welcome to the Moval',
  'from_name' => config('app.from_name'),
  'fullName' => $fields['name'],
  'userId' => $fields['email'],
  'password' => $fields['password'],
  'months' => $duraton_delete_photo,
  'adminLoginLink' => config('app.website_url'),
  'contactEmail' => config('app.from_email_address'),
  'mail_template' => "emails.admin-welcome-email"
  ];
  Mail::to($email)->send(new WelcomeEmail($maildata));






    return $this->sendResponse(new AdminResource($admin), "success", 201);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, $id)
  {

    if ($request->platform == 1) {

      $created_by = Auth::user()->id;

      if ($request->user()->tokenCan('type:employee')) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      } else if ($request->user()->tokenCan('type:admin')) {
        $loggedInAdmin = Admin::where('id', '=', $created_by)->where('status', '!=', '2')->first();
        if (is_null($loggedInAdmin)) {
          return $this->sendError('Admin does not exist.');
        }
        if ($loggedInAdmin->parent_id != 0) {
          return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }
      }

      if ($request->user()->tokenCan('type:admin')) {
        $admin = Admin::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', $created_by)->first();
      } else {
        $admin = Admin::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', '0')->first();
      }

      if (is_null($admin)) {
        return $this->sendError('Admin does not exist.');
      }
      return $this->sendResponse(new AdminResource($admin), 'Post fetched.');
    } else if ($request->platform == 2) {
      $created_by = Auth::user()->id;

      if ($request->user()->tokenCan('type:employee')) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      } else if ($request->user()->tokenCan('type:admin')) {
        $loggedInAdmin = Admin_ms::where('id', '=', $created_by)->where('status', '!=', '2')->first();
        if (is_null($loggedInAdmin)) {
          return $this->sendError('Admin does not exist.');
        }
        if ($loggedInAdmin->parent_id != 0) {
          return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }
      }

      if ($request->user()->tokenCan('type:admin')) {
        $admin = Admin_ms::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', $created_by)->first();
      } else {
        $admin = Admin_ms::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', '0')->first();
      }

      if (is_null($admin)) {
        return $this->sendError('Admin does not exist.');
      }
      return $this->sendResponse(new AdminResource($admin), 'Post fetched.');
    } else {
      return $this->sendError('Platfrom Not Matching.', ['error' => 'Enter Correct Platform'], 401);
    }
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

    if ($request->platform == 1) {
      $TBL_ADMIN   = config('global.admin_table');

      $update_by = Auth::user()->id;

      if ($request->user()->tokenCan('type:employee')) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      } else if ($request->user()->tokenCan('type:admin')) {
        $loggedInAdmin = Admin::where('id', '=', $update_by)->where('status', '!=', '2')->first();
        if (is_null($loggedInAdmin)) {
          return $this->sendError('Admin does not exist.');
        }
        if ($loggedInAdmin->parent_id != 0) {
          return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }
      }

      if ($request->user()->tokenCan('type:admin')) {
        $admin = Admin::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', $update_by)->first();
      } else {
        $admin = Admin::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', '0')->first();
      }

      if (is_null($admin)) {
        return $this->sendError('Admin does not exist.');
      }


      $TBL_SUPERADMIN = 'tbl_super_admin';
      $TBL_ADMIN = 'tbl_admin';
      $TBL_EMPLOYEE = 'tbl_employee';

      $arrFieldsToBeValidate = [
        'name' => 'required|string',
        'email' => 'required|string|unique:' . $TBL_ADMIN . ',email,' . $id . '|unique:' . $TBL_SUPERADMIN . ',email|unique:' . $TBL_EMPLOYEE . ',email',
        'address' => 'required|string',
        'mobile_no' => 'required|numeric|digits:10',
        'status' => 'required|string|in:active,inactive'
      ];

      if ($request->user()->tokenCan('type:admin')) {
        $arrFieldsToBeValidate['can_download_report'] = 'required|string|in:0,1';
      }

      $letter_head_img = "";
      $letter_footer_img = "";
      $signature_img = "";

      if ($request->user()->tokenCan('type:super_admin')) {
        // $arrFieldsToBeValidate["letter_head_img"] = 'required|mimes:png,jpg,jpeg,mp4,mov|max:10240';
        // $arrFieldsToBeValidate["letter_footer_img"] = 'required|mimes:png,jpg,jpeg,mp4,mov|max:10240';
        $arrFieldsToBeValidate["designation"] = 'required|string';
        $arrFieldsToBeValidate["membership_no"] = 'required|string';
        $arrFieldsToBeValidate["each_report_cost"] = 'required|numeric';
        $arrFieldsToBeValidate["number_of_photograph"] = 'required|numeric';
        $arrFieldsToBeValidate["duraton_delete_photo"] = 'required|numeric';
        $arrFieldsToBeValidate["report_no_start_from"] = 'required|numeric';
        $arrFieldsToBeValidate["reference_no_prefix"] = 'required|string';
        $arrFieldsToBeValidate["billing_start_from"] = 'required|date_format:Y-m-d';
        $arrFieldsToBeValidate["authorized_person_name"] = 'required|string';
      }


      $fields = $request->validate($arrFieldsToBeValidate);

      $admin->name = $fields['name'];
      $admin->email = $fields['email'];
      $admin->address = $fields['address'];
      $admin->mobile_no = $fields['mobile_no'];

      if ($request->user()->tokenCan('type:admin')) {
        $admin->can_download_report = $fields['can_download_report'];
      }

      if ($request->user()->tokenCan('type:super_admin')) {

        if ($request->hasFile('letter_head_img')) {
          $letter_head_img = $this->storeImage($request->file('letter_head_img'), 'admin_document/letter_head_img');
        }

        if ($request->hasFile('letter_footer_img')) {
          $letter_footer_img = $this->storeImage($request->file('letter_footer_img'), 'admin_document/letter_footer_img');
        }
        //$letter_footer_img = $this->storeImage($request->file('letter_footer_img'), 'admin_document/letter_footer_img');
        if ($request->hasFile('signature_img')) {
          $signature_img = $this->storeImage($request->file('signature_img'), 'admin_document/signature_img');
        }
        
        //dd($letter_footer_img);
        // Delete existing letter_head_img if a new one is provided
        if ($letter_head_img && $admin->letter_head_img) {
          Storage::disk('public')->delete($admin->letter_head_img);
          $admin->letter_head_img = $letter_head_img;
        }else{
          $admin->letter_head_img = $letter_head_img;
        }

        // Delete existing letter_footer_img if a new one is provided
        if ($letter_footer_img && $admin->letter_footer_img) {
          Storage::disk('public')->delete($admin->letter_footer_img);
          $admin->letter_footer_img = $letter_footer_img;
        }else{
          $admin->letter_footer_img = $letter_footer_img;
        }

        // Delete existing signature_img if a new one is provided
        if ($signature_img && $admin->signature_img) {
          Storage::disk('public')->delete($admin->signature_img);
          $admin->signature_img = $signature_img;
        }else{
          $admin->signature_img = $signature_img;
        }

        $admin->designation = $fields['designation'];
        $admin->membership_no = $fields['membership_no'];
        $admin->each_report_cost = $fields['each_report_cost'];
        $admin->number_of_photograph = $fields['number_of_photograph'];
        $admin->duraton_delete_photo = $fields['duraton_delete_photo'];
        $admin->reference_no_prefix = $fields['reference_no_prefix'];
        $admin->report_no_start_from = $fields['report_no_start_from'];
        $admin->billing_start_from = $fields['billing_start_from'];
        $admin->authorized_person_name = $fields['authorized_person_name'];
      }

      $admin->status = $fields['status'] == "active" ? '1' : '0';

      if ($request->exists('password')) {
        $password = $request->input('password');
        if ($password != "") {
          $fields = $request->validate([
            'password' => 'required|string|min:6|max:15'
          ]);
          $admin->password = bcrypt($fields['password']);
        }
      }

      $admin->update();
      return $this->sendResponse(new AdminResource($admin), 'Admin updated.', 200);
    } else if ($request->platform == 2) {
      $TBL_ADMIN      = 'tbl_ms_admin';

      $update_by = Auth::user()->id;
      //dd($update_by);
      if ($request->user()->tokenCan('type:employee')) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      } else if ($request->user()->tokenCan('type:admin')) {
        $loggedInAdmin = Admin_ms::where('id', '=', $update_by)->where('status', '!=', '2')->first();
        if (is_null($loggedInAdmin)) {
          return $this->sendError('Admin does not exist.');
        }
        if ($loggedInAdmin->parent_id != 0) {
          return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }
      }

      if ($request->user()->tokenCan('type:admin')) {
        //dd($request->user()->tokenCan('type:admin'));
        $admin = Admin_ms::where('id', '=', $id)
          ->where('status', '!=', '2')->first();
          //->where('parent_id', '=', $update_by)
      } else {
        $admin = Admin_ms::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', '0')->first();
      }

      if (is_null($admin)) {
        return $this->sendError('Admin does not exist.');
      }

      $TBL_MS_SUPERADMIN = 'tbl_ms_super_admin';
      $TBL_MS_WK = 'tbl_ms_workshop_contact_person';
      $TBL_MS_EMPLOYEE = 'tbl_ms_employee';

      $arrFieldsToBeValidate = [
        'name' => 'required|string',
        'email' => 'required|string|unique:' . $TBL_ADMIN . ',email,' . $id . '|unique:' . $TBL_MS_SUPERADMIN . ',email|unique:' . $TBL_MS_WK . ',email|unique:' . $TBL_MS_EMPLOYEE . ',email',
        'address' => 'required|string',
        'mobile_no' => 'required|numeric|digits:10',
        'status' => 'required|string|in:active,inactive'
      ];

      if ($request->user()->tokenCan('type:admin')) {
        $arrFieldsToBeValidate['can_download_report'] = 'required|string|in:0,1';
      }

      $letter_head_img = "";
      $letter_footer_img = "";
      $signature_img = "";

      if ($request->user()->tokenCan('type:super_admin')) {
        // $arrFieldsToBeValidate["letter_head_img"] = 'required|mimes:png,jpg,jpeg,mp4,mov|max:10240';
        // $arrFieldsToBeValidate["letter_footer_img"] = 'required|mimes:png,jpg,jpeg,mp4,mov|max:10240';
        $arrFieldsToBeValidate["designation"] = 'required|string';
        $arrFieldsToBeValidate["membership_no"] = 'required|string';
        $arrFieldsToBeValidate["each_report_cost"] = 'required|numeric';
        $arrFieldsToBeValidate["number_of_photograph"] = 'required|numeric';
        $arrFieldsToBeValidate["duraton_delete_photo"] = 'required|numeric';
        $arrFieldsToBeValidate["report_no_start_from"] = 'required|numeric';
        $arrFieldsToBeValidate["reference_no_prefix"] = 'required|string';
        $arrFieldsToBeValidate["billing_start_from"] = 'required|date_format:Y-m-d';
        $arrFieldsToBeValidate["authorized_person_name"] = 'required|string';
      }


      $fields = $request->validate($arrFieldsToBeValidate);

      $admin->name = $fields['name'];
      $admin->email = $fields['email'];
      $admin->address = $fields['address'];
      $admin->mobile_no = $fields['mobile_no'];
      $here = '';
      if ($request->user()->tokenCan('type:admin')) {
        $admin->can_download_report = $fields['can_download_report'];
      }

      if ($request->user()->tokenCan('type:super_admin')) {
        $here = '1Super';

        if ($request->hasFile('letter_head_img')) {
          $letter_head_img = $this->storeImage($request->file('letter_head_img'), 'admin_document/letter_head_img');
        }

        if ($request->hasFile('letter_footer_img')) {
          $letter_footer_img = $this->storeImage($request->file('letter_footer_img'), 'admin_document/letter_footer_img');
        }
        //$letter_footer_img = $this->storeImage($request->file('letter_footer_img'), 'admin_document/letter_footer_img');
        if ($request->hasFile('signature_img')) {
          $signature_img = $this->storeImage($request->file('signature_img'), 'admin_document/signature_img');
        }
        
        //dd($signature_img);
        // Delete existing letter_head_img if a new one is provided
        if ($letter_head_img && $admin->letter_head_img) {
            Storage::disk('public')->delete($admin->letter_head_img);
            $admin->letter_head_img = $letter_head_img;
          }else{
            if (!empty($letter_head_img)) {
              $admin->letter_head_img = $letter_head_img;
            }
            
          }

        // Delete existing letter_footer_img if a new one is provided
        if ($letter_footer_img && $admin->letter_footer_img) {
          Storage::disk('public')->delete($admin->letter_footer_img);
          $admin->letter_footer_img = $letter_footer_img;
        }else{
          if (!empty($letter_footer_img)) {
            $admin->letter_footer_img = $letter_footer_img;
          }
          
        }

        // Delete existing signature_img if a new one is provided
        if ($signature_img && $admin->signature_img) {
          Storage::disk('public')->delete($admin->signature_img);
          $admin->signature_img = $signature_img;
        }else{
          if (!empty($signature_img)) {
            $admin->signature_img = $signature_img;
          }
          
        }

        $admin->designation = $fields['designation'];
        $admin->membership_no = $fields['membership_no'];
        $admin->each_report_cost = $fields['each_report_cost'];
        $admin->number_of_photograph = $fields['number_of_photograph'];
        $admin->duraton_delete_photo = $fields['duraton_delete_photo'];
        $admin->reference_no_prefix = $fields['reference_no_prefix'];
        $admin->report_no_start_from = $fields['report_no_start_from'];
        $admin->billing_start_from = $fields['billing_start_from'];
        $admin->authorized_person_name = $fields['authorized_person_name'];
      }

      $admin->status = $fields['status'] == "active" ? '1' : '0';

      if ($request->exists('password')) {
        $password = $request->input('password');
        if ($password != "") {
          $fields = $request->validate([
            'password' => 'required|string|min:6|max:15'
          ]);
          $admin->password = bcrypt($fields['password']);
        }
      }

      $admin->update();
      return $this->sendResponse(new AdminResource($admin), 'Admin Mv updated.'. $here, 200);
    } else {
      return $this->sendError('Platfrom Not Matching.', ['error' => 'Enter Correct Platform'], 401);
    }
  }

  /** 
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id)
  {

    if ($request->platform == 1) {
      $update_by = Auth::user()->id;

      if ($request->user()->tokenCan('type:employee')) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      } else if ($request->user()->tokenCan('type:admin')) {
        $parent_admin_id    = getAdminIdIfAdminLoggedIn($update_by);
        if ($parent_admin_id != $update_by) {
          return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }
      }

      if ($request->user()->tokenCan('type:admin')) {
        $admin = Admin::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', $update_by)->first();
          if ($admin->trashed()) {
            $admin->restore();
            return $this->sendResponse([], 'Admin Restore successfully.', 200);
          }else{
            $admin->delete();
            return $this->sendResponse([], 'Admin Deleted successfully.', 200);
          }
         

        

        $adminUseCount = 0;
        $adminUseCount += Employee::where('created_by', '=', $id)->orWhere('modified_by', '=', $id)->count();
        $adminUseCount += Job::where('approved_by', '=', $id)->orWhere('rejected_by', '=', $id)
          ->orWhere('completed_by', '=', $id)->count();
        $adminUseCount += Client::where('created_by', '=', $id)->orWhere('modified_by', '=', $id)->count();

        if ($adminUseCount > 0) {
          return $this->sendError('Admin account in use.');
        }

        $admin->delete();
      } else {

        $TBL_IMPORTANT_UPDATES      = config('global.tbl_important_updates');
        $TBL_JOB_FILES_TEMP        = config('global.job_files_table_temp');

        $admin = Admin::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', '0')->first();


        if (isset($admin->letter_head_img)) {
          $imagePath = public_path('storage/' . $admin->letter_head_img);
          if (File::exists($imagePath)) {
            unlink($imagePath);
          }
        }
        if (isset($admin->letter_footer_img)) {
          $imagePath = public_path('storage/' . $admin->letter_footer_img);
          if (File::exists($imagePath)) {
            unlink($imagePath);
          }
        }
        if (isset($admin->signature_img)) {
          $imagePath = public_path('storage/' . $admin->signature_img);
          if (File::exists($imagePath)) {
            unlink($imagePath);
          }
        }

        Admin::where('parent_id', '=', $id)->delete();
        Employee::where('parent_admin_id', '=', $id)->delete();
        $jobList = Job::where('parent_admin_id', '=', $id)->get();
        foreach ($jobList as $job) {
          JobDetail::where('job_id', '=', $job->id)->delete();
          JobTransactionHistory::where('job_id', '=', $job->id)->delete();
          PaymentHistory::where('job_id', '=', $job->id)->delete();
          $images = DB::table($TBL_JOB_FILES_TEMP)->where([
            ['job_id', '=', $job->id],
          ])->get();
          foreach ($images as $row) {
            $imagePath = public_path('storage/' . $row->name);
            if (File::exists($imagePath)) {
              unlink($imagePath);
            }
            $row->delete();
          }

          $jobFiles = JobFile::where([
            ['job_id', '=', $job->id],
          ])->get();

          foreach ($jobFiles as $jobFile) {
            $imagePath = public_path('storage/' . $jobFile->name);
            if (File::exists($imagePath)) {
              unlink($imagePath);
            }
            $jobFile->delete();
          }
        }

        Client::where('parent_admin_id', '=', $id)->delete();
        BranchContactPerson::where('parent_admin_id', '=', $id)->delete();
        ClientBranch::where('parent_admin_id', '=', $id)->delete();
        AdminInvoice::where('admin_id', '=', $id)->delete();

        $allImportantUpdatesForMarkSeen = ImportantUpdates::select([$TBL_IMPORTANT_UPDATES . '.id']);
        $allImportantUpdatesForMarkSeen =  $allImportantUpdatesForMarkSeen->where(function ($query) use ($TBL_IMPORTANT_UPDATES, $id) {
          $query->orWhere($TBL_IMPORTANT_UPDATES . '.to_admin_ids', 'LIKE', '%' . $id . ',%')
            ->orWhere($TBL_IMPORTANT_UPDATES . '.to_admin_ids', 'LIKE', '%,' . $id . '%')
            ->orWhere($TBL_IMPORTANT_UPDATES . '.to_admin_ids', 'LIKE', '%,' . $id . ',%');
        });
        $allImportantUpdatesForMarkSeen->delete();

        $admin->delete();
      }

      return $this->sendResponse([], 'Admin delete successfully.', 200);
    } else if ($request->platform == 2) {

      $update_by = Auth::user()->id;

      if ($request->user()->tokenCan('type:employee')) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      } else if ($request->user()->tokenCan('type:admin')) {
        $parent_admin_id    = getAdminIdIfAdminLoggedIn1($update_by);
        if ($parent_admin_id != $update_by) {
          return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }
      }

      if ($request->user()->tokenCan('type:admin')) {
        $admin = Admin_ms::withTrashed()->where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', $update_by)->first();

          if ($admin->trashed()) {
            $admin->restore();
            return $this->sendResponse([], 'Admin Restore successfully.', 200);
          }else{
            $admin->delete();
            return $this->sendResponse([], 'Admin Deleted successfully.', 200);
          }
        // if (is_null($admin)) {
        //   return $this->sendError('Admin does not exist.');
        // }

        $adminUseCount = 0;
        $adminUseCount += Employeems::where('created_by', '=', $id)->orWhere('modified_by', '=', $id)->count();
        /* $adminUseCount += Job::where('approved_by','=', $id)->orWhere('rejected_by','=', $id)
->orWhere('completed_by','=', $id)->count();*/
        $adminUseCount += Clientms::where('created_by', '=', $id)->orWhere('modified_by', '=', $id)->count();

        if ($adminUseCount > 0) {
          return $this->sendError('Admin account in use.');
        }

        $admin->delete();

        return $this->sendResponse([], 'Admin delete successfully.', 200);
      } else {

        $TBL_IMPORTANT_UPDATES      = config('global.tbl_important_updates');
        $TBL_JOB_FILES_TEMP        = config('global.job_files_table_temp');

        $admin = Admin_ms::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', '0')->first();


        if (isset($admin->letter_head_img)) {
          $imagePath = public_path('storage/' . $admin->letter_head_img);
          if (File::exists($imagePath)) {
            unlink($imagePath);
          }
        }
        if (isset($admin->letter_footer_img)) {
          $imagePath = public_path('storage/' . $admin->letter_footer_img);
          if (File::exists($imagePath)) {
            unlink($imagePath);
          }
        }
        if (isset($admin->signature_img)) {
          $imagePath = public_path('storage/' . $admin->signature_img);
          if (File::exists($imagePath)) {
            unlink($imagePath);
          }
        }

        Admin_ms::where('parent_id', '=', $id)->delete();
        Employeems::where('parent_admin_id', '=', $id)->delete();
        /*$jobList = Job::where('parent_admin_id','=', $id)->get();
foreach($jobList as $job){
JobDetail::where('job_id','=', $job->id)->delete();
JobTransactionHistory::where('job_id','=', $job->id)->delete();
PaymentHistory::where('job_id','=', $job->id)->delete();
$images = DB::table($TBL_JOB_FILES_TEMP)->where([
['job_id', '=', $job->id],
])->get();
foreach($images as $row){
$imagePath = public_path('storage/'.$row->name);
if(File::exists($imagePath)){
unlink($imagePath);
}
$row->delete();
}

$jobFiles = JobFile::where([
['job_id', '=', $job->id],
])->get();

foreach($jobFiles as $jobFile){
$imagePath = public_path('storage/'.$jobFile->name);
if(File::exists($imagePath)){
unlink($imagePath);
}
$jobFile->delete();
}*/
      }

      Clientms::where('parent_admin_id', '=', $id)->delete();
      //BranchContactPerson::where('parent_admin_id','=', $id)->delete();
      //ClientBranch::where('parent_admin_id','=', $id)->delete();
      //AdminInvoice::where('admin_id','=', $id)->delete();

      /* $allImportantUpdatesForMarkSeen = ImportantUpdates::select([$TBL_IMPORTANT_UPDATES.'.id']);
$allImportantUpdatesForMarkSeen =  $allImportantUpdatesForMarkSeen->where(function($query) use ($TBL_IMPORTANT_UPDATES,$id) {
$query->orWhere($TBL_IMPORTANT_UPDATES.'.to_admin_ids','LIKE','%'.$id.',%')
->orWhere($TBL_IMPORTANT_UPDATES.'.to_admin_ids','LIKE','%,'.$id.'%')
->orWhere($TBL_IMPORTANT_UPDATES.'.to_admin_ids','LIKE','%,'.$id.',%');
});
$allImportantUpdatesForMarkSeen->delete();*/

      $admin->delete();
      return $this->sendResponse([], 'Admin delete successfully.', 200);
    } else {
      return $this->sendError('Platfrom Not Matching.', ['error' => 'Enter Correct Platform'], 401);
    }
  }


  /**
   * Updat status of specified resource in storage.
   *
   * @param  int  $id
   * @param  String  $status
   * @return \Illuminate\Http\Response
   */
  public function updateStatus(Request $request, $id, $status)
  {
    if ($request->platform == 1) {
      $update_by = Auth::user()->id;

      if ($request->user()->tokenCan('type:employee')) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      } else if ($request->user()->tokenCan('type:admin')) {
        $loggedInAdmin = Admin::where('id', '=', $update_by)->where('status', '!=', '2')->first();
        if (is_null($loggedInAdmin)) {
          return $this->sendError('Admin does not exist.');
        }
        if ($loggedInAdmin->parent_id != 0) {
          return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }
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

      if ($request->user()->tokenCan('type:admin')) {
        $admin = Admin::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', $update_by)->first();
      } else {
        $admin = Admin::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', '0')->first();
      }

      if (is_null($admin)) {
        return $this->sendError('Admin does not exist.');
      }

      $admin->status = $status == 'active' ? '1'  : '0';
      $admin->update();

      if ($admin->parent_id == '0') {
        if ($status == 'active') {
          Admin::where('parent_id', '=', $id)->update(['status' => '1']);
        } else {
          Admin::where('parent_id', '=', $id)->update(['status' => '0']);
        }
      }

      return $this->sendResponse(new AdminResource($admin), 'Status change successfully.', 200);
    } else if ($request->platform == 2) {
      $update_by = Auth::user()->id;

      if ($request->user()->tokenCan('type:employee')) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      } else if ($request->user()->tokenCan('type:admin')) {
        $loggedInAdmin = Admin_ms::where('id', '=', $update_by)->where('status', '!=', '2')->first();
        if (is_null($loggedInAdmin)) {
          return $this->sendError('Admin does not exist.');
        }
        if ($loggedInAdmin->parent_id != 0) {
          return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
        }
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

      if ($request->user()->tokenCan('type:admin')) {
        $admin = Admin_ms::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', $update_by)->first();
      } else {
        $admin = Admin_ms::where('id', '=', $id)
          ->where('status', '!=', '2')
          ->where('parent_id', '=', '0')->first();
      }

      if (is_null($admin)) {
        return $this->sendError('Admin does not exist.');
      }

      $admin->status = $status == 'active' ? '1'  : '0';
      $admin->update();

      if ($admin->parent_id == '0') {
        if ($status == 'active') {
          Admin_ms::where('parent_id', '=', $id)->update(['status' => '1']);
        } else {
          Admin_ms::where('parent_id', '=', $id)->update(['status' => '0']);
        }
      }

      return $this->sendResponse(new AdminResource($admin), 'Status change successfully.', 200);
    } else {
      return $this->sendError('Platfrom Not Matching.', ['error' => 'Enter Correct Platform'], 401);
    }
  }

  /**
   * Change Password of specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function changePassword(Request $request)
  {
    $update_by = Auth::user()->id;
    if ($request->user()->tokenCan('type:employee')) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    }


    if (isset($request->platform) && $request->platform == 2) {
      $admin = Admin_ms::where('id', '=', $update_by)->where('status', '!=', '2')->first();
    } else {
      $admin = Admin::where('id', '=', $update_by)->where('status', '!=', '2')->first();
    }
    if (is_null($admin)) {
      return $this->sendError('Admin does not exist.');
    }



    $fields = $request->validate([
      'old_password' => 'required|string|min:6|max:15',
      'password' => 'required|string|confirmed|min:6|max:15'
    ]);

    // Check email
    if (isset($request->platform) && $request->platform == 2) {
      $admin = Admin_ms::where('id', $update_by)->first();
    } else {
      $admin = Admin::where('id', $update_by)->first();
    }

    // Check password
    if (!Hash::check($fields['old_password'], $admin->password)) {
      return $this->sendError('Unauthorised.', ['error' => 'Incorrect Old Password'], 401);
    }

    $admin->password = bcrypt($fields['password']);
    $admin->update();

    return $this->sendResponse(new AdminResource($admin), 'Password set successfully.', 200);
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
      'email' => 'required|string',
      'platform' => 'required|numeric',
    ]);
    if ($fields['platform'] == 1) {
      $email = $fields['email'];

      $superAdminEmail      = config('global.super_admin_email');

      if ($superAdminEmail == $fields['email']) {
        // Check email
        $admin = SuperAdmin::where('email', $fields['email'])->where('status', '!=', '2')->first();
        if (is_null($admin)) {
          return $this->sendError('Unauthorised.', ['error' => 'Bad Credentials'], 401);
        }

        $password   = Str::random(10);

        $admin->password = bcrypt($password);
        $admin->update();

        $email = $admin->email;
        $maildata = [
          'subject' => 'Fogot Password Request',
          'from_name' => config('app.from_name'),
          'fullName' => "Super Admin",
          'password' => $password,
          'contactEmail' => config('app.from_email_address'),
          'mail_template' => "emails.forgot-password-super-admin-email"
        ];
        //SendMail::dispatch($email, $maildata)->onQueue('send-email')->onConnection('database');
        Mail::to($email)->send(new WelcomeEmail($maildata));
      } else {
        $admin = Admin::where('email', '=', $email)->where('status', '!=', '2')->first();

        if (is_null($admin)) {
          return $this->sendError('Admin does not exist.');
        }

        if ($admin->status == '0') {
          return $this->sendError('Account is inactive.');
        }

        $digits = 4;
        $strOTP = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

        $startTime  =   date('Y-m-d H:i:s');
        $expiryTime =   date('Y-m-d H:i:s', strtotime('+15 minutes', strtotime($startTime)));

        $admin->otp_sent = $strOTP;
        $admin->expiry_time = $expiryTime;
        $admin->update();

        $email = $admin->email;

        $maildata = [
          'subject' => 'Moval - One Time Password',
          'from_name' => config('app.from_name'),
          'fullName' => $admin->name,
          'otp' => $strOTP,
          'contactEmail' => config('app.from_email_address'),
          'mail_template' => "emails.password-reset-otp-email"
        ];
        //SendMail::dispatch($email, $maildata)->onQueue('send-email')->onConnection('database');
        Mail::to($email)->send(new WelcomeEmail($maildata));
      }
    } else if ($fields['platform'] == 2) {

      $email = $fields['email'];

      $superAdminEmail      = config('global.super_admin_email');

      if ($superAdminEmail == $fields['email']) {
        // Check email
        $admin = SuperAdmin_ms::where('email', $fields['email'])->where('status', '!=', '2')->first();
        if (is_null($admin)) {
          return $this->sendError('Unauthorised.', ['error' => 'Bad Credentials'], 401);
        }

        $password   = Str::random(10);

        $admin->password = bcrypt($password);
        $admin->update();

        $email = $admin->email;
        $maildata = [
          'subject' => 'Fogot Password Request',
          'from_name' => config('app.from_name'),
          'fullName' => "Super Admin",
          'password' => $password,
          'contactEmail' => config('app.from_email_address'),
          'mail_template' => "emails.forgot-password-super-admin-email"
        ];
        //SendMail::dispatch($email, $maildata)->onQueue('send-email')->onConnection('database');
        Mail::to($email)->send(new WelcomeEmail($maildata));
      } else {
        $admin = Admin_ms::where('email', '=', $email)->where('status', '!=', '2')->first();

        if (is_null($admin)) {
          return $this->sendError('Admin does not exist.');
        }

        if ($admin->status == '0') {
          return $this->sendError('Account is inactive.');
        }

        $digits = 4;
        $strOTP = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

        $startTime  =   date('Y-m-d H:i:s');
        $expiryTime =   date('Y-m-d H:i:s', strtotime('+15 minutes', strtotime($startTime)));

        $admin->otp_sent = $strOTP;
        $admin->expiry_time = $expiryTime;
        $admin->update();

        $email = $admin->email;

        $maildata = [
          'subject' => 'Moval - One Time Password',
          'from_name' => config('app.from_name'),
          'fullName' => $admin->name,
          'otp' => $strOTP,
          'contactEmail' => config('app.from_email_address'),
          'mail_template' => "emails.password-reset-otp-email"
        ];
        //SendMail::dispatch($email, $maildata)->onQueue('send-email')->onConnection('database');
        Mail::to($email)->send(new WelcomeEmail($maildata));
      }



      return $this->sendResponse(new AdminResource($admin), 'Otp Sent Successfully.', 200);
    }
  }

  /**
   * Verify Otp.
   *
   * @param  int  $id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function verifyOTP(Request $request, $id)
  {

    $fields = $request->validate([
      'otp' => 'required|string',
      'platform' => 'required|numeric'
    ]);
    if ($fields['platform'] == 1) {
      $admin = Admin::where('id', '=', $id)->where('status', '=', '1')->first();
      if (is_null($admin)) {
        return $this->sendError('Admin does not exist.');
      }

      if ($admin->status == '0') {
        return $this->sendError('Account is inactive.');
      }

      $otp = $fields['otp'];

      $currentTime    =   strtotime(date('Y-m-d H:i:s'));
      $expiryTime     =   strtotime($admin->expiry_time);


      if ($admin->otp_sent != $otp) {
        return $this->sendError('Invalid OTP.');
      }

      if ($expiryTime < $currentTime) {
        return $this->sendError('OTP Expired.');
      }

      $token = $admin->createToken('moval-app-admin-token', ['type:admin'])->plainTextToken;
      $response = [
        'detail' => new AdminResource($admin),
        'token' => $token
      ];
    } else if ($fields['platform'] == 2) {
      $admin = Admin_ms::where('id', '=', $id)->where('status', '=', '1')->first();
      if (is_null($admin)) {
        return $this->sendError('Admin does not exist.');
      }

      if ($admin->status == '0') {
        return $this->sendError('Account is inactive.');
      }

      $otp = $fields['otp'];

      $currentTime    =   strtotime(date('Y-m-d H:i:s'));
      $expiryTime     =   strtotime($admin->expiry_time);


      if ($admin->otp_sent != $otp) {
        return $this->sendError('Invalid OTP.');
      }

      if ($expiryTime < $currentTime) {
        return $this->sendError('OTP Expired.');
      }

      $token = $admin->createToken('moval-app-admin-token', ['type:admin'])->plainTextToken;
      $response = [
        'detail' => new AdminResource($admin),
        'token' => $token
      ];
    }
    return $this->sendResponse($response, 'OTP verified successfully.', 200);
  }

  /**
   * Set Password of specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function setPassword(Request $request)
  {
    $update_by = Auth::user()->id;
    if ($request->user()->tokenCan('type:employee')) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    }


    $fields = $request->validate([
      'password' => 'required|string|confirmed|min:6|max:15',
      'platform' => 'required|string'
    ]);


    //MV

    if ($fields['platform'] == 1) {
      $admin = Admin::where('id', '=', $update_by)->where('status', '!=', '2')->first();
      if (is_null($admin)) {
        return $this->sendError('Admin does not exist.');
      }



      if (Hash::check($fields['password'], $admin->password)) {
        return $this->sendError('Unauthorised.', ['error' => 'New password should be different from your current password'], 400);
      }

      $admin->password = bcrypt($fields['password']);
      $admin->is_set_password = 'yes';
      $admin->update();

      return $this->sendResponse(new AdminResource($admin), 'Password set successfully.', 200);
    }


    //MS

    else if ($fields['platform'] == 2) {

      $admin = Admin_ms::where('id', '=', $update_by)->where('status', '!=', '2')->first();
      if (is_null($admin)) {
        return $this->sendError('Admin does not exist.');
      }



      if (Hash::check($fields['password'], $admin->password)) {
        return $this->sendError('Unauthorised.', ['error' => 'New password should be different from your current password'], 400);
      }

      $admin->password = bcrypt($fields['password']);
      $admin->is_set_password = 'yes';
      $admin->update();

      return $this->sendResponse(new AdminResource($admin), 'Password set successfully.', 200);
    } else {
      return $this->sendError('Platfrom Not Matching.', ['error' => 'Enter Correct Platform'], 401);
    }
  }







  /**
   * Dashboard Data.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getDashboardData(Request $request)
  {

    $TBL_JOB        = config('global.job_table');

    if (!$request->user()->tokenCan('type:admin')) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    }

    $logged_in_id       = Auth::user()->id;
    $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

    $allJobs = Job::select([
      $TBL_JOB . 'job_status',
      'count($TBL_JOB.*) as job_count'
    ])
      ->where($TBL_JOB . '.status', '!=', '2')
      ->where($TBL_JOB . '.parent_admin_id', '=', $parent_admin_id)
      ->groupBy($TBL_JOB . 'job_status');

    $response = [
      'job_count_by_job_status' => $allJobs
    ];

    return $this->sendResponse($response, 'success.', 200);
  }


  /**
   * Generate Invoice.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function generateInvoice(Request $request)
  {


    $TBL_ADMIN              = config('global.admin_table');
    $TBL_JOB                = config('global.job_table');
    $TBL_ADMIN_INVOICE      = config('global.admin_invoice_table');

    $today_date             =   date('Y-m-d');

    $allAdmins = Admin::select([$TBL_ADMIN . '.*'])
      ->where($TBL_ADMIN . '.parent_id', '0')
      ->where($TBL_ADMIN . '.status', '!=', '2')
      ->whereDate($TBL_ADMIN . '.next_billing_date', '<=', $today_date)
      ->get();



    $arrStatus = ["finalized"];

    foreach ($allAdmins as $admin) {

      $last_billing_date   = date('Y-m-d', strtotime($admin->next_billing_date . " -1 month"));
      //$last_billing_date   = date('Y-m-d', strtotime($admin->next_billing_date. " -5 days"));

      $allJobs = Job::select([
        $TBL_JOB . '.*',
      ])
        ->where($TBL_JOB . '.status', '!=', '2')
        ->where($TBL_JOB . '.parent_admin_id', '=', $admin->id)
        ->whereIn('job_status', $arrStatus);

      $allJobs =  $allJobs->whereDate($TBL_JOB . '.completed_at', '>=', $last_billing_date)
        ->whereDate($TBL_JOB . '.completed_at', '<', $admin->next_billing_date);

      $total_completed_jobs = $allJobs->count();
      $per_report_cost = $admin->each_report_cost;
      $total_bill_amount = $total_completed_jobs * $per_report_cost;
      $lastDateOfPayment = date('Y-m-d', strtotime($admin->next_billing_date . " +4 days"));

      $arrFieldsToBeAdd = [
        'admin_id' => $admin->id,
        'invoice_date' => $admin->next_billing_date,
        'number_of_reports' => $total_completed_jobs,
        'last_date_of_payment' => $lastDateOfPayment,
        'report_cost' => $per_report_cost,
        'bill_amount' => $total_bill_amount
      ];



      if ($total_bill_amount > 0) {
        $adminInvoice = AdminInvoice::create($arrFieldsToBeAdd);
      }

      $admin->next_billing_date   =  date('Y-m-d', strtotime($admin->next_billing_date . " +1 month"));
      //$admin->next_billing_date   =  date('Y-m-d', strtotime($admin->next_billing_date. " +5 days"));
      $admin->update();
    }


    return $this->sendResponse([], 'success.', 200);
  }


  /**
   * Generate Invoice.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getAdminInfo(Request $request)
  {
    if ($request->platform == 1) {
      $TBL_ADMIN_INVOICE          = config('global.admin_invoice_table');
      $TBL_IMPORTANT_UPDATES      = config('global.tbl_important_updates');

      $loggedInAdmin = Auth::user()->id;
      if (!$request->user()->tokenCan('type:admin')) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      }

      $adminId =  getAdminIdIfAdminLoggedIn($loggedInAdmin);


      $adminInvoiceList = AdminInvoice::where($TBL_ADMIN_INVOICE . '.admin_id', '=', $adminId)
        ->where($TBL_ADMIN_INVOICE . '.payment_status', '=', 'pending')
        ->where($TBL_ADMIN_INVOICE . '.bill_amount', '!=', 0)
        ->orderBy('created_at', 'desc')->get();



      $is_bill_due    =   0;
      $isShowMessageToSubAdmin = 0;
      $totalAmountDue = 0;
      $lastDateOfPayment = "";
      $diff     =   0;
      if ($adminInvoiceList->count() > 0) {
        $is_bill_due            =   1;
        $lastAdminInvoice       = $adminInvoiceList[0];
        $todayDate              = date("Y-m-d");
        $invoiceGenerateDate    = $lastAdminInvoice->created_at;
        $lastDateOfPayment      = $lastAdminInvoice->last_date_of_payment;

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

      $allImportantUpdates = ImportantUpdates::select([$TBL_IMPORTANT_UPDATES . '.id']);

      $allImportantUpdates =  $allImportantUpdates->where(function ($query) use ($TBL_IMPORTANT_UPDATES, $loggedInAdmin) {
        $query->orWhere($TBL_IMPORTANT_UPDATES . '.to_admin_ids', 'LIKE', '%' . $loggedInAdmin . ',%')
          ->orWhere($TBL_IMPORTANT_UPDATES . '.to_admin_ids', 'LIKE', '%,' . $loggedInAdmin . '%')
          ->orWhere($TBL_IMPORTANT_UPDATES . '.to_admin_ids', 'LIKE', '%,' . $loggedInAdmin . ',%');
      });

      $allImportantUpdates =  $allImportantUpdates->where($TBL_IMPORTANT_UPDATES . '.seen_admin_ids', 'NOT LIKE', '%' . $loggedInAdmin . ',%');
      $allImportantUpdates =  $allImportantUpdates->where($TBL_IMPORTANT_UPDATES . '.seen_admin_ids', 'NOT LIKE', '%,' . $loggedInAdmin . ',%');
      $allImportantUpdates =  $allImportantUpdates->where($TBL_IMPORTANT_UPDATES . '.seen_admin_ids', 'NOT LIKE', '%,' . $loggedInAdmin . '%');


      $response = [
        'diff' => $diff,
        'is_bill_due' => $is_bill_due,
        'total_amount_due' => $totalAmountDue,
        'last_date_of_payment' => $lastDateOfPayment,
        'is_show_message_to_subadmin' => $isShowMessageToSubAdmin,
        'important_updates_unseen_count' =>  $allImportantUpdates->count()
      ];

      return $this->sendResponse($response, 'success.', 200);
    } else if ($request->platform == 2) {
      $TBL_ADMIN_INVOICE          = config('global.admin_invoice_table');
      $TBL_IMPORTANT_UPDATES      = config('global.tbl_important_updates');

      $loggedInAdmin = Auth::user()->id;
      if (!$request->user()->tokenCan('type:admin')) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      }

      $adminId =  getAdminIdIfAdminLoggedIn1($loggedInAdmin);


      /*$adminInvoiceList = AdminInvoice::where($TBL_ADMIN_INVOICE.'.admin_id','=',$adminId)
->where($TBL_ADMIN_INVOICE.'.payment_status','=','pending')
->where($TBL_ADMIN_INVOICE.'.bill_amount','!=',0)
->orderBy('created_at', 'desc')->get();
*/


      $is_bill_due    =   0;
      $isShowMessageToSubAdmin = 0;
      $totalAmountDue = 0;
      $lastDateOfPayment = "";
      $diff     =   0;
      /* if ($adminInvoiceList->count() > 0) {
$is_bill_due            =   1;
$lastAdminInvoice       = $adminInvoiceList[0];
$todayDate              = date("Y-m-d");
$invoiceGenerateDate    = $lastAdminInvoice->created_at;
$lastDateOfPayment      = $lastAdminInvoice->last_date_of_payment;

if (strtotime($lastDateOfPayment) < strtotime($todayDate)){
return $this->sendError('Unauthorised.', ['error'=>'Subscription expired'],401);
}

$totalAmountDue = $lastAdminInvoice->bill_amount;
// Declare and define two dates
$date1 = strtotime($lastDateOfPayment);
$date2 = strtotime($todayDate);

// Formulate the Difference between two dates
$diff = abs($date2 - $date1);
$days = floor(($diff)/ (60*60*24));

if ($days <= 2){
$isShowMessageToSubAdmin = 1;
}
}
*/
      /*$allImportantUpdates = ImportantUpdates::select([$TBL_IMPORTANT_UPDATES.'.id']);

$allImportantUpdates =  $allImportantUpdates->where(function($query) use ($TBL_IMPORTANT_UPDATES,$loggedInAdmin) {
$query->orWhere($TBL_IMPORTANT_UPDATES.'.to_admin_ids','LIKE','%'.$loggedInAdmin.',%')
->orWhere($TBL_IMPORTANT_UPDATES.'.to_admin_ids','LIKE','%,'.$loggedInAdmin.'%')
->orWhere($TBL_IMPORTANT_UPDATES.'.to_admin_ids','LIKE','%,'.$loggedInAdmin.',%');
});

$allImportantUpdates =  $allImportantUpdates->where($TBL_IMPORTANT_UPDATES.'.seen_admin_ids','NOT LIKE','%'.$loggedInAdmin.',%');
$allImportantUpdates =  $allImportantUpdates->where($TBL_IMPORTANT_UPDATES.'.seen_admin_ids','NOT LIKE','%,'.$loggedInAdmin.',%');
$allImportantUpdates =  $allImportantUpdates->where($TBL_IMPORTANT_UPDATES.'.seen_admin_ids','NOT LIKE','%,'.$loggedInAdmin.'%');

*/
      $response = [
        'diff' => $diff,
        'is_bill_due' => $is_bill_due,
        'total_amount_due' => $totalAmountDue,
        'last_date_of_payment' => $lastDateOfPayment,
        'is_show_message_to_subadmin' => $isShowMessageToSubAdmin,
        //'important_updates_unseen_count' =>  $allImportantUpdates->count()
      ];

      return $this->sendResponse($response, 'success.', 200);
    } else {
      return $this->sendError('Platfrom Not Matching.', ['error' => 'Enter Correct Platform'], 401);
    }
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function getInvoiceList(Request $request)
  {
    $TBL_ADMIN      = config('global.admin_table');
    $created_by     = Auth::user()->id;

    if (!$request->user()->tokenCan('type:admin')) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    } else if ($request->user()->tokenCan('type:admin')) {
      $loggedInAdmin = Admin::where('id', '=', $created_by)->where('status', '!=', '2')->first();
      if (is_null($loggedInAdmin)) {
        return $this->sendError('Admin does not exist.');
      }
      if ($loggedInAdmin->parent_id != 0) {
        return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
      }
    }

    $logged_in_id       = Auth::user()->id;
    $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

    if ($logged_in_id != $parent_admin_id) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    }

    $TBL_ADMIN_INVOICE      = config('global.admin_invoice_table');

    $allInvoiceList = AdminInvoice::select([$TBL_ADMIN_INVOICE . '.*'])
      ->where('admin_id', '=', $logged_in_id)
      ->orderBy('created_at', 'desc');

    $searchKeyword = "";
    if ($request->exists('search_keyword')) {
      $searchKeyword = $request->input('search_keyword');
      if ($searchKeyword != '') {
        $allInvoiceList =  $allInvoiceList->where(function ($query) use ($TBL_ADMIN_INVOICE, $searchKeyword) {
          $query->orWhere($TBL_ADMIN_INVOICE . '.invoice_date', 'LIKE', '%' . $searchKeyword . '%')
            ->orWhere($TBL_ADMIN_INVOICE . '.number_of_reports', 'LIKE', '%' . $searchKeyword . '%')
            ->orWhere($TBL_ADMIN_INVOICE . '.report_cost', 'LIKE', '%' . $searchKeyword . '%')
            ->orWhere($TBL_ADMIN_INVOICE . '.payment_status', 'LIKE', '%' . $searchKeyword . '%')
            ->orWhere($TBL_ADMIN_INVOICE . '.bill_amount', 'LIKE', '%' . $searchKeyword . '%');
        });
      }
    }

    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');

    if ($fromDate != null && $fromDate != '' && $toDate != null && $toDate != '') {
      $allInvoiceList =  $allInvoiceList->whereDate($TBL_ADMIN_INVOICE . '.invoice_date', '>=', $fromDate)
        ->whereDate($TBL_ADMIN_INVOICE . '.invoice_date', '<=', $toDate);
    }

    $allInvoiceList = $request->exists('all')
      ? AdminInvoiceResources::collection($allInvoiceList->get())
      : new AdminInvoiceResourcesCollection($allInvoiceList->paginate(20));
    return $this->sendResponse($allInvoiceList, 'Posts fetched.');
  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function resendAdminCredentials(Request $request, $id)
  {

    if (!$request->user()->tokenCan('type:super_admin') && !$request->user()->tokenCan('type:admin')) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    }

    // $admin = Admin::where('id','=', $id)
    //                 ->where('status','!=', '2')
    //                 ->where('parent_id','=', 0)->first();

    $admin = Admin::where('id', '=', $id)
      ->where('status', '!=', '2')
      ->first();

    if (is_null($admin)) {
      return $this->sendError('Admin does not exist.');
    }

    $password   = Str::random(10);

    $admin->password = bcrypt($password);
    //$admin->is_set_password = 'yes';
    $admin->update();

    $email = $admin->email;
    $maildata = [
      'subject' => 'Welcome to the Moval',
      'from_name' => config('app.from_name'),
      'fullName' => $admin->name,
      'userId' => $admin->email,
      'password' => $password,
      'months' => $admin->duraton_delete_photo,
      'adminLoginLink' => config('app.website_url'),
      'contactEmail' => config('app.from_email_address'),
      'mail_template' => "emails.admin-welcome-email"
    ];
    //SendMail::dispatch($email, $maildata)->onQueue('send-email')->onConnection('database');
    Mail::to($email)->send(new WelcomeEmail($maildata));


    return $this->sendResponse(new AdminResource($admin), 'Post fetched.');
  }


  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function getAdminSettingsByType(Request $request, $type)
  {

    if (!$request->user()->tokenCan('type:admin')) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    }

    $logged_in_id       = Auth::user()->id;
    if ($platform == 1) {
      $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);
    } else {
      $parent_admin_id    = getAdminIdIfAdminLoggedIn1($logged_in_id);
    }


    if ($logged_in_id != $parent_admin_id) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    }

    if ($type == 'razorpay') {
      return $this->getRazonPaymentSettings();
    }
  }

  private function getRazonPaymentSettings()
  {
    $logged_in_id       = Auth::user()->id;

    $settings = AdminSettings::where('type', '=', 'razorpay')
      ->where('admin_id', '=', $logged_in_id)->first();

    $host               = request()->getSchemeAndHttpHost();

    $CALL_BACK_URL      = $host . config('global.razor_pay_job_payment_call_back');

    $settingsData["call_back_url"]      =   $CALL_BACK_URL;
    $settingsData["payment_settings"]   =   [];
    if (!is_null($settings)) {
      $settingsData["payment_settings"] = json_decode($settings->message);
    }
    return $this->sendResponse($settingsData, 'Data fetched', 200);
  }

  public function saveAdminSettingsByType(Request $request)
  {

    if (!$request->user()->tokenCan('type:admin')) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    }

    $logged_in_id       = Auth::user()->id;
    $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

    if ($logged_in_id != $parent_admin_id) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    }

    $arrFieldsToBeValidate = [
      'type' => 'required|string|in:razorpay',
      'settings' => 'required|string'
    ];

    $fields = $request->validate($arrFieldsToBeValidate);

    $settings = AdminSettings::where('type', '=', $fields['type'])
      ->where('admin_id', '=', $logged_in_id)->first();

    if (is_null($settings)) {
      $settings = AdminSettings::create([
        'admin_id' => $logged_in_id,
        'type' => $fields['type'],
        'message' => $fields['settings']
      ]);
    } else {
      $settings->type = $fields['type'];
      $settings->message = $fields['settings'];
      $settings->update();
    }

    $host               = request()->getSchemeAndHttpHost();

    $CALL_BACK_URL      = $host . config('global.razor_pay_job_payment_call_back');

    $settingsData["call_back_url"]      =   $CALL_BACK_URL;
    $settingsData["payment_settings"]   =   [];
    if (!is_null($settings)) {
      $settingsData["payment_settings"] = json_decode($settings->message);
    }

    return $this->sendResponse($settingsData, 'Data fetched', 200);
  }


  /**
   * Send Payment Link
   *  @param  Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function getPaymentLink(Request $request, $invoiceId)
  {
    if (!$request->user()->tokenCan('type:admin')) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    }

    $logged_in_id       = Auth::user()->id;
    $parent_admin_id    = getAdminIdIfAdminLoggedIn($logged_in_id);

    if ($logged_in_id != $parent_admin_id) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    }

    $admin = Admin::where('id', '=', $logged_in_id)->where('status', '!=', '2')->first();
    if (is_null($admin)) {
      return $this->sendError('Admin does not exist.');
    }

    $settings = AppSetting::where('type', '=', 'razorpay')->first();
    if (is_null($settings)) {
      return $this->sendError('Something went wrong');
    }

    $adminInvoice = AdminInvoice::where('admin_id', '=', $logged_in_id)->where('id', '=', $invoiceId)->where('payment_status', '=', "pending")
      ->orderBy('created_at', 'desc')->first();

    if (is_null($adminInvoice)) {
      return $this->sendError('Something went wrong');
    }

    $razorPaymentSettings = json_decode($settings->message);
    $api = new Api($razorPaymentSettings->apikey, $razorPaymentSettings->secret);

    $host               = request()->headers->get('origin');
    $CALL_BACK_URL      = $host . config('global.razor_payment_thankyou_page') . "billing";

    $expiryDate = date('Y-m-d H:i:s');
    $expiryDate = strtotime($expiryDate . " + 1 day");
    $paymentReferenceId = generateRandomString();
    $amountToPay = $adminInvoice->bill_amount * 100;
    $description = "Payment for Invoice Id #" . $invoiceId;


    $response = $api->paymentLink->create(
      array(
        'amount' => $amountToPay,
        'currency' => 'INR',
        'accept_partial' => false,
        'description' => $description,
        'reference_id' => $paymentReferenceId,
        'expire_by' => $expiryDate,
        'customer' => array(
          'name' => $admin->name,
          'email' => $admin->email,
          'contact' => '+91' . $admin->mobile_no
        ),
        'notify' => array('sms' => true, 'email' => true),
        'reminder_enable' => true,
        'notes' =>
        array('invoice_id' => $invoiceId, 'admin_id' => $admin->id),
        'callback_url' => $CALL_BACK_URL,
        'callback_method' => 'get',
        'options' => array('checkout' => array('name' => 'Moval'))
      )
    );
    if (!is_null($response)) {
      if (!isset($response->id)) {
        return $this->sendError('Unable to generate payment link.');
      }

      $paymentHistory =  AdminPaymentHistory::create([
        'invoice_id' => $invoiceId,
        'admin_id' => $parent_admin_id,
        'payment_link_reference_id' => $response->reference_id,
        'payment_link_id' => $response->id,
        'payment_link' => $response->short_url,
      ]);

      $adminInvoice->payment_link_tracking_id = $paymentHistory->id;
      $adminInvoice->payment_link_send_date = date('Y-m-d H:i:s');
      $adminInvoice->update();

      $responseData["payment_link"]  =   $response->short_url;

      return $this->sendResponse($responseData, 'Payment link sent successfully.', 200);
    }

    return $this->sendError('Unable to generate payment link.');
  }

  /**
   *  Verify Payment
   *  @param  Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function verifAdminPayment(Request $request)
  {
    $fields = $request->validate([
      'razorpay_payment_id' => 'required|string',
      'razorpay_payment_link_id' => 'required|string',
      'razorpay_payment_link_reference_id' => 'required|string',
      'razorpay_payment_link_status' => 'required|string',
      'razorpay_signature' => 'required|string',
    ]);

    $paymentHistory = AdminPaymentHistory::where('payment_link_id', '=', $fields['razorpay_payment_link_id'])
      ->where('payment_link_reference_id', '=', $fields['razorpay_payment_link_reference_id'])->first();
    if (is_null($paymentHistory)) {
      return $this->sendError('Bad Request');
    }

    $settings = AppSetting::where('type', '=', 'razorpay')->first();
    if (is_null($settings)) {
      return $this->sendError('Something went wrong');
    }

    $razorPaymentSettings = json_decode($settings->message);
    $api = new Api($razorPaymentSettings->apikey, $razorPaymentSettings->secret);
    $attributes  = array(
      'razorpay_payment_link_id'  => $fields['razorpay_payment_link_id'],
      'razorpay_payment_link_reference_id'  => $fields['razorpay_payment_link_reference_id'],
      'razorpay_payment_link_status' => $fields['razorpay_payment_link_status'],
      'razorpay_payment_id' => $fields['razorpay_payment_id'],
      'razorpay_signature' => $fields['razorpay_signature']
    );

    try {
      $api->utility->verifyPaymentSignature($attributes);
    } catch (\Exception $e) {
      return $this->sendError($e->getMessage());
    }

    $paymentHistory->callback_signature = $fields['razorpay_signature'];
    $paymentHistory->link_status = $fields['razorpay_payment_link_status'];
    $paymentHistory->payment_id  = $fields['razorpay_payment_id'];
    $paymentHistory->update();

    return $this->sendResponse(array(), 'Verified.', 200);
  }

  /**
   *  Job Payment Webhook
   *  @param  Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateAdminPaymentStatus(Request $request)
  {

    $payload = $request->all();

    if ((!isset($payload['entity'])) || $payload['entity'] != 'event') {
      //Unknown webhook as currrently configured to support only events
      return $this->sendError("Unknown request");
    }

    $paymentStatus = '';
    if ($payload['event'] ==  "payment_link.paid") {
      $paymentStatus = 'completed';
    } else if ($payload['event'] ==  "payment_link.cancelled") {
      $paymentStatus = 'rejected';
    } else if ($payload['event'] ==  "payment_link.expired") {
      $paymentStatus = 'expired';
    }

    if ($paymentStatus != '') {
      $paymentLinkId = $payload['payload']['payment_link']['entity']['id'];

      $paymentHistory = AdminPaymentHistory::where('payment_link_id', '=', $paymentLinkId)->first();
      if (is_null($paymentHistory)) {
        return $this->sendError('No records found for payment');
      }

      $settings = AppSetting::where('type', '=', 'razorpay')->first();
      if (is_null($settings)) {
        return $this->sendError('Something went wrong');
      }

      $razorPaymentSettings = json_decode($settings->message);
      $api = new Api($razorPaymentSettings->apikey, $razorPaymentSettings->secret);
      $webhookSecret = $razorPaymentSettings->job_payment_webhook_secret;
      $webhookSignature = $request->header('X-Razorpay-Signature');
      //$payload = $request->getContent();

      try {
        $api->utility->verifyWebhookSignature($payload, $webhookSignature, $webhookSecret);
      } catch (\Exception $e) {
        //return $this->sendError($e->getMessage());
      }

      $paymentHistory->payment_status =  $paymentStatus;

      if ($paymentStatus == 'completed') {
        $orderId = $payload['payload']['order']['entity']['id'];
        $paymentLinkId = $payload['payload']['payment_link']['entity']['id'];
        $paymentHistory->order_id = $orderId;
        $paymentHistory->payment_id = $paymentLinkId;
        $paymentHistory->link_status = "paid";

        $adminInvoice = AdminInvoice::where('id', '=', $paymentHistory->invoice_id)->first();
        if (!is_null($adminInvoice)) {
          $adminInvoice->payment_status = "completed";
          $adminInvoice->payment_date = date('Y-m-d H:i:s');
          $adminInvoice->payment_mode = "RazorPay";
          $adminInvoice->update();


          $AdminDetails = Admin::where('id', '=', $adminInvoice->admin_id)->first();
          if ($AdminDetails) {
            $adminInvoice->name = $AdminDetails->name;
            $adminInvoice->address = $AdminDetails->address;
          } else {
            $adminInvoice->name     = "";
            $adminInvoice->address  = "";
          }


          view()->share('invoice-admin', $adminInvoice);

          $newFilename = 'moval_invoice_' . $adminInvoice->id . '.pdf';

          PDF::loadView('invoice-admin', array('invoice' => $adminInvoice, "draft" => false), [], [
            'watermark'      => "CONFIDENTIAL",
            'show_watermark' => true,
            'margin_header'              => 0,
            'watermark_text_alpha' => 0.03
          ])->save('storage/app/public/admin_invoice/' . $newFilename);
        }
      }
      $paymentHistory->update();

      return $this->sendResponse(array(), 'Success.', 200);
    } else {
      return $this->sendError("Invalid payment status");
    }
  }

  /** 
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function deleteAdminPhotos(Request $request, $id, $created_date)
  {

    if (!$request->user()->tokenCan('type:super_admin')) {
      return $this->sendError('Unauthorised.', ['error' => 'Not authenticated'], 401);
    }

    $TBL_IMPORTANT_UPDATES      = config('global.tbl_important_updates');
    $TBL_JOB_FILES_TEMP        = config('global.job_files_table_temp');

    $admin = Admin::where('id', '=', $id)
      ->where('status', '!=', '2')
      ->where('parent_id', '=', '0')->first();

    if (is_null($admin)) {
      return $this->sendError('Admin does not exist.');
    }

    $jobList = Job::where('parent_admin_id', '=', $id)->whereDate('created_at', "<=", $created_date)->get();
    foreach ($jobList as $job) {
      $images = DB::table($TBL_JOB_FILES_TEMP)->where([
        ['job_id', '=', $job->id],
      ])->get();

      // $imagePath = public_path('storage/job_finalize_report_'.$job->id.'.pdf');
      // if(File::exists($imagePath)){
      //     unlink($imagePath);
      // }

      // $imagePath = public_path('storage/job_finalize_report_draft_'.$job->id.'.pdf');
      // if(File::exists($imagePath)){
      //     unlink($imagePath);
      // }


      foreach ($images as $row) {
        $imagePath = public_path('storage/' . $row->name);
        if (File::exists($imagePath)) {
          unlink($imagePath);
        }
        $row->delete();
      }

      $jobFiles = JobFile::where([
        ['job_id', '=', $job->id],
      ])->get();

      foreach ($jobFiles as $jobFile) {
        $imagePath = public_path('storage/' . $jobFile->name);
        if (File::exists($imagePath)) {
          unlink($imagePath);
        }




        $jobFile->delete();
      }
    }

    return $this->sendResponse([], 'Admin data deleted successfully.', 200);
  }
}
