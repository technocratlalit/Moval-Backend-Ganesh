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
            $table->decimal('total_labourAmtWithGst', 10, 2)->nullable()->after('totallabourest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_assisment_calculations', function (Blueprint $table) {
            $table->dropColumn('total_labourAmtWithGst');
        });
    }
};
