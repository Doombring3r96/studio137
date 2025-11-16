<?php

namespace App\Http\Controllers;

use App\Models\Brief;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BriefController extends Controller
{
    public function index(): View
    {
        $briefs = Brief::with(['service', 'createdBy'])
            ->latest()
            ->paginate(10);

        return view('briefs.index', compact('briefs'));
    }

    public function create(): View
    {
        $services = Service::whereDoesntHave('brief')
            ->where('estado', 'activo')
            ->get();

        return view('briefs.create', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'servicio_id' => 'required|exists:services,id|unique:briefs,servicio_id',
            'tipo' => 'required|in:logo,marca,cm',
            'document_path' => 'nullable|string|max:500',
            'fecha_recibida' => 'required|date',
            'contenido_json' => 'nullable|json',
        ]);

        $validated['created_by'] = auth()->id();

        Brief::create($validated);

        return redirect()->route('briefs.index')
            ->with('success', 'Brief creado exitosamente.');
    }

    public function show(Brief $brief): View
    {
        $brief->load(['service', 'createdBy', 'updatedBy']);
        
        return view('briefs.show', compact('brief'));
    }

    public function edit(Brief $brief): View
    {
        $services = Service::where('estado', 'activo')->get();

        return view('briefs.edit', compact('brief', 'services'));
    }

    public function update(Request $request, Brief $brief): RedirectResponse
    {
        $validated = $request->validate([
            'servicio_id' => 'required|exists:services,id|unique:briefs,servicio_id,' . $brief->id,
            'tipo' => 'required|in:logo,marca,cm',
            'document_path' => 'nullable|string|max:500',
            'fecha_recibida' => 'required|date',
            'contenido_json' => 'nullable|json',
        ]);

        $validated['updated_by'] = auth()->id();

        $brief->update($validated);

        return redirect()->route('briefs.index')
            ->with('success', 'Brief actualizado exitosamente.');
    }

    public function destroy(Brief $brief): RedirectResponse
    {
        $brief->delete();

        return redirect()->route('briefs.index')
            ->with('success', 'Brief eliminado exitosamente.');
    }
}