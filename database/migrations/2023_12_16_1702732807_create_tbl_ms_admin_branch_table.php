<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreateTblMsAdminBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tbl_ms_admin_branch', function (Blueprint $table) {
            $table->id();
            $table->text('admin_branch_name');
            $table->text('contact_person');
            $table->text('mobile_no');
            $table->text('email'); 	
            $table->unsignedBigInteger('admin_id'); 
            $table->foreign('admin_id')->references('id')->on('tbl_ms_admin');	
            $table->text('address')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('modified_by')->nullable();
            $table->softDeletes();
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		    $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ms_admin_branch');
    }
}
