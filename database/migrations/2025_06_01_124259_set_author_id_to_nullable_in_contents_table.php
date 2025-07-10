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
        if (Schema::hasTable('contents') && Schema::hasColumn('contents', 'author_id')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->integer('author_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('contents') && Schema::hasColumn('contents', 'author_id')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->dropColumn('author_id');
            });
        }
    }
};
