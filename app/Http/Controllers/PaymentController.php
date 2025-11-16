<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::with(['cliente', 'service', 'createdBy'])
            ->latest()
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create(): View
    {
        $clientes = User::whereHas('role', function($query) {
            $query->where('name', 'cliente');
        })->get();

        $services = Service::where('estado', 'activo')->get();

        return view('payments.create', compact('clientes', 'services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_user_id' => 'required|exists:users,id',
            'servicio_id' => 'required|exists:services,id',
            'cantidad' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'tipo' => 'required|in:mensual,unico',
            'comprobante_path' => 'nullable|string|max:500',
            'estado' => 'required|in:pendiente,pagado,revisado',
        ]);

        $validated['created_by'] = auth()->id();

        Payment::create($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Pago registrado exitosamente.');
    }

    public function show(Payment $payment): View
    {
        $payment->load(['cliente', 'service', 'createdBy', 'updatedBy']);
        
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment): View
    {
        $clientes = User::whereHas('role', function($query) {
            $query->where('name', 'cliente');
        })->get();

        $services = Service::where('estado', 'activo')->get();

        return view('payments.edit', compact('payment', 'clientes', 'services'));
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_user_id' => 'required|exists:users,id',
            'servicio_id' => 'required|exists:services,id',
            'cantidad' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'tipo' => 'required|in:mensual,unico',
            'comprobante_path' => 'nullable|string|max:500',
            'estado' => 'required|in:pendiente,pagado,revisado',
        ]);

        $validated['updated_by'] = auth()->id();

        $payment->update($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Pago actualizado exitosamente.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Pago eliminado exitosamente.');
    }

    public function markAsPaid(Payment $payment): RedirectResponse
    {
        $payment->update([
            'estado' => 'pagado',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Pago marcado como realizado.');
    }
}