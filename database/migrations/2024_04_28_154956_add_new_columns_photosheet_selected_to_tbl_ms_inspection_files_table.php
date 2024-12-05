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
        Schema::table('tbl_ms_inspection_files', function (Blueprint $table) {
            $table->tinyInteger('photosheet_selected')->default(0)->comment('0 - No,1 - Yes')->after('original_file_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_inspection_files', function (Blueprint $table) {
            $table->dropColumn('photosheet_selected');
        });
    }
};
