<?php

namespace App\Http\Controllers;

use App\Models\PublicationCalendar;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PublicationCalendarController extends Controller
{
    public function index(): View
    {
        $calendars = PublicationCalendar::with(['service', 'creador', 'createdBy'])
            ->latest()
            ->paginate(10);

        return view('publication-calendars.index', compact('calendars'));
    }

    public function create(): View
    {
        $services = Service::where('estado', 'activo')->get();
        $creadores = User::whereHas('role', function($query) {
            $query->where('name', 'cm');
        })->get();

        return view('publication-calendars.create', compact('services', 'creadores'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'servicio_id' => 'required|exists:services,id',
            'creador_id' => 'required|exists:users,id',
            'fecha_ini' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_ini',
            'estado' => 'required|in:pendiente,enviado,rechazado,en_revision,corregido,entregado',
            'document_path' => 'nullable|string|max:500',
        ]);

        $validated['created_by'] = auth()->id();

        PublicationCalendar::create($validated);

        return redirect()->route('publication-calendars.index')
            ->with('success', 'Calendario de publicación creado exitosamente.');
    }

    public function show(PublicationCalendar $publicationCalendar): View
    {
        $publicationCalendar->load([
            'service', 
            'creador', 
            'artworks', 
            'reports', 
            'createdBy', 
            'updatedBy'
        ]);
        
        return view('publication-calendars.show', compact('publicationCalendar'));
    }

    public function edit(PublicationCalendar $publicationCalendar): View
    {
        $services = Service::where('estado', 'activo')->get();
        $creadores = User::whereHas('role', function($query) {
            $query->where('name', 'cm');
        })->get();

        return view('publication-calendars.edit', compact('publicationCalendar', 'services', 'creadores'));
    }

    public function update(Request $request, PublicationCalendar $publicationCalendar): RedirectResponse
    {
        $validated = $request->validate([
            'servicio_id' => 'required|exists:services,id',
            'creador_id' => 'required|exists:users,id',
            'fecha_ini' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_ini',
            'estado' => 'required|in:pendiente,enviado,rechazado,en_revision,corregido,entregado',
            'document_path' => 'nullable|string|max:500',
        ]);

        $validated['updated_by'] = auth()->id();

        $publicationCalendar->update($validated);

        return redirect()->route('publication-calendars.index')
            ->with('success', 'Calendario de publicación actualizado exitosamente.');
    }

    public function destroy(PublicationCalendar $publicationCalendar): RedirectResponse
    {
        $publicationCalendar->delete();

        return redirect()->route('publication-calendars.index')
            ->with('success', 'Calendario de publicación eliminado exitosamente.');
    }

    public function addCorrection(Request $request, PublicationCalendar $publicationCalendar): RedirectResponse
    {
        if (!$publicationCalendar->puedeSerCorregido()) {
            return redirect()->back()
                ->with('error', 'No se pueden agregar más correcciones. Límite alcanzado.');
        }

        $publicationCalendar->incrementarCorrecciones();
        $publicationCalendar->update([
            'ultimo_autor_correccion' => auth()->id(),
            'estado' => 'en_revision',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Corrección agregada al calendario.');
    }

    public function markAsDelivered(PublicationCalendar $publicationCalendar): RedirectResponse
    {
        $publicationCalendar->marcarComoEntregado();

        return redirect()->back()
            ->with('success', 'Calendario marcado como entregado.');
    }
}