<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('about', 'substack')) {
            Schema::table('about', function (Blueprint $table) {
                $table->string('substack', 255)->nullable()->after('x');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('about', 'substack')) {
            Schema::table('about', function (Blueprint $table) {
                $table->dropColumn('substack');
            });
        }
    }
};
