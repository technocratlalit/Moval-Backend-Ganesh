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
        Schema::table('tbl_ms_inspection_details', function (Blueprint $table) {
            $table->unsignedBigInteger('int_reference_no')->after('inspection_reference_no')->nullable();  
            $table->tinyInteger('is_generated')->default(0)->comment('0 - No,1 - Yes')->after('int_reference_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_inspection_details', function (Blueprint $table) {
            $table->dropColumn('is_generated');
            $table->dropColumn('int_reference_no');
        });
    }
};
