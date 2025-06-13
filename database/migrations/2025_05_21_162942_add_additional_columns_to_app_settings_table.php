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
        if (Schema::hasTable('app_settings')) {
            Schema::table('app_settings', function (Blueprint $table) {
                $table->integer('content_per_page')->default(10);
                $table->integer('video_quality')->default(480);
                $table->text('download_path')->default(storage_path('app\public\content'));
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('app_settings')) {
            Schema::table('app_settings', function (Blueprint $table) {
                $table->dropColumn('content_per_page');
                $table->dropColumn('video_quality');
                $table->dropColumn('download_path');
            });
        }
    }
};
