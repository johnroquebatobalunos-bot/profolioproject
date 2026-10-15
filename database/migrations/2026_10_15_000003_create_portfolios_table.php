<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug')->unique();
            $table->char('management_key_hash', 64)->unique();
            $table->string('photo_url')->nullable();
            $table->string('email');
            $table->string('phone', 40)->nullable();
            $table->string('address')->nullable();
            $table->text('about')->nullable();
            $table->json('education')->nullable();
            $table->json('skills')->nullable();
            $table->json('projects')->nullable();
            $table->json('experience')->nullable();
            $table->json('links')->nullable();
            $table->string('template', 16)->default('simple');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};