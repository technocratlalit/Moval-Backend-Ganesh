<?php

use App\Models\Admin;
use App\Models\Employee;
use App\Models\Workshop_contact_person;
use App\Models\Admin_ms;
use App\Models\Employeems;
use App\Models\Branch;
use App\Models\SuperAdmin;
use App\Models\Msbranchcontact;
use App\Models\SuperAdmin_ms;
use App\Models\Clientms;
use App\Models\Inspection;
use App\Models\Workshop;
use App\Models\ClientBranchMs;
use App\Models\TaxSetting;
use App\Models\AssismentDetailList;
use App\Models\AssismentDetail;
use App\Models\Jobms;
use App\Models\SopMaster;
use App\Models\Manuallupload;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


// function UploadFilesToS3($bucket='s3', $folder='images/', $file){
//     // $imageName =  $file->getClientOriginalName().'.'.$file->getClientOriginalExtension(); 
//     $imageName =  $file->getClientOriginalName(); 
//     $fileUpload =  Storage::disk($bucket)->put($folder . $imageName, file_get_contents($file));
//     return ['is_upload' => $fileUpload,'store_image_name' => $imageName ]; 
// }

function number_format_custom($param1=null, $param2 = 2, $param3 = '.', $param4 = '') {
    return number_format($param1, $param2, $param3, $param4);
}


function DeleteFilesFromS3($bucket, $imagePath)
{
    if (Storage::disk($bucket)->exists($imagePath)) {
        return Storage::disk($bucket)->delete($imagePath);
    }
    return false;
}


if (!function_exists('sendNotificationToEmployee')) {
    function sendNotificationToEmployee($registratoin_ids, $message, $topics = false, $topicName = "")
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FIREBASE_PUSH_KEY = config('global.firebase_push_key');
        $JOB_NOTIFICATION_CHANNEL = config('global.job_notification_channel_id');

        $fields = array();
        $fields['data'] = $message;

        $message['android_channel_id'] = $JOB_NOTIFICATION_CHANNEL;

        $fields['notification'] = $message;

        if ($topics) {
            $fields['to'] = "/topics/" . $topicName;
        } else {
            $fields['registration_ids'] = $registratoin_ids;
        }

        $fields['priority'] = 'high';

        //header with content_type api key
        $headers = array('Content-Type:application/json', 'Authorization:key=' . $FIREBASE_PUSH_KEY);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            // die('FCM Send Error: ' . curl_error($ch));

        }
        curl_close($ch);
    }
}

if (!function_exists('sendJobNotificationToEmployee')) {
    function sendJobNotificationToEmployee($token, $jobId, $notificatonType)
    {

        $NOTIFICATION_TITLE = "";
        $NOTIFICATION_MESSAGE = "";
        if ($notificatonType == "job_assigned_to_employee") {
            $NOTIFICATION_TITLE = config('global.new_job_notification_title');
            $NOTIFICATION_MESSAGE = config('global.new_job_notification_message');
        } else if ($notificatonType == "job_submitted_by_employee") {
            $NOTIFICATION_TITLE = config('global.job_submitted_notification_title');
            $NOTIFICATION_MESSAGE = config('global.job_submitted_notification_message');
        }
        $message = array(
            "title" => $NOTIFICATION_TITLE, "body" => $NOTIFICATION_MESSAGE,
            "sound" => "default", "inspection_id" => $jobId
        );
        $registrationId[] = $token;
        sendNotificationToEmployee($registrationId, $message);
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 15)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('encryptString')) {
    function encryptString($stringData)
    {
        $privateKey = 'AA74CDCC2BBRT935136HH7B63C27'; // user define key
        $secretKey = '5fgf5HJ5g27'; // user define secret key
        $encryptMethod = "AES-256-CBC";

        $key = hash('sha256', $privateKey);
        $ivalue = substr(hash('sha256', $secretKey), 0, 16); // sha256 is hash_hmac_algo
        $result = openssl_encrypt($stringData, $encryptMethod, $key, 0, $ivalue);
        return base64_encode($result);  // output is a encripted value
    }
}


if (!function_exists('decryptString')) {
    function decryptString($encryptString)
    {
        $privateKey = 'AA74CDCC2BBRT935136HH7B63C27'; // user define key
        $secretKey = '5fgf5HJ5g27'; // user define secret key
        $encryptMethod = "AES-256-CBC";
        $key = hash('sha256', $privateKey);
        $ivalue = substr(hash('sha256', $secretKey), 0, 16); // sha256 is hash_hmac_algo
        return openssl_decrypt(base64_decode($encryptString), $encryptMethod, $key, 0, $ivalue);
    }
}

