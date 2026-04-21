@extends('layouts.app')
@section('title', 'Clientes')
@section('page-title', 'Gestión de Clientes')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people me-2"></i>Listado de Clientes</span>
        <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Cliente
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="buscar" class="form-control form-control-sm"
                       placeholder="Buscar por nombre, cédula, teléfono, email..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm">Buscar</button>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>#</th><th>Nombre</th><th>Cédula</th><th>Teléfono</th><th>Email</th><th>Mascotas</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($clientes as $cliente)
                <tr>
                    <td class="text-muted small">{{ $cliente->id }}</td>
                    <td>
                        <a href="{{ route('clientes.show', $cliente) }}" class="fw-semibold text-decoration-none">
                            {{ $cliente->nombre_completo }}
                        </a>
                    </td>
                    <td class="small">{{ $cliente->cedula }}</td>
                    <td class="small">{{ $cliente->telefono }}</td>
                    <td class="small">{{ $cliente->email ?? '—' }}</td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $cliente->pacientes_count }} mascota(s)</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No se encontraron clientes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clientes->hasPages())
    <div class="card-footer">{{ $clientes->links() }}</div>
    @endif
</div>
@endsection
