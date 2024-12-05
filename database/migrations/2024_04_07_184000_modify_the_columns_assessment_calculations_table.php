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
        Schema::table('tbl_ms_assisment_calculations', function (Blueprint $table) {
            // $table->renameColumn('totalmateriallab', 'totalEstAmt');
            $table->renameColumn('PaintingMaterialDepAmt', 'totalMetalAmt');
            $table->renameColumn('PaitingMaterialAfterDep', 'totalRubberAmt');
            $table->renameColumn('LabourAmt', 'totalGlassAmt');
            $table->renameColumn('GSTamount', 'totalFibreAmt');
            $table->renameColumn('LabourAmtIMT', 'totalReconditionAmt');
            $table->renameColumn('PaintingMaterialDepAmtIMT', 'totalMetalIMTAmt');
            $table->renameColumn('PaitingMaterialAfterDepIMT', 'totalRubberIMTAmt');
            $table->renameColumn('totalmateriallabIMT', 'DepAmtMetal');
            $table->renameColumn('GSTamountIMT', 'DepAmtRubber');
            $table->renameColumn('PaintingIMTDepAmount', 'DepAmtGlass');
            $table->renameColumn('AIreconditionpart', 'DepAmtFibre');
            $table->renameColumn('AInetlabour', 'DepAmtIMTMetal');
            $table->decimal('DepAmtIMTRubber', 10, 2)->nullable()->after('AInetlabour');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_assisment_calculations', function (Blueprint $table) {
            $table->renameColumn('totalMetalAmt', 'PaintingMaterialDepAmt');
            $table->renameColumn('totalRubberAmt', 'PaitingMaterialAfterDep');
            $table->renameColumn('totalGlassAmt', 'LabourAmt');
            $table->renameColumn('totalFibreAmt', 'GSTamount');
            $table->renameColumn('totalReconditionAmt', 'LabourAmtIMT');
            $table->renameColumn('totalMetalIMTAmt', 'PaintingMaterialDepAmtIMT');
            $table->renameColumn('totalRubberIMTAmt', 'PaitingMaterialAfterDepIMT');
            $table->renameColumn('DepAmtMetal', 'totalmateriallabIMT');
            $table->renameColumn('DepAmtRubber', 'GSTamountIMT');
            $table->renameColumn('DepAmtGlass', 'PaintingIMTDepAmount');
            $table->renameColumn('DepAmtFibre', 'AIreconditionpart');
            $table->renameColumn('DepAmtIMTMetal', 'AInetlabour');
            $table->dropColumn('DepAmtIMTRubber');
            $table->renameColumn('totalEstLabourAmt', 'paintinglabest');
            $table->renameColumn('totalAssLabourAmt', 'IMTPaintingLabEst');
        });
    }
    
};