if (!function_exists('getAdminIdIfAdminLoggedIn')) {
    function getAdminIdIfAdminLoggedIn($adminId)
    {
        $admin = Admin::where('id', $adminId)->first();
        if ($admin) {
            if ($admin->parent_id == '0') {
                return $admin->id;
            } else {
                return $admin->parent_id;
            }
        } else {
            return 0;
        }
    }
}
if (!function_exists('getAdminIdIfAdminLoggedIn1')) {
    function getAdminIdIfAdminLoggedIn1($adminId)
    {
        $admin = Admin_ms::where('id', $adminId)->first();
        if ($admin) {
            if ($admin->parent_id == '0') {
                return $admin->id;
            } else {
                return $admin->parent_id;
            }
        } else {
            return 0;
        }
    }
}


if (!function_exists('getAdminIdIfEmployeeLoggedIn')) {
    function getAdminIdIfEmployeeLoggedIn($employeeId)
    {
        $employee = Employee::where('id', '=', $employeeId)->first();
        if ($employee) {
            return $employee->parent_admin_id;
        } else {
            return '';
        }
    }
}

if (!function_exists('getAdminIdIfEmployeeLoggedIn1')) {
    function getAdminIdIfEmployeeLoggedIn1($employeeId)
    {
        $employee = Employeems::where('id', '=', $employeeId)->first();
        return $employee->parent_admin_id;
    }
}


if (!function_exists('getAdminBranchChilderns')) {
    function getAdminBranchChilderns($adminId)
    {
        return Branch::where(['admin_id' => $adminId])->pluck('id');


    }
}


if (!function_exists('getBranchbybranchid')) {
    function getBranchbybranchid($branchId)
    {
        $branch = Branch::where('id', '=', $branchId)->first();
        if ($branch) {
            return $branch->admin_branch_name;
        } else {
            return '';
        }
    }
}
if (!function_exists('getsuperadminemail')) {
    function getsuperadminemail()
    {
        $superAdmin = SuperAdmin::where('id', 1)->first();
        if ($superAdmin) {
            return $superAdmin->email;
        } else {
            return '';
        }
    }
}


function checkIsBranchAdminIsDisableOrEnable($id)
{
    $branchAdmin = Admin_ms::withTrashed()->where('id', $id)->first();

    if ($branchAdmin !== null) {
        if ($branchAdmin->trashed()) {
            return null; // The branch admin is disabled (trashed)
        }
        return $id; // The branch admin is enabled
    } else {
        return null; // No branch admin found with the given ID
    }
}


if (!function_exists('getClientBranchById')) {
    function getClientBranchById($id)
    {
        $clientBranch = ClientBranchMs::where('id', $id)->first();
        if ($clientBranch) {
            return $clientBranch->office_name;
        } else {
            return '';
        }
    }
}

if (!function_exists('checkEmailExistOrNot')) {
    function checkEmailExistOrNot($email)
    {
        $superadmin = SuperAdmin_ms::where('email', $email)->first();
        $msbranchcontact = Msbranchcontact::where('email', $email)->first();
        $admin_ms = Admin_ms::where('email', $email)->first();
        $employeems = Employeems::where('email', $email)->first();
        if ($superadmin || $msbranchcontact || $admin_ms || $employeems) {
            return "exists";
        } else {
            return '';
        }
    }
}


if (!function_exists('checkEmailExistOrNotmv')) {
    function checkEmailExistOrNotmv($email)
    {
        $superadmin = SuperAdmin::where('email', $email)->first();
        $admin = Admin::where('email', $email)->first();
        $employee = Employee::where('email', $email)->first();
        if ($superadmin || $admin || $employee) {
            return "exists";
        } else {
            return '';
        }
    }
}

if (!function_exists('getmainAdminIdIfAdminLoggedIn')) {
    function getmainAdminIdIfAdminLoggedIn($adminId)
    {
        $admin = Admin_ms::where('id', $adminId)->first();
        //  return $admin->getSuperAdmin->id; 
        if ($admin) {
            if ($admin->parent_id == '0') {
                return $admin->id;
            } else {

                $id = $admin->parent_id;
                $parent_admin = Admin_ms::where('id', $id)->first();
                if ($parent_admin) {
                    if ($parent_admin->parent_id == '0') {
                        $id = $parent_admin->id;
                    } else {
                        $id = $parent_admin->parent_id;
                    }
                }

                return $id;
            }
        } else {
            return $adminId;
        }
    }
}


