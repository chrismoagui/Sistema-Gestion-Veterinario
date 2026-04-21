@extends('layouts.app')
@section('title', 'Nueva Notificación')
@section('page-title', 'Enviar Notificación')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-bell me-2"></i>Nueva Notificación</div>
    <div class="card-body">
        <form method="POST" action="{{ route('notificaciones.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Cliente *</label>
                    <select name="cliente_id" class="form-select" required>
                        <option value="">Seleccionar cliente...</option>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre_completo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Tipo *</label>
                    <select name="tipo" class="form-select" required>
                        @foreach(['recordatorio_cita','confirmacion_cita','cancelacion_cita','resultado_examen','vacuna_pendiente','personalizada'] as $t)
                        <option value="{{ $t }}">{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Canal *</label>
                    <select name="canal" class="form-select" required>
                        <option value="email">Email</option>
                        <option value="sms">SMS</option>
                        <option value="sistema">Sistema</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Programar envío</label>
                    <input type="datetime-local" name="programada_para" class="form-control">
                    <div class="form-text">Dejar vacío para enviar inmediatamente.</div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Asunto *</label>
                    <input type="text" name="asunto" class="form-control" required placeholder="Asunto de la notificación">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Mensaje *</label>
                    <textarea name="mensaje" class="form-control" rows="5" required
                              placeholder="Contenido del mensaje..."></textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-send me-2"></i>Enviar Notificación
                </button>
                <a href="{{ route('notificaciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
