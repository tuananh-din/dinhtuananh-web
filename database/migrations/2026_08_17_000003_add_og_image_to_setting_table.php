<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('setting', 'og_image')) {
            Schema::table('setting', function (Blueprint $table) {
                $table->string('og_image')->nullable()->after('logo');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('setting', 'og_image')) {
            Schema::table('setting', function (Blueprint $table) {
                $table->dropColumn('og_image');
            });
        }
    }
};
