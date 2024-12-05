<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblBranchContactPersonTable extends Migration
{
	public function up()
	{
		Schema::create('tbl_branch_contact_person', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('branch_id')->nullable(); // foreign key of tbl_client_branches  
			$table->foreign('branch_id')->references('id')->on('tbl_client_branches');

			$table->unsignedBigInteger('parent_admin_id')->nullable();
			$table->foreign('parent_admin_id')->references('id')->on('tbl_admin');

			// $table->bigInteger('branch_id',20);
			$table->string('name', 250);
			$table->string('designation', 250);
			$table->text('email');
			$table->string('mobile_no', 20);
			$table->string('landline_no', 200);
			$table->enum('status', ['0', '1', '2', ''])->default('1');

			$table->unsignedBigInteger('created_by')->default(1);
			$table->foreign('created_by')->references('id')->on('tbl_admin');

			$table->unsignedBigInteger('modified_by')->default(1);
			$table->foreign('modified_by')->references('id')->on('tbl_admin');
			
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
 
		});
	}

	public function down()
	{
		Schema::dropIfExists('tbl_branch_contact_person');
	}
}
