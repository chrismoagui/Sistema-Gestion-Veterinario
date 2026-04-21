<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Notificacion::with(['cliente', 'cita']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $notificaciones = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('notificaciones.index', compact('notificaciones'));
    }

    public function create()
    {
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        return view('notificaciones.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id'      => 'required|exists:clientes,id',
            'tipo'            => 'required|in:recordatorio_cita,confirmacion_cita,cancelacion_cita,resultado_examen,vacuna_pendiente,personalizada',
            'canal'           => 'required|in:email,sms,sistema',
            'asunto'          => 'required|string|max:200',
            'mensaje'         => 'required|string',
            'programada_para' => 'nullable|date|after:now',
        ]);

        $notificacion = Notificacion::create($validated);

        // Enviar inmediatamente si no está programada
        if (!$validated['programada_para']) {
            $this->enviarNotificacion($notificacion);
        }

        return redirect()->route('notificaciones.index')
            ->with('success', 'Notificación creada exitosamente.');
    }

    public function enviar(Notificacion $notificacion)
    {
        $this->enviarNotificacion($notificacion);

        return redirect()->route('notificaciones.index')
            ->with('success', 'Notificación enviada exitosamente.');
    }

    private function enviarNotificacion(Notificacion $notificacion): void
    {
        try {
            if ($notificacion->canal === 'email') {
                $cliente = $notificacion->cliente;
                if ($cliente->email) {
                    Mail::raw($notificacion->mensaje, function ($message) use ($notificacion, $cliente) {
                        $message->to($cliente->email, $cliente->nombre_completo)
                                ->subject($notificacion->asunto);
                    });
                }
            }

            $notificacion->update([
                'estado'      => 'enviada',
                'fecha_envio' => now(),
            ]);
        } catch (\Exception $e) {
            $notificacion->update([
                'estado'         => 'fallida',
                'error_detalle'  => $e->getMessage(),
            ]);
        }
    }
}
