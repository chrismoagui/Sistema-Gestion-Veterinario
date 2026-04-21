@extends('layouts.app')
@section('title', 'Reporte - ' . $paciente->nombre)
@section('page-title', 'Reporte Médico del Paciente')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0">{{ $paciente->nombre }} — Historial Completo</h5>
        <small class="text-muted">{{ $paciente->especie }} {{ $paciente->raza ? '· ' . $paciente->raza : '' }} · Dueño: {{ $paciente->cliente->nombre_completo }}</small>
    </div>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-printer me-1"></i>Imprimir
    </button>
</div>

@forelse($paciente->historiales->sortByDesc('fecha_consulta') as $hist)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between">
        <span class="fw-semibold">{{ $hist->fecha_consulta->format('d/m/Y') }}</span>
        <span class="text-muted small">Dr. {{ $hist->veterinario->user->name }}</span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="text-muted small fw-semibold">DIAGNÓSTICO</label>
                <p>{{ $hist->diagnostico }}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small fw-semibold">TRATAMIENTO</label>
                <p>{{ $hist->tratamiento }}</p>
            </div>
            @if($hist->medicamentos)
            <div class="col-12">
                <label class="text-muted small fw-semibold">MEDICAMENTOS</label>
                <p>{{ $hist->medicamentos }}</p>
            </div>
            @endif
            <div class="col-md-3">
                <label class="text-muted small fw-semibold">PESO</label>
                <p>{{ $hist->peso_consulta ? $hist->peso_consulta . ' kg' : 'N/D' }}</p>
            </div>
            <div class="col-md-3">
                <label class="text-muted small fw-semibold">TEMPERATURA</label>
                <p>{{ $hist->temperatura ? $hist->temperatura . ' °C' : 'N/D' }}</p>
            </div>
        </div>
    </div>
</div>
@empty
<div class="text-center py-5 text-muted">Sin historial médico registrado.</div>
@endforelse
@endsection
