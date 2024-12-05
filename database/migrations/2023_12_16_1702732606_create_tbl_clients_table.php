<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblClientsTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_clients', function (Blueprint $table) {

		$table->id();
		$table->string('name',250);
		$table->string('email',50);
		$table->text('address');
		$table->string('contact_person_name',200);
		$table->string('mobile_no',200);
		$table->string('registered_mobile_no',20);
		$table->enum('mode_of_payment',['Prepaid','Postpaid'])->default('Prepaid');
		$table->decimal('amount_per_job',10,2)->default('0.00');
		$table->enum('status',['0','1','2'])->default('1');
		
		
		$table->unsignedBigInteger('parent_admin_id')->default(1); 
		$table->foreign('parent_admin_id')->references('id')->on('tbl_admin');

		$table->unsignedBigInteger('created_by')->default(1); 
		$table->foreign('created_by')->references('id')->on('tbl_admin');

		$table->unsignedBigInteger('modified_by')->default(1); 
		$table->foreign('modified_by')->references('id')->on('tbl_admin');

		// $table->integer('parent_admin_id',11)->default('1');


		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
 
		 

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_clients');
    }
}