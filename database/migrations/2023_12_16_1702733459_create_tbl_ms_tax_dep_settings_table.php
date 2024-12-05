<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblMsTaxDepSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_ms_tax_dep_settings', function (Blueprint $table) {

		$table->id();
		$table->unsignedBigInteger('inspection_id')->nullable(); 
		$table->foreign('inspection_id')->references('id')->on('tbl_ms_inspection_details');
		$table->unsignedBigInteger('IsZeroDep')->nullable();
		$table->text('DepBasedOn')->nullable();
		$table->unsignedBigInteger('MetalDepPer')->nullable();
		$table->unsignedBigInteger('RubberDepPer')->nullable();
		$table->unsignedBigInteger('GlassDepPer')->nullable();
		$table->unsignedBigInteger('FibreDepPer')->nullable();
		$table->text('GSTonEstimatedLab')->nullable();
		$table->text('GstonAssessedLab')->nullable();
		$table->unsignedBigInteger('GSTLabourPer')->default('0');
		$table->unsignedBigInteger('GSTEstimatedPartsPer')->default('0');
		$table->unsignedBigInteger('GSTAssessedPartsPer')->default('0');
		$table->unsignedBigInteger('IMT23DepPer')->default('0');
		$table->unsignedBigInteger('MutipleGSTonParts')->nullable();
		$table->unsignedBigInteger('MultipleGSTonLab')->nullable();
		$table->unsignedBigInteger('IGSTonPartsAndLab')->nullable();
		$table->softDeletes();
		$table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		$table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_ms_tax_dep_settings');
    }
}