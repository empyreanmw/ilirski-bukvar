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
        if (Schema::hasTable('contents')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->renameColumn('series_id', 'parent_id');
                $table->string('parent_type')->nullable();
                $table->string('local_url')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('contents')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->renameColumn('parent_id', 'series_id');
                $table->dropColumn('parent_type');
                $table->dropColumn('local_url');
            });
        }
    }
};
