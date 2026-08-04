<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('blogs', 'is_published')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->boolean('is_published')->default(true);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('blogs', 'is_published')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropColumn('is_published');
            });
        }
    }
};
