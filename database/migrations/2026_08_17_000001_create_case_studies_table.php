<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('case_studies')) {
            return;
        }

        Schema::create('case_studies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('summary', 500)->nullable();
            $table->string('client')->nullable();
            $table->string('industry')->nullable();
            $table->string('platforms')->nullable();
            $table->string('duration')->nullable();
            $table->string('role')->nullable();
            $table->string('kpi_1_label')->nullable();
            $table->string('kpi_1_value')->nullable();
            $table->string('kpi_2_label')->nullable();
            $table->string('kpi_2_value')->nullable();
            $table->string('kpi_3_label')->nullable();
            $table->string('kpi_3_value')->nullable();
            $table->string('kpi_4_label')->nullable();
            $table->string('kpi_4_value')->nullable();
            $table->longText('content')->nullable();
            $table->string('title_seo')->nullable();
            $table->text('desc_seo')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_studies');
    }
};
