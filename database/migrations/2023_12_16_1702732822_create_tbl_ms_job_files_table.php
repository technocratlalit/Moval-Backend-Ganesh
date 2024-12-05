<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsJobFilesTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_job_files', function (Blueprint $table) {
			$table->id(); 
			$table->unsignedBigInteger('job_id')->nullable(); 
			$table->foreign('job_id')->references('id')->on('tbl_ms_job');
			$table->unsignedBigInteger('sop_id')->nullable(); 
			$table->string('sop_label')->nullable();
			$table->text('original_file_name')->nullable();
			$table->text('edited_file_name')->nullable();
			$table->datetime('original_image_uploaded_date')->nullable();
			$table->datetime('edited_image_uploaded_date')->nullable();
			$table->datetime('original_image_edited_date')->nullable();
			$table->datetime('edited_image_edited_date')->nullable();
			$table->string('uploaded_by')->nullable();  
			$table->unsignedBigInteger('edited_by')->nullable();  
			$table->unsignedBigInteger('file_type')->nullable()->comment('1 vehicle image , 2 vehicle documet , 3 custom vehicle image , 4 custom vehicle document'); 
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_job_files');
    }
}