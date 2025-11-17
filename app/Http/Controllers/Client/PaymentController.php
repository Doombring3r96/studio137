<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::where('cliente_user_id', auth()->id())
            ->with(['service'])
            ->latest()
            ->paginate(10);

        // Estadísticas
        $stats = [
            'pagados' => Payment::where('cliente_user_id', auth()->id())
                ->where('estado', 'pagado')
                ->count(),
            'pendientes' => Payment::where('cliente_user_id', auth()->id())
                ->where('estado', 'pendiente')
                ->count(),
            'total_pagado' => Payment::where('cliente_user_id', auth()->id())
                ->where('estado', 'pagado')
                ->sum('cantidad'),
        ];

        // Agregar propiedades para la vista
        $payments->each(function ($payment) {
            $payment->service->tipo_formateado = $this->getServiceTypeName($payment->service->tipo);
            $payment->estado_clase = $this->getStatusClass($payment->estado);
        });

        return view('client.payments.index', compact('payments', 'stats'));
    }

    public function showPaymentForm(Payment $payment): View
    {
        $this->authorize('view', $payment);

        if ($payment->estado !== 'pendiente') {
            abort(403, 'Este pago ya ha sido procesado.');
        }

        return view('client.payments.pay', compact('payment'));
    }

    public function processPayment(Request $request, Payment $payment): RedirectResponse
    {
        $this->authorize('view', $payment);

        if ($payment->estado !== 'pendiente') {
            return redirect()->route('client.payments.index')
                ->with('error', 'Este pago ya ha sido procesado.');
        }

        $validated = $request->validate([
            'comprobante' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Guardar el comprobante
        $path = $request->file('comprobante')->store('comprobantes', 'public');

        // Actualizar el pago
        $payment->update([
            'comprobante_path' => $path,
            'estado' => 'revisado', // Cambia a revisado para que el CEO lo confirme
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('client.payments.index')
            ->with('success', 'Comprobante de pago subido exitosamente. El pago será confirmado después de la revisión.');
    }

    private function getServiceTypeName($type): string
    {
        return match($type) {
            'identidad_corporativa' => 'Identidad Corporativa',
            'community_manager' => 'Community Manager',
            'marketing_digital' => 'Marketing Digital',
            default => $type
        };
    }

    private function getStatusClass($status): string
    {
        return match($status) {
            'pagado' => 'bg-green-100 text-green-800',
            'pendiente' => 'bg-yellow-100 text-yellow-800',
            'revisado' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}