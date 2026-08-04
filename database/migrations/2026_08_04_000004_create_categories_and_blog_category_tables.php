<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('blog_category')) {
            Schema::create('blog_category', function (Blueprint $table) {
                $table->unsignedInteger('blog_id');
                $table->unsignedInteger('category_id');
                $table->unique(['blog_id', 'category_id']);
                $table->foreign('blog_id')->references('id')->on('blogs')->cascadeOnDelete();
                $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_category');
        Schema::dropIfExists('categories');
    }
};
