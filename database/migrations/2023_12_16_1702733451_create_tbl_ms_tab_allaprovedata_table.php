<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsTabAllaprovedataTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_tab_allaprovedata', function (Blueprint $table) {

			$table->id();
			$table->unsignedBigInteger('inspection_id')->nullable(); 
			$table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');
			$table->text('inspection_policy_detail')->nullable();
			$table->text('inspection_vehicle_detail')->nullable();
			$table->text('inspection_accident_detail')->nullable();
			$table->text('inspection_cause_detail')->nullable();
			$table->text('inspection_final_report_comment')->nullable();
			$table->text('inspection_attachments')->nullable();
			$table->text('inspection_feebill_report')->nullable();
			$table->text('report_dynamic_section')->nullable();
			$table->unsignedBigInteger('created_by')->nullable();  
			$table->unsignedBigInteger('modified_by')->nullable();  
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP')); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_tab_allaprovedata');
    }
}