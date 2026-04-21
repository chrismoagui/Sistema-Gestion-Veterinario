<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historiales_medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->onDelete('cascade');
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('veterinario_id')->constrained('veterinarios')->onDelete('cascade');
            $table->date('fecha_consulta');
            $table->text('diagnostico');
            $table->text('tratamiento');
            $table->text('medicamentos')->nullable();
            $table->text('examenes_realizados')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->decimal('peso_consulta', 6, 2)->nullable();
            $table->decimal('temperatura', 4, 1)->nullable();
            $table->date('proxima_consulta')->nullable();
            $table->text('notas_adicionales')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historiales_medicos');
    }
};
