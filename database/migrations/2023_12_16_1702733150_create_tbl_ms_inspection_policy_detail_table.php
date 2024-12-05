<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsInspectionPolicyDetailTable extends Migration
{
	public function up()
	{
		Schema::create('tbl_ms_inspection_policy_detail', function (Blueprint $table) {

			$table->id();
			$table->unsignedBigInteger('inspection_id')->nullable(); 
			$table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');
			$table->text('inspection_reference_no');
			$table->text('report_type')->nullable();
			$table->text('reportGeneratedOn')->nullable();
			$table->text('claim_no')->nullable();
			$table->text('policy_no')->nullable();
			$table->text('policy_valid_from')->nullable();
			$table->text('policy_valid_to')->nullable();
			$table->text('sum_insured')->nullable();
			$table->text('policy_type')->nullable();
			$table->text('status_of_64vb')->nullable();
			$table->text('status_of_pre_insp')->nullable();
			$table->text('status_of_NCB')->nullable();
			$table->text('payment_mode')->nullable();
			$table->text('settlement_type')->nullable();

			$table->unsignedBigInteger('client_id', 20)->nullable();

			$table->unsignedBigInteger('client_branch_id', 20)->nullable();
			$table->string('appointing_office_code', 200)->nullable();
			$table->text('operating_office_code')->nullable();
			// $table->unsignedBigInteger('client_id')->nullable();
			// $table->foreign('client_id')->references('id')->on('tbl_ms_clients');
			// $table->unsignedBigInteger('client_branch_id')->nullable();
			// $table->foreign('client_branch_id')->references('id')->on('tbl_ms_client_branches');
			// $table->unsignedBigInteger('appointing_branch_id')->nullable();



			$table->text('operating_officer')->nullable();
			$table->text('insured_name')->nullable();
			$table->text('insured_address')->nullable();
			$table->text('insured_mobile_no')->nullable();
			$table->text('registured_owner')->nullable();
			$table->string('bank_name', 200)->nullable();
			$table->text('bank_address')->nullable();
			$table->text('account_no')->nullable();
			$table->text('ifsc_code')->nullable();
			$table->text('HPA')->nullable();
			$table->string('bank_id', 50)->nullable();
			$table->text('insurer_name')->nullable();
			
			$table->text('client_name')->nullable();
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}

	public function down()
	{
		Schema::dropIfExists('tbl_ms_inspection_policy_detail');
	}
}
