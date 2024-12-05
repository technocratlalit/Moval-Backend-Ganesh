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
        Schema::create('tbl_ms_assessmentsheet_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->tinyInteger('display_ai')->default(0)->comment('0 - No,1 - Yes');
            $table->tinyInteger('display_hsn')->default(0)->comment('0 - No,1 - Yes');
            $table->tinyInteger('copy_est_amt')->default(0)->comment('0 - No,1 - Yes');
            $table->tinyInteger('description_in_uppercase')->default(0)->comment('0 - No,1 - Yes');
            $table->tinyInteger('description_in_sentancecase')->default(0)->comment('0 - No,1 - Yes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_ms_assessmentsheet_settings');
    }
};
