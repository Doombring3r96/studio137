<?php

namespace App\Http\Controllers\Cm;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\PublicationCalendar;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ArtworkController extends Controller
{
    public function index(Request $request, PublicationCalendar $calendar): View
    {
        $this->authorize('view', $calendar);

        $query = Artwork::where('calendar_id', $calendar->id);

        // Filtros
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('tipo') && $request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        $artworks = $query->latest()->paginate(10);

        $calendar->load(['service.cliente']);

        return view('cm.artworks.index', compact('calendar', 'artworks'));
    }

    public function create(PublicationCalendar $calendar): View
    {
        $this->authorize('update', $calendar);

        return view('cm.artworks.create', compact('calendar'));
    }

    public function store(Request $request, PublicationCalendar $calendar): RedirectResponse
    {
        $this->authorize('update', $calendar);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha_pub' => 'required|date',
            'cuerpo' => 'nullable|string',
            'copy' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipo' => 'required|in:color,venta',
            'estado' => 'required|in:pendiente,enviado,aprobado,rechazado',
        ]);

        // Procesar imagen si se subió
        if ($request->hasFile('img_path')) {
            $path = $request->file('img_path')->store('artworks', 'public');
            $validated['img_path'] = $path;
        }

        $validated['calendar_id'] = $calendar->id;
        $validated['autor_id'] = auth()->id();
        $validated['created_by'] = auth()->id();

        Artwork::create($validated);

        return redirect()->route('cm.calendars.artworks.index', $calendar)
            ->with('success', 'Arte creado exitosamente.');
    }

    public function edit(PublicationCalendar $calendar, Artwork $artwork): View
    {
        $this->authorize('update', $calendar);
        $this->authorize('update', $artwork);

        return view('cm.artworks.edit', compact('calendar', 'artwork'));
    }

    public function update(Request $request, PublicationCalendar $calendar, Artwork $artwork): RedirectResponse
    {
        $this->authorize('update', $calendar);
        $this->authorize('update', $artwork);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha_pub' => 'required|date',
            'cuerpo' => 'nullable|string',
            'copy' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipo' => 'required|in:color,venta',
            'estado' => 'required|in:pendiente,enviado,aprobado,rechazado',
        ]);

        // Procesar nueva imagen si se subió
        if ($request->hasFile('img_path')) {
            // Eliminar imagen anterior si existe
            if ($artwork->img_path && Storage::disk('public')->exists($artwork->img_path)) {
                Storage::disk('public')->delete($artwork->img_path);
            }
            
            $path = $request->file('img_path')->store('artworks', 'public');
            $validated['img_path'] = $path;
        }

        $validated['updated_by'] = auth()->id();

        $artwork->update($validated);

        return redirect()->route('cm.calendars.artworks.index', $calendar)
            ->with('success', 'Arte actualizado exitosamente.');
    }

    public function destroy(PublicationCalendar $calendar, Artwork $artwork): RedirectResponse
    {
        $this->authorize('update', $calendar);
        $this->authorize('delete', $artwork);

        // Eliminar imagen si existe
        if ($artwork->img_path && Storage::disk('public')->exists($artwork->img_path)) {
            Storage::disk('public')->delete($artwork->img_path);
        }

        $artwork->delete();

        return redirect()->route('cm.calendars.artworks.index', $calendar)
            ->with('success', 'Arte eliminado exitosamente.');
    }
}