<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('nombre');
            $table->string('especie'); // Perro, Gato, Ave, etc.
            $table->string('raza')->nullable();
            $table->enum('sexo', ['macho', 'hembra'])->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->decimal('peso', 6, 2)->nullable();
            $table->string('color')->nullable();
            $table->string('microchip', 50)->nullable()->unique();
            $table->boolean('esterilizado')->default(false);
            $table->text('alergias')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