if (!function_exists('checkEmailExistOrNot')) {
    function checkEmailExistOrNot($email)
    {
        $superadmin = SuperAdmin_ms::where('email', $email)->first();
        $msbranchcontact = Msbranchcontact::where('email', $email)->first();
        $admin_ms = Admin_ms::where('email', $email)->first();
        $employeems = Employeems::where('email', $email)->first();
        if ($superadmin || $msbranchcontact || $admin_ms || $employeems) {
            return "exists";
        } else {
            return '';
        }
    }
}


if (!function_exists('checkEmailExistOrNotmv')) {
    function checkEmailExistOrNotmv($email)
    {
        $superadmin = SuperAdmin::where('email', $email)->first();
        $admin = Admin::where('email', $email)->first();
        $employee = Employee::where('email', $email)->first();
        if ($superadmin || $admin || $employee) {
            return "exists";
        } else {
            return '';
        }
    }
}

if (!function_exists('getBranchidbyAdminid')) {
    function getBranchidbyAdminid($adminId)
    {
        $admin = Admin_ms::where('id', $adminId)->first();
        if ($admin)
            return $admin->admin_branch_id;
    }
}

if (!function_exists('getClientNameByClientid')) {
    function getClientNameByClientid($clientid)
    {
        $client = Clientms::where('id', $clientid)->first();
        if ($client)
            return $client->client_name;
    }
}

if (!function_exists('adminname')) {
    function adminname($admin_id = '')
    {
        if ($admin_id != '') {
            $client = Admin_ms::where('id', $admin_id)->first();
            if ($client)
                return $client->name;
        } else {
            return 'not assigned';
        }
    }
}


if (!function_exists('getdeprecationpercentagebyjobid')) {
    function getdeprecationpercentagebymonth($inspection_id, $type, $amount = "")
    {
        $tax_setting = TaxSetting::where('inspection_id', $inspection_id)->first();
        $insepection = Inspection::with('get_vehicle_detail', 'get_accident_detail')->where('id', $inspection_id)->first();
        $actualDateOfRegPur = [];
        if(isset($insepection->get_vehicle_detail->date_of_purchase) && !empty($insepection->get_vehicle_detail->date_of_purchase)) {
            $actualDateOfRegPur[] = date('Y-m-d', strtotime($insepection->get_vehicle_detail->date_of_purchase));
        }
        if(isset($insepection->get_vehicle_detail->date_of_registration) && !empty($insepection->get_vehicle_detail->date_of_registration)) {
            $actualDateOfRegPur[] = date('Y-m-d', strtotime($insepection->get_vehicle_detail->date_of_registration));
        }
        $actualDateOfRegPur = !empty($actualDateOfRegPur) ? min($actualDateOfRegPur) : '';

        $date_time_accident = (isset($insepection->get_accident_detail->date_time_accident) && !empty($insepection->get_accident_detail->date_time_accident)) ? date('Y-m-d', strtotime($insepection->get_accident_detail->date_time_accident)) : '';

        if (!empty($actualDateOfRegPur) && !empty($date_time_accident)) {
            $startdate = $actualDateOfRegPur;
            if($tax_setting->DepBasedOn == 'MD' && !empty($insepection->get_vehicle_detail->vehicle_model)) {
                $startdate = date('Y-m', strtotime($insepection->get_vehicle_detail->vehicle_model)).'-01';
            }
            $enddate = $date_time_accident;
            $date = new DateTime($enddate);
            $endDate = $date->format('Y-m-d');
            $startDate = new DateTime($startdate);
            $endDate = new DateTime($endDate);
            $interval = $startDate->diff($endDate);
            $month = intval(($interval->format('%y') * 12) + $interval->format('%m'));

            $tdeuction = 0;
            if ($month >= 0 && $month < 6) {
                if ($type == 'Rubber') {
                    $tdeuction = 50;
                } else if ($type == 'Metal') {
                    $tdeuction = 0;
                } else if ($type == 'Fibre') {
                    $tdeuction = 30;
                }
            } else if ($month >= 6 && $month < 12) {
                if ($type == 'Rubber') {
                    $tdeuction = 50;
                } else if ($type == 'Metal') {
                    $tdeuction = 5;
                } else if ($type == 'Fibre') {
                    $tdeuction = 30;
                }
            } else if ($month >= 12 && $month < 24) {

                if ($type == 'Rubber') {

                    $tdeuction = 50;
                } else if ($type == 'Metal') {

                    $tdeuction = 10;
                } else if ($type == 'Fibre') {
                    $tdeuction = 30;
                }
            } else if ($month >= 24 && $month < 36) {
                if ($type == 'Rubber') {
                    $tdeuction = 50;
                } else if ($type == 'Metal') {
                    $tdeuction = 15;
                } else if ($type == 'Fibre') {
                    $tdeuction = 30;
                }
            } else if ($month >= 36 && $month < 48) {
                if ($type == 'Rubber') {
                    $tdeuction = 50;
                } else if ($type == 'Metal') {
                    $tdeuction = 25;
                } else if ($type == 'Fibre') {
                    $tdeuction = 30;
                }
            } else if ($month >= 48 && $month < 60) {
                if ($type == 'Rubber') {
                    $tdeuction = 50;
                } else if ($type == 'Metal') {
                    $tdeuction = 35;
                } else if ($type == 'Fibre') {
                    $tdeuction = 30;
                }
            } else if ($month >= 60 && $month < 120) {
                if ($type == 'Rubber') {
                    $tdeuction = 50;
                } else if ($type == 'Metal') {
                    $tdeuction = 40;
                } else if ($type == 'Fibre') {
                    $tdeuction = 30;
                }
            } else if ($month >= 120) {
                if ($type == 'Rubber') {
                    $tdeuction = 50;
                } else if ($type == 'Metal') {
                    $tdeuction = 50;
                } else if ($type == 'Fibre') {
                    $tdeuction = 30;
                }
            }
            if ($amount != "") {
                return $damount = ($amount * $tdeuction) / 100;
            } else {
                return $tdeuction;
            }
        } else {
            return 0;
        }
    }
}


