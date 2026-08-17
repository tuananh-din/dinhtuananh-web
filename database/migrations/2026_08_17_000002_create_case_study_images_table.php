<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('case_study_images')) {
            return;
        }

        Schema::create('case_study_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('case_study_id');
            $table->string('image');
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('case_study_id')
                ->references('id')
                ->on('case_studies')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_study_images');
    }
};
