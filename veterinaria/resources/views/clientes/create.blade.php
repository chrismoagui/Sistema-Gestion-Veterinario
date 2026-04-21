@extends('layouts.app')
@section('title', 'Nuevo Cliente')
@section('page-title', 'Registrar Cliente')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-person-plus me-2"></i>Datos del Cliente</div>
    <div class="card-body">
        <form method="POST" action="{{ route('clientes.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Apellido *</label>
                    <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Cédula / Documento *</label>
                    <input type="text" name="cedula" class="form-control" value="{{ old('cedula') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Teléfono *</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="Para envío de notificaciones">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Dirección</label>
                    <textarea name="direccion" class="form-control" rows="2">{{ old('direccion') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i>Registrar Cliente
                </button>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