if (!function_exists('getworkshopnamebyid')) {
    function getworkshopnamebyid($wid)
    {
        $data = Workshop::where('id', $wid)->first();
        if ($data) {
            return $data->workshop_name;
        } else {
            return $wid;
        }
    }
}


if (!function_exists('getgstamount')) {
    function getgstamount($amount, $per)
    {

        return $damount = ($amount * $per) / 100;
    }
}

if (!function_exists('getclientname')) {
    function getclientname($id)
    {
        $data = Clientms::where('id', $id)->first();
        if ($data) {
            return $data->name;
        } else {
            return $id;
        }
    }
}
if (!function_exists('getclientbranchname')) {
    function getclientbranchname($id)
    {
        $data = ClientBranchMs::where('id', $id)->first();
        if ($data) {
            return $data->name;
        } else {
            return $id;
        }
    }
}


if (!function_exists('checkgstpartAss')) {
    function checkgstpartAss($id, $assid)
    {
        $data = TaxSetting::where('inspection_id', $id)->first();
        $data2 = AssismentDetailList::where('id', $assid)->first();

        if ($data->MutipleGSTonParts != "true") {

            return $data->GSTAssessedPartsPer;
        } else {
            return $data2->gst;
        }
    }
}

if (!function_exists('checkgstpartEst')) {
    function checkgstpartEst($id, $assid)
    {
        $data = TaxSetting::where('inspection_id', $id)->first();
        $data2 = AssismentDetailList::where('id', $assid)->first();

        if ($data->MutipleGSTonParts != "true") {

            return $data->GSTEstimatedPartsPer;
        } else {
            return $data2->gst;
        }
    }
}

if (!function_exists('imt23dep')) {
    function imt23dep($id, $amount)
    {
        $data = TaxSetting::where('inspection_id', $id)->first();
        $tax = 0;

        if ($data && $data->IMT23DepPer != "") {

            $tax = $data->IMT23DepPer;
        }

        return $damount = ($amount * $tax) / 100;
    }
}

if (!function_exists('labourgstass')) {
    function labourgstass($id, $amount, $taxf = 0)
    {
        $data = TaxSetting::where('inspection_id', $id)->first();
        $tax = 0;

        if ($data && $data->GstonAssessedLab == 'Y') {
            if ($data->MultipleGSTonLab == 0) {
                $tax = $data->GSTLabourPer;
            } else {
                $tax = $taxf;
            }
        }


        return $damount = ($amount * $tax) / 100;
    }
}

if (!function_exists('labourgstest')) {
    function labourgstest($id, $amount, $taxf = 0)
    {
        $data = TaxSetting::where('inspection_id', $id)->first();
        $tax = 0;

        if ($data && $data->GSTonEstimatedLab == "Y") {

            if ($data->MultipleGSTonLab == 0) {
                $tax = $data->GSTLabourPer;
            } else {
                $tax = $taxf;
            }
        }

        return $damount = ($amount * $tax) / 100;
    }
}


