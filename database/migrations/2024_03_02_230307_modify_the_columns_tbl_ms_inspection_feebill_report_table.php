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
        Schema::table('tbl_ms_inspection_feebill_report', function (Blueprint $table) {
            $table->text('issued_to')->default(3)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_inspection_feebill_report', function (Blueprint $table) {
            $table->text('issued_to')->change();
        });
    }
};
