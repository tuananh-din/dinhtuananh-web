<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about', function (Blueprint $table) {
            if (!Schema::hasColumn('about', 'facebook')) {
                $table->string('facebook', 255)->nullable()->after('address');
            }

            if (!Schema::hasColumn('about', 'instagram')) {
                $table->string('instagram', 255)->nullable();
            }

            if (!Schema::hasColumn('about', 'linkedin')) {
                $table->string('linkedin', 255)->nullable();
            }

            if (!Schema::hasColumn('about', 'x')) {
                $table->string('x', 255)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('about', function (Blueprint $table) {
            $columns = [];

            foreach (['facebook', 'instagram', 'linkedin', 'x'] as $column) {
                if (Schema::hasColumn('about', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