if (!function_exists('vehicle_reg_no')) {
    function vehicle_reg_no($inspection_id)
    {
        $data = Inspection::where('id', $inspection_id)->first();

        $reg = "";
        if ($data && $data->vehicle_reg_no) {

            $reg = $data->vehicle_reg_no;
        }

        return $reg;
    }
}

if (!function_exists('insured_name')) {
    function insured_name($inspection_id)
    {
        $data = Inspection::where('id', $inspection_id)->first();

        $reg = "";
        if ($data && $data->insured_name) {

            $reg = $data->insured_name;
        }

        return $reg;
    }
}

if (!function_exists('assigned_to_name')) {
    function assigned_to_name($inspection_id)
    {

        $data = Inspection::where('id', $inspection_id)->first();

        if ($data) {
            if ($data->Job_Route_To == 2)
                return 'Motor survey Link';
        }


        if ($data->jobjssignedto_surveyorEmpId != 0 && $data->jobjssignedto_surveyorEmpId != "") {
            $id = $data->jobjssignedto_surveyorEmpId;
            $type = $data->user_role;
        } else if ($data->jobassignedto_workshopEmpid != 0 && $data->jobassignedto_workshopEmpid != "") {
            $id = $data->jobassignedto_workshopEmpid;
            $type = $data->user_role;
        } else {
            return '';
        }


        if ($id != '') {
            if ($type == 'employee') {
                $client = Employeems::where('id', $id)->first();
                if ($client) {
                    return $client->name;
                }
            } else {

                $client = Admin_ms::where('id', $id)->first();
                if ($client) {
                    return $client->name;
                }
            }
        } else {
            return 'not assigned';
        }
    }
}

if (!function_exists('submitted_by')) {
    function submitted_by($inspection_id)
    {

        $ans = '';
        $data = Inspection::where('id', $inspection_id)->first();
        if ($data->job_status != '' && $data->job_status == 'submitted') {
            if ($data->Job_Route_To == 2) {
                $ans = 'Survey Link'; // $data->submitted_by; 
            } else {
                if ($data->submitted_by != '') {


                    if ($data->submitted_by_role == 'employee') {
                        $client = Employeems::where('id', $data->submitted_by)->first();
                        if ($client) {
                            $ans = $client->name;
                        }
                    } else {

                        $client = Admin_ms::where('id', $data->submitted_by)->first();
                        if ($client) {
                            $ans = $client->name;
                        }
                    }
                }
            }
        } else {
            $ans = 'not submitted yet';
        }

        return $ans;
    }
}


if (!function_exists('approved_by')) {
    function approved_by($inspection_id)
    {

        $ans = '';
        $data = Inspection::where('id', $inspection_id)->first();
        if ($data->job_status != '' && $data->job_status == 'approved') {

            if ($data->approved_by != '') {


                $client = Admin_ms::where('id', $data->approved_by)->first();
                if ($client) {
                    $ans = $client->name;
                }
            } else {
                $ans = 'not approved yet';
            }
        } else {
            $ans = 'not approved yet';
        }

        return $ans;
    }
}


if (!function_exists('photosuploaded1')) {
    function photosuploaded1($inspection_id)
    {

        return $data = Manuallupload::where('inspection_id', $inspection_id)->where('file_type', 1)->count();
    }
}

if (!function_exists('photosuploaded2')) {
    function photosuploaded2($inspection_id)
    {

        return $data = Manuallupload::where('inspection_id', $inspection_id)->where('file_type', 2)->count();
    }
}


if (!function_exists('can_record_video')) {
    function can_record_video($inspection_id)
    {
        $data = SopMaster::where('id', $inspection_id)->first();

        $reg = 0;
        if ($data->can_record_video) {

            $reg = $data->can_record_video;
        }

        return $reg;
    }
}

if (!function_exists('getasslist')) {
    function getasslist($inspection_id, $col)
    {
        $data = Inspection::where('id', $inspection_id)->first();

        $reg = 0;
        if ($data && $data->$col != '') {

            $reg = $data->$col;
        }

        return $reg;
    }
}


