<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->nullable()->constrained('citas')->onDelete('set null');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->enum('tipo', ['recordatorio_cita', 'confirmacion_cita', 'cancelacion_cita', 'resultado_examen', 'vacuna_pendiente', 'personalizada']);
            $table->enum('canal', ['email', 'sms', 'sistema']);
            $table->string('asunto');
            $table->text('mensaje');
            $table->enum('estado', ['pendiente', 'enviada', 'fallida'])->default('pendiente');
            $table->datetime('fecha_envio')->nullable();
            $table->datetime('programada_para')->nullable();
            $table->text('error_detalle')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
