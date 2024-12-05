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
        Schema::table('tbl_ms_workshop', function (Blueprint $table) {
            $table->tinyInteger('is_local_workshop')->default(0)->comment('0 - No,1 - Yes, 2= Authorize')->after('admin_branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_workshop', function (Blueprint $table) {
            $table->dropColumn('is_local_workshop');
        });
    }
};
