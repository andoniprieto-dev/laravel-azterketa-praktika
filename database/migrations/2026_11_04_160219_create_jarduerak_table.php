<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jarduerak', function (Blueprint $table) {
            $table->id();
            $table->string('izenburua');
            $table->text('deskribapena')->nullable();
            $table->dateTime('data');
            $table->unsignedInteger('edukiera');
            $table->decimal('prezioa', 8, 2);
            // Kategoria bat ezin da ezabatu jarduerak baditu
            $table->foreignId('kategoria_id')->constrained('kategoriak')->restrictOnDelete();
            // FK izena ez da konbentziokoa (user_id) -> taula esplizitua; nullable nullOnDelete-rako
            $table->foreignId('begiralea_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jarduerak');
    }
};
