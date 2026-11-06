<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Taula pibota: bi singularrak, alfabetikoki (jarduera < user)
        Schema::create('jarduera_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jarduera_id')->constrained('jarduerak')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('ordainduta')->default(false);
            $table->timestamps();

            $table->unique(['jarduera_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jarduera_user');
    }
};
