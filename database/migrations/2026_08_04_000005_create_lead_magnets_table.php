<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { if (!Schema::hasTable('lead_magnets')) Schema::create('lead_magnets', function (Blueprint $table) { $table->increments('id'); $table->string('name'); $table->text('description')->nullable(); $table->string('file_path'); $table->string('cover_image')->nullable(); $table->boolean('is_active')->default(1); $table->timestamps(); }); }
    public function down(): void { Schema::dropIfExists('lead_magnets'); }
};
