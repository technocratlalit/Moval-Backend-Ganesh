<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblClientBranchesTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_client_branches', function (Blueprint $table) {

		$table->id();
		// $table->bigInteger('client_id',20);
		$table->unsignedBigInteger('client_id'); 
		$table->foreign('client_id')->references('id')->on('tbl_clients');
		

		$table->unsignedBigInteger('parent_admin_id')->default(1); 
		$table->foreign('parent_admin_id')->references('id')->on('tbl_admin');

		// $table->integer('parent_admin_id',11)->default('1');

		$table->string('name',500);
		$table->string('email',250)->nullable();
		$table->string('manager_email',250)->nullable();
		$table->text('address')->nullable();
		$table->string('contact_person_name',250)->nullable();
		$table->string('mobile_no',200)->nullable();
		$table->string('registered_mobile_no',20)->nullable();
		$table->enum('mode_of_payment',['Prepaid','Postpaid'])->default('Prepaid');
		$table->decimal('amount_per_job',10,2)->default('0.00');
		$table->enum('status',['0','1','2'])->default(1);
		
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));


        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_client_branches');
    }
}