<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblJobDetailTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_job_detail', function (Blueprint $table) {

		$table->id();
		// $table->bigInteger('job_id',20);
		$table->unsignedBigInteger('job_id')->nullable(); 
		$table->foreign('job_id')->references('id')->on('tbl_job');

		$table->string('vehicle_class',50)->nullable();
		$table->date('registration_date')->nullable();
		$table->string('type_of_body',50)->nullable();
		$table->string('manufactoring_year',5)->nullable();
		$table->string('maker',150)->nullable();
		$table->string('model',50)->nullable();
		$table->string('chassis_no',100)->nullable();
		$table->string('engine_no',100)->nullable();
		$table->enum('rc_status',['0','1'])->nullable();
		$table->string('seating_capacity',50)->nullable();
		$table->string('issuing_authority',250)->nullable();
		$table->string('fuel_type',50)->nullable();
		$table->string('color',20)->nullable();
		$table->string('odometer_reading',20)->nullable();
		$table->date('fitness_valid_upto')->nullable();
		$table->string('laden_weight',100)->nullable();
		$table->string('unladen_weight',100)->nullable();
		$table->decimal('requested_value',10,2)->nullable();
		$table->enum('engine_transmission',['0','1'])->default('0');
		$table->enum('electrical_gadgets',['0','1'])->default('0');
		$table->enum('right_side',['0','1'])->default('0');
		$table->enum('left_body',['0','1'])->default('0');
		$table->enum('front_body',['0','1'])->default('0');
		$table->enum('back_body',['0','1'])->default('0');
		$table->enum('load_body',['0','1'])->default('0');
		$table->enum('all_glass_condition',['0','1'])->default('0');
		$table->enum('cabin_condition',['0','1'])->default('0');
		$table->enum('head_lamp',['0','1'])->default('0');
		$table->enum('tyres_condition',['0','1'])->default('0');
		$table->enum('maintenance',['0','1'])->default('0');
		$table->text('other_damages')->nullable();
		$table->datetime('created_at');
		$table->datetime('updated_at');

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_job_detail');
    }
}