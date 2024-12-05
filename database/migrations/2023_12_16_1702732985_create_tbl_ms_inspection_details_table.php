<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsInspectionDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_inspection_details', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('client_branch_id')->nullable();
            $table->foreign('client_branch_id')->references('id')->on('tbl_ms_client_branches');
            // merger of tbl_ms_js start from here
            $table->unsignedBigInteger('Job_Route_To')->nullable();
			$table->unsignedBigInteger('workshop_id')->nullable();
			$table->foreign('workshop_id')->references('id')->on('tbl_ms_workshop');
            $table->unsignedBigInteger('sop_id')->nullable();
            $table->unsignedBigInteger('upload_type')->nullable();
            $table->unsignedBigInteger('jobjssignedto_surveyorEmpId')->nullable();
            $table->enum('job_status', ['created', 'pending', 'submitted', 'approved', 'rejected', 'finalized'])->default('created');
            $table->text('spot_survey')->nullable();
            $table->text('contact_no')->nullable();
            $table->text('vehicle_reg_no')->nullable();
           // $table->text('date_time_appoinment')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('tbl_ms_clients');
            $table->unsignedBigInteger('admin_branch_id')->nullable();
            $table->foreign('admin_branch_id')->references('id')->on('tbl_ms_admin_branch');
            $table->text('user_role')->nullable();
            $table->datetime('assigned_on')->nullable();
			$table->text('signature_image')->nullable();
            $table->text('inspection_reference_no')->nullable();
			$table->text('video_file')->nullable();
			$table->text('job_remark')->nullable();
            $table->unsignedBigInteger('jobassignedto_workshopEmpid')->nullable();
			$table->string('submitted_by_role', 100)->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable();
			$table->unsignedBigInteger('admin_id')->default(1);
            $table->unsignedBigInteger('approved_by')->nullable();
			$table->string('submitted_by')->nullable();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
             // merger of tbl_ms_js end from here
            $table->text('claim_type')->nullable();
            $table->text('claim_no')->nullable();
            $table->text('policy_no')->nullable();
            $table->text('policy_valid_from')->nullable();
            $table->text('policy_valid_to')->nullable();
            $table->text('client_name')->nullable();
            $table->text('branch_name')->nullable();
            $table->text('appointing_office_code')->nullable();
            $table->text('operating_office_code')->nullable();
            $table->text('insured_name')->nullable();
            $table->text('insured_address')->nullable();
            $table->text('insured_mobile_no')->nullable();
            $table->text('vehicle_reg_no')->nullable();
            $table->unsignedBigInteger('vehicle_type')->nullable();

            $table->string('date_of_registration',60)->nullable();
            $table->text('chassis_no')->nullable();
            $table->text('engine_no')->nullable();
            $table->text('vehicle_make')->nullable();
            $table->text('vehicle_model')->nullable();
            $table->text('odometer_reading')->nullable();
            $table->text('date_of_appointment')->nullable();
            $table->text('place_accident')->nullable();
            $table->text('place_survey')->nullable();

            $table->unsignedBigInteger('workshop_name')->nullable();
            $table->unsignedBigInteger('workshop_branch_id')->nullable();
            $table->foreign('workshop_branch_id')->references('id')->on('tbl_ms_workshop_branch');
            $table->text('contact_person')->nullable();
            $table->text('contact_person_mobile')->nullable();
            $table->text('accident_brief_description')->nullable();
            $table->text('damage_corroborates_with_cause_of_loss')->nullable();
            $table->text('accompanied_insurer_officer_details')->nullable();
            $table->text('major_physical_damages')->nullable();
            $table->text('suspected_Internal_damages')->nullable();
            $table->text('spot_Survey_details')->nullable();
            $table->text('preexisting_old_damages')->nullable();
            $table->text('preferred_mode_of_assessment')->nullable();
            $table->text('surveyor_APP_token_number')->nullable();
            $table->text('ILA_discussed_with')->nullable();
            $table->text('ILA_Submitted_on')->nullable();
            $table->text('Reference_No')->nullable();
            $table->text('Chassis_No_PV')->nullable();
            $table->text('Engine_No_PV')->nullable();
            $table->text('Survey_Date_time')->nullable();
            $table->text('Vehicular_document_observation')->nullable();
            $table->text('LossEstimate')->nullable();
            $table->text('ImposedClause')->nullable();
            $table->text('SalvageAmt')->nullable();
            $table->text('CompulsoryDeductable')->nullable();
            $table->text('NetLiabilityOnRepairBasis')->nullable();
            $table->text('InsurerLiability')->nullable();
            $table->text('CustomerLiability')->nullable();
            $table->double('LessCompulsoryDeductable')->default(0);
            $table->double('TowingCharges')->default(0);
            $table->double('loss_estimate')->nullable();
            $table->double('insurer_liability')->nullable();
            $table->unsignedBigInteger('update_by')->nullable();
            $table->date('updated_date')->nullable();
            $table->softDeletes();
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_inspection_details');
    }
}
