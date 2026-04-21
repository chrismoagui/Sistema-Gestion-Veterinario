<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('veterinarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('especialidad');
            $table->string('telefono', 20)->nullable();
            $table->string('numero_licencia')->unique();
            $table->time('horario_inicio')->default('08:00:00');
            $table->time('horario_fin')->default('18:00:00');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('veterinarios');
    }
};
