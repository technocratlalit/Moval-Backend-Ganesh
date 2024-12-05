<?php

namespace App\Http\Controllers\MotorValuation;

use App\Http\Resources\admin_invoice\AdminInvoiceResources;
use App\Http\Resources\admin_invoice\AdminInvoiceResourcesCollection;
use Illuminate\Http\Request;
use App\Models\SuperAdmin;
use App\Models\Admin_ms;
use App\Models\SuperAdmin_ms;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\super_admin\SuperAdminResource;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeEmail;
use App\Models\AdminInvoice;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Http\Controllers\BaseController;
class SuperAdminController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // $TBL_SUPER_ADMIN   = config('global.super_admin_table');

        // $fields = $request->validate([
        //     'name' => 'required|string',
        //     'email' => 'required|string|unique:'.$TBL_SUPER_ADMIN.',email',
        //     'address' => 'required|string',
        //     'mobile_no' => 'required|numeric|digits:10',
        //     'password' => 'required|string|min:6|max:15',
        //     'status' => 'required|string|in:active,inactive',
        // ]);

        // $admin = SuperAdmin::create([
        //     'name' => $fields['name'],
        //     'email' => $fields['email'],
        //     'password' => bcrypt($fields['password']),
        //     'address' => $fields['address'],
        //     'mobile_no' => $fields['mobile_no'],
        //     'status' => ($fields['status'] == "active" ? '1' : '0'),
        // ]);
        // return $this->sendResponse(new SuperAdminResource($admin),"success",201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Change Password of specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
		    if($request->platform ==1){
        $update_by = Auth::user()->id;
        if(!$request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $admin = SuperAdmin::where('id','=', $update_by)->where('status','!=', '2')->first();
        if (is_null($update_by)) {
            return $this->sendError('Admin does not exist.');
        }

        $fields = $request->validate([
            'old_password' => 'required|string|min:6|max:15',
            'password' => 'required|string|confirmed|min:6|max:15'
        ]);

        // Check password
        if(!Hash::check($fields['old_password'], $admin->password)) {
            return $this->sendError('Unauthorised.', ['error'=>'Incorrect Old Password'],401);
        }

        $admin->password = bcrypt($fields['password']);
        $admin->update();

        return $this->sendResponse(new SuperAdminResource($admin), 'Password set successfully.',200);
			}
			
	   else if($request->platform ==2){ 
		  $update_by = Auth::user()->id;
        if(!$request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $admin = SuperAdmin_ms::where('id','=', $update_by)->where('status','!=', '2')->first();
        if (is_null($update_by)) {
            return $this->sendError('Admin does not exist.');
        }

        $fields = $request->validate([
            'old_password' => 'required|string|min:6|max:15',
            'password' => 'required|string|confirmed|min:6|max:15'
        ]);

        // Check password
        if(!Hash::check($fields['old_password'], $admin->password)) {
            return $this->sendError('Unauthorised.', ['error'=>'Incorrect Old Password'],401);
        }

        $admin->password = bcrypt($fields['password']);
        $admin->update();

        return $this->sendResponse(new SuperAdminResource($admin), 'Password set successfully.',200);
		
       }
     else{
       return $this->sendError('Platfrom Not Matching.', ['error'=>'Enter Correct Platform'],401);
      }
   
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
     if($request->platform ==1){
	  
        $fields = $request->validate([
            'email' => 'required|string',
        ]);

        $email = $fields['email'];

        $admin = SuperAdmin::where('email','=', $email)->where('status','!=', '2')->first();

        if (is_null($admin)) {
            return $this->sendError('Admin does not exist.');
        }

        if ($admin->status == '0'){
            return $this->sendError('Account is inactive.');
        }

        $digits = 4;
        $strOTP = rand(pow(10, $digits-1), pow(10, $digits)-1);

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
        Mail::to($email)->send(new WelcomeEmail($maildata));

        return $this->sendResponse(new SuperAdminResource($admin), 'Otp Sent Successfully.',200);
		
  }
  
    else if($request->platform ==2){
		
		  $fields = $request->validate([
            'email' => 'required|string',
        ]);

        $email = $fields['email'];

        $admin = SuperAdmin_ms::where('email','=', $email)->where('status','!=', '2')->first();

        if (is_null($admin)) {
            return $this->sendError('Admin does not exist.');
        }

        if ($admin->status == '0'){
            return $this->sendError('Account is inactive.');
        }

        $digits = 4;
        $strOTP = rand(pow(10, $digits-1), pow(10, $digits)-1);

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
        Mail::to($email)->send(new WelcomeEmail($maildata));

        return $this->sendResponse(new SuperAdminResource($admin), 'Otp Sent Successfully.',200);
		
   }
   else{
     return $this->sendError('Platfrom Not Matching.', ['error'=>'Enter Correct Platform'],401);
   }
   
    }

    /**
     * Verify Otp.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyOTP(Request $request,$id)
    {

  if($request->platform ==1){
        $fields = $request->validate([
            'otp' => 'required|string'
        ]);

        $admin = SuperAdmin::where('id','=', $id)->where('status','=', '1')->first();
        if (is_null($admin)) {
            return $this->sendError('Admin does not exist.');
        }

        if ($admin->status == '0'){
            return $this->sendError('Account is inactive.');
        }

        $otp = $fields['otp'];

        $currentTime    =   strtotime(date('Y-m-d H:i:s'));
        $expiryTime     =   strtotime($admin->expiry_time);


        if ($admin->otp_sent != $otp){
            return $this->sendError('Invalid OTP.');
        }

        if ($expiryTime < $currentTime){
            return $this->sendError('OTP Expired.');
        }

        $token = $admin->createToken('moval-app-super-admin-token',['type:super_admin'])->plainTextToken;
        $response = [
            'detail' => new SuperAdminResource($admin),
            'token' => $token
        ];

        return $this->sendResponse($response, 'OTP verified successfully.',200);
    }
	
	   else if($request->platform ==2){
		   
		    $fields = $request->validate([
            'otp' => 'required|string'
        ]);

        $admin = SuperAdmin_ms::where('id','=', $id)->where('status','=', '1')->first();
        if (is_null($admin)) {
            return $this->sendError('Admin does not exist.');
        }

        if ($admin->status == '0'){
            return $this->sendError('Account is inactive.');
        }

        $otp = $fields['otp'];

        $currentTime    =   strtotime(date('Y-m-d H:i:s'));
        $expiryTime     =   strtotime($admin->expiry_time);


        if ($admin->otp_sent != $otp){
            return $this->sendError('Invalid OTP.');
        }

        if ($expiryTime < $currentTime){
            return $this->sendError('OTP Expired.');
        }

        $token = $admin->createToken('moval-app-super-admin-token',['type:super_admin'])->plainTextToken;
        $response = [
            'detail' => new SuperAdminResource($admin),
            'token' => $token
        ];

        return $this->sendResponse($response, 'OTP verified successfully.',200);
		
   }
   else{
     return $this->sendError('Platfrom Not Matching.', ['error'=>'Enter Correct Platform'],401);
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

     if($request->platform ==1){
        $TBL_ADMIN   = config('global.admin_table');

        $update_by = Auth::user()->id;
        if(!$request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $allAdmins = Admin::select([$TBL_ADMIN.'.*'])
        ->where($TBL_ADMIN.'.parent_id', '0')
        ->where($TBL_ADMIN.'.status','!=', '2')
        ->orderBy('created_at', 'desc');


        $response = [
            'admin_count' => $allAdmins->count()
        ];
		   return $this->sendResponse($response, 'success.',200);
	 }
	 else if($request->platform ==2) {
		 $TBL_ADMIN      = 'tbl_ms_admin';

        $update_by = Auth::user()->id;
        if(!$request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $allAdmins = Admin_ms::select([$TBL_ADMIN.'.*'])
        ->where($TBL_ADMIN.'.parent_id', '0')
        ->where($TBL_ADMIN.'.status','!=', '2')
        ->orderBy('created_at', 'desc');


        $response = [
            'admin_count' => $allAdmins->count()
        ];
		   return $this->sendResponse($response, 'success.',200);
	 }
   else{
     return $this->sendError('Platfrom Not Matching.', ['error'=>'Enter Correct Platform'],401);
   }
     
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInvoiceList(Request $request)
    {

       /* if(!$request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }
*/
        $TBL_ADMIN_INVOICE      = config('global.admin_invoice_table');
        $TBL_ADMIN      = config('global.admin_table');

        $allInvoiceList = AdminInvoice::select([
                                $TBL_ADMIN_INVOICE.'.*',
                                'invoice_of_admin.name as admin_name',
                                'invoice_of_admin.email as admin_email',
                            ])
                            ->leftJoin($TBL_ADMIN.' as invoice_of_admin',function($join){
                                $join->on('invoice_of_admin.id', '=', 'admin_id');
                            })
                            ->orderBy('created_at', 'desc');


        $searchKeyword = "";
        if ($request->exists('search_keyword')) {
            $searchKeyword = $request->input('search_keyword');
            if ($searchKeyword != '') {
                $allInvoiceList =  $allInvoiceList->where(function($query) use ($TBL_ADMIN_INVOICE,$searchKeyword) {
                    $query->orWhere($TBL_ADMIN_INVOICE.'.invoice_date','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_ADMIN_INVOICE.'.number_of_reports','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_ADMIN_INVOICE.'.report_cost','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_ADMIN_INVOICE.'.payment_status','LIKE','%'.$searchKeyword.'%')
                    ->orWhere($TBL_ADMIN_INVOICE.'.bill_amount','LIKE','%'.$searchKeyword.'%')
                    ->orWhere('invoice_of_admin.name','LIKE','%'.$searchKeyword.'%')
                    ->orWhere('invoice_of_admin.email','LIKE','%'.$searchKeyword.'%');
                });
            }
        }

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($fromDate != null && $fromDate != '' && $toDate != null && $toDate != '') {
            $allInvoiceList =  $allInvoiceList->whereDate($TBL_ADMIN_INVOICE.'.invoice_date','>=',$fromDate)
                ->whereDate($TBL_ADMIN_INVOICE.'.invoice_date','<=',$toDate);
        }

        if(isset($admin_email)){
            $allInvoiceList =  $allInvoiceList->where('approved_by_admin.name','LIKE','%'.$searchKeyword.'%');
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
    public function markPaymentDone(Request $request)
    {

        if(!$request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }

        $arrFieldsToBeValidate = [
            'invoice_id' => 'required|numeric',
            'pay_amount' => 'required|numeric'
        ];

        $fields = $request->validate($arrFieldsToBeValidate);

        $invoice_id = $fields["invoice_id"];
        $pay_amount = $fields["pay_amount"];

        $adminInvoice = AdminInvoice::where('id','=', $invoice_id)->first();

        if (is_null($adminInvoice)) {
            return $this->sendError('Invoie does not exist.');
        }

        if ($adminInvoice->payment_status == "completed"){
            return $this->sendError('There is no due payment for this invoice');
        }

        $alreadyPaid = $adminInvoice->paid_amount;

        if (($alreadyPaid + $pay_amount) > $adminInvoice->bill_amount) {
            return $this->sendError('Pay amount is greater than remaining amount');
        }

        $adminInvoice->paid_amount = ($alreadyPaid + $pay_amount);
        $adminInvoice->payment_mode = "Cash";
        $adminInvoice->payment_date = date('Y-m-d');
        if ($adminInvoice->paid_amount == $adminInvoice->bill_amount ){
            $adminInvoice->payment_status = "completed";
            // generate invoice
            $adminInvoice->update();
            
            $AdminDetails = Admin::where('id','=', $adminInvoice->admin_id)->first();   
            if($AdminDetails){
                $adminInvoice->name = $AdminDetails->name;
                $adminInvoice->address = $AdminDetails->address;
            }else{
                $adminInvoice->name     = "";
                $adminInvoice->address  = "";
            }

            if (!is_null($adminInvoice)) {
                    view()->share('invoice-admin',$adminInvoice);
                    $newFilename = 'moval_invoice_'.$adminInvoice->id.'.pdf';
                    PDF::loadView('invoice-admin',array('invoice'=>$adminInvoice,"draft"=>false),[],[
                        'watermark'      => "CONFIDENTIAL",
                        'show_watermark' => true,
                        'margin_header'              => 0,
                        'watermark_text_alpha' => 0.03
                    ])->save('storage/app/public/admin_invoice/'.$newFilename);

            }


        }
        

        return $this->sendResponse(new AdminInvoiceResources($adminInvoice), 'Payment Done.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAdminSettings(Request $request)
    {

        if(!$request->user()->tokenCan('type:super_admin')) {
            return $this->sendError('Unauthorised.', ['error'=>'Not authenticated'],401);
        }


        $arrFieldsToBeValidate = [
            'type' => 'required|string|in:razorpay',
            'settings' => 'required|string'
        ];

        $fields = $request->validate($arrFieldsToBeValidate);

        $settings = AppSetting::where('type','=', $fields['type'])->first();
        if (is_null($settings)) {
            $settings = AppSetting::create([
                'type' => $fields['type'],
                'message' => $fields['settings']
            ]);
        }else{
            $settings->type = $fields['type'];
            $settings->message = $fields['settings'];
            $settings->update();
        }

        return $this->sendResponse($settings, 'Payment Done.');
    }



}
