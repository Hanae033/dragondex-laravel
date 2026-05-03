<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dragons', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo');      // Fuego, Hielo, Veneno...
            $table->integer('poder');
            $table->foreignId('domador_id')
                  ->constrained('domadores')
                  ->onDelete('cascade'); // Al borrar domador → borra sus dragones
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dragons');
    }
};
