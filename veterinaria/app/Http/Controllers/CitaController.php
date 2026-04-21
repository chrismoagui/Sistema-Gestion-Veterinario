<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Veterinario;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $query = Cita::with(['paciente.cliente', 'veterinario.user']);

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora', $request->fecha);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('veterinario_id')) {
            $query->where('veterinario_id', $request->veterinario_id);
        }

        $citas       = $query->orderBy('fecha_hora', 'desc')->paginate(15)->withQueryString();
        $veterinarios = Veterinario::with('user')->where('activo', true)->get();

        return view('citas.index', compact('citas', 'veterinarios'));
    }

    public function create()
    {
        $pacientes    = Paciente::with('cliente')->where('activo', true)->orderBy('nombre')->get();
        $veterinarios = Veterinario::with('user')->where('activo', true)->get();
        return view('citas.create', compact('pacientes', 'veterinarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id'      => 'required|exists:pacientes,id',
            'veterinario_id'   => 'required|exists:veterinarios,id',
            'fecha_hora'       => 'required|date|after:now',
            'duracion_minutos' => 'required|integer|min:15|max:240',
            'tipo'             => 'required|in:consulta,vacunacion,cirugia,control,urgencia,otro',
            'motivo_consulta'  => 'required|string',
            'observaciones'    => 'nullable|string',
        ]);

        // Verificar disponibilidad
        $veterinario = Veterinario::findOrFail($validated['veterinario_id']);
        if (!$veterinario->estaDisponible($validated['fecha_hora'], $validated['duracion_minutos'])) {
            return back()->withErrors(['fecha_hora' => 'El veterinario no está disponible en ese horario.'])->withInput();
        }

        $validated['registrado_por'] = auth()->id();
        $cita = Cita::create($validated);

        // Crear notificación automática
        $this->crearNotificacionCita($cita, 'confirmacion_cita');

        return redirect()->route('citas.show', $cita)
            ->with('success', 'Cita registrada exitosamente.');
    }

    public function show(Cita $cita)
    {
        $cita->load(['paciente.cliente', 'veterinario.user', 'historial', 'factura', 'notificaciones']);
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        $pacientes    = Paciente::with('cliente')->where('activo', true)->orderBy('nombre')->get();
        $veterinarios = Veterinario::with('user')->where('activo', true)->get();
        return view('citas.edit', compact('cita', 'pacientes', 'veterinarios'));
    }

    public function update(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'paciente_id'      => 'required|exists:pacientes,id',
            'veterinario_id'   => 'required|exists:veterinarios,id',
            'fecha_hora'       => 'required|date',
            'duracion_minutos' => 'required|integer|min:15|max:240',
            'tipo'             => 'required|in:consulta,vacunacion,cirugia,control,urgencia,otro',
            'estado'           => 'required|in:programada,confirmada,en_curso,completada,cancelada,no_asistio',
            'motivo_consulta'  => 'required|string',
            'observaciones'    => 'nullable|string',
            'motivo_cancelacion' => 'nullable|string',
        ]);

        $cita->update($validated);

        return redirect()->route('citas.show', $cita)
            ->with('success', 'Cita actualizada exitosamente.');
    }

    public function cancelar(Request $request, Cita $cita)
    {
        $request->validate(['motivo_cancelacion' => 'required|string|min:10']);

        $cita->update([
            'estado'             => 'cancelada',
            'motivo_cancelacion' => $request->motivo_cancelacion,
        ]);

        $this->crearNotificacionCita($cita, 'cancelacion_cita');

        return redirect()->route('citas.show', $cita)
            ->with('success', 'Cita cancelada.');
    }

    public function verificarDisponibilidad(Request $request)
    {
        $request->validate([
            'veterinario_id'   => 'required|exists:veterinarios,id',
            'fecha_hora'       => 'required|date',
            'duracion_minutos' => 'required|integer',
        ]);

        $veterinario  = Veterinario::findOrFail($request->veterinario_id);
        $disponible   = $veterinario->estaDisponible($request->fecha_hora, $request->duracion_minutos);

        return response()->json(['disponible' => $disponible]);
    }

    private function crearNotificacionCita(Cita $cita, string $tipo): void
    {
        $cita->load('paciente.cliente');
        $cliente = $cita->paciente->cliente;
        if (!$cliente->email) return;

        Notificacion::create([
            'cita_id'    => $cita->id,
            'cliente_id' => $cliente->id,
            'tipo'       => $tipo,
            'canal'      => 'email',
            'asunto'     => $tipo === 'confirmacion_cita'
                ? "Confirmación de cita - {$cita->fecha_hora->format('d/m/Y H:i')}"
                : "Cancelación de cita - {$cita->fecha_hora->format('d/m/Y H:i')}",
            'mensaje'    => "Estimado(a) {$cliente->nombre_completo}, su cita ha sido " .
                ($tipo === 'confirmacion_cita' ? 'confirmada' : 'cancelada') .
                " para el {$cita->fecha_hora->format('d/m/Y')} a las {$cita->fecha_hora->format('H:i')}.",
            'estado'     => 'pendiente',
        ]);
    }
}
