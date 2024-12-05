<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsJobTable extends Migration
{
	public function up()
	{
		Schema::create('tbl_ms_job', function (Blueprint $table) {

			$table->id();
			$table->unsignedBigInteger('claim_type')->nullable();
			$table->unsignedBigInteger('Job_Route_To')->nullable();
			$table->text('spot_survey')->nullable();
			$table->text('vehicle_reg_no')->nullable();
			$table->text('insured_name')->nullable();
			$table->text('place_survey')->nullable();
			$table->unsignedBigInteger('workshop_id')->nullable();
			$table->unsignedBigInteger('workshop_branch_id')->nullable(); 
			$table->foreign('workshop_branch_id')->references('id')->on('tbl_ms_workshop_branch');
			$table->string('contact_person', 200)->nullable();
			$table->text('contact_no')->nullable();
			$table->unsignedBigInteger('client_id')->default(1);
			$table->foreign('client_id')->references('id')->on('tbl_ms_clients');
			$table->unsignedBigInteger('admin_branch_id')->default(1);
			$table->foreign('admin_branch_id')->references('id')->on('tbl_ms_admin_branch');
			$table->text('date_time_appoinment')->nullable();
			$table->unsignedBigInteger('sop_id')->nullable(); 
			$table->text('branch_name')->nullable();
			$table->unsignedBigInteger('upload_type')->nullable();  
			$table->unsignedBigInteger('jobjssignedto_surveyorEmpId')->nullable();
			$table->enum('job_status', ['created', 'pending', 'submitted', 'approved', 'rejected', 'finalized'])->default('created');
			$table->unsignedBigInteger('jobassignedto_workshopEmpid')->nullable();
			$table->text('user_role')->nullable();
			$table->datetime('assigned_on')->nullable();
			$table->text('signature_image')->nullable();
			$table->text('inspection_reference_no')->nullable();
			$table->text('video_file')->nullable();
			$table->text('job_remark')->nullable(); 
			$table->string('submitted_by_role', 100)->nullable();
			$table->unsignedBigInteger('assigned_by')->nullable(); 
			$table->unsignedBigInteger('admin_id')->default(1);
			$table->foreign('admin_id')->references('id')->on('tbl_ms_admin');
			$table->unsignedBigInteger('approved_by')->nullable(); 
			$table->string('submitted_by')->nullable(); 
			$table->unsignedBigInteger('created_by')->nullable(); 
			$table->unsignedBigInteger('updated_by')->nullable(); 
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		    $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}

	public function down()
	{
		Schema::dropIfExists('tbl_ms_job');
	}
}
