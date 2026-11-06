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
        Schema::create('jarduera_u_s_e_r', function (Blueprint $table) {
            $table->id();
            $table -> foreignId('jarduera_id') -> constrained('jarduerak') -> cascadeOnDelete();
            $table -> foreignId('user_id') -> constrained('users') -> cascadeOnDelete();
            $table -> boolean('ordainduta') -> default(false);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jarduera_u_s_e_r');
    }
};
