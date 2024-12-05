<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsBankDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_bank_details', function (Blueprint $table) {

		$table->id();
		$table->text('bank_code')->nullable();
		$table->text('bank_name')->nullable();
		$table->text('branch_address')->nullable();
		$table->text('account_number')->nullable();
		$table->text('account_type')->nullable();
		$table->text('ifsc')->nullable();
		$table->text('micr')->nullable();

		$table->unsignedBigInteger('created_by')->nullable();  


//		$table->unsignedBigInteger('admin_id');
//        $table->foreign('admin_id')->references('id')->on('tbl_ms_admin');
//
//		$table->unsignedBigInteger('admin_branch_id')->nullable();
//        $table->foreign('admin_branch_id')->references('id')->on('tbl_ms_admin_branch') ;
//
//		$table->unsignedBigInteger('super_admin_id')->default(1);
//        $table->foreign('super_admin_id')->references('id')->on('tbl_ms_super_admin');

		$table->softDeletes();
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
 
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_bank_details');
    }
}