<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsAssessmentDetailListTable extends Migration
{
	public function up()
	{
		Schema::create('tbl_ms_assessment_detail_list', function (Blueprint $table) {

			$table->id();
			$table->unsignedBigInteger('inspection_id')->nullable();
			    $table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');

			//$table->unsignedBigInteger('job_id')->nullable();
			// $table->foreign('job_id')->references('id')->on('tbl_ms_inspection_details')->onDelete('SET NULL');
			$table->unsignedBigInteger('assisment_id');
			$table->foreign('assisment_id')->references('id')->on('tbl_ms_assessment_details')->onDelete('SET NULL');
			$table->text('description')->nullable();
			$table->unsignedBigInteger('gst')->nullable();
			$table->unsignedBigInteger('qe')->nullable();
			$table->unsignedBigInteger('qa')->nullable();
			$table->string('imt_23', 100)->nullable();
			$table->text('hsn_code')->nullable();
			$table->string('category')->nullable();
			$table->text('labour_type')->nullable();
			$table->text('sac')->nullable();
			$table->text('remarks')->nullable();
			$table->text('e_sr_no')->nullable();
			$table->text('b_sr_no')->nullable();
			$table->double('est_rate')->nullable();
			$table->double('ass_rate')->nullable();
			$table->double('ai_part_amt')->nullable();
			$table->double('est_amt')->nullable();
			$table->double('ass_amt')->nullable();
			$table->double('est_lab')->nullable();
			$table->double('ass_lab')->nullable();
			$table->double('painting_lab')->nullable();
			$table->double('billed_part_amt')->nullable();
			$table->double('billed_lab_amt')->nullable();
			$table->double('billed_labour_amt')->nullable();
			$table->double('payable_amt')->nullable();
			$table->double('lab_ai_amt')->nullable();
			$table->double('billed_paint_amt')->nullable();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}

	public function down()
	{
		Schema::dropIfExists('tbl_ms_assessment_detail_list');
	}
}
