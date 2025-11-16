<?php

namespace App\Http\Controllers;

use App\Models\Logo;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LogoController extends Controller
{
    public function index(): View
    {
        $logos = Logo::with(['service', 'autor', 'createdBy'])
            ->latest()
            ->paginate(10);

        return view('logos.index', compact('logos'));
    }

    public function create(): View
    {
        $services = Service::where('estado', 'activo')->get();
        $designers = User::whereHas('role', function($query) {
            $query->where('name', 'designer');
        })->get();

        return view('logos.create', compact('services', 'designers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'servicio_id' => 'required|exists:services,id',
            'autor_id' => 'required|exists:users,id',
            'tipo' => 'required|in:propuestas,version1,version2',
            'img_path' => 'required|string|max:500',
            'estado' => 'required|in:pendiente,enviado,rechazado,en_revision,corregido,entregado',
            'version' => 'nullable|in:vertical,horizontal,una_tinta,negativo_una_tinta,negativo_color',
            'descripcion' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        Logo::create($validated);

        return redirect()->route('logos.index')
            ->with('success', 'Logo creado exitosamente.');
    }

    public function show(Logo $logo): View
    {
        $logo->load(['service', 'autor', 'manual', 'createdBy', 'updatedBy']);
        
        return view('logos.show', compact('logo'));
    }

    public function edit(Logo $logo): View
    {
        $services = Service::where('estado', 'activo')->get();
        $designers = User::whereHas('role', function($query) {
            $query->where('name', 'designer');
        })->get();

        return view('logos.edit', compact('logo', 'services', 'designers'));
    }

    public function update(Request $request, Logo $logo): RedirectResponse
    {
        $validated = $request->validate([
            'servicio_id' => 'required|exists:services,id',
            'autor_id' => 'required|exists:users,id',
            'tipo' => 'required|in:propuestas,version1,version2',
            'img_path' => 'required|string|max:500',
            'estado' => 'required|in:pendiente,enviado,rechazado,en_revision,corregido,entregado',
            'version' => 'nullable|in:vertical,horizontal,una_tinta,negativo_una_tinta,negativo_color',
            'descripcion' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        $logo->update($validated);

        return redirect()->route('logos.index')
            ->with('success', 'Logo actualizado exitosamente.');
    }

    public function destroy(Logo $logo): RedirectResponse
    {
        $logo->delete();

        return redirect()->route('logos.index')
            ->with('success', 'Logo eliminado exitosamente.');
    }

    public function changeStatus(Request $request, Logo $logo): RedirectResponse
    {
        $request->validate([
            'estado' => 'required|in:pendiente,enviado,rechazado,en_revision,corregido,entregado',
        ]);

        $logo->update([
            'estado' => $request->estado,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Estado del logo actualizado exitosamente.');
    }
}