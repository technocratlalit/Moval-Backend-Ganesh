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
        Schema::create('tbl_ms_pod_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_branch_id')->nullable(); 
            $table->foreign('admin_branch_id')->references('id')->on('tbl_ms_admin_branch');
            $table->text('particular_of_damage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_ms_pod_templates');
    }
};
