<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('roles.index', [
            'roles' => Role::latest()->paginate(10)
        ]);
    }

    public function create(): View
    {
        return view('roles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles',
            'description' => 'nullable|string',
        ]);

        Role::create($validated);

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    public function show(Role $role): View
    {
        return view('roles.show', compact('role'));
    }

    public function edit(Role $role): View
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
        ]);

        $role->update($validated);

        return redirect()->route('roles.index')
            ->with('success', 'Rol actualizado exitosamente.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        // Verificar si hay usuarios con este rol antes de eliminar
        if ($role->users()->exists()) {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado exitosamente.');
    }
}