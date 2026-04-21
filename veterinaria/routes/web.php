<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\VeterinarioController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\NotificacionController;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Clientes
    Route::resource('clientes', ClienteController::class);

    // Pacientes
    Route::resource('pacientes', PacienteController::class);

    // Veterinarios
    Route::resource('veterinarios', VeterinarioController::class);

    // Citas
    Route::resource('citas', CitaController::class);
    Route::post('citas/{cita}/cancelar', [CitaController::class, 'cancelar'])->name('citas.cancelar');
    Route::get('citas/disponibilidad', [CitaController::class, 'verificarDisponibilidad'])->name('citas.disponibilidad');

    // Historial médico
    Route::resource('historial', HistorialController::class);
    Route::get('historial/reporte/{paciente}', [HistorialController::class, 'reportePaciente'])->name('historial.reporte');

    // Facturas
    Route::resource('facturas', FacturaController::class)->except(['edit', 'update', 'destroy']);
    Route::post('facturas/{factura}/pago', [FacturaController::class, 'registrarPago'])->name('facturas.pago');
    Route::post('facturas/{factura}/anular', [FacturaController::class, 'anular'])->name('facturas.anular');

    // Notificaciones
    Route::resource('notificaciones', NotificacionController::class)->only(['index', 'create', 'store']);
    Route::post('notificaciones/{notificacion}/enviar', [NotificacionController::class, 'enviar'])->name('notificaciones.enviar');
});

// Auth routes básico
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
