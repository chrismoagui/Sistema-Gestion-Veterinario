@extends('layouts.app')
@section('title', 'Editar Paciente')
@section('page-title', 'Editar Paciente')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Editar: {{ $paciente->nombre }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('pacientes.update', $paciente) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Dueño / Cliente *</label>
                    <select name="cliente_id" class="form-select" required>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ $paciente->cliente_id == $c->id ? 'selected' : '' }}>
                            {{ $c->nombre_completo }} — {{ $c->cedula }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $paciente->nombre) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Especie *</label>
                    <input type="text" name="especie" class="form-control" value="{{ old('especie', $paciente->especie) }}"
                           list="especies-list" required>
                    <datalist id="especies-list">
                        <option value="Perro"><option value="Gato"><option value="Ave">
                        <option value="Conejo"><option value="Hamster"><option value="Reptil">
                    </datalist>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Raza</label>
                    <input type="text" name="raza" class="form-control" value="{{ old('raza', $paciente->raza) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Sexo</label>
                    <select name="sexo" class="form-select">
                        <option value="">No especificado</option>
                        <option value="macho" {{ $paciente->sexo == 'macho' ? 'selected' : '' }}>Macho</option>
                        <option value="hembra" {{ $paciente->sexo == 'hembra' ? 'selected' : '' }}>Hembra</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control"
                           value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Peso (kg)</label>
                    <input type="number" name="peso" class="form-control" step="0.01" min="0"
                           value="{{ old('peso', $paciente->peso) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Color</label>
                    <input type="text" name="color" class="form-control" value="{{ old('color', $paciente->color) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Microchip</label>
                    <input type="text" name="microchip" class="form-control" value="{{ old('microchip', $paciente->microchip) }}">
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="esterilizado"
                               id="esterilizado" value="1" {{ $paciente->esterilizado ? 'checked' : '' }}>
                        <label class="form-check-label" for="esterilizado">Esterilizado/Castrado</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Alergias</label>
                    <textarea name="alergias" class="form-control" rows="2">{{ old('alergias', $paciente->alergias) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $paciente->observaciones) }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i>Guardar Cambios
                </button>
                <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