if (!function_exists('checkdep')) {

    function checkdep($inspection_id)
    {

        $MetalDepPer = 0;
        $GlassDepPer = 0;
        $RubberDepPer = 0;
        $FibreDepPer = 0;


        $types = ['metal', 'Glass', 'Fibre', 'Rubber', 'Recondition'];

        $data = TaxSetting::where('inspection_id', $inspection_id)->first();

        if ($data->IsZeroDep == 0 && $data->IsZeroDep != '') {


            $MetalDepPer = getdeprecationpercentagebymonth($inspection_id, 'Metal');
            $GlassDepPer = getdeprecationpercentagebymonth($inspection_id, 'Glass');
            $RubberDepPer = getdeprecationpercentagebymonth($inspection_id, 'Rubber');
            $FibreDepPer = getdeprecationpercentagebymonth($inspection_id, 'Fibre');
        }

        return $dep = [
            'metal' => $MetalDepPer,
            'Glass' => $GlassDepPer,
            'Rubber' => $RubberDepPer,
            'Fibre' => $FibreDepPer,
            'Recondition' => 0,
        ];
    }
}


if (!function_exists('checkgst')) {
    function checkgst($inspection_id, $gst = 0)
    {

        $data = TaxSetting::where('inspection_id', $inspection_id)->first();
        $GSTEstimatedPartsPer = 0;
        $GSTAssessedPartsPer = 0;
        if ($data->GSTEstimatedPartsPer != 0) {
            if ($data->MutipleGSTonParts == 0) {
                $GSTEstimatedPartsPer = $data->GSTEstimatedPartsPer;
            } else {
                $GSTEstimatedPartsPer = $gst;
            }
        }

        if ($data->GSTAssessedPartsPer != 0) {
            if ($data->MutipleGSTonParts == 0) {
                $GSTAssessedPartsPer = $data->GSTAssessedPartsPer;
            } else {
                $GSTAssessedPartsPer = $gst;
            }
        }


        return $da = [
            'GSTEstimatedPartsPer' => $GSTEstimatedPartsPer,
            'GSTAssessedPartsPer' => $GSTAssessedPartsPer
        ];
    }
}

if (!function_exists('senderrormsg')) {
    function senderrormsg($errors)
    {
        $formattedErrors = [
            'message' => 'The given data was invalid.',
            'errors' => $errors->toArray(),
        ];

        // Return the custom error response
        return new JsonResponse($formattedErrors, 422);
    }
}


function formatdate($date, $n = "")
{
    $date = date_create($date);
    if ($n == '') {
        return date_format($date, "d/m/Y");
    } else {
        return date_format($date, "d/m/Y H:i");
    }
}

if (!function_exists('getadminrole')) {
    function getadminrole($id)
    {

        $admin = Admin_ms::where('id', $id)->first();
        if ($admin) {
            return $admin->role;
        } else {
            return "";
        }
    }
}

if (!function_exists('getBranchidofadmin')) {
    function getBranchidofadmin($id)
    {
        $branch = Admin_ms::where('id', $id)->first();
        if ($branch) {
            return $branch->admin_branch_id;
        } else {
            return '';
        }
    }
}


if (!function_exists('getBranchidofemplyee')) {
    function getBranchidofemplyee($id)
    {
        $branch = Employeems::where('id', $id)->first();
        if ($branch) {
            return $branch->admin_branch_id;
        } else {
            return '';
        }
    }
}

if (!function_exists('getassignedname')) {
    function getassignedname($name, $workshopEmpid, $surveyorEmpId)
    {
        if ($name === "contact_person") {
            $assignedName = Workshop_contact_person::where('id', $workshopEmpid)->value('name');
        } elseif ($name === "employee") {
            $assignedName = Employeems::where('id', $surveyorEmpId)->value('name');
        } else {
            $assignedName = Admin_ms::where('id', $surveyorEmpId)->value('name');
        }

        return $assignedName ?? null;
    }
}

if (!function_exists('decimalValue')) {
    function decimalValue($number)
    {
        $dictionary = array(
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
        );
        return ($number > 0) ? $dictionary[$number[0]].' '.$dictionary[$number[1]] : 'Zero';
    }
}

