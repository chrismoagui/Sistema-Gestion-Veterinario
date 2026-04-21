@extends('layouts.app')
@section('title', 'Nuevo Paciente')
@section('page-title', 'Registrar Paciente')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header"><i class="bi bi-heart me-2"></i>Datos del Paciente</div>
    <div class="card-body">
        <form method="POST" action="{{ route('pacientes.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Dueño / Cliente *</label>
                    <select name="cliente_id" class="form-select" required>
                        <option value="">Seleccionar dueño...</option>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->nombre_completo }} — {{ $c->cedula }}
                        </option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        ¿El dueño no está registrado? <a href="{{ route('clientes.create') }}">Registrar cliente</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Nombre de la mascota *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}"
                           placeholder="Ej: Firulais" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Especie *</label>
                    <input type="text" name="especie" class="form-control" value="{{ old('especie') }}"
                           placeholder="Ej: Perro, Gato..." list="especies-list" required>
                    <datalist id="especies-list">
                        <option value="Perro"><option value="Gato"><option value="Ave">
                        <option value="Conejo"><option value="Hamster"><option value="Reptil">
                    </datalist>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Raza</label>
                    <input type="text" name="raza" class="form-control" value="{{ old('raza') }}"
                           placeholder="Ej: Labrador">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Sexo</label>
                    <select name="sexo" class="form-select">
                        <option value="">No especificado</option>
                        <option value="macho" {{ old('sexo') == 'macho' ? 'selected' : '' }}>Macho</option>
                        <option value="hembra" {{ old('sexo') == 'hembra' ? 'selected' : '' }}>Hembra</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control"
                           value="{{ old('fecha_nacimiento') }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Peso (kg)</label>
                    <input type="number" name="peso" class="form-control" value="{{ old('peso') }}"
                           step="0.01" min="0" placeholder="Ej: 5.5">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Color</label>
                    <input type="text" name="color" class="form-control" value="{{ old('color') }}"
                           placeholder="Ej: Café con blanco">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Microchip</label>
                    <input type="text" name="microchip" class="form-control" value="{{ old('microchip') }}"
                           placeholder="Número de microchip">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="esterilizado"
                               id="esterilizado" value="1" {{ old('esterilizado') ? 'checked' : '' }}>
                        <label class="form-check-label" for="esterilizado">Esterilizado/Castrado</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Alergias conocidas</label>
                    <textarea name="alergias" class="form-control" rows="2"
                              placeholder="Lista de alergias o intolerancias...">{{ old('alergias') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2"
                              placeholder="Notas adicionales...">{{ old('observaciones') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i>Registrar Paciente
                </button>
                <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
