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
        if (Schema::hasTable('series') && !Schema::hasColumn('series', 'is_favorite')) {
            Schema::table('series', function (Blueprint $table) {
                $table->boolean('is_favorite')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('series') && Schema::hasColumn('series', 'is_favorite')) {
            Schema::table('series', function (Blueprint $table) {
                $table->removeColumn('is_favorite');
            });
        }
    }
};
