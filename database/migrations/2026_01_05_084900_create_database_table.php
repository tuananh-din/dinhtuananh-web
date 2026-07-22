<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->string('favicon')->nullable();
            $table->string('logo')->nullable();
            $table->text('code_header')->nullable();
            $table->text('code_footer')->nullable();
            $table->text('slogan')->nullable();
            $table->text('note')->nullable();

            $table->string('title_seo')->nullable();
            $table->text('desc_seo')->nullable();
            $table->text('key_seo')->nullable();
            $table->timestamps();
        });
        Schema::create('about', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->text('about_me')->nullable();
            $table->string('tel')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();

            $table->string('title_seo')->nullable();
            $table->text('desc_seo')->nullable();
            $table->text('key_seo')->nullable();
            $table->timestamps();
        });

        Schema::create('socials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->string('logo')->nullable();
            $table->text('slogan')->nullable();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();

            $table->timestamps();
        });
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();

            $table->string('title_seo')->nullable();
            $table->text('desc_seo')->nullable();
            $table->text('key_seo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('services');
        Schema::dropIfExists('socials');
        Schema::dropIfExists('about');
        Schema::dropIfExists('setting');
    }
};
