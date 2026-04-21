@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div style="min-height:100vh;background:linear-gradient(135deg,#0a4a4a 0%,#0d6e6e 60%,#1a9090 100%);display:flex;align-items:center;justify-content:center;">
    <div style="width:100%;max-width:420px;padding:1.5rem;">
        <div class="text-center mb-4">
            <div style="width:64px;height:64px;background:rgba(255,255,255,.15);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i class="bi bi-heart-pulse-fill" style="font-size:2rem;color:#fff;"></i>
            </div>
            <h3 style="color:#fff;font-weight:700;">VetSystem</h3>
            <p style="color:rgba(255,255,255,.65);font-size:.875rem;">Sistema de Gestión Veterinaria</p>
        </div>

        <div class="card p-4">
            <h5 class="fw-700 mb-1">Bienvenido de nuevo</h5>
            <p class="text-muted small mb-4">Ingresa tus credenciales para continuar</p>

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Correo electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                               placeholder="usuario@veterinaria.com" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">Recordarme</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </button>
            </form>
        </div>

        <p class="text-center mt-3" style="color:rgba(255,255,255,.5);font-size:.75rem;">
            © {{ date('Y') }} VetSystem — Todos los derechos reservados
        </p>
    </div>
</div>
@endsection
