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
        // Add the new columns first
        Schema::table('tbl_ms_assessmentsheet_settings', function (Blueprint $table) {
            $table->tinyInteger('display_bill_sr_no')->default(0)->comment('0 - No,1 - Yes')->after('description_in_sentancecase');
            $table->tinyInteger('display_gst_summary')->default(0)->comment('0 - No,1 - Yes')->after('display_bill_sr_no');
            $table->tinyInteger('display_gst_summary_part_category_wise')->default(0)->comment('0 - No,1 - Yes')->after('display_gst_summary');
        });
    
        // Then rename the column
        Schema::table('tbl_ms_assessmentsheet_settings', function (Blueprint $table) {
            $table->renameColumn('admin_id', 'inspection_id');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, revert the column renaming
        Schema::table('tbl_ms_assessmentsheet_settings', function (Blueprint $table) {
            $table->renameColumn('inspection_id', 'admin_id');
        });
    
        // Then, drop the new columns
        Schema::table('tbl_ms_assessmentsheet_settings', function (Blueprint $table) {
            $table->dropColumn('display_bill_sr_no');
            $table->dropColumn('display_gst_summary');
            $table->dropColumn('display_gst_summary_part_category_wise');
        });
    }
};
