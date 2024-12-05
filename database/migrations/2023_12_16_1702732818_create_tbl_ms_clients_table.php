<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsClientsTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_clients', function (Blueprint $table) {

		$table->id();
		$table->string('client_name',250);
		$table->text('client_address');
		$table->string('contact_details',200);
		$table->enum('status',['0','1','2'])->default('1');
		$table->unsignedBigInteger('parent_admin_id')->default(1);  
		$table->unsignedBigInteger('created_by')->nullable(); 
		$table->unsignedBigInteger('modified_by')->nullable();  
		$table->unsignedBigInteger('admin_id'); 
        $table->foreign('admin_id')->references('id')->on('tbl_ms_admin');
		$table->unsignedBigInteger('admin_branch_id')->nullable(); 
        $table->foreign('admin_branch_id')->references('id')->on('tbl_ms_admin_branch') ;
		$table->softDeletes();
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
 

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_clients');
    }
}