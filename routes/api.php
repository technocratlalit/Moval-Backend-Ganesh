<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\MotorValuation\{
    EmployeeController,
    WorksopBranchController,
    AuthController,
    SopController,
    AdminController,
    JobController,
    ClientController,
    ClientBranchController,
    DashboardController,
    AppSettingController,
    BranchContactPersonController,
    SuperAdminController,
    ImportantUpdatesController,
    UploadController,
    JobFillingController,
    InspectionController,
    ReportGeneration,
    BankController
};


use App\Http\Controllers\MotorSurvey\{

    JobmsController,
    AssismentController,
    EmployeemsController,
    FeebillmsController,
    AdminmsController,
    ClientmsController,
    ClientBranchMsController,
    JobFillingControllerms,
    BranchMsController,
    ApprovedInspection,
    InspectionTabsController,
    EstimateController,
    ReportsController,
    LabourRemarksController,
    ShortcutsController,
    CabinBodyAssessmentController
};


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('dev-test', function () {
    return "test pipline" . env('APP_NAME');
});

Route::post('empadmin/auth', [AuthController::class, 'empadminLogin']);
Route::post('file_upload', [UploadController::class, 'upload']);
Route::get('job-link-get/{id}', [JobmsController::class, 'linkget']);

// Route::post('job-upload-link', [JobFillingController::class, 'store_link']);
Route::post('job-upload-link', [JobFillingController::class, 'store']);

Route::get('getimageinbase', [JobFillingController::class, 'getimage']);

Route::post('employee/auth', [AuthController::class, 'employeeLogin']);
Route::post('admin/auth', [AuthController::class, 'adminLogin']);
Route::post('employee/forgot-password', [EmployeeController::class, 'sendForgotPasswordRequest']);
Route::post('employee/verify-otp/{id}', [EmployeeController::class, 'verifyOTP']);
Route::post('admin/forgot-password', [AdminController::class, 'sendForgotPasswordRequest']);
Route::post('admin/verify-otp/{id}', [AdminController::class, 'verifyOTP']);
Route::post('job/verify-payment', [JobController::class, 'verifyPayment']);
Route::post('job/razor-job-payment-link-webhook', [JobController::class, 'updateJobPaymentStatus']);

Route::post('admin/verify-payment', [AdminController::class, 'verifAdminPayment']);
Route::post('admin/razor-admin-payment-link-webhook', [AdminController::class, 'updateAdminPaymentStatus']);

Route::post('super-admin/auth', [AuthController::class, 'superAdminLogin']);
Route::post('super-admin/forgot-password', [SuperAdminController::class, 'sendForgotPasswordRequest']);
Route::post('super-admin/verify-otp/{id}', [SuperAdminController::class, 'verifyOTP']);
Route::get('admin/generate-invoice', [AdminController::class, 'generateInvoice']);

Route::get('storage/job_files/{filename}', function ($filename) {
    $path = storage_path('app/public/job_files/' . $filename);
    if (!File::exists($path)) {
        abort(404);
    }
    return response()->file($path);
});

Route::get('storage/images/{filename}', function ($filename) {
    $path = storage_path('app/public/images/' . $filename);
    if (!File::exists($path)) {
        abort(404);
    }
    return response()->file($path);
});

Route::get('storage/pdf_report/{filename}', function ($filename) {
    $decryptFileName = decryptString($filename);
    $path = storage_path('app/public/pdf_report/' . $decryptFileName);
    if (!File::exists($path)) {
        abort(404);
    }
    return response()->file($path);
});

Route::get('storage/admin_invoice/{filename}', function ($filename) {
    $decryptFileName = decryptString($filename);
    $path = storage_path('app/public/admin_invoice/' . $decryptFileName);
    if (!File::exists($path)) {
        abort(404);
    }
    return response()->file($path);
});

