<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsClientBranchesTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_client_branches', function (Blueprint $table) {

		$table->id();
		$table->unsignedBigInteger('client_id'); 
        $table->foreign('client_id')->references('id')->on('tbl_ms_clients');
		$table->unsignedBigInteger('parent_admin_id')->default(1);  
		$table->string('office_code');
		$table->string('office_name',500);
		$table->text('office_address');
		$table->text('gst_no')->nullable();
		$table->string('contact_detail',250)->nullable();
		$table->string('manager_name')->nullable();
		$table->string('manager_email')->nullable();
		$table->string('manager_mobile_no',200)->nullable();
		$table->string('within_state',20)->nullable();
		$table->enum('mode_of_payment',['Prepaid','Postpaid'])->default('Prepaid');
		$table->decimal('amount_per_job',10,2)->default('0.00');
		$table->enum('status',['0','1','2'])->default('1');
		$table->softDeletes();
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));


        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_client_branches');
    }
}