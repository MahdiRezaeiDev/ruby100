<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->default('+61401724002');
            $table->string('phone_display')->default('+61 401 724 002');
            $table->string('address')->nullable();
            $table->string('area')->nullable();
            $table->string('hero_kicker')->nullable();
            $table->string('hero_title')->default('RUBY100');
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('about_title')->nullable();
            $table->longText('about_body')->nullable();
            $table->string('about_image')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('notify_email')->nullable();
            $table->longText('google_reviews_embed')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
