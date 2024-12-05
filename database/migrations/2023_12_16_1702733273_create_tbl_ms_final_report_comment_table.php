<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsFinalReportCommentTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_final_report_comment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inspection_id')->nullable(); 
			$table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');
            $table->text('comment')->nullable();
            $table->softDeletes();
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_final_report_comment');
    }
}