<?php

namespace App\Http\Controllers\Cm;

use App\Http\Controllers\Controller;
use App\Models\PublicationCalendar;
use App\Models\Service;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        $query = PublicationCalendar::where('creador_id', $user->id)
            ->with(['service.cliente', 'artworks']);

        // Filtros
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('cliente') && $request->cliente) {
            $query->whereHas('service', function($q) use ($request) {
                $q->where('cliente_user_id', $request->cliente);
            });
        }

        $calendars = $query->latest()->paginate(10);

        // Agregar propiedades calculadas
        $calendars->each(function ($calendar) {
            $calendar->service->tipo_formateado = $this->getServiceTypeName($calendar->service->tipo);
            $calendar->progreso = $this->calculateProgress($calendar);
            $calendar->dias_restantes = $calendar->fecha_fin->diffInDays(now(), false) * -1;
        });

        $clients = Service::whereHas('publicationCalendars', function($query) use ($user) {
                $query->where('creador_id', $user->id);
            })
            ->with('cliente')
            ->get()
            ->pluck('cliente')
            ->unique();

        return view('cm.calendars.index', compact('calendars', 'clients'));
    }

    public function create(): View
    {
        $services = Service::where('tipo', 'community_manager')
            ->where('estado', 'activo')
            ->whereHas('brief')
            ->with('cliente')
            ->get();

        return view('cm.calendars.create', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'servicio_id' => 'required|exists:services,id',
            'fecha_ini' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_ini',
            'document_path' => 'nullable|string|max:500',
            'descripcion' => 'nullable|string',
        ]);

        $validated['creador_id'] = auth()->id();
        $validated['created_by'] = auth()->id();

        $calendar = PublicationCalendar::create($validated);

        // Crear asignación automática
        Assignment::create([
            'servicio_id' => $calendar->servicio_id,
            'tarea_tipo' => 'crear_calendario_publicacion',
            'assigned_to' => auth()->id(),
            'assigned_by' => auth()->id(),
            'fecha_inicio' => $calendar->fecha_ini,
            'fecha_fin' => $calendar->fecha_fin,
            'estado' => 'en_proceso',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('cm.calendars.show', $calendar)
            ->with('success', 'Calendario creado exitosamente. Ahora puedes agregar los artes.');
    }

    public function show(PublicationCalendar $calendar): View
    {
        $this->authorize('view', $calendar);

        $calendar->load(['service.cliente', 'artworks.autor', 'creador']);
        
        $calendar->service->tipo_formateado = $this->getServiceTypeName($calendar->service->tipo);
        $calendar->progreso = $this->calculateProgress($calendar);
        $calendar->dias_restantes = $calendar->fecha_fin->diffInDays(now(), false) * -1;

        return view('cm.calendars.show', compact('calendar'));
    }

    public function edit(PublicationCalendar $calendar): View
    {
        $this->authorize('update', $calendar);

        $services = Service::where('tipo', 'community_manager')
            ->where('estado', 'activo')
            ->with('cliente')
            ->get();

        return view('cm.calendars.edit', compact('calendar', 'services'));
    }

    public function update(Request $request, PublicationCalendar $calendar): RedirectResponse
    {
        $this->authorize('update', $calendar);

        $validated = $request->validate([
            'servicio_id' => 'required|exists:services,id',
            'fecha_ini' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_ini',
            'document_path' => 'nullable|string|max:500',
            'descripcion' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        $calendar->update($validated);

        return redirect()->route('cm.calendars.show', $calendar)
            ->with('success', 'Calendario actualizado exitosamente.');
    }

    public function destroy(PublicationCalendar $calendar): RedirectResponse
    {
        $this->authorize('delete', $calendar);

        // Solo se puede eliminar calendarios en estado pendiente
        if ($calendar->estado !== 'pendiente') {
            return redirect()->back()
                ->with('error', 'Solo se pueden eliminar calendarios en estado pendiente.');
        }

        $calendar->delete();

        return redirect()->route('cm.calendars.index')
            ->with('success', 'Calendario eliminado exitosamente.');
    }

    public function submitForApproval(PublicationCalendar $calendar): RedirectResponse
    {
        $this->authorize('update', $calendar);

        if ($calendar->artworks->count() === 0) {
            return redirect()->back()
                ->with('error', 'No puedes enviar un calendario sin artes programados.');
        }

        $calendar->update([
            'estado' => 'enviado',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Calendario enviado para aprobación del director de marca.');
    }

    public function markAsCompleted(PublicationCalendar $calendar): RedirectResponse
    {
        $this->authorize('update', $calendar);

        $calendar->update([
            'estado' => 'completado',
            'fecha_entrega_real' => now(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Calendario marcado como completado.');
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

    private function calculateProgress(PublicationCalendar $calendar): int
    {
        $baseProgress = match($calendar->estado) {
            'pendiente' => 20,
            'en_proceso' => 40,
            'enviado' => 60,
            'en_revision' => 70,
            'entregado' => 90,
            'completado' => 100,
            default => 10
        };

        // Incrementar progreso basado en artes completados
        $totalArtes = $calendar->artworks->count();
        if ($totalArtes > 0) {
            $completedArtes = $calendar->artworks->where('estado', 'aprobado')->count();
            $artProgress = ($completedArtes / $totalArtes) * 30; // 30% del progreso total
            $baseProgress += $artProgress;
        }

        return min($baseProgress, 100);
    }
}