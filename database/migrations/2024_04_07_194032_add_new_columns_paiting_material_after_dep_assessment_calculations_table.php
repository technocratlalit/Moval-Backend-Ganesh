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
            $table->decimal('PaitingMaterialAfterDep', 10, 2)->nullable();
            $table->decimal('PaintingMaterialDepAmt', 10, 2)->nullable()->after('PaitingMaterialAfterDep');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_assisment_calculations', function (Blueprint $table) {
            $table->dropColumn('PaitingMaterialAfterDep');
        });
    }
};
