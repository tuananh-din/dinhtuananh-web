<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('blogs', 'deleted_at')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('blogs', 'deleted_at')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }
    }
};
