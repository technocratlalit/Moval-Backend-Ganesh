<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsDynamicSectionsTable extends Migration
{
	public function up()
	{
		Schema::create('tbl_ms_dynamic_sections', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('inspection_id')->nullable(); 
			$table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');
			$table->unsignedBigInteger('SrNo')->nullable();
			$table->text('Heading');
			$table->text('Details');
			$table->text('section_type')->nullable();
			$table->text('add_Report')->nullable();
			$table->unsignedBigInteger('created_by')->nullable(); 
			$table->unsignedBigInteger('modified_by')->nullable(); 
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

		});
	}

	public function down()
	{
		Schema::dropIfExists('tbl_ms_dynamic_sections');
	}
}
