<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsClientContactPersonTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_client_contact_person', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('parent_admin_id')->default(1); ;
			$table->unsignedBigInteger('client_branch_id')->nullable(); 
			$table->foreign('client_branch_id')->references('id')->on('tbl_ms_client_branches') ;
			$table->string('name',250);
			$table->string('designation',250);
			$table->string('email');
			$table->string('mobile_no',20);
			$table->string('landline_no',200);
			$table->enum('status',['0','1','2',''])->default('1');
			$table->unsignedBigInteger('created_by')->default(1);  
			$table->unsignedBigInteger('modified_by')->default(1);  
			$table->softDeletes(); 
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_client_contact_person');
    }
}