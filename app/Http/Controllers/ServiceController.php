<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Service::class, 'service');
    }

    public function index(): View
    {
        $user = auth()->user();
        
        if ($user->isCliente()) {
            $services = Service::where('cliente_user_id', $user->id)
                ->with(['cliente', 'createdBy'])
                ->latest()
                ->paginate(10);
        } elseif ($user->isDirectorMarca()) {
            $services = Service::where('tipo', 'community_manager')
                ->orWhereHas('assignments', function($query) use ($user) {
                    $query->whereIn('assigned_to', $user->subordinates()->pluck('id'));
                })
                ->with(['cliente', 'createdBy'])
                ->latest()
                ->paginate(10);
        } elseif ($user->isDirectorCreativo()) {
            $services = Service::where('tipo', 'identidad_corporativa')
                ->orWhereHas('assignments', function($query) use ($user) {
                    $query->whereIn('assigned_to', $user->subordinates()->pluck('id'));
                })
                ->with(['cliente', 'createdBy'])
                ->latest()
                ->paginate(10);
        } elseif ($user->isDesigner() || $user->isCM()) {
            $services = Service::whereHas('assignments', function($query) use ($user) {
                    $query->where('assigned_to', $user->id);
                })
                ->with(['cliente', 'createdBy'])
                ->latest()
                ->paginate(10);
        } else {
            // Developer y CEO ven todo
            $services = Service::with(['cliente', 'createdBy'])
                ->latest()
                ->paginate(10);
        }

        return view('services.index', compact('services'));
    }

    public function create(): View
    {
        $clientes = User::getActiveClients();
        return view('services.create', compact('clientes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipo' => 'required|in:identidad_corporativa,community_manager,marketing_digital',
            'fecha_ini' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_ini',
            'costo' => 'required|numeric|min:0',
            'cliente_user_id' => 'required|exists:users,id',
            'estado' => 'required|in:activo,inactivo,culminado,cancelado',
        ]);

        $validated['created_by'] = auth()->id();
        $service = Service::create($validated);

        // Notificar al cliente
        $this->notifyClient($service, 'Nuevo servicio creado');

        return redirect()->route('services.index')
            ->with('success', 'Servicio creado exitosamente.');
    }

    public function show(Service $service): View
    {
        $service->load(['cliente', 'brief', 'payments', 'logos', 'publicationCalendars', 'assignments.assignedTo']);
        return view('services.show', compact('service'));
    }

    public function edit(Service $service): View
    {
        $clientes = User::getActiveClients();
        return view('services.edit', compact('service', 'clientes'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'tipo' => 'required|in:identidad_corporativa,community_manager,marketing_digital',
            'fecha_ini' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_ini',
            'costo' => 'required|numeric|min:0',
            'cliente_user_id' => 'required|exists:users,id',
            'estado' => 'required|in:activo,inactivo,culminado,cancelado',
        ]);

        $oldEstado = $service->estado;
        $validated['updated_by'] = auth()->id();
        $service->update($validated);

        // Notificar cambio de estado
        if ($oldEstado !== $service->estado) {
            $this->notifyStatusChange($service, $oldEstado, $service->estado);
        }

        return redirect()->route('services.index')
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    public function changeStatus(Request $request, Service $service): RedirectResponse
    {
        $this->authorize('changeStatus', $service);

        $request->validate([
            'estado' => 'required|in:activo,inactivo,culminado,cancelado',
        ]);

        $oldEstado = $service->estado;
        $service->update([
            'estado' => $request->estado,
            'updated_by' => auth()->id(),
        ]);

        $this->notifyStatusChange($service, $oldEstado, $request->estado);

        return redirect()->back()
            ->with('success', 'Estado del servicio actualizado exitosamente.');
    }

    private function notifyClient(Service $service, string $message): void
    {
        // Implementar notificación al cliente
        // Usar el sistema de notificaciones de Laravel
    }

    private function notifyStatusChange(Service $service, string $oldStatus, string $newStatus): void
    {
        // Implementar notificación de cambio de estado
    }
}