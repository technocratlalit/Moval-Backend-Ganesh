<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsWorkshopTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_workshop', function (Blueprint $table) {

			$table->id();
			$table->text('workshop_name');
			$table->text('address');
			$table->text('gst_no');
			$table->text('contact_detail');
			$table->unsignedBigInteger('admin_id')->default(1); 
			$table->foreign('admin_id')->references('id')->on('tbl_ms_admin');	
			$table->unsignedBigInteger('admin_branch_id')->nullable(); 
			$table->foreign('admin_branch_id')->references('id')->on('tbl_ms_admin_branch') ;
			$table->unsignedBigInteger('super_admin_id')->default(1); 
			$table->foreign('super_admin_id')->references('id')->on('tbl_ms_super_admin');
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_workshop');
    }
}