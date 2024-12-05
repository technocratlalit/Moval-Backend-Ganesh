<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsWorkshopContactPersonTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_workshop_contact_person', function (Blueprint $table) {

			$table->id();
			$table->string('name');
			$table->text('mobile_no');
			$table->text('email'); 
			$table->text('otp');
			$table->text('username');
			$table->string('is_set_password',10)->default('no');
			$table->unsignedBigInteger('workshop_branch_id'); 
			$table->foreign('workshop_branch_id')->references('id')->on('tbl_ms_workshop_branch');	 
			$table->unsignedBigInteger('created_by')->nullable();  
			$table->softDeletes();
			$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_workshop_contact_person');
    }
}