<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('torneos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dragon_local_id')
                  ->constrained('dragons')
                  ->onDelete('cascade');
            $table->foreignId('dragon_visitante_id')
                  ->constrained('dragons')
                  ->onDelete('cascade');
            $table->date('fecha');
            $table->enum('resultado', ['local', 'visitante', 'empate']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('torneos');
    }
};
