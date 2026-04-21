<?php

namespace App\Http\Controllers;

use App\Models\HistorialMedico;
use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function index(Request $request)
    {
        $query = HistorialMedico::with(['paciente.cliente', 'veterinario.user']);

        if ($request->filled('paciente_id')) {
            $query->where('paciente_id', $request->paciente_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_consulta', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_consulta', '<=', $request->fecha_hasta);
        }

        $historiales = $query->orderBy('fecha_consulta', 'desc')->paginate(15)->withQueryString();
        $pacientes   = Paciente::where('activo', true)->orderBy('nombre')->get();

        return view('historial.index', compact('historiales', 'pacientes'));
    }

    public function create(Request $request)
    {
        $cita_id = $request->query('cita_id');
        $cita    = $cita_id ? Cita::with(['paciente.cliente', 'veterinario.user'])->findOrFail($cita_id) : null;
        $pacientes = Paciente::with('cliente')->where('activo', true)->get();

        return view('historial.create', compact('cita', 'pacientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cita_id'             => 'required|exists:citas,id',
            'paciente_id'         => 'required|exists:pacientes,id',
            'fecha_consulta'      => 'required|date',
            'diagnostico'         => 'required|string',
            'tratamiento'         => 'required|string',
            'medicamentos'        => 'nullable|string',
            'examenes_realizados' => 'nullable|string',
            'recomendaciones'     => 'nullable|string',
            'peso_consulta'       => 'nullable|numeric|min:0',
            'temperatura'         => 'nullable|numeric|min:30|max:45',
            'proxima_consulta'    => 'nullable|date|after:today',
            'notas_adicionales'   => 'nullable|string',
        ]);

        $validated['veterinario_id'] = auth()->user()->veterinario?->id
            ?? Cita::find($validated['cita_id'])->veterinario_id;

        $historial = HistorialMedico::create($validated);

        // Marcar la cita como completada
        Cita::find($validated['cita_id'])->update(['estado' => 'completada']);

        return redirect()->route('historial.show', $historial)
            ->with('success', 'Historial médico registrado exitosamente.');
    }

    public function show(HistorialMedico $historial)
    {
        $historial->load(['paciente.cliente', 'veterinario.user', 'cita']);
        return view('historial.show', compact('historial'));
    }

    public function edit(HistorialMedico $historial)
    {
        $historial->load(['cita', 'paciente']);
        return view('historial.edit', compact('historial'));
    }

    public function update(Request $request, HistorialMedico $historial)
    {
        $validated = $request->validate([
            'diagnostico'         => 'required|string',
            'tratamiento'         => 'required|string',
            'medicamentos'        => 'nullable|string',
            'examenes_realizados' => 'nullable|string',
            'recomendaciones'     => 'nullable|string',
            'peso_consulta'       => 'nullable|numeric|min:0',
            'temperatura'         => 'nullable|numeric|min:30|max:45',
            'proxima_consulta'    => 'nullable|date',
            'notas_adicionales'   => 'nullable|string',
        ]);

        $historial->update($validated);

        return redirect()->route('historial.show', $historial)
            ->with('success', 'Historial actualizado exitosamente.');
    }

    public function reportePaciente(Paciente $paciente)
    {
        $paciente->load(['cliente', 'historiales.veterinario.user']);
        return view('historial.reporte', compact('paciente'));
    }
}
