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
        if(Schema::hasTable('queue_job_requests')) {
            Schema::create('queue_job_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('job_reference_id');
                $table->string('job_reference');
                $table->string('job_id');
                $table->string('job_type');
                $table->string('job_status');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_job_requests');
    }
};
