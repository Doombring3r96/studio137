<?php

namespace App\Http\Controllers;

use App\Models\Manual;
use App\Models\Logo;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ManualController extends Controller
{
    public function index(): View
    {
        $manuals = Manual::with(['logo', 'service', 'createdBy'])
            ->latest()
            ->paginate(10);

        return view('manuals.index', compact('manuals'));
    }

    public function create(): View
    {
        $logos = Logo::whereDoesntHave('manual')
            ->where('estado', 'entregado')
            ->get();

        $services = Service::where('estado', 'activo')->get();

        return view('manuals.create', compact('logos', 'services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'logo_id' => 'required|exists:logos,id|unique:manuals,logo_id',
            'servicio_id' => 'required|exists:services,id',
            'manual_path' => 'required|string|max:500',
        ]);

        $validated['created_by'] = auth()->id();

        Manual::create($validated);

        return redirect()->route('manuals.index')
            ->with('success', 'Manual creado exitosamente.');
    }

    public function show(Manual $manual): View
    {
        $manual->load(['logo', 'service', 'createdBy', 'updatedBy']);
        
        return view('manuals.show', compact('manual'));
    }

    public function edit(Manual $manual): View
    {
        $logos = Logo::where('estado', 'entregado')->get();
        $services = Service::where('estado', 'activo')->get();

        return view('manuals.edit', compact('manual', 'logos', 'services'));
    }

    public function update(Request $request, Manual $manual): RedirectResponse
    {
        $validated = $request->validate([
            'logo_id' => 'required|exists:logos,id|unique:manuals,logo_id,' . $manual->id,
            'servicio_id' => 'required|exists:services,id',
            'manual_path' => 'required|string|max:500',
        ]);

        $validated['updated_by'] = auth()->id();

        $manual->update($validated);

        return redirect()->route('manuals.index')
            ->with('success', 'Manual actualizado exitosamente.');
    }

    public function destroy(Manual $manual): RedirectResponse
    {
        $manual->delete();

        return redirect()->route('manuals.index')
            ->with('success', 'Manual eliminado exitosamente.');
    }
}