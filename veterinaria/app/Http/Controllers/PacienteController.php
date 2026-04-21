<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Cliente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Paciente::with('cliente')->where('activo', true);

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('especie', 'like', "%{$buscar}%")
                  ->orWhere('raza', 'like', "%{$buscar}%")
                  ->orWhereHas('cliente', fn($c) => $c->where('nombre', 'like', "%{$buscar}%")
                      ->orWhere('apellido', 'like', "%{$buscar}%"));
            });
        }

        if ($request->filled('especie')) {
            $query->where('especie', $request->especie);
        }

        $pacientes = $query->orderBy('nombre')->paginate(15)->withQueryString();
        $especies   = Paciente::distinct()->pluck('especie');

        return view('pacientes.index', compact('pacientes', 'especies'));
    }

    public function create()
    {
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        return view('pacientes.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id'       => 'required|exists:clientes,id',
            'nombre'           => 'required|string|max:100',
            'especie'          => 'required|string|max:50',
            'raza'             => 'nullable|string|max:100',
            'sexo'             => 'nullable|in:macho,hembra',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'peso'             => 'nullable|numeric|min:0|max:500',
            'color'            => 'nullable|string|max:50',
            'microchip'        => 'nullable|string|max:50|unique:pacientes',
            'esterilizado'     => 'boolean',
            'alergias'         => 'nullable|string',
            'observaciones'    => 'nullable|string',
        ]);

        $paciente = Paciente::create($validated);

        return redirect()->route('pacientes.show', $paciente)
            ->with('success', 'Paciente registrado exitosamente.');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load(['cliente', 'citas.veterinario.user', 'historiales.veterinario.user']);
        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        return view('pacientes.edit', compact('paciente', 'clientes'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'cliente_id'       => 'required|exists:clientes,id',
            'nombre'           => 'required|string|max:100',
            'especie'          => 'required|string|max:50',
            'raza'             => 'nullable|string|max:100',
            'sexo'             => 'nullable|in:macho,hembra',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'peso'             => 'nullable|numeric|min:0|max:500',
            'color'            => 'nullable|string|max:50',
            'microchip'        => 'nullable|string|max:50|unique:pacientes,microchip,' . $paciente->id,
            'esterilizado'     => 'boolean',
            'alergias'         => 'nullable|string',
            'observaciones'    => 'nullable|string',
        ]);

        $paciente->update($validated);

        return redirect()->route('pacientes.show', $paciente)
            ->with('success', 'Paciente actualizado exitosamente.');
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->update(['activo' => false]);
        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente desactivado correctamente.');
    }
}
