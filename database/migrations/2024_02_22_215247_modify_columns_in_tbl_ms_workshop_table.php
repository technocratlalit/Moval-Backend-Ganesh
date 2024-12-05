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
            $table->text('address')->nullable()->change();
            $table->text('gst_no')->nullable()->change();
            $table->text('contact_detail')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_workshop', function (Blueprint $table) {
            $table->text('address')->change();
            $table->text('gst_no')->change();
            $table->text('contact_detail')->change();
        });
    }
};
