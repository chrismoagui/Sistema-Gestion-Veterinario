<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Paciente;
use App\Models\Veterinario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        $stats = [
            'citas_hoy'         => Cita::whereDate('fecha_hora', $hoy)->count(),
            'citas_pendientes'  => Cita::whereIn('estado', ['programada', 'confirmada'])->count(),
            'total_pacientes'   => Paciente::where('activo', true)->count(),
            'total_clientes'    => Cliente::where('activo', true)->count(),
            'facturas_pendientes' => Factura::where('estado', 'pendiente')->count(),
            'ingresos_mes'      => Factura::where('estado', 'pagada')
                                    ->whereMonth('fecha_emision', $hoy->month)
                                    ->sum('total'),
        ];

        $citas_hoy = Cita::with(['paciente.cliente', 'veterinario.user'])
            ->whereDate('fecha_hora', $hoy)
            ->orderBy('fecha_hora')
            ->get();

        $proximas_citas = Cita::with(['paciente.cliente', 'veterinario.user'])
            ->whereIn('estado', ['programada', 'confirmada'])
            ->where('fecha_hora', '>', now())
            ->orderBy('fecha_hora')
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'citas_hoy', 'proximas_citas'));
    }
}