if (!function_exists('convertNumberToWords')) {
    function convertNumberToWords($number)
    {
        $number = ($number > 0) ? $number : 0;
        $decimalString = '';
        if (strpos($number, '.') !== false) {
            $decimalValue = explode('.', $number);
            if (isset($decimalValue[1])) {
                $decimalString = decimalValue($decimalValue[1]);
            }
        }
        $dictionary = array(
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety',
        );

        if (!is_numeric($number)) {
            return false;
        }

        $string = '';

        if ($number < 0) {
            $string = 'negative ';
            $number = abs($number);
        }

        if ($number < 21) {
            $string .= $dictionary[$number];
        } elseif ($number < 100) {
            $tens = ((int)($number / 10)) * 10;
            $units = $number % 10;
            $string .= $dictionary[$tens];
            if ($units) {
                $string .= '-' . $dictionary[$units];
            }
        } elseif ($number < 1000) {
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string .= $dictionary[$hundreds] . ' Hundred';
            if ($remainder) {
                $string .= ' and ' . convertNumberToWords($remainder);
            }
        } elseif ($number < 100000) {
            $thousands = (int)($number / 1000);
            $remainder = $number % 1000;
            $string .= convertNumberToWords($thousands) . ' Thousand';
            if ($remainder) {
                $string .= ' ' . convertNumberToWords($remainder);
            }
        } elseif ($number < 10000000) {
            $lakhs = (int)($number / 100000);
            $remainder = $number % 100000;
            $string .= convertNumberToWords($lakhs) . ' Lakh';
            if ($remainder) {
                $string .= ' ' . convertNumberToWords($remainder);
            }
        } else {
            $crores = (int)($number / 10000000);
            $remainder = $number % 10000000;
            $string .= convertNumberToWords($crores) . ' Crore';
            if ($remainder) {
                $string .= ' ' . convertNumberToWords($remainder);
            }
        }

        return !empty($decimalString) ? str_replace(' and ', ' ', $string).' and '.$decimalString : str_replace(' and ', ' ', $string);
    }
}


if (!function_exists('getAwsBaseUrl')) {
    function getAwsBaseUrl() {
        return 'moval-techkrate.s3.ap-south-1.amazonaws.com';
    }
}

if (!function_exists('getUniquePartsAndLabourGST')) {
    function getUniquePartsAndLabourGST($alldetails = []) {
        $uniqueGstValue = [];
        $uniqueBilledGstValue = [];
        $uniqueLabourGstValue = [];
        $uniqueBilledLabourGstValue = [];
        if(!empty($alldetails)) {
            foreach ($alldetails as $details) {
                $gst = !empty($details['gst']) ? $details['gst'] : 0;
//                if(!empty($details['category'])){
                    $uniqueGstValue[$gst] = $gst;
//                }
//                if($details['est_lab'] > 0 || $details['painting_lab'] > 0){
                    $uniqueLabourGstValue[$gst] = $gst;
//                }
//                if($details['billed_part_amt'] > 0){
                    $uniqueBilledGstValue[$gst] = $gst;
//                }
//                if($details['ass_lab'] > 0){
                    $uniqueBilledLabourGstValue[$gst] = $gst;
//                }
                if(!empty($details['quantities'])) {
                    foreach ($details['quantities'] as $quantities) {
                        $gst = !empty($quantities['gst']) ? $quantities['gst'] : 0;
//                        if(!empty($quantities['category'])){
                            $uniqueGstValue[$gst] = $gst;
//                        }
//                        if($quantities['est_lab'] > 0 || $quantities['painting_lab'] > 0){
                            $uniqueLabourGstValue[$gst] = $gst;
//                        }
//                        if($quantities['billed_part_amt'] > 0){
                            $uniqueBilledGstValue[$gst] = $gst;
//                        }
//                        if($quantities['ass_lab'] > 0){
                            $uniqueBilledLabourGstValue[$gst] = $gst;
//                        }
                    }
                }
            }
        }
        return ['uniqueGstValue' => $uniqueGstValue, 'uniqueLabourGstValue' => $uniqueLabourGstValue, 'uniqueBilledGstValue' => $uniqueBilledGstValue, 'uniqueBilledLabourGstValue' => $uniqueBilledLabourGstValue];
    }
}


if (!function_exists('getPartsGstCondition')) {
    function getPartsGstCondition($lossAssessment = [], $uniqueGstValue = []) {
        $MultipleEstPartsGst = [];
        $nonMultipleEstPartsGst = [];
        $MultipleAssPartsGst = [];
        $nonMultipleAssPartsGst = [];
        if(!empty($lossAssessment['MutipleGSTonParts']) && $lossAssessment['GSTEstimatedPartsPer'] > 0) {
            $MultipleEstPartsGst = array_combine(array_values($uniqueGstValue), $uniqueGstValue);
        } else {
            $nonGstEstPart = ($lossAssessment['GSTEstimatedPartsPer'] > 0) ? intval($lossAssessment['GSTEstimatedPartsPer']) : 0;
            $nonMultipleEstPartsGst[$nonGstEstPart] = $nonGstEstPart;
        }

        if(!empty($lossAssessment['MutipleGSTonParts']) && $lossAssessment['GSTAssessedPartsPer'] > 0) {
            $MultipleAssPartsGst = array_combine(array_values($uniqueGstValue), $uniqueGstValue);;
        } else {
            $nonGstAssPart = ($lossAssessment['GSTAssessedPartsPer'] > 0) ? intval($lossAssessment['GSTAssessedPartsPer']) : 0;
            $nonMultipleAssPartsGst[$nonGstAssPart] = $nonGstAssPart;
        }
        return ['MultipleEstPartsGst' => $MultipleEstPartsGst, 'nonMultipleEstPartsGst' => $nonMultipleEstPartsGst, 'MultipleAssPartsGst' => $MultipleAssPartsGst, 'nonMultipleAssPartsGst' => $nonMultipleAssPartsGst];
    }
}