Route::get('storage/pdf_resources/{filename}', function ($filename) {
    $path = storage_path('app/public/pdf_resources/' . $filename);
    if (!File::exists($path)) {
        abort(404);
    }
    return response()->file($path);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('mobile-app-file-upload', [UploadController::class, 'mobileAppUpload']);
    Route::post('approvedinpectionsave/{id}', [ApprovedInspection::class, 'store']);
    Route::get('gettabdata/{id}', [ApprovedInspection::class, 'get']);

    Route::get('getclientbranches', [ApprovedInspection::class, 'getbranchbyclientid']);
    Route::post('savedynamicsection', [ApprovedInspection::class, 'savedynamicsection']);
    Route::get('getdynamicsectiondata', [ApprovedInspection::class, 'getdynamicsectiondata']);
    Route::post('updatedynamicsection', [ApprovedInspection::class, 'updatedynamicsection']);
    Route::delete('deletedynamicsection', [ApprovedInspection::class, 'deletedynamicsection']);
    Route::post('savedynamicsectionmaster', [ApprovedInspection::class, 'savedynamicsectionmaster']);
    Route::get('getdynamicsectionmasterdata', [ApprovedInspection::class, 'getdynamicsectionmasterdata']);
    Route::post('updatedynamicsectionmaster', [ApprovedInspection::class, 'updatedynamicsectionmaster']);
    Route::delete('deletedynamicsectionmaster', [ApprovedInspection::class, 'deletedynamicsectionmaster']);
    Route::get('getdistinctvariant', [ApprovedInspection::class, 'getdistinctvariant']);
    Route::post('storeattachmentmaster', [ApprovedInspection::class, 'storeattachmentmaster']);
    Route::post('storeattachment', [ApprovedInspection::class, 'storeattachment']);
    Route::get('getstoreattachment', [ApprovedInspection::class, 'getstoreattachment']);
    Route::get('getvehicledata', [ApprovedInspection::class, 'getvehicledata']);
    Route::delete('deletemasterattachment', [ApprovedInspection::class, 'deletemasterattachment']);
    Route::post('assessment/{id}', [AssismentController::class, 'store']);
    Route::post('taxdepsetting/{job_id}', [AssismentController::class, 'storetax']);
    Route::get('gettaxdep/{job_id}', [AssismentController::class, 'getdeptaxok']);
    Route::get('taxdepsetting/{job_id}', [AssismentController::class, 'gettax']);
    Route::get('getassessmentsummary/{job_id}', [AssismentController::class, 'get_summery_list']);
    Route::put('assessment/{id}', [AssismentController::class, 'update']);
    Route::get('assessment/{id}', [AssismentController::class, 'show']);
    Route::delete('assessment/{id}', [AssismentController::class, 'destroy']);
    Route::post('sop', [SopController::class, 'index']);
    Route::put('sop/{id}', [SopController::class, 'updatesop']);
    Route::get('sop/{id}', [SopController::class, 'showsop']);
    Route::get('sopall/{id}', [SopController::class, 'showsopall']);
    Route::delete('sop/{id}', [SopController::class, 'delsop']);
    Route::post('branch', [BranchMsController::class, 'branch']);
    Route::put('branch/{id}', [BranchMsController::class, 'branchupdate']);
    Route::get('branch/{id}', [BranchMsController::class, 'branchshow']);
    Route::get('branchall/{id}', [BranchMsController::class, 'branchshowall']);
    Route::delete('branch/{id}', [BranchMsController::class, 'branchdelete']);
    Route::get('delete-letter-head', [BranchMsController::class, 'deleteAdminBranchLetterhead']);

    // New Routes
    Route::post('workshop', [SopController::class, 'workshop']);
    Route::put('workshop/{id}', [SopController::class, 'workshopupdate']);
    Route::delete('workshop/{id}', [SopController::class, 'workshopdelete']);
    Route::get('workshop/{id}', [SopController::class, 'workshopshow']);
    Route::get('workshopall/{id}', [SopController::class, 'workshopshowall']);
    Route::post('wbcontact', [SopController::class, 'workshopbranch']);
    Route::put('wbcontact/{id}', [SopController::class, 'workshopbranchupdte']);
    Route::delete('wbcontact/{id}', [SopController::class, 'workshopbranwbcontactbybranchchdelete']);
    Route::get('wbcontact/{id}', [SopController::class, 'workshopbranchshow']);
    Route::get('wballcontact', [SopController::class, 'workshopbranchshowall']);
    Route::get('wbcontactbybranch', [SopController::class, 'workshopbranchshowbyid']);
    Route::post('workshop/set-password', [SopController::class, 'worksopContactSetPassword']);
    Route::get('employeems/{id}/{status}', [EmployeemsController::class, 'updateStatus']);

    Route::post('employeems', [EmployeemsController::class, 'store']);
    Route::put('employeems/{id}', [EmployeemsController::class, 'update']);
    Route::get('employeems/{id}', [EmployeemsController::class, 'employeebyid']);
    Route::get('list-ms-employee', [EmployeemsController::class, 'list']);
    Route::delete('delempms/{id}', [EmployeemsController::class, 'delemployee']);

    Route::post('wsbranch', [WorksopBranchController::class, 'store']);
    Route::put('wsbranch/{id}', [WorksopBranchController::class, 'update']);
    Route::get('wsbranch/{id}', [WorksopBranchController::class, 'showbyid']);
    Route::get('wsbranchall', [WorksopBranchController::class, 'list']);
    Route::delete('wsbranch/{id}', [WorksopBranchController::class, 'deletewsb']);
    Route::get('wsbranch_wid/{id}', [WorksopBranchController::class, 'showbyid_wid']);
    Route::post('add-local-workshop', [WorksopBranchController::class, 'localWorkshop']);
    Route::get('edit-local-workshop/{workshop_id}', [WorksopBranchController::class, 'editworkshop']);

    // Super Admin Api Routes
    Route::post('super-admin/change-password', [SuperAdminController::class, 'changePassword']);
    Route::get('super-admin/dashboard', [SuperAdminController::class, 'getDashboardData']);
    Route::get('super-admin/admin-invoice-list', [SuperAdminController::class, 'getInvoiceList']);
    Route::post('super-admin/payment-done', [SuperAdminController::class, 'markPaymentDone']);

    Route::post('adminms', [AdminmsController::class, 'store']);
    Route::get('adminms/{id}', [AdminmsController::class, 'show']);
    Route::get('adminms', [AdminmsController::class, 'index']);
    Route::put('adminms/{id}', [AdminmsController::class, 'update']);
    Route::delete('adminms/{id}', [AdminmsController::class, 'destroy']);
    Route::get('adminms/{id}/{status}', [AdminmsController::class, 'updateStatus']);

    Route::get('getadminemployee', [AdminmsController::class, 'getalladminemployeeNew']);
    Route::get('get_job_details_list', [AdminmsController::class, 'getAdminBasedData']);

    Route::post('feebill', [FeebillmsController::class, 'store']);
    Route::get('feebill/{id}', [FeebillmsController::class, 'show']);
    Route::delete('feebill/{id}', [FeebillmsController::class, 'destroy']);
    Route::get('feebill', [FeebillmsController::class, 'index']);
    Route::get('fee-bill-calculation/{inspection_id}', [FeebillmsController::class, 'feeBillCalculation']);


    Route::get('get-job-by-id/{id}', [InspectionController::class, 'index']);
    Route::post('update-inspection', [InspectionController::class, 'update']);
    Route::get('inspection-jobstatus/{id}', [InspectionController::class, 'updatejob']);
    Route::post('assesment-inputupdate', [InspectionController::class, 'update_inputbox']);
    //Job Post
    Route::post('manual-upload', [JobFillingController::class, 'store']);
    Route::post('manual-upload-app', [JobFillingController::class, 'UploadSignture']);
    Route::get('get-job-files', [JobFillingController::class, 'get_job_files']);

    Route::get('sop-branchid/{id}', [JobFillingController::class, 'sop_branchid']);
    Route::get('workshop-branchid/{id}', [JobFillingController::class, 'workshop_branchid']);
    Route::get('client-branchid/{id}', [JobFillingController::class, 'client_branchid']);
    Route::get('employee-branchid/{id}', [JobFillingController::class, 'employee_branchid']);
    Route::get('admin-branchid/{id}', [JobFillingController::class, 'admin_branchid']);
    Route::get('subadmin-branchid/{id}', [JobFillingController::class, 'subadmin_branchid']);



    Route::post('job-assign-ms', [JobmsController::class, 'assign']);
    Route::get('jobms-app', [JobmsController::class, 'jobmsApp']);

    Route::post('jobms', [JobmsController::class, 'store']);
    Route::get('jobms/{id}', [JobmsController::class, 'show']);
    Route::put('jobms/{id}', [JobmsController::class, 'update']);
    Route::delete('jobms/{id}', [JobmsController::class, 'destroy']);
    Route::get('jobms', [JobmsController::class, 'index']);
    Route::get('job-link-create/{id}', [JobmsController::class, 'linkcreate']);

    // Admin Api Routes
    Route::resource('admin', AdminController::class);
    Route::post('admin/logout', [AuthController::class, 'adminLogout']);
    Route::post('admin/change-password', [AdminController::class, 'changePassword']);
    Route::post('admin/set-password', [AdminController::class, 'setPassword']);
    Route::get('admin/{id}/{status}', [AdminController::class, 'updateStatus']);
    Route::get('admin-info', [AdminController::class, 'getAdminInfo']);
    Route::get('admin-invoice-list', [AdminController::class, 'getInvoiceList']);
    Route::get('admin-resend-credentials/{id}', [AdminController::class, 'resendAdminCredentials']);
    Route::get('admin-settings/{type}', [AdminController::class, 'getAdminSettingsByType']);
    Route::post('admin-save-settings', [AdminController::class, 'saveAdminSettingsByType']);
    Route::post('admin-payment-link/{id}', [AdminController::class, 'getPaymentLink']);
    Route::get('admin-delete-jobfiles/{id}/{created_date}', [AdminController::class, 'deleteAdminPhotos']);
    Route::resource('important-updates', ImportantUpdatesController::class);

    // Contact Person Api Routes
    Route::resource('contactperson', BranchContactPersonController::class);
    Route::get('contactperson/{id}/{status}', [BranchContactPersonController::class, 'updateStatus']);

    // Employee Api Routes
    Route::resource('employee', EmployeeController::class);
    Route::post('employee/change-password', [EmployeeController::class, 'changePassword']);
    Route::post('employee/update-token', [EmployeeController::class, 'updateToken']);
    Route::post('employee/set-password/{id}', [EmployeeController::class, 'setPassword']);
    Route::get('employee/reset-device/{id}', [EmployeeController::class, 'resetDeviceId']);
    Route::get('employee/{id}/{status}', [EmployeeController::class, 'updateStatus']);

    //client ms api
    Route::post('clientms', [ClientmsController::class, 'store']);
    Route::get('clientms/{id}', [ClientmsController::class, 'show']);
    Route::put('clientms/{id}', [ClientmsController::class, 'update']);
    Route::delete('clientms/{id}', [ClientmsController::class, 'destroy']);
    Route::get('clientms/{id}/{status}', [ClientmsController::class, 'updateStatus']);
    Route::get('clientms', [ClientmsController::class, 'index']);

    //client branch ms api
    Route::post('clientbranchms', [ClientBranchMsController::class, 'store']);
    Route::get('clientbranchms/{id}', [ClientBranchMsController::class, 'show']);
    Route::get('OfficebyCode', [ClientBranchMsController::class, 'OfficebyCode']);
    Route::get('clientbranchms', [ClientBranchMsController::class, 'index']);
    Route::put('clientbranchms/{id}', [ClientBranchMsController::class, 'update']);
    Route::delete('clientbranchms/{id}', [ClientBranchMsController::class, 'destroy']);
    Route::get('clientbranchms/{id}/{status}', [ClientBranchMsController::class, 'updateStatus']);


    //client branch contact person
    Route::post('client-branch-contact', [ClientmsController::class, 'client_contact_store']);
    Route::put('client-branch-contact-update/{id}', [ClientmsController::class, 'client_contact_update']);
    Route::delete('client-branch-contact-delete/{id}', [ClientmsController::class, 'client_contact_delete']);
    Route::get('client-branch-contact-get/{id}', [ClientmsController::class, 'client_contact_getbyid']);
    Route::get('client-branch-contact-branch-get/{id}', [ClientmsController::class, 'client_contact_getbybranchid']);
    Route::get('client-branch-contact-branch-getall', [ClientmsController::class, 'client_contact_getall']);


    // Client Api Routes
    Route::resource('client', ClientController::class);
    Route::get('client/{id}/{status}', [ClientController::class, 'updateStatus']);
    Route::resource('clientbranch', ClientBranchController::class);
    Route::get('clientbranch/{id}/{status}', [ClientBranchController::class, 'updateStatus']);
    Route::get('settings/{action}', [AppSettingController::class, 'index']);
    Route::post('settings', [AppSettingController::class, 'store']);

    // Job Api Routes
    // Route::resource('job', JobController::class);
    Route::resource('job', JobmsController::class);
    Route::post('job/get-vehicle-detail', [JobController::class, 'getVehicleDetail']);
    Route::post('job/send-mail-report', [JobController::class, 'sendReportInEmail']);
    Route::post('job/send-payment-link', [JobController::class, 'sendPaymentLink']);
    Route::post('job/upload-image-video/{id}', [JobController::class, 'uploadJobFile']);
    Route::post('job/update-job-image/{id}', [JobController::class, 'updateJobImage']);
    Route::post('job/submit-image-video/{id}', [JobController::class, 'submitJobImageAndVideo']);
    Route::put('job/update-job-basic-info/{id}', [JobController::class, 'updateJobBasicInfo']);
    Route::post('job/vehicle-variants/{id}', [JobController::class, 'getModelsByMaker']);
    Route::post('job/assign-employee/{id}', [JobController::class, 'assignEmployeeToJob']);
    Route::put('job/update-job/{id}', [JobController::class, 'updateJob']);
    Route::put('job/re-active/{id}', [JobController::class, 'reactiveJob']);
    Route::put('job/update-remark/{id}', [JobController::class, 'updateJobRemark']);
    Route::get('job/finalize-report/{id}', [JobController::class, 'finalizeReport']);
    Route::put('job/{id}/{status}', [JobController::class, 'updateStatus']);

    // Dashboard Api Routes
    Route::resource('dashboard', DashboardController::class);
    Route::get('dashboardms', [DashboardController::class, 'indexms']);

    //Bank Post
    Route::post('post-bank-details', [BankController::class, 'saveUpdateBankDetails']);
    Route::delete('delete-bank/{id}', [BankController::class, 'deleteBank']);
    Route::get('get-bank-list/{admin_id}', [BankController::class, 'getBankList']);
    Route::get('get-bank-list/{admin_id}/{admin_branch_id}', [BankController::class, 'getBankList']);

    //For Labour & Remarks
    Route::post('save-update-labour-remarks', [LabourRemarksController::class, 'saveUpdateLabourRemarks']);
    Route::post('save-update-labour-remarks/{id}', [LabourRemarksController::class, 'saveUpdateLabourRemarks']);
    Route::get('get-labour-remarks/{admin_id}', [LabourRemarksController::class, 'labourRemarksList']);
    Route::get('get-labour-remarks/{admin_id}/{type}', [LabourRemarksController::class, 'labourRemarksList']);
    Route::delete('delete-labour-remarks/{admin_id}/{id}', [LabourRemarksController::class, 'deleteLabourRemarks']);

    //For Shortcuts
    Route::post('save-update-shortcuts', [ShortcutsController::class, 'saveUpdateShortcuts']);
    Route::post('save-update-shortcuts/{id}', [ShortcutsController::class, 'saveUpdateShortcuts']);
    Route::get('get-shortcuts/{admin_id}', [ShortcutsController::class, 'shortcutsList']);
    Route::get('get-tag-shortcuts/{admin_id}', [ShortcutsController::class, 'shortcutsListWithTagIndex']);
    Route::delete('delete-shortcuts/{admin_id}/{id}', [ShortcutsController::class, 'deleteShortcuts']);
    Route::post('save-update-cabin-body-assessment/{id}', [CabinBodyAssessmentController::class, 'saveUpdateCabinBodyAssessment']);
    Route::get('get-cabin-body-assessment-list/{id}', [CabinBodyAssessmentController::class, 'getCabinBodyAssessmentList']);
    Route::get('get-cabin-body-assessment-summary/{id}', [CabinBodyAssessmentController::class, 'getCabinBodyAssessmentSummaryData']);
    Route::post('save-update-cabin-body-tax/{id}', [CabinBodyAssessmentController::class, 'saveUpdateCabinBodyAssessmentTax']);
    Route::get('get-cabin-body-tax-setting/{id}', [CabinBodyAssessmentController::class, 'getCabinBodyTaxSetting']);

    Route::post('save-update-estimates-details/{inspection_id}', [InspectionController::class, 'saveUpdateEstimatesDetails']);
    Route::get('get-estimated-detail/{inspection_id}', [InspectionController::class, 'getEstimatesDetails']);
});


