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
        Schema::create('jarduerak', function (Blueprint $table) {
            $table->id();
            $table -> string('izenburua');
            $table -> text('deskribapena') -> nullable();
            $table -> dateTime('date');
            $table -> unsignedInteger('edukiera');
            $table -> decimal('prezioa', 8, 2);
            $table -> foreignId('kategoria_id') -> constrained('kategoriak') -> cascadeOnDelete();
            $table -> foreignId('begiralea_id') -> constrained('users') -> cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jarduerak');
    }
};
