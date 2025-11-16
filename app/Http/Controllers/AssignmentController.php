<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AssignmentController extends Controller
{
    public function index(): View
    {
        $assignments = Assignment::with(['service', 'assignedTo', 'assignedBy', 'createdBy'])
            ->latest()
            ->paginate(10);

        return view('assignments.index', compact('assignments'));
    }

    public function create(): View
    {
        $services = Service::where('estado', 'activo')->get();
        $users = User::whereHas('role', function($query) {
            $query->whereIn('name', ['designer', 'cm']);
        })->get();

        return view('assignments.create', compact('services', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'servicio_id' => 'required|exists:services,id',
            'tarea_tipo' => 'required|string|max:100',
            'assigned_to' => 'required|exists:users,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'estado' => 'required|in:pendiente,en_proceso,completado,cancelado',
        ]);

        $validated['assigned_by'] = auth()->id();
        $validated['created_by'] = auth()->id();

        Assignment::create($validated);

        return redirect()->route('assignments.index')
            ->with('success', 'Asignación creada exitosamente.');
    }

    public function show(Assignment $assignment): View
    {
        $assignment->load(['service', 'assignedTo', 'assignedBy', 'createdBy', 'updatedBy']);
        
        return view('assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment): View
    {
        $services = Service::where('estado', 'activo')->get();
        $users = User::whereHas('role', function($query) {
            $query->whereIn('name', ['designer', 'cm']);
        })->get();

        return view('assignments.edit', compact('assignment', 'services', 'users'));
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $validated = $request->validate([
            'servicio_id' => 'required|exists:services,id',
            'tarea_tipo' => 'required|string|max:100',
            'assigned_to' => 'required|exists:users,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'estado' => 'required|in:pendiente,en_proceso,completado,cancelado',
        ]);

        $validated['updated_by'] = auth()->id();

        $assignment->update($validated);

        return redirect()->route('assignments.index')
            ->with('success', 'Asignación actualizada exitosamente.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $assignment->delete();

        return redirect()->route('assignments.index')
            ->with('success', 'Asignación eliminada exitosamente.');
    }

    public function myAssignments(): View
    {
        $assignments = Assignment::with(['service', 'assignedBy'])
            ->where('assigned_to', auth()->id())
            ->latest()
            ->paginate(10);

        return view('assignments.my', compact('assignments'));
    }

    public function complete(Assignment $assignment): RedirectResponse
    {
        if ($assignment->assigned_to !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'No tienes permiso para completar esta asignación.');
        }

        $assignment->marcarComoCompletado();

        return redirect()->back()
            ->with('success', 'Asignación marcada como completada.');
    }

    public function start(Assignment $assignment): RedirectResponse
    {
        if ($assignment->assigned_to !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'No tienes permiso para iniciar esta asignación.');
        }

        $assignment->marcarComoEnProceso();

        return redirect()->back()
            ->with('success', 'Asignación marcada como en proceso.');
    }
}