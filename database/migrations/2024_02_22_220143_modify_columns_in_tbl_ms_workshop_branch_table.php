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
        Schema::table('tbl_ms_workshop_branch', function (Blueprint $table) {
            $table->text('contact_details')->nullable()->change();
            $table->text('manager_name')->nullable()->change();
            $table->text('manager_mobile_num')->nullable()->change();
            $table->text('manager_email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_workshop_branch', function (Blueprint $table) {
            $table->text('contact_details')->change();
            $table->text('manager_name')->change();
            $table->text('manager_mobile_num')->change();
            $table->text('manager_email')->change();
        });
    }
};
