<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Brief;
use App\Models\Logo;
use App\Models\PublicationCalendar;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::where('cliente_user_id', auth()->id())
            ->with(['brief', 'logos', 'publicationCalendars'])
            ->latest()
            ->paginate(10);

        // Agregar propiedades calculadas para la vista
        $services->each(function ($service) {
            $service->tipo_formateado = $this->getServiceTypeName($service->tipo);
            $service->costo_formateado = '$ ' . number_format($service->costo, 2);
            $service->estado_clase = $this->getStatusClass($service->estado);
            $service->progreso = $this->calculateProgress($service);
            $service->acciones_pendientes = $this->getPendingActions($service);
        });

        return view('client.services.index', compact('services'));
    }

    public function show(Service $service): View
    {
        $this->authorize('view', $service);

        $service->load(['brief', 'logos', 'publicationCalendars.artworks', 'payments']);

        $service->tipo_formateado = $this->getServiceTypeName($service->tipo);
        $service->costo_formateado = '$ ' . number_format($service->costo, 2);
        $service->estado_clase = $this->getStatusClass($service->estado);
        $service->progreso = $this->calculateProgress($service);
        $service->acciones_pendientes = $this->getPendingActions($service);

        return view('client.services.show', compact('service'));
    }

    public function showBriefForm(Service $service): View
    {
        $this->authorize('view', $service);

        if ($service->brief) {
            return redirect()->route('client.services.show', $service)
                ->with('info', 'El brief ya fue completado para este servicio.');
        }

        return view('client.services.brief', compact('service'));
    }

    public function storeBrief(Request $request, Service $service): RedirectResponse
    {
        $this->authorize('view', $service);

        if ($service->brief) {
            return redirect()->route('client.services.show', $service)
                ->with('error', 'El brief ya fue completado para este servicio.');
        }

        $validated = $request->validate([
            'contenido_json' => 'required|json',
            'document_path' => 'nullable|string|max:500',
        ]);

        Brief::create([
            'servicio_id' => $service->id,
            'tipo' => $service->tipo === 'identidad_corporativa' ? 'logo' : 'cm',
            'fecha_recibida' => now(),
            'contenido_json' => $validated['contenido_json'],
            'document_path' => $validated['document_path'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('client.services.show', $service)
            ->with('success', 'Brief completado exitosamente.');
    }

    public function showLogos(Service $service): View
    {
        $this->authorize('view', $service);

        $logos = $service->logos()->with(['autor'])->latest()->get();

        return view('client.services.logos', compact('service', 'logos'));
    }

    public function showCalendars(Service $service): View
    {
        $this->authorize('view', $service);

        $calendars = $service->publicationCalendars()->with(['artworks', 'creador'])->latest()->get();

        return view('client.services.calendars', compact('service', 'calendars'));
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
            'activo' => 'bg-green-100 text-green-800',
            'inactivo' => 'bg-gray-100 text-gray-800',
            'culminado' => 'bg-blue-100 text-blue-800',
            'cancelado' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    private function calculateProgress(Service $service): int
    {
        // Lógica simple de progreso basada en el estado y las tareas completadas
        $baseProgress = match($service->estado) {
            'activo' => 30,
            'culminado' => 100,
            'cancelado' => 0,
            default => 10
        };

        // Incrementar progreso si tiene brief
        if ($service->brief) {
            $baseProgress += 20;
        }

        // Incrementar progreso si tiene logos entregados
        if ($service->logos->where('estado', 'entregado')->count() > 0) {
            $baseProgress += 25;
        }

        // Incrementar progreso si tiene calendarios entregados
        if ($service->publicationCalendars->where('estado', 'entregado')->count() > 0) {
            $baseProgress += 25;
        }

        return min($baseProgress, 100);
    }

    private function getPendingActions(Service $service): array
    {
        $actions = [];

        if (!$service->brief) {
            $actions[] = [
                'texto' => 'Completar brief del servicio',
                'url' => route('client.services.brief.create', $service)
            ];
        }

        if ($service->tipo === 'identidad_corporativa') {
            $logosPendientes = $service->logos->whereIn('estado', ['enviado', 'en_revision']);
            if ($logosPendientes->count() > 0) {
                $actions[] = [
                    'texto' => 'Revisar propuestas de logo',
                    'url' => route('client.services.logos', $service)
                ];
            }
        }

        if ($service->tipo === 'community_manager') {
            $calendariosPendientes = $service->publicationCalendars->whereIn('estado', ['enviado', 'en_revision']);
            if ($calendariosPendientes->count() > 0) {
                $actions[] = [
                    'texto' => 'Revisar calendarios de publicación',
                    'url' => route('client.services.calendars', $service)
                ];
            }
        }

        return $actions;
    }
    // Agregar al final del ServiceController
public function approveLogo(Logo $logo): RedirectResponse
{
    $this->authorize('view', $logo->service);

    if ($logo->estado !== 'enviado' && $logo->estado !== 'en_revision') {
        return redirect()->back()->with('error', 'No se puede aprobar este logo en su estado actual.');
    }

    $logo->update([
        'estado' => 'entregado',
        'updated_by' => auth()->id(),
    ]);

    return redirect()->back()->with('success', 'Logo aprobado exitosamente.');
}

public function rejectLogo(Request $request, Logo $logo): RedirectResponse
{
    $this->authorize('view', $logo->service);

    $request->validate([
        'rejection_reason' => 'required|string|max:1000',
    ]);

    if ($logo->estado !== 'enviado' && $logo->estado !== 'en_revision') {
        return redirect()->back()->with('error', 'No se puede rechazar este logo en su estado actual.');
    }

    $logo->update([
        'estado' => 'rechazado',
        'descripcion' => $logo->descripcion . "\n\nMotivo de rechazo: " . $request->rejection_reason,
        'updated_by' => auth()->id(),
    ]);

    return redirect()->back()->with('success', 'Logo rechazado. Se ha enviado tu feedback al diseñador.');
}

public function approveCalendar(PublicationCalendar $calendar): RedirectResponse
{
    $this->authorize('view', $calendar->service);

    if ($calendar->estado !== 'enviado' && $calendar->estado !== 'en_revision') {
        return redirect()->back()->with('error', 'No se puede aprobar este calendario en su estado actual.');
    }

    $calendar->update([
        'estado' => 'entregado',
        'fecha_entrega_real' => now(),
        'updated_by' => auth()->id(),
    ]);

    return redirect()->back()->with('success', 'Calendario aprobado exitosamente.');
}

public function correctCalendar(Request $request, PublicationCalendar $calendar): RedirectResponse
{
    $this->authorize('view', $calendar->service);

    $request->validate([
        'correction_notes' => 'required|string|max:2000',
    ]);

    if ($calendar->correcciones_count >= 2) {
        return redirect()->back()->with('error', 'Se ha alcanzado el límite máximo de correcciones para este calendario.');
    }

    $calendar->update([
        'estado' => 'en_revision',
        'correcciones_count' => $calendar->correcciones_count + 1,
        'ultimo_autor_correccion' => auth()->id(),
        'updated_by' => auth()->id(),
    ]);

    // Aquí podrías agregar notificaciones al CM

    return redirect()->back()->with('success', 'Corrección enviada. El equipo revisará tus comentarios.');
}
}