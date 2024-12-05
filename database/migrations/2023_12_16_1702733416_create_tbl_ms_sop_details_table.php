<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsSopDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_sop_details', function (Blueprint $table) {

		$table->id();
        $table->unsignedBigInteger('sop_id'); 
        $table->foreign('sop_id')->references('id')->on('tbl_ms_admin_branch');
		$table->text('form_field_lable');
        $table->softDeletes();
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		$table->unsignedBigInteger('type')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_sop_details');
    }
}