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
        if (Schema::hasTable('contents') && !Schema::hasColumn('contents', 'size')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->float('size')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('contents') && Schema::hasColumn('contents', 'size')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->dropColumn('size');
            });
        }
    }
};