// Routes for Motor Survey

Route::prefix('ms')->middleware('auth:sanctum')->group(function () {
    Route::get('surveyfee', [InspectionTabsController::class, 'surveyfee']);
    Route::get('/get/tab-data', [InspectionTabsController::class, 'getApprovedData']);
    Route::post('/post/tab-data', [InspectionTabsController::class, 'StoreorUpdate']);
    Route::get('getvehicledata', [InspectionTabsController::class, 'getvehicledata']);
    Route::post('/get/base-encode', [InspectionTabsController::class, 'ConvertURltoBase']);
    Route::get('/admin-resend-credentials/{id}', [AdminmsController::class, 'resendAdminCredentials']);
    Route::get('/get-branch-admins/{branch_admin_id}', [BranchMsController::class, 'getBranchAdmins']);
    Route::post('/post/reinsection-tab', [InspectionTabsController::class, 'ConvertURltoBase']);
    Route::post('add-pod-template', [InspectionTabsController::class, 'podTemplates']);
    Route::get('pod-template-list/{admin_branchId}', [InspectionTabsController::class, 'podTemplateList']);
    Route::get('replace-tags-data/{inspection_id}', [InspectionTabsController::class, 'replaceTagsData']);
    Route::post('import-estimate-structure', [EstimateController::class, 'estimateStore']);
    Route::get('get-estimate-structure', [EstimateController::class, 'estimateStructureList']);
    Route::get('estimate-structure-details/{id}', [EstimateController::class, 'estimateDeatils']);
    Route::get('/preview-reports', [ReportsController::class, 'previewReports']);
    Route::get('/fee-bill-reports', [ReportsController::class, 'feeBillReports']);
});

Route::post('/rotate-image', [ImageController::class,'rotateImage']);
Route::get('/image/{imageName}', [ImageController::class,'getImage']);
// Routes for Motor Valuation
Route::prefix('mv')->middleware('auth:sanctum')->group(function () {
    Route::get('/admin-resend-credentials/{id}', [AdminmsController::class, 'resendAdminCredentials']);
    Route::get('/get-branch-admins/{branch_admin_id}', [BranchMsController::class, 'getBranchAdmins']);
});

// Routes for Mobile App
Route::prefix('mobile-app')->middleware('auth:sanctum')->group(function () {
    Route::post('manual-upload-docs', [JobFillingController::class, 'store']);
    Route::post('upload-signature', [JobFillingController::class, 'UploadSignture']);
    Route::get('get-job-files', [JobFillingController::class, 'get_job_files']);
});
