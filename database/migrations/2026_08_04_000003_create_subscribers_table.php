<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('subscribers')) {
            Schema::create('subscribers', function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->string('source')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('subscribers', function (Blueprint $table) {
            if (!Schema::hasColumn('subscribers', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('subscribers', 'source')) {
                $table->string('source')->nullable();
            }
            if (!Schema::hasColumn('subscribers', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('subscribers', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
