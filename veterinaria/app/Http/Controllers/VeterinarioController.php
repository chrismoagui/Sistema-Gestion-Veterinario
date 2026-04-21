<?php

namespace App\Http\Controllers;

use App\Models\Veterinario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VeterinarioController extends Controller
{
    public function index()
    {
        $veterinarios = Veterinario::with('user')->where('activo', true)->paginate(15);
        return view('veterinarios.index', compact('veterinarios'));
    }

    public function create()
    {
        return view('veterinarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'email'           => 'required|email|unique:users',
            'password'        => 'required|min:8|confirmed',
            'especialidad'    => 'required|string|max:100',
            'telefono'        => 'nullable|string|max:20',
            'numero_licencia' => 'required|string|max:50|unique:veterinarios',
            'horario_inicio'  => 'required',
            'horario_fin'     => 'required|after:horario_inicio',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'rol'      => 'veterinario',
        ]);

        Veterinario::create([
            'user_id'         => $user->id,
            'especialidad'    => $request->especialidad,
            'telefono'        => $request->telefono,
            'numero_licencia' => $request->numero_licencia,
            'horario_inicio'  => $request->horario_inicio,
            'horario_fin'     => $request->horario_fin,
        ]);

        return redirect()->route('veterinarios.index')
            ->with('success', 'Veterinario registrado exitosamente.');
    }

    public function show(Veterinario $veterinario)
    {
        $veterinario->load(['user', 'citas' => fn($q) => $q->orderBy('fecha_hora', 'desc')->limit(10)]);
        return view('veterinarios.show', compact('veterinario'));
    }

    public function edit(Veterinario $veterinario)
    {
        $veterinario->load('user');
        return view('veterinarios.edit', compact('veterinario'));
    }

    public function update(Request $request, Veterinario $veterinario)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'email'           => 'required|email|unique:users,email,' . $veterinario->user_id,
            'especialidad'    => 'required|string|max:100',
            'telefono'        => 'nullable|string|max:20',
            'numero_licencia' => 'required|string|max:50|unique:veterinarios,numero_licencia,' . $veterinario->id,
            'horario_inicio'  => 'required',
            'horario_fin'     => 'required',
        ]);

        $veterinario->user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        $veterinario->update([
            'especialidad'    => $request->especialidad,
            'telefono'        => $request->telefono,
            'numero_licencia' => $request->numero_licencia,
            'horario_inicio'  => $request->horario_inicio,
            'horario_fin'     => $request->horario_fin,
        ]);

        return redirect()->route('veterinarios.show', $veterinario)
            ->with('success', 'Veterinario actualizado exitosamente.');
    }

    public function destroy(Veterinario $veterinario)
    {
        $veterinario->update(['activo' => false]);
        $veterinario->user->update(['activo' => false]);
        return redirect()->route('veterinarios.index')
            ->with('success', 'Veterinario desactivado.');
    }
}
