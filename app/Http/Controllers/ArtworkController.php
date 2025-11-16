<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\PublicationCalendar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ArtworkController extends Controller
{
    public function index(): View
    {
        $artworks = Artwork::with(['calendar', 'autor', 'createdBy'])
            ->latest()
            ->paginate(10);

        return view('artworks.index', compact('artworks'));
    }

    public function create(): View
    {
        $calendars = PublicationCalendar::where('estado', '!=', 'entregado')->get();
        $autores = User::whereHas('role', function($query) {
            $query->whereIn('name', ['designer', 'cm']);
        })->get();

        return view('artworks.create', compact('calendars', 'autores'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'calendar_id' => 'required|exists:publication_calendars,id',
            'autor_id' => 'required|exists:users,id',
            'fecha_pub' => 'nullable|date',
            'titulo' => 'required|string|max:255',
            'cuerpo' => 'nullable|string',
            'copy' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'img_path' => 'nullable|string|max:500',
            'tipo' => 'required|in:color,venta',
            'estado' => 'required|in:pendiente,enviado,rechazado,aprobado',
        ]);

        $validated['created_by'] = auth()->id();

        Artwork::create($validated);

        return redirect()->route('artworks.index')
            ->with('success', 'Arte creado exitosamente.');
    }

    public function show(Artwork $artwork): View
    {
        $artwork->load(['calendar', 'autor', 'createdBy', 'updatedBy']);
        
        return view('artworks.show', compact('artwork'));
    }

    public function edit(Artwork $artwork): View
    {
        $calendars = PublicationCalendar::where('estado', '!=', 'entregado')->get();
        $autores = User::whereHas('role', function($query) {
            $query->whereIn('name', ['designer', 'cm']);
        })->get();

        return view('artworks.edit', compact('artwork', 'calendars', 'autores'));
    }

    public function update(Request $request, Artwork $artwork): RedirectResponse
    {
        $validated = $request->validate([
            'calendar_id' => 'required|exists:publication_calendars,id',
            'autor_id' => 'required|exists:users,id',
            'fecha_pub' => 'nullable|date',
            'titulo' => 'required|string|max:255',
            'cuerpo' => 'nullable|string',
            'copy' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'img_path' => 'nullable|string|max:500',
            'tipo' => 'required|in:color,venta',
            'estado' => 'required|in:pendiente,enviado,rechazado,aprobado',
        ]);

        $validated['updated_by'] = auth()->id();

        $artwork->update($validated);

        return redirect()->route('artworks.index')
            ->with('success', 'Arte actualizado exitosamente.');
    }

    public function destroy(Artwork $artwork): RedirectResponse
    {
        $artwork->delete();

        return redirect()->route('artworks.index')
            ->with('success', 'Arte eliminado exitosamente.');
    }

    public function approve(Artwork $artwork): RedirectResponse
    {
        $artwork->marcarComoAprobado();

        return redirect()->back()
            ->with('success', 'Arte aprobado exitosamente.');
    }

    public function reject(Artwork $artwork): RedirectResponse
    {
        $artwork->marcarComoRechazado();

        return redirect()->back()
            ->with('success', 'Arte rechazado.');
    }
}