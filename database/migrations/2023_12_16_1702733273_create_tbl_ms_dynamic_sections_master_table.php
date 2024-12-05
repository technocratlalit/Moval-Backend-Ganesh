<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsDynamicSectionsMasterTable extends Migration
{
	public function up()
	{
		Schema::create('tbl_ms_dynamic_sections_master', function (Blueprint $table) {

			$table->id();
			$table->unsignedBigInteger('admin_id');
			$table->foreign('admin_id')->references('id')->on('tbl_ms_admin');
			$table->unsignedBigInteger('SrNo')->nullable();
			$table->text('Heading');
			$table->text('Details');
			$table->unsignedBigInteger('admin_branch_id')->nullable();
			// $table->foreign('admin_branch_id')->references('id')->on('tbl_ms_admin_branch');
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}

	public function down()
	{
		Schema::dropIfExists('tbl_ms_dynamic_sections_master');
	}
}
