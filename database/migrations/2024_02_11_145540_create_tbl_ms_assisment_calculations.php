<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_ms_assisment_calculations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inspection_id');
            $table->string('vehicle_reg_no')->nullable();
            $table->string('Insurerd_name')->nullable();
            $table->decimal('totalestonlypart', 10, 2)->nullable();
            $table->decimal('totalreconditionestonly', 10, 2)->nullable();
            $table->decimal('totalendoresmentestonly', 10, 2)->nullable();
            $table->decimal('partMetalAssamount', 10, 2)->nullable();
            $table->decimal('partRubberAssamount', 10, 2)->nullable();
            $table->decimal('partGlassAssamount', 10, 2)->nullable();
            $table->decimal('partFibreAssamount', 10, 2)->nullable();
            $table->decimal('totalendoresmentAss', 10, 2)->nullable();
            $table->decimal('totalassparts', 10, 2)->nullable();
            $table->decimal('totalestparts', 10, 2)->nullable();
            $table->decimal('totalreconditionAss', 10, 2)->nullable();
            $table->decimal('totalreconditionEst', 10, 2)->nullable();
            $table->decimal('totalAssWithReconditon', 10, 2)->nullable();
            $table->decimal('totalEstWithReconditon', 10, 2)->nullable();
            $table->decimal('totallabourass', 10, 2)->nullable();
            $table->decimal('totallabourest', 10, 2)->nullable();
            $table->decimal('paintinglabass', 10, 2)->nullable();
            $table->decimal('paintinglabest', 10, 2)->nullable();
            $table->decimal('IMTPaintingLabAss', 10, 2)->nullable();
            $table->decimal('IMTPaintingLabEst', 10, 2)->nullable();
            $table->decimal('netlabourAss', 10, 2)->nullable();
            $table->decimal('netlabourEst', 10, 2)->nullable();
            $table->decimal('totalass', 10, 2)->nullable();
            $table->decimal('totalest', 10, 2)->nullable();
            $table->decimal('ImposedClause', 10, 2)->nullable();
            $table->decimal('CompulsoryDeductable', 10, 2)->nullable();
            $table->decimal('SalvageAmt', 10, 2)->nullable();
            $table->decimal('CustomerLiability', 10, 2)->nullable();
            $table->decimal('TowingCharges', 10, 2)->nullable();
            $table->decimal('netbody', 10, 2)->nullable();
            $table->decimal('alltotalass', 10, 2)->nullable();
            $table->decimal('totalmateriallab', 10, 2)->nullable();
            $table->decimal('PaintingMaterialDepAmt', 10, 2)->nullable();
            $table->decimal('PaitingMaterialAfterDep', 10, 2)->nullable();
            $table->decimal('LabourAmt', 10, 2)->nullable();
            $table->decimal('GSTamount', 10, 2)->nullable();
            $table->decimal('LabourAmtIMT', 10, 2)->nullable();
            $table->decimal('PaintingMaterialDepAmtIMT', 10, 2)->nullable();
            $table->decimal('PaitingMaterialAfterDepIMT', 10, 2)->nullable();
            $table->decimal('totalmateriallabIMT', 10, 2)->nullable();
            $table->decimal('GSTamountIMT', 10, 2)->nullable();
            $table->decimal('PaintingIMTDepAmount', 10, 2)->nullable();
            $table->decimal('insurer_liability', 10, 2)->nullable();
            $table->decimal('AIreconditionpart', 10, 2)->nullable();
            $table->decimal('AInetlabour', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_ms_assisment_calculations');
    }
};
