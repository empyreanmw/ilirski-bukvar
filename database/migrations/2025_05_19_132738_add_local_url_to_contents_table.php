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
        if (Schema::hasTable('contents') && !Schema::hasColumn('contents', 'local_url')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->string('local_url')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('contents') && Schema::hasColumn('contents', 'local_url')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->dropColumn('local_url');
            });
        }
    }
};
