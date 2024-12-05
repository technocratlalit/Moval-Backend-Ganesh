<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsWorkshopBranchTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_workshop_branch', function (Blueprint $table) {
			$table->id();
			$table->text('workshop_branch_name');
			$table->text('address');
			$table->text('contact_details');
			$table->text('manager_name');
			$table->text('manager_mobile_num');
			$table->text('manager_email');
			$table->text('gst_no')->nullable();
			$table->unsignedBigInteger('workshop_id'); 
			$table->foreign('workshop_id')->references('id')->on('tbl_ms_workshop');	
			$table->unsignedBigInteger('created_by')->nullable();  
			$table->unsignedBigInteger('modified_by')->nullable();  
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_workshop_branch');
    }
}