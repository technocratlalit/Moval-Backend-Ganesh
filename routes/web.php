<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\MotorValuation\ReportGeneration;
use App\Http\Controllers\MotorSurvey\CommonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('ilapdf', [ReportGeneration::class, 'index']);
Route::get('viewpdf', [ReportGeneration::class, 'viewpdf']);
Route::get('VehicleRegnNo_ILA', [ReportGeneration::class, 'index']);
Route::get('VehicleRegnNo_WorkApprovalSheet', [ReportGeneration::class, 'viewpdf']);
Route::get('preview-reports', [ReportGeneration::class, 'previewReports']);
Route::get('fee-bill-reports', [ReportGeneration::class, 'feeBillReports']);

Route::get('reinspection-reports', [ReportGeneration::class, 'reinspectionReports']);
Route::get('photo-sheets-reports', [ReportGeneration::class, 'photoSheetReport']);
// Route::get('bill-check-reports', [ReportGeneration::class, 'billCheckReports']);
// Route::get('scrutiny-sheet-reports', [ReportGeneration::class, 'scrutinySheetReports']);

Route::get('/config/clear', function () {
//    Artisan::call('config:cache');
    Artisan::call('optimize:clear');
    return "cleared and cached";
});

Route::get('/', function () {
//    Artisan::call('cache:clear');
//    Artisan::call('optimize');
//    Artisan::call('route:cache');
//    Artisan::call('route:clear');
//    Artisan::call('view:clear');
//    Artisan::call('config:cache');
//
//     $details = [
//        'title' => 'Mail from ItSolutionStuff.com',
//        'body' => 'This is for testing email using smtp'
//    ];
    return view('welcome');
});


Route::get('/login', function () { echo 'login first'; })->name('login');

//For changing AssismentDetail param name
Route::get('change-assisment-details-name', [CommonController::class, 'changeDetailsKey'])->name('change-assisment-details-name');
Route::get('update-bank-data', [CommonController::class, 'updateBank'])->name('update-bank-data');