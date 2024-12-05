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
        Schema::table('tbl_ms_workshop_contact_person', function (Blueprint $table) {
            $table->datetime('last_login_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('status',['0','1','2'])->default('1');
            $table->string('otp_sent',10)->nullable();
			$table->datetime('expiry_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_ms_workshop_contact_person', function (Blueprint $table) {
            $table->dropColumn('last_login_date');
            $table->dropColumn('status');
            $table->dropColumn('otp_sent');
            $table->dropColumn('expiry_time');
        });
    }
};
