<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema Veterinaria')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:   #0d6e6e;
            --primary-light: #e6f4f4;
            --accent:    #f4a261;
            --sidebar-w: 260px;
            --bg:        #f0f4f8;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: #0a4a4a;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            transition: transform .3s;
            z-index: 1000;
        }
        #sidebar .brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        #sidebar .brand h5 { color: #fff; font-weight: 700; margin: 0; font-size: .95rem; }
        #sidebar .brand small { color: #7ecece; font-size: .75rem; }
        #sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: .6rem 1.25rem;
            border-radius: 8px;
            margin: 2px 10px;
            font-size: .875rem;
            transition: all .2s;
            display: flex; align-items: center; gap: .6rem;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background: rgba(255,255,255,.12);
            color: #fff;
        }
        #sidebar .nav-link i { font-size: 1rem; width: 20px; }
        #sidebar .nav-section {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255,255,255,.35);
            padding: 1rem 1.35rem .3rem;
        }

        /* Main content */
        #main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        #topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: .75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 900;
        }
        #topbar .page-title { font-weight: 700; font-size: 1.1rem; color: #1a202c; }
        .content-area { padding: 1.5rem; flex: 1; }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            padding: 1rem 1.25rem;
        }

        /* Stats */
        .stat-card {
            border-radius: 12px;
            padding: 1.25rem;
            color: #fff;
            border: none;
        }
        .stat-card .stat-val { font-size: 2rem; font-weight: 700; }
        .stat-card .stat-lbl { font-size: .8rem; opacity: .85; }
        .stat-card .stat-icon { font-size: 2.5rem; opacity: .25; }

        /* Badges */
        .badge { font-weight: 500; }

        /* Table */
        .table th { font-size: .8rem; text-transform: uppercase; letter-spacing: .05em; color: #718096; font-weight: 600; }
        .table td { vertical-align: middle; font-size: .875rem; }

        /* Buttons */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: #0b5e5e; border-color: #0b5e5e; }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); }

        /* Forms */
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(13,110,110,.15);
        }

        /* Alert */
        .alert { border-radius: 10px; border: none; }

        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

@auth
<!-- Sidebar -->
<nav id="sidebar">
    <div class="brand">
        <div class="d-flex align-items-center gap-2">
            <div style="width:36px;height:36px;background:#7ecece;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-heart-pulse-fill" style="color:#0a4a4a;font-size:1.1rem;"></i>
            </div>
            <div>
                <h5>VetSystem</h5>
                <small>Gestión Veterinaria</small>
            </div>
        </div>
    </div>

    <div class="py-2 flex-grow-1 overflow-auto">
        <div class="nav-section">Principal</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <div class="nav-section">Pacientes</div>
        <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Clientes / Dueños
        </a>
        <a href="{{ route('pacientes.index') }}" class="nav-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}">
            <i class="bi bi-heart"></i> Mascotas
        </a>

        <div class="nav-section">Operaciones</div>
        <a href="{{ route('citas.index') }}" class="nav-link {{ request()->routeIs('citas.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Citas
        </a>
        <a href="{{ route('historial.index') }}" class="nav-link {{ request()->routeIs('historial.*') ? 'active' : '' }}">
            <i class="bi bi-file-medical"></i> Historial Médico
        </a>
        <a href="{{ route('facturas.index') }}" class="nav-link {{ request()->routeIs('facturas.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Facturas
        </a>
        <a href="{{ route('notificaciones.index') }}" class="nav-link {{ request()->routeIs('notificaciones.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notificaciones
        </a>

        @if(auth()->user()->esAdmin())
        <div class="nav-section">Administración</div>
        <a href="{{ route('veterinarios.index') }}" class="nav-link {{ request()->routeIs('veterinarios.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Veterinarios
        </a>
        @endif
    </div>

    <div class="p-3 border-top" style="border-color: rgba(255,255,255,.1) !important;">
        <div class="d-flex align-items-center gap-2">
            <div style="width:32px;height:32px;background:#7ecece;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#0a4a4a;font-size:.8rem;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div style="color:#fff;font-size:.8rem;font-weight:600;" class="text-truncate">{{ auth()->user()->name }}</div>
                <div style="color:#7ecece;font-size:.7rem;">{{ ucfirst(auth()->user()->rol) }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm" style="color:rgba(255,255,255,.5);" title="Cerrar sesión">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Main -->
<div id="main">
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="page-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('citas.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Nueva Cita
            </a>
            <small class="text-muted d-none d-md-block">{{ now()->format('d/m/Y') }}</small>
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>
@else
    @yield('content')
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