if (!function_exists('getLabourGstCondition')) {
    function getLabourGstCondition($lossAssessment = [], $uniqueLabourGstValue = []) {
        $labourEstMultipleGst = [];
        $labourEstNoneMultipleGst = [];
        $labourAssMultipleGst = [];
        $labourAssNoneMultipleGst = [];
        if(!empty($lossAssessment['MultipleGSTonLab']) && $lossAssessment['GstonAssessedLab'] == 'Y' && $lossAssessment['GSTLabourPer'] > 0) {
            $labourAssMultipleGst = array_combine(array_values($uniqueLabourGstValue), $uniqueLabourGstValue);
        }else {
            $GSTLabourPer = ($lossAssessment['GSTLabourPer'] > 0 && $lossAssessment['GstonAssessedLab'] == 'Y') ? intval($lossAssessment['GSTLabourPer']) : 0;
            $labourAssNoneMultipleGst[$GSTLabourPer] = $GSTLabourPer;
        }

        if(!empty($lossAssessment['MultipleGSTonLab']) && $lossAssessment['GSTonEstimatedLab'] == 'Y' && $lossAssessment['GSTLabourPer'] > 0) {
            $labourEstMultipleGst = array_combine(array_values($uniqueLabourGstValue), $uniqueLabourGstValue);
        }else {
            $GSTLabourPer = ($lossAssessment['GSTLabourPer'] > 0 && $lossAssessment['GSTonEstimatedLab'] == 'Y') ? intval($lossAssessment['GSTLabourPer']) : 0;
            $labourEstNoneMultipleGst[$GSTLabourPer] = $GSTLabourPer;
        }
        return ['labourEstMultipleGst' => $labourEstMultipleGst, 'labourEstNoneMultipleGst' => $labourEstNoneMultipleGst, 'labourAssMultipleGst' => $labourAssMultipleGst, 'labourAssNoneMultipleGst' => $labourAssNoneMultipleGst];
    }
}

if (!function_exists('getBilledGstCondition')) {
    function getBilledGstCondition($lossAssessment = [], $uniqueBilledGstValue = []) {
        $MultipleGSTonBilled = [];
        $noneMultipleGSTonBilled = [];
        if(!empty($lossAssessment['MultipleGSTonBilled']) && $lossAssessment['GSTBilledPartPer'] > 0) {
            $MultipleGSTonBilled = array_combine(array_values($uniqueBilledGstValue), $uniqueBilledGstValue);;
        } else {
            $GSTBilledPartPer = ($lossAssessment['GSTBilledPartPer'] > 0) ? $lossAssessment['GSTBilledPartPer'] : 0;
            $noneMultipleGSTonBilled[$GSTBilledPartPer] = $GSTBilledPartPer;
        }
        return ['MultipleGSTonBilled' => $MultipleGSTonBilled, 'noneMultipleGSTonBilled' => $noneMultipleGSTonBilled];
    }
}

if (!function_exists('getBilledLabourGstCondition')) {
    function getBilledLabourGstCondition($lossAssessment = [], $uniqueBilledGstValue = []) {
        $MultipleGSTonBilledLabour = [];
        $noneMultipleGSTonBilledLabour = [];
        if(!empty($lossAssessment['MultipleGSTonLab']) && $lossAssessment['GstonAssessedLab'] == 'Y' && $lossAssessment['GSTLabourPer'] > 0) {
            $MultipleGSTonBilledLabour = array_combine(array_values($uniqueBilledGstValue), $uniqueBilledGstValue);
        }else {
            $GSTLabourPer = ($lossAssessment['GSTLabourPer'] > 0 && $lossAssessment['GstonAssessedLab'] == 'Y') ? intval($lossAssessment['GSTLabourPer']) : 0;
            $noneMultipleGSTonBilledLabour[$GSTLabourPer] = $GSTLabourPer;
        }
        return ['MultipleGSTonBilledLabour' => $MultipleGSTonBilledLabour, 'noneMultipleGSTonBilledLabour' => $noneMultipleGSTonBilledLabour];
    }
}