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
        Schema::table('tbl_ms_inspection_accident_detail', function (Blueprint $table) {
            $table->string('workshop_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_inspection_accident_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('workshop_name')->nullable()->change();
        });
    }
};
